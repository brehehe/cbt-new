<?php

namespace App\Services\ExamType;

use App\Models\Master\Exam\ExamType;

class ExamTypeService
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
        $exam_type = ExamType::updateOrCreate(
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
        $result = ExamType::findOrFail($id);
        $result->delete();
    }
}
