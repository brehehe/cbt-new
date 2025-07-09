<?php

namespace Database\Seeders\CodeSystem\Encounter;

use App\Models\Master\CodeSystem\Encounter\MasterEncounterServiceType;
use App\Models\Master\CodeSystem\Encounter\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/Encounter/CodeSystem-service-type.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {

        MasterEncounterServiceType::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
            //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
