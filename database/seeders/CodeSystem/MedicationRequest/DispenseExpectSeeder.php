<?php

namespace Database\Seeders\CodeSystem\MedicationRequest;

use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseExpect;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DispenseExpectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path('seeders/CodeSystem/Files/MedicationRequest/CodeSystem-medicationrequest-dispense-expect.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {

        MasterMedicationRequestDispenseExpect::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
