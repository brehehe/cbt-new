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

            $existingAnswer = null;
            if (!empty($request['id'])) {
                $existingAnswer = Answer::withoutGlobalScope('user_scope')
                    ->withTrashed()
                    ->where('question_id', $question->id)
                    ->find($request['id']);
            }

             if (!Storage::disk($disk)->exists($folder)) {
                Storage::disk($disk)->makeDirectory($folder);
            }

            // helper: ubah apa pun (TemporaryUploadedFile / URL) jadi path storage konsisten: "/question/....jpg"
            $normalize = function ($val) use ($folder, $disk) {
                if ($val instanceof TemporaryUploadedFile) {
                    $stored = $val->store($folder, $disk);           // "answer/2025/11/xxx.jpg"
                    return '/'.ltrim($stored, '/');                  // "/answer/2025/11/xxx.jpg"
                }
                // String bisa berupa URL lengkap atau path
                // Coba extract path dari URL
                $parsed = parse_url($val);
                if ($parsed !== false && isset($parsed['path'])) {
                    $path = $parsed['path'];  // /storage/answer/2026/02/xxx.jpg
                } else {
                    $path = (string) $val;
                }
                
                // Hilangkan /storage dan buat path relatif
                $path = Str::of($path)->replaceFirst('/storage', '')->trim('/')->prepend('/')->toString();
                return $path;  // "/answer/2026/02/xxx.jpg"
            };

            // 1) FINAL = normalisasi semua item di request['images'] (ini sumber kebenaran)
            $final = [];
            foreach (($request['images'] ?? []) as $img) {
                if ($img === null || $img === '') {
                    continue;
                }
                $final[$normalize($img)] = true;   // pakai associative utk unique
            }

            // 2) Normalisasi OLD utk hitung mana yang dihapus
            $old = [];
            foreach (($request['old_images'] ?? []) as $img) {
                if ($img === null || $img === '') {
                    continue;
                }
                $old[$normalize($img)] = true;
            }

            if (empty($final) && $existingAnswer?->images) {
                $existingImages = is_string($existingAnswer->images)
                    ? json_decode($existingAnswer->images, true)
                    : $existingAnswer->images;

                foreach ($existingImages ?? [] as $img) {
                    if ($img === null || $img === '') {
                        continue;
                    }
                    $final[$normalize($img)] = true;
                }
            }

            // 3) Hapus file yang tidak ada lagi di final
            $toDelete = array_diff(array_keys($old), array_keys($final));
            foreach ($toDelete as $rm) {
                $filePath = ltrim($rm, '/');  // hapus leading slash
                if (Storage::disk($disk)->exists($filePath)) {
                    Storage::disk($disk)->delete($filePath);
                    \Log::info('✅ Hapus gambar jawaban: ' . $filePath);
                }
            }

            // 4) Simpan hasil akhir
            $imagePaths = array_keys($final);

            if ($existingAnswer) {
                $alphabet = (array_key_exists('alphabet', $request) && $request['alphabet'] !== null && $request['alphabet'] !== '')
                    ? $request['alphabet']
                    : ($existingAnswer->alphabet ?? null);
            } else {
                $alphabet = (array_key_exists('alphabet', $request) && $request['alphabet'] !== null && $request['alphabet'] !== '')
                    ? $request['alphabet']
                    : null;
            }
            $context = (array_key_exists('context', $request) && $request['context'] !== null)
                ? $request['context']
                : ($existingAnswer?->context ?? null);
            $isCorrect = array_key_exists('is_correct', $request)
                ? $request['is_correct']
                : ($existingAnswer?->is_correct ?? false);

            if (($alphabet === null || $alphabet === '') && !$existingAnswer) {
                $alphabet = $this->resolveAlphabet($existingAnswer, $question);
            }

            // 🔹 Simpan ke database
            $answer = Answer::withoutGlobalScope('user_scope')
                ->withTrashed()
                ->updateOrCreate(
                    ['id' => $request['id'] ?? null],
                    [
                        'question_id' => $question->id,
                        'company_id' => $request['company_id'] ?? null,
                        'alphabet'   => $alphabet,
                        'context'    => $context,
                        'images'     => json_encode($imagePaths),
                        'is_correct' => $isCorrect,
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

    private function resolveAlphabet($existingAnswer, $question): string
    {
        if ($existingAnswer?->order) {
            return chr(64 + (int) $existingAnswer->order);
        }

        $nextOrder = (int) Answer::withoutGlobalScope('user_scope')
            ->where('question_id', $question->id)
            ->max('order');

        $nextOrder = $nextOrder > 0 ? $nextOrder + 1 : 1;

        return chr(64 + $nextOrder);
    }

}
