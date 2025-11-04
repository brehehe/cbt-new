<?php

namespace App\Services\Answer;

use Carbon\Carbon;
use App\Traits\UploadFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Question\Answer;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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

             if (!Storage::disk($disk)->exists($folder)) {
                Storage::disk($disk)->makeDirectory($folder);
            }

            // helper: ubah apa pun (TemporaryUploadedFile / URL) jadi path storage konsisten: "/question/....jpg"
            $normalize = function ($val) use ($folder, $disk) {
                if ($val instanceof TemporaryUploadedFile) {
                    $stored = $val->store($folder, $disk);           // "question/2025/11/xxx.jpg"
                    return '/'.ltrim($stored, '/');                  // "/question/2025/11/xxx.jpg"
                }
                // String URL/relative -> ambil path & hilangkan prefix "/storage"
                $path = parse_url($val, PHP_URL_PATH) ?? (string) $val;   // "/storage/question/....jpg"
                $path = Str::of($path)->replaceFirst('/storage', '')->start('/')->toString();
                return $path;                                             // "/question/....jpg"
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
                Storage::disk($disk)->delete(ltrim($rm, '/')); // hapus "question/....jpg"
            }

            // 4) Simpan hasil akhir
            $imagePaths = array_keys($final);

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
