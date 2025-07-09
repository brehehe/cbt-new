<?php

namespace Database\Seeders\CodeSystem\Condition;

use App\Models\Master\CodeSystem\Condition\MasterConditionSeverity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeveritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $datas = [
            [
                'code'    => 24484000,
                'display' => 'Severe'
            ],
            [
                'code'    => 6736007,
                'display' => 'Moderate'
            ],
            [
                'code'    => 255604002,
                'display' => 'Mild'
            ],
        ];

        foreach ($datas as $key => $value) {

            $master_verification_status = MasterConditionSeverity::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            ]);
        }
    }
}
