<?php

namespace App\Services\Answer;

use Carbon\Carbon;
use App\Traits\UploadFile;
use App\Models\Master\Question\Answer;

class AnswerService
{
    use UploadFile;
    /**
     * Create a new class instance.
     */
    public $main_folder;

    public function __construct()
    {
        //
        $this->main_folder = Carbon::now()->isoFormat('Y') . '/' . Carbon::now()->isoFormat('MM');
    }

    public function updateOrCreate($question, $request)
    {
        $images = [];

        $images = $this->multipleFileUpload($request['old_images'], $request['images'], "answer/$this->main_folder");

        $answer = $question->answers()->updateOrCreate(
            [
                'id' => $request['id'] ?? null,
            ],
            [
                'company_id' => $request['company_id'] ?? null,
                'alphabet'   => $request['alphabet'] ?? null,
                'context'    => $request['context'] ?? null,
                'images'     => json_encode($images),
                'is_correct' => $request['is_correct'] ?? false,
            ]
        );

        return $answer;
    }
}
