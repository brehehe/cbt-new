<?php

namespace App\Services\Module;

use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ModuleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreate($request)
    {
        $existing = null;
        if (! empty($request['id'])) {
            $existing = Module::find($request['id']);
        }

        $questionPickType = $request['question_pick_type'] ?? ($existing?->question_pick_type ?? 'manual');

        $categoryQuestionSettings = $this->resolveSettings(
            $request,
            $existing,
            'category_question_settings'
        );

        $topicQuestionSettings = $this->resolveSettings(
            $request,
            $existing,
            'topic_question_settings'
        );

        $material = Module::updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'user_id' => $request['user_id'] ?? null,
                'company_id' => $request['company_id'] ?? null,
                'question_type_id' => $request['question_type_id'] ?? null,
                'name' => $request['name'] ?? null,
                'duration' => $request['duration'] ?? null,
                'random_question' => $request['random_question'] ?? false,
                'description' => $request['description'] ?? null,
                'studys' => json_encode($request['studys']) ?? json_encode([]),
                'is_all_study' => $request['is_all_study'] ?? false,
                'question_pick_type' => $questionPickType,
                'category_question_settings' => $categoryQuestionSettings,
                'topic_question_settings' => $topicQuestionSettings,
            ]
        );

        if ($questionPickType === 'category') {
            $this->syncModuleQuestionsByCategory($material, $categoryQuestionSettings, $request['company_id'] ?? null);
        } elseif ($questionPickType === 'topic') {
            $this->syncModuleQuestionsByTopic($material, $topicQuestionSettings, $request['company_id'] ?? null);
        }

        return $material;
    }

    private function syncModuleQuestionsByCategory(Module $module, array $categoryQuestionSettings, ?string $companyId): void
    {
        $enabledCategoryIds = array_keys($categoryQuestionSettings);
        $now = Carbon::now();

        $targetQuestions = Question::withoutGlobalScope('user_scope')
            ->whereIn('category_question_id', $enabledCategoryIds)
            ->get(['id', 'study_id']);

        $targetQuestionIds = $targetQuestions->pluck('id')->toArray();

        DB::transaction(function () use ($module, $targetQuestionIds, $targetQuestions, $companyId, $now) {
            // Get existing questions in this module for this pick type
            $existingModuleQuestions = ModuleQuestion::withoutGlobalScope('user_scope')
                ->where('module_id', $module->id)
                ->where('question_pick_type', 'category')
                ->get(['id', 'question_id']);

            $existingQuestionIds = $existingModuleQuestions->pluck('question_id')->toArray();

            // 1. Identify questions to remove (exist in DB but not in target)
            $toRemoveIds = array_diff($existingQuestionIds, $targetQuestionIds);
            if (! empty($toRemoveIds)) {
                ModuleQuestion::withoutGlobalScope('user_scope')
                    ->where('module_id', $module->id)
                    ->whereIn('question_id', $toRemoveIds)
                    ->where('question_pick_type', 'category')
                    ->forceDelete();
            }

            // 2. Identify questions to add (exist in target but not in DB)
            $toAddQuestionIds = array_diff($targetQuestionIds, $existingQuestionIds);
            if (! empty($toAddQuestionIds)) {
                $lastOrder = ModuleQuestion::withoutGlobalScope('user_scope')->max('order') ?? 0;
                $newRecords = [];
                $targetQuestionsMap = $targetQuestions->keyBy('id');

                foreach ($toAddQuestionIds as $qId) {
                    $questionData = $targetQuestionsMap->get($qId);
                    $newRecords[] = [
                        'id' => (string) Str::uuid(),
                        'module_id' => $module->id,
                        'question_id' => $qId,
                        'company_id' => $companyId,
                        'study_id' => $questionData->study_id,
                        'question_pick_type' => 'category',
                        'order' => ++$lastOrder,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach (array_chunk($newRecords, 200) as $chunk) {
                    ModuleQuestion::insert($chunk);
                }
            }
        });
    }

    private function syncModuleQuestionsByTopic(Module $module, array $topicQuestionSettings, ?string $companyId): void
    {
        $enabledTopicIds = array_keys($topicQuestionSettings);
        $now = Carbon::now();

        $targetQuestions = Question::withoutGlobalScope('user_scope')
            ->whereIn('topic_id', $enabledTopicIds)
            ->get(['id', 'study_id']);

        $targetQuestionIds = $targetQuestions->pluck('id')->toArray();

        DB::transaction(function () use ($module, $targetQuestionIds, $targetQuestions, $companyId, $now) {
            // Get existing questions in this module for this pick type
            $existingModuleQuestions = ModuleQuestion::withoutGlobalScope('user_scope')
                ->where('module_id', $module->id)
                ->where('question_pick_type', 'topic')
                ->get(['id', 'question_id']);

            $existingQuestionIds = $existingModuleQuestions->pluck('question_id')->toArray();

            // 1. Identify questions to remove
            $toRemoveIds = array_diff($existingQuestionIds, $targetQuestionIds);
            if (! empty($toRemoveIds)) {
                ModuleQuestion::withoutGlobalScope('user_scope')
                    ->where('module_id', $module->id)
                    ->whereIn('question_id', $toRemoveIds)
                    ->where('question_pick_type', 'topic')
                    ->forceDelete();
            }

            // 2. Identify questions to add
            $toAddQuestionIds = array_diff($targetQuestionIds, $existingQuestionIds);
            if (! empty($toAddQuestionIds)) {
                $lastOrder = ModuleQuestion::withoutGlobalScope('user_scope')->max('order') ?? 0;
                $newRecords = [];
                $targetQuestionsMap = $targetQuestions->keyBy('id');

                foreach ($toAddQuestionIds as $qId) {
                    $questionData = $targetQuestionsMap->get($qId);
                    $newRecords[] = [
                        'id' => (string) Str::uuid(),
                        'module_id' => $module->id,
                        'question_id' => $qId,
                        'company_id' => $companyId,
                        'study_id' => $questionData->study_id,
                        'question_pick_type' => 'topic',
                        'order' => ++$lastOrder,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                foreach (array_chunk($newRecords, 200) as $chunk) {
                    ModuleQuestion::insert($chunk);
                }
            }
        });
    }

    private function resolveSettings(array $request, ?Module $existing, string $key): array
    {
        if (array_key_exists($key, $request)) {
            return $request[$key] ?? [];
        }

        if ($existing) {
            $existingSettings = $existing->{$key};
            if (is_string($existingSettings)) {
                $existingSettings = json_decode($existingSettings, true) ?? [];
            }

            return $existingSettings ?? [];
        }

        return [];
    }

    public function delete($id)
    {
        $result = Module::findOrFail($id);
        $result->delete();
    }
}
