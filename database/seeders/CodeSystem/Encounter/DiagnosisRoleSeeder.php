<?php

namespace Database\Seeders\CodeSystem\Encounter;

use App\Models\Master\CodeSystem\Encounter\MasterEncounterDiagnosisRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiagnosisRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datas = [
            [
                'code'    => 'AD',
                'display' => 'Admission diagnosis'
            ],
            [
                'code'    => 'DD',
                'display' => 'Discharge diagnosis'
            ],
            [
                'code'    => 'CC',
                'display' => 'Chief complaint'
            ],
            [
                'code'    => 'CM',
                'display' => 'Comorbidity diagnosis'
            ],
            [
                'code'    => 'pre-op',
                'display' => 'pre-op diagnosis'
            ],
            [
                'code'    => 'post-op',
                'display' => 'post-op diagnosis'
            ],
            [
                'code'    => 'billing',
                'display' => 'Billing'
            ],
        ];

        foreach ($datas as $key => $value) {

            MasterEncounterDiagnosisRole::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            ]);
        }
    }
}
