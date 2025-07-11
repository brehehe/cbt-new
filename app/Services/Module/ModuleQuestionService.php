<?php

namespace App\Services\Module;

use App\Models\Master\Question\ModuleQuestion;

class ModuleQuestionService
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
        foreach ($request['question_id'] ?? [] as $key => $question_id) {
            ModuleQuestion::create([
                'company_id'  => $request['company_id'] ?? null,
                'module_id'   => $request['module_id'] ?? null,
                'question_id' => $question_id,
            ]);
        }
    }

    public function delete($id)
    {
        $result = ModuleQuestion::findOrFail($id);
        $result->delete();
    }
}
