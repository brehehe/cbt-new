<?php

namespace Database\Seeders;

use App\Jobs\Question\QuestionImportJob;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class QuestionPaketSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $folderPath = database_path('seeders/csvs');
            $files = File::files($folderPath);
            $user = Auth::user() ?? User::first(); // fallback kalau seeder tanpa auth
            $study_id = 'seeded_study_auto'; // dummy id untuk Job

            // Paket dari .env
            $package = env('QUESTION_PACKAGE', 'Lite');

            $paketMap = [
                'Lite' => 50,
                'Medium' => 75,
                'Advance' => 125,
            ];

            $paketKomprehensif = [
                'Lite' => 400,
                'Medium' => 600,
                'Advance' => 1000,
            ];

            $header = [
                'Prodi', 'Topik Soal', 'Kategori Materi', 'Materi Soal',
                'Tipe Soal', 'Soal', 'Deskripsi Soal', 'A', 'B', 'C', 'D', 'E', 'Jawaban',
            ];

            foreach ($files as $file) {
                $filename = $file->getFilename();
                $rows = array_map('str_getcsv', file($file->getRealPath()));

                if ($rows[0] !== $header) {
                    Log::warning("Header tidak sesuai di file {$filename}");

                    continue;
                }

                array_shift($rows);
                shuffle($rows);

                // Tentukan jumlah sesuai paket
                $count = str_contains(strtolower($filename), 'komprehensif')
                    ? $paketKomprehensif[$package]
                    : $paketMap[$package];

                $selected = array_slice($rows, 0, $count);

                // Ubah ke Collection agar sama dengan struktur QuestionImport
                $collection = new Collection;
                $collection->push(collect($header)); // header baris pertama

                foreach ($selected as $row) {
                    // Map 13 columns to 20 columns for QuestionImportJob compatibility
                    $mappedRow = [
                        $row[0] ?? null,  // 0: Prodi
                        $row[1] ?? null,  // 1: Topik Soal
                        $row[2] ?? null,  // 2: Kategori Materi
                        $row[3] ?? null,  // 3: Materi Soal
                        $row[4] ?? null,  // 4: Tipe Soal
                        null,             // 5: Kategori Soal
                        $row[5] ?? null,  // 6: Soal
                        $row[6] ?? null,  // 7: Deskripsi Soal
                        null,             // 8: URL Gambar Soal
                        $row[7] ?? null,  // 9: A
                        null,             // 10: URL Gambar A
                        $row[8] ?? null,  // 11: B
                        null,             // 12: URL Gambar B
                        $row[9] ?? null,  // 13: C
                        null,             // 14: URL Gambar C
                        $row[10] ?? null, // 15: D
                        null,             // 16: URL Gambar D
                        $row[11] ?? null, // 17: E
                        null,             // 18: URL Gambar E
                        $row[12] ?? null, // 19: Jawaban
                    ];
                    $collection->push(collect($mappedRow));
                }

                // Dispatch ke Job Import
                QuestionImportJob::dispatch($study_id, $user, $collection);

                Log::info("✅ {$count} soal acak dikirim ke queue dari {$filename} (paket: {$package})");
            }

            Log::info("🎯 Semua CSV telah dikirim ke QuestionImportJob untuk paket {$package}");
        } catch (\Throwable $th) {
            Log::error('Gagal menjalankan QuestionPaketSeeder', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
        }
    }
}
