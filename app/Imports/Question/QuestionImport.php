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
    * @param Collection $collection
    */
    public function __construct(
        protected string $study_id,        // contoh param
    ) {}

    public function collection(Collection $collections)
    {
        //
        try {
            //head column
            $header = [
                'Prodi',
                'Topik Soal',
                'Kategori Materi',
                'Materi Soal',
                'Tipe Soal',
                'Soal',
                'Deskripsi Soal',
                'A',
                'B',
                'C',
                'D',
                'E',
                'Jawaban',
            ];

            for ($i = 0; $i < count($header); $i++) {
                if ($collections[0][$i] != $header[$i]) {
                    throw new Exception("Header " . $header[$i] . " Tidak di temukan");
                }
            }

            $user = Auth::user();

            QuestionImportJob::dispatch($this->study_id, $user, $collections);
        } catch (Exception | \Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error("Ada kesalahan saat Question Import", $error);
        }
    }
}
