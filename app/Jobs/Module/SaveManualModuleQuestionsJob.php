<?php

namespace App\Jobs\Module;

use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class SaveManualModuleQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $moduleId,
        public array $questionIds,
        public ?string $companyId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (empty($this->questionIds)) {
            return;
        }

        try {
            $now = Carbon::now();

            // Retrieve all target questions and their study_id
            $questions = Question::withoutGlobalScope('user_scope')
                ->whereIn('id', $this->questionIds)
                ->get(['id', 'study_id']);

            $questionsMap = $questions->keyBy('id');

            // Find existing question_ids in this module to avoid duplicate insertions
            $existingQuestionIds = ModuleQuestion::withoutGlobalScope('user_scope')
                ->where('module_id', $this->moduleId)
                ->whereNull('deleted_at')
                ->pluck('question_id')
                ->toArray();

            $toAddQuestionIds = array_diff($this->questionIds, $existingQuestionIds);

            if (empty($toAddQuestionIds)) {
                Log::info('SaveManualModuleQuestionsJob: No new questions to add to module', [
                    'module_id' => $this->moduleId,
                ]);

                return;
            }

            DB::transaction(function () use ($toAddQuestionIds, $questionsMap, $now) {
                // Get the max order once
                $lastOrder = ModuleQuestion::withoutGlobalScope('user_scope')->max('order') ?? 0;
                $newRecords = [];

                foreach ($toAddQuestionIds as $qId) {
                    $question = $questionsMap->get($qId);
                    if (! $question) {
                        continue;
                    }

                    $newRecords[] = [
                        'id' => (string) Str::uuid(),
                        'module_id' => $this->moduleId,
                        'question_id' => $qId,
                        'company_id' => $this->companyId,
                        'study_id' => $question->study_id,
                        'question_pick_type' => 'manual',
                        'order' => ++$lastOrder,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach (array_chunk($newRecords, 200) as $chunk) {
                    ModuleQuestion::insert($chunk);
                }
            });

            Log::info('SaveManualModuleQuestionsJob: Successfully synced manual questions', [
                'module_id' => $this->moduleId,
                'count_added' => count($toAddQuestionIds),
            ]);

        } catch (Throwable $th) {
            Log::error('Ada Kesalahaan saat SaveManualModuleQuestionsJob', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'module_id' => $this->moduleId,
            ]);
            throw $th;
        }
    }
}
