<?php

namespace Database\Seeders\CodeSystem\MedicationRequest;

use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequest;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestIdentifierUse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class IdentifierUseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/MedicationRequest/CodeSystem-identifier-use.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {

        MasterMedicationRequestIdentifierUse::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
