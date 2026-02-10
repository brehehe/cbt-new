<?php

namespace App\Services\ExamSession;

use App\Models\Master\Exam\ExamSession;

class ExamSessionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreate(array $data)
    {
        // dd($data);
        $exam_room = ExamSession::updateOrCreate(
            [
                'id' => $data['id'] ?? null
            ],
            [
                'name'        => $data['name'] ?? '',
                'code'        => $data['code'] ?? null,
                'description' => $data['description'] ?? null,
                'is_active'   => $data['is_active'] ?? true,
            ]
        );

        return $exam_room;
    }

    public function delete($id)
    {
        $result = ExamSession::findOrFail($id);
        $result->delete();
    }
}
