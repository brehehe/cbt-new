<?php

namespace App\Services\ExamRoom;

use App\Models\Master\Exam\ExamRoom;

class ExamRoomService
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
        $exam_room = ExamRoom::updateOrCreate(
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
        $result = ExamRoom::findOrFail($id);
        $result->delete();
    }
}
