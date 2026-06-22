<?php

namespace App\Imports\Question;

use App\Jobs\Question\QuestionImportJob;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class QuestionImport implements ToCollection
{
    /**
     * @param  Collection  $collection
     */
    public function __construct(
        protected string $study_id,
        protected string $import_type = 'pg',
    ) {}

    public function collection(Collection $collections)
    {
        try {
            // head column
            if ($this->import_type == 'pg') {
                $header = [
                    'Prodi',
                    'Topik Soal',
                    'Kategori Materi',
                    'Materi Soal',
                    'Tipe Soal',
                    'Kategori Soal',
                    'Soal',
                    'Deskripsi Soal',
                    'URL Gambar Soal',
                    'A',
                    'URL Gambar A',
                    'B',
                    'URL Gambar B',
                    'C',
                    'URL Gambar C',
                    'D',
                    'URL Gambar D',
                    'E',
                    'URL Gambar E',
                    'Jawaban',
                ];
            } else {
                // Format Essay
                $header = [
                    'Prodi',
                    'Topik Soal',
                    'Kategori Materi',
                    'Materi Soal',
                    'Tipe Soal',
                    'Kategori Soal',
                    'Soal',
                    'Deskripsi Soal',
                    'URL Gambar Soal',
                    'Jawaban Referensi',
                    'URL Gambar Jawaban',
                ];
            }

            for ($i = 0; $i < count($header); $i++) {
                if (trim($collections[0][$i]) != $header[$i]) {
                    throw new Exception('Header '.$header[$i].' Tidak di temukan. Harap periksa kembali template anda.');
                }
            }

            $user = Auth::user();

            QuestionImportJob::dispatch($this->study_id, $user, $collections, $this->import_type);
        } catch (Exception|\Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada kesalahan saat Question Import', $error);
        }
    }
}
