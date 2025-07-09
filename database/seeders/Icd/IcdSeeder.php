<?php

namespace Database\Seeders\Icd;

use App\Models\Icd\Icd10;
use App\Models\Icd\Icd9;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class IcdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = database_path('seeders/csvs/icd9.csv');

        if (!File::exists($file)) {
            $this->command->error("File $file tidak ditemukan.");
            return;
        }

        $csv = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($csv)); // Ambil header pertama

        foreach ($csv as $row) {
            $data = array_combine($header, $row);

            Icd9::create([
                'code' => $data['CODE'],
                'display' => $data['DISPLAY'],
                'version' => $data['VERSION'],
            ]);
        }


        $file2 = database_path('seeders/csvs/icd10.csv');

        if (!File::exists($file2)) {
            $this->command->error("File $file2 tidak ditemukan.");
            return;
        }

        $csv2 = array_map('str_getcsv', file($file2));
        $headers = array_map('trim', array_shift($csv2)); // Ambil header pertama

        foreach ($csv2 as $row2) {
            $data2 = array_combine($headers, $row2);

            Icd10::create([
                'code' => $data2['CODE'],
                'display' => $data2['DISPLAY'],
                'version' => $data2['VERSION'],
            ]);
        }

        // $this->command->info("Berhasil mengimpor data dari CSV.");
    }
}
