<?php

namespace App\Services\Module;

use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Topic;
use Illuminate\Support\Facades\DB;

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
        if (!empty($request['id'])) {
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
                'id' => $request['id'] ?? null
            ],
            [
                'user_id'          => $request['user_id'] ?? null,
                'company_id'       => $request['company_id'] ?? null,
                'question_type_id' => $request['question_type_id'] ?? null,
                'name'             => $request['name'] ?? null,
                'duration'         => $request['duration'] ?? null,
                'random_question'  => $request['random_question'] ?? false,
                'description'      => $request['description'] ?? null,
                'studys'           => json_encode($request['studys']) ?? json_encode([]),
                'is_all_study'     => $request['is_all_study'] ?? false,
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

        DB::transaction(function () use ($module, $enabledCategoryIds, $companyId) {
            // Add questions from enabled categories
            if (!empty($enabledCategoryIds)) {
                $questions = Question::withoutGlobalScope('user_scope')
                    ->whereIn('category_question_id', $enabledCategoryIds)
                    ->get(['id', 'study_id', 'category_question_id']);

                foreach ($questions as $question) {
                    $moduleQuestion = ModuleQuestion::withTrashed()->firstOrCreate(
                        [
                            'module_id' => $module->id,
                            'question_id' => $question->id,
                        ],
                        [
                            'company_id' => $companyId,
                            'study_id' => $question->study_id,
                            'question_pick_type' => 'category',
                        ]
                    );

                    if ($moduleQuestion->trashed()) {
                        $moduleQuestion->restore();
                    }
                }
            }

            // Delete module_questions from disabled (unchecked) categories
            $disabledCategoryIds = CategoryQuestion::pluck('id')
                ->diff($enabledCategoryIds)
                ->toArray();

            if (!empty($disabledCategoryIds)) {
                $questionIdsToDelete = Question::withoutGlobalScope('user_scope')
                    ->whereIn('category_question_id', $disabledCategoryIds)
                    ->pluck('id')
                    ->toArray();

                if (!empty($questionIdsToDelete)) {
                    ModuleQuestion::withoutGlobalScope('user_scope')
                        ->where('module_id', $module->id)
                        ->whereIn('question_id', $questionIdsToDelete)
                        ->where('question_pick_type', 'category')
                        ->forceDelete();
                }
            }
        });
    }

    private function syncModuleQuestionsByTopic(Module $module, array $topicQuestionSettings, ?string $companyId): void
    {
        $enabledTopicIds = array_keys($topicQuestionSettings);

        DB::transaction(function () use ($module, $enabledTopicIds, $companyId) {
            // Add questions from enabled topics
            if (!empty($enabledTopicIds)) {
                $questions = Question::withoutGlobalScope('user_scope')
                    ->whereIn('topic_id', $enabledTopicIds)
                    ->get(['id', 'study_id', 'topic_id']);

                foreach ($questions as $question) {
                    $moduleQuestion = ModuleQuestion::withTrashed()->firstOrCreate(
                        [
                            'module_id' => $module->id,
                            'question_id' => $question->id,
                        ],
                        [
                            'company_id' => $companyId,
                            'study_id' => $question->study_id,
                            'question_pick_type' => 'topic',
                        ]
                    );

                    if ($moduleQuestion->trashed()) {
                        $moduleQuestion->restore();
                    }
                }
            }

            // Delete module_questions from disabled (unchecked) topics
            $disabledTopicIds = Topic::pluck('id')
                ->diff($enabledTopicIds)
                ->toArray();

            if (!empty($disabledTopicIds)) {
                $questionIdsToDelete = Question::withoutGlobalScope('user_scope')
                    ->whereIn('topic_id', $disabledTopicIds)
                    ->pluck('id')
                    ->toArray();

                if (!empty($questionIdsToDelete)) {
                    ModuleQuestion::withoutGlobalScope('user_scope')
                        ->where('module_id', $module->id)
                        ->whereIn('question_id', $questionIdsToDelete)
                        ->where('question_pick_type', 'topic')
                        ->forceDelete();
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
