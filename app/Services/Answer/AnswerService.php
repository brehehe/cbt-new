<?php

namespace App\Services\Answer;

use App\Models\Master\Question\Answer;

class AnswerService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreate($question, $request)
    {
        $answer = $question->answers()->updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'company_id' => $request['company_id'] ?? null,
                'alphabet'   => $request['alphabet'] ?? null,
                'context'    => $request['context'] ?? null,
                // 'images'     => $request['images'] ?? null,
                'is_correct' => $request['is_correct'] ?? false,
            ]
        );

        return $answer;
    }
}
