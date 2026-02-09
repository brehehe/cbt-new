<?php

namespace App\Services\Module;

use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use App\Models\Category\CategoryQuestion;
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

        $categoryQuestionSettings = null;
        if (array_key_exists('category_question_settings', $request)) {
            $categoryQuestionSettings = $request['category_question_settings'] ?? [];
        } elseif ($existing) {
            $existingSettings = $existing->category_question_settings;
            if (is_string($existingSettings)) {
                $existingSettings = json_decode($existingSettings, true) ?? [];
            }
            $categoryQuestionSettings = $existingSettings ?? [];
        } else {
            $categoryQuestionSettings = [];
        }

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
                'category_question_settings' => $categoryQuestionSettings,
            ]
        );

        $this->syncModuleQuestionsByCategory($material, $categoryQuestionSettings, $request['company_id'] ?? null);

        return $material;
    }

    private function syncModuleQuestionsByCategory(Module $module, array $categoryQuestionSettings, ?string $companyId): void
    {
        $enabledCategoryIds = array_keys($categoryQuestionSettings);
        $allCategoryIds = CategoryQuestion::pluck('id')->toArray();
        $disabledCategoryIds = array_values(array_diff($allCategoryIds, $enabledCategoryIds));

        DB::transaction(function () use ($module, $enabledCategoryIds, $disabledCategoryIds, $companyId) {
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
                        ]
                    );

                    
                    if ($moduleQuestion->trashed()) {
                        $moduleQuestion->restore();
                    }
                }
            }

            if (!empty($disabledCategoryIds)) {
                $questionIdsToDisable = Question::withoutGlobalScope('user_scope')
                    ->whereIn('category_question_id', $disabledCategoryIds)
                    ->pluck('id')
                    ->toArray();

                if (!empty($questionIdsToDisable)) {
                    ModuleQuestion::where('module_id', $module->id)
                        ->whereIn('question_id', $questionIdsToDisable)
                        ->delete();
                }
            }
        });
    }

    public function delete($id)
    {
        $result = Module::findOrFail($id);
        $result->delete();
    }
}
