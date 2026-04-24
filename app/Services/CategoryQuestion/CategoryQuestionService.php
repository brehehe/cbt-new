<?php

namespace App\Services\CategoryQuestion;

use App\Models\Category\CategoryQuestion;

class CategoryQuestionService
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
        $exam_type = CategoryQuestion::updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'company_id' => $request['company_id'] ?? null,
                'name' => $request['name'] ?? null,
                'description' => $request['description'] ?? null,
            ]
        );

        return $exam_type;
    }

    public function delete($id)
    {
        $result = CategoryQuestion::findOrFail($id);
        $result->delete();
    }
}
