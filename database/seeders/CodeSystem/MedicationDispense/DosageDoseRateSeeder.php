<?php

namespace Database\Seeders\CodeSystem\MedicationDispense;

use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseDosageDoseRate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DosageDoseRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/MedicationDispense/CodeSystem-medicationdispense-dosage-dose-rate.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {

        MasterMedicationDispenseDosageDoseRate::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
