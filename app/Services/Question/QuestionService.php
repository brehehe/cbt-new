<?php

namespace App\Services\Question;

use Carbon\Carbon;
use App\Traits\UploadFile;
use App\Models\Master\Question\Question;
use Illuminate\Support\Facades\File;

class QuestionService
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
    public function updateOrCreate($request)
    {
        $images = [];
        if (!empty($request['images'])) {
            foreach ($request['old_images'] ?? [] as $key => $old_image) {
                File::delete(public_path('storage/' . $old_image));
            }

            foreach ($request['images'] as $key => $image) {
                $get_url_image = $this->uploadFile($image, "/public/question/{$this->main_folder}");
                $images[] = "/question/{$this->main_folder}/". $get_url_image[1];
            }
        }

        $question = Question::updateOrCreate(
            [
                'id' => $request['id'] ?? null
            ],
            [
                'company_id'           => $request['company_id'] ?? null,
                'topic_id'             => $request['topic_id'] ?? null,
                'material_category_id' => $request['material_category_id'] ?? null,
                'material_id'          => $request['material_id'] ?? null,
                'question_type_id'     => $request['question_type_id'] ?? null,
                'question'             => $request['question'] ?? null,
                'weight_correct'       => $request['weight_correct'] ?? false,
                'weight_incorrect'     => $request['weight_incorrect'] ?? false,
                'description'          => $request['description'] ?? null,
            ]
        );

        if (!empty($request['images'])) {
            $question->update([
                'images' => json_encode($images)
            ]);
        }

        return $question;
    }

    public function delete($id)
    {
        $result = Question::findOrFail($id);
        $result->delete();
    }
}
