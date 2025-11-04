<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixRecordingStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exam:fix-recording-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix invalid status values in exam_recordings table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Find the specific record with invalid status
            $record = DB::table('exam_recordings')
                ->where('id', '019938ea-6711-70ca-bc2f-392a045d23cc')
                ->first();

            if ($record) {
                $this->info("Found record with status: " . $record->status);

                // Update to valid status
                DB::table('exam_recordings')
                    ->where('id', '019938ea-6711-70ca-bc2f-392a045d23cc')
                    ->update(['status' => 'completed']);

                $this->info("Updated status to 'completed'");
            } else {
                $this->info("Specific record not found");
            }

            // Check for any other records with invalid status
            $invalidRecords = DB::select("SELECT id, status FROM exam_recordings WHERE status NOT IN ('recording', 'completed', 'failed')");

            if (count($invalidRecords) > 0) {
                $this->warn("Found " . count($invalidRecords) . " records with invalid status:");
                foreach ($invalidRecords as $invalid) {
                    $this->line("ID: {$invalid->id}, Status: {$invalid->status}");

                    // Fix them
                    DB::table('exam_recordings')
                        ->where('id', $invalid->id)
                        ->update(['status' => 'completed']);
                    $this->info("Fixed record {$invalid->id}");
                }
            } else {
                $this->info("No records with invalid status found.");
            }

            $this->info("All done!");

            return 0;
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}
