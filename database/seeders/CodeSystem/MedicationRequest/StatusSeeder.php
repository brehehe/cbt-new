<?php

namespace Database\Seeders\CodeSystem\MedicationRequest;

use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/MedicationRequest/CodeSystem-medicationrequest-status.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        $this->_create($datas);
    }

    private function _create($datas, $parent_id = null)
    {
        foreach ($datas as $key => $value) {

        MasterMedicationRequestStatus::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);

        if (isset($value['concept'])) {
                $this->_create($value['concept']);
            }
        }
    }
}
