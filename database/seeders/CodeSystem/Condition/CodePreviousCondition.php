<?php

namespace Database\Seeders\CodeSystem\Condition;

use App\Models\Master\CodeSystem\Condition\MasterConditionCodePreviousCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CodePreviousCondition extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/Condition/CodeSystem-condition-code-previous-condition.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {
            // $property = isset($value['property']) ? collect($value['property'])->firstWhere('code', 'notSelectable') : null;

            MasterConditionCodePreviousCondition::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                // 'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
