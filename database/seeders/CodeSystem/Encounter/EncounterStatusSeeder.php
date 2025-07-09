<?php

namespace Database\Seeders\CodeSystem\Encounter;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Master\CodeSystem\Encounter\EncounterStatus;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus;
use Illuminate\Support\Facades\File;

class EncounterStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/Encounter/CodeSystem-encounter-status.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {
            // $property = collect($value['property'])->firstWhere('code', 'notSelectable');

            MasterEncounterStatus::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                // 'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
