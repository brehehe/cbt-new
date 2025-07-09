<?php

namespace Database\Seeders\CodeSystem\Encounter;

use App\Models\Master\CodeSystem\Encounter\ActPriority;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ActPrioritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/Encounter/CodeSystem-v3-ActPriority.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {

            ActPriority::create([
                    'code'       => isset($value['code']) ? $value['code'] : null,
                    'display'    => isset($value['display']) ? $value['display'] : null,
                    'definition' => isset($value['definition']) ? $value['definition'] : null,
                //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
                ]);
            }
    }
}
