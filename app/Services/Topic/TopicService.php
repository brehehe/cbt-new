<?php

namespace App\Services\Topic;

use App\Models\Master\Question\Topic;

class TopicService
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
        $topic = Topic::updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'company_id' => $request['company_id'] ?? null,
                'study_id' => $request['study_id'] ?? null,
                'name' => $request['name'] ?? null,
                'description' => $request['description'] ?? null,
            ]
        );

        return $topic;
    }

    public function delete($id)
    {
        $result = Topic::findOrFail($id);
        $result->delete();
    }
}
