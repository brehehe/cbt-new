<?php

namespace Database\Seeders\CodeSystem\Condition;

use App\Models\Master\CodeSystem\Condition\MasterConditionVerificationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class VerificationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/Condition/CodeSystem-condition-ver-status.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        $this->_updateCreate($datas);
    }

    private function _updateCreate(array $datas, $master_condition_verification_status_id = null)
    {
        foreach ($datas as $key => $value) {

            $master_verification_status = MasterConditionVerificationStatus::create([
                'code'                                    => isset($value['code']) ? $value['code'] : null,
                'master_condition_verification_status_id' => $master_condition_verification_status_id,
                'display'                                 => isset($value['display']) ? $value['display'] : null,
                'definition'                              => isset($value['definition']) ? $value['definition'] : null,
                'comments'                                => isset($value['comments']) ? $value['comments'] : null,
            ]);

            if (isset($value['concept'])) {
                $this->_updateCreate($value['concept'], $master_verification_status?->id);
            }
        }
    }
}
