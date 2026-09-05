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
            if ($collections->isEmpty()) {
                throw new Exception('File Excel kosong atau tidak memiliki data.');
            }

            // Find header row dynamically (within the first 50 rows)
            $foundHeaderRow = false;
            foreach ($collections as $rowIndex => $row) {
                if ($rowIndex > 50) {
                    break;
                }
                $rowArray = $row instanceof Collection ? $row->toArray() : (array) $row;
                foreach ($rowArray as $cell) {
                    if (is_string($cell) && strtolower(trim($cell)) === 'prodi') {
                        $foundHeaderRow = true;
                        break 2;
                    }
                }
            }

            if (! $foundHeaderRow) {
                throw new Exception('Header Prodi Tidak di temukan. Harap periksa kembali template anda.');
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
            throw $th;
        }
    }
}
