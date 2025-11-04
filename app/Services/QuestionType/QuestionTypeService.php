<?php

namespace App\Services\QuestionType;

use App\Models\Master\Question\QuestionType;

class QuestionTypeService
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
        $exam_type = QuestionType::updateOrCreate(
            [
                'id' => $request['id'] ?? null
            ],
            [
                'company_id'           => $request['company_id'] ?? null,
                'name'                 => $request['name'] ?? null,
                'description'          => $request['description'] ?? null,
            ]
        );

        return $exam_type;
    }

    public function delete($id)
    {
        $result = QuestionType::findOrFail($id);
        $result->delete();
    }
}
