<?php

namespace App\Services\Question;

use App\Models\Master\Question\Question;
use App\Traits\UploadFile;

class QuestionService
{
    use UploadFile;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function updateOrCreate($request)
    {
        dd($request);
        $question = Question::updateOrCreate(
            [
                'id' => $request['id'] ?? null
            ],
            [
                'company_id'       => $request['company_id'] ?? null,
                'question_type_id' => $request['question_type_id'] ?? null,
                'name'             => $request['name'] ?? null,
                'duration'         => $request['duration'] ?? null,
                'random_question'  => $request['random_question'] ?? false,
                'description'      => $request['description'] ?? null,
            ]
        );

        return $question;
    }

    public function delete($id)
    {
        $result = Question::findOrFail($id);
        $result->delete();
    }
}
