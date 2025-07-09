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
                'id' => $request['id'] ?? null
            ],
            [
                'name'        => $request['name'] ?? null,
                'description' => $request['description'] ?? null,
            ]
        );

        return $topic;
    }

    public function delete($topic)
    {
        $topic->delete();
    }
}
