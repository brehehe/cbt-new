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
            $folder = "question/{$this->main_folder}";
            $disk   = 'public';

            if (!Storage::disk($disk)->exists($folder)) {
                Storage::disk($disk)->makeDirectory($folder);
            }

            // helper: ubah apa pun (TemporaryUploadedFile / URL) jadi path storage konsisten: "/question/....jpg"
            $normalize = function ($val) use ($folder, $disk) {
                if ($val instanceof TemporaryUploadedFile) {
                    $stored = $val->store($folder, $disk);           // "question/2025/11/xxx.jpg"
                    return '/'.ltrim($stored, '/');                  // "/question/2025/11/xxx.jpg"
                }
                // String bisa berupa URL lengkap atau path
                // Coba extract path dari URL
                $parsed = parse_url($val);
                if ($parsed !== false && isset($parsed['path'])) {
                    $path = $parsed['path'];  // /storage/question/2026/02/xxx.jpg
                } else {
                    $path = (string) $val;
                }
                
                // Hilangkan /storage dan buat path relatif
                $path = Str::of($path)->replaceFirst('/storage', '')->trim('/')->prepend('/')->toString();
                return $path;  // "/question/2026/02/xxx.jpg"
            };

            // 1) FINAL = normalisasi semua item di request['images'] (ini sumber kebenaran)
            $final = [];
            foreach (($request['images'] ?? []) as $img) {
                $final[$normalize($img)] = true;   // pakai associative utk unique
            }

            // 2) Normalisasi OLD utk hitung mana yang dihapus
            $old = [];
            foreach (($request['old_images'] ?? []) as $img) {
                $old[$normalize($img)] = true;
            }

            // 3) Hapus file yang tidak ada lagi di final
            $toDelete = array_diff(array_keys($old), array_keys($final));
            foreach ($toDelete as $rm) {
                $filePath = ltrim($rm, '/');  // hapus leading slash
                if (Storage::disk($disk)->exists($filePath)) {
                    Storage::disk($disk)->delete($filePath);
                    \Log::info('✅ Hapus gambar soal: ' . $filePath);
                }
            }

            // 4) Simpan hasil akhir
            $imagePaths = array_keys($final);

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
                    'latex'                => $request['latex'] ?? null,
                    'images'               => json_encode($imagePaths),
                    'weight_correct'       => $request['weight_correct'] ?? null,
                    'weight_incorrect'     => $request['weight_incorrect'] ?? null,
                    'description'          => $request['description'] ?? null,
                    'category_question_id'  => $request['category_question_id'] ?? null,
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
