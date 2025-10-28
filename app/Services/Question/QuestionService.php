<?php

namespace App\Services\Question;

use Carbon\Carbon;
use App\Traits\UploadFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Master\Question\Question;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
    try {
        $imagePaths = [];

        // 🔹 Pastikan folder tujuan ada
        $folder = "question/{$this->main_folder}";
        $disk = 'public';
        if (!\Storage::disk($disk)->exists($folder)) {
            \Storage::disk($disk)->makeDirectory($folder);
        }

        // 🔹 Proses semua gambar
        if (isset($request['images']) && is_array($request['images'])) {
            foreach ($request['images'] as $img) {
                // Jika masih berupa TemporaryUploadedFile dari Livewire
                if ($img instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $storedPath = $img->store($folder, $disk);
                    $imagePaths[] = '/' . $storedPath;
                } else {
                    // Jika dari edit (sudah asset('storage/...'))
                    $path = str_replace(asset('storage'), '', $img);
                    $imagePaths[] = $path;
                }
            }
        }

        // 🔹 Gabungkan gambar lama jika masih dipertahankan
        if (!empty($request['old_images'])) {
            foreach ($request['old_images'] as $old) {
                $path = str_replace(asset('storage'), '', $old);
                if (!in_array($path, $imagePaths)) {
                    $imagePaths[] = $path;
                }
            }
        }

        // 🔹 Simpan data
        $question = \App\Models\Master\Question\Question::updateOrCreate(
            ['id' => $request['id'] ?? null],
            [
                'user_id'              => $request['user_id'] ?? null,
                'study_id'             => $request['study_id'] ?? null,
                'company_id'           => $request['company_id'] ?? null,
                'topic_id'             => $request['topic_id'] ?? null,
                'material_category_id' => $request['material_category_id'] ?? null,
                'material_id'          => $request['material_id'] ?? null,
                'question_type_id'     => $request['question_type_id'] ?? null,
                'question'             => $request['question'] ?? null,
                'images'               => json_encode($imagePaths),
                'weight_correct'       => $request['weight_correct'] ?? null,
                'weight_incorrect'     => $request['weight_incorrect'] ?? null,
                'description'          => $request['description'] ?? null,
            ]
        );

        return $question;

    } catch (\Throwable $th) {
        \Log::error('❌ Error di QuestionService::updateOrCreate', [
            'msg'  => $th->getMessage(),
            'file' => $th->getFile(),
            'line' => $th->getLine(),
        ]);
        throw $th;
    }
}


    private function uploadImages(array $old_images, array $new_images)
    {
        $images = $currentUrls = $newPaths = $uploadFiles =[];

        foreach ($new_images as $item) {
            if (is_string($item)) {
                $relative = ltrim(Str::after(parse_url($item, PHP_URL_PATH), '/storage/'), '/');
                $currentUrls[] = asset('storage/'. $relative);
            }

            if ($item instanceof TemporaryUploadedFile) {
                $url_image = $this->uploadFile($item, "/public/question/{$this->main_folder}");
                $newPaths[] = "/question/{$this->main_folder}/". $url_image[1];
            }
        }

        $toDelete = array_diff($old_images, $currentUrls);

        foreach ($toDelete as $oldPath) {
            $relativePath = ltrim(Str::after(parse_url($oldPath, PHP_URL_PATH), '/storage/'), '/');
            Storage::disk('public')->delete($relativePath);
        }

        $uploadFiles = array_merge($currentUrls, $newPaths);

        foreach ($uploadFiles as $key => $file) {
            $images[] = '/'.ltrim(Str::after(parse_url($file, PHP_URL_PATH), '/storage/'), '/');
        }

        return $images;
    }

    public function delete($id)
    {
        $result = Question::findOrFail($id);
        $result->delete();
    }
}
