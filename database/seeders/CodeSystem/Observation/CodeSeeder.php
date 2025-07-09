<?php

namespace Database\Seeders\CodeSystem\Observation;

use App\Models\Master\CodeSystem\Observation\MasterObservationCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // $file = database_path('seeders/CodeSystem/Files/Observation/LoincTableCore.csv');
        $file = database_path('seeders/csvs/tableConvert.com_ixsmxz.csv');

        if (!File::exists($file)) {
            $this->command->error("File $file tidak ditemukan.");
            return;
        }

        $csv = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($csv)); // Ambil header pertama


        foreach ($csv as $row) {
            $data = array_combine($header, $row);

            MasterObservationCode::create([
                'code' => $data['LOINC_NUM'],
                'display' => $data['LONG_COMMON_NAME']
            ]);
        }
    }
}
