<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\User\UserDetail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncStudentNim extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:sync-nim
                            {company_id? : Optional UUID of the company to scope the synchronization}
                            {--dry-run : Simulate the synchronization without saving changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize NIM between users and user_details tables for existing records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->argument('company_id');
        $dryRun = $this->option('dry-run');

        $query = User::query()->where('type_user', 'employee'); // Mahasiswa/employees

        if ($companyId) {
            $query->where('company_id', $companyId);
            $this->info("Scoping synchronization to company: {$companyId}");
        } else {
            $this->info('Synchronizing NIM for all users in the system...');
        }

        if ($dryRun) {
            $this->warn('DRY RUN MODE ENABLED. No changes will be saved to the database.');
        }

        $totalChecked = 0;
        $totalUpdated = 0;

        // Eager load userDetail
        $query->with('userDetail');

        $query->chunk(100, function ($users) use ($dryRun, &$totalChecked, &$totalUpdated) {
            foreach ($users as $user) {
                $totalChecked++;
                $userDetail = $user->userDetail;

                $userNim = $user->nim ? trim($user->nim) : null;
                $detailNim = ($userDetail && $userDetail->nim) ? trim($userDetail->nim) : null;
                $detailStudentId = ($userDetail && $userDetail->student_id) ? trim($userDetail->student_id) : null;

                // Find a non-empty NIM among these
                $resolvedNim = $userNim ?: ($detailStudentId ?: $detailNim);

                if ($resolvedNim) {
                    $userNeedsUpdate = ($userNim !== $resolvedNim);
                    $detailNeedsUpdate = ! $userDetail || ($detailNim !== $resolvedNim) || ($detailStudentId !== $resolvedNim);

                    if ($userNeedsUpdate || $detailNeedsUpdate) {
                        $this->line("User: {$user->name} ({$user->email})");
                        $this->line("  Current NIMs: users.nim='{$userNim}', user_details.nim='{$detailNim}', user_details.student_id='{$detailStudentId}'");
                        $this->info("  Resolved NIM to sync: '{$resolvedNim}'");

                        if (! $dryRun) {
                            DB::transaction(function () use ($user, $userDetail, $resolvedNim, $userNeedsUpdate, $detailNeedsUpdate) {
                                if ($userNeedsUpdate) {
                                    $user->nim = $resolvedNim;
                                    $user->save();
                                }

                                if ($detailNeedsUpdate) {
                                    if (! $userDetail) {
                                        UserDetail::create([
                                            'user_id' => $user->id,
                                            'company_id' => $user->company_id,
                                            'nim' => $resolvedNim,
                                            'student_id' => $resolvedNim,
                                        ]);
                                    } else {
                                        $userDetail->nim = $resolvedNim;
                                        $userDetail->student_id = $resolvedNim;
                                        $userDetail->save();
                                    }
                                }
                            });
                        }
                        $totalUpdated++;
                    }
                }
            }
        });

        $this->info('Synchronization finished.');
        $this->line("Total users checked: {$totalChecked}");
        $this->line("Total users synchronized: {$totalUpdated}");

        return 0;
    }
}
