<?php

namespace Database\Seeders\CodeSystem\MedicationDispense;

use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseOrderableDrugForm;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm;

class OrderableDrugFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/MedicationDispense/CodeSystem-v3-orderableDrugForm.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {
            $property = isset($value['property']) ? collect($value['property'])->firstWhere('code', 'notSelectable') : null;

            MasterMedicationDispenseOrderableDrugForm::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
