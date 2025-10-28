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
        try {
            $imagePaths = [];

            $folder = "answer/{$this->main_folder}";
            $disk = 'public';

            // Pastikan folder ada
            if (!\Storage::disk($disk)->exists($folder)) {
                \Storage::disk($disk)->makeDirectory($folder);
            }

            // 🔹 Proses gambar baru dari Livewire tmp
            if (isset($request['images']) && is_array($request['images'])) {
                foreach ($request['images'] as $img) {
                    if ($img instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                        // Pindahkan dari tmp ke folder final
                        $storedPath = $img->store($folder, $disk);
                        $imagePaths[] = '/' . $storedPath;
                    } else {
                        // Jika sudah berupa asset('storage/...') atau path lama
                        $path = str_replace(asset('storage'), '', $img);
                        $imagePaths[] = $path;
                    }
                }
            }

            // 🔹 Tambahkan old_images yang masih dipertahankan
            if (!empty($request['old_images'])) {
                foreach ($request['old_images'] as $old) {
                    $path = str_replace(asset('storage'), '', $old);
                    if (!in_array($path, $imagePaths)) {
                        $imagePaths[] = $path;
                    }
                }
            }

            // 🔹 Simpan ke database
            $answer = $question->answers()->updateOrCreate(
                ['id' => $request['id'] ?? null],
                [
                    'company_id' => $request['company_id'] ?? null,
                    'alphabet'   => $request['alphabet'] ?? null,
                    'context'    => $request['context'] ?? null,
                    'images'     => json_encode($imagePaths),
                    'is_correct' => $request['is_correct'] ?? false,
                ]
            );

            return $answer;
        } catch (\Throwable $th) {
            \Log::error('❌ Error di AnswerService::updateOrCreate', [
                'msg'  => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
            throw $th;
        }
    }

}
