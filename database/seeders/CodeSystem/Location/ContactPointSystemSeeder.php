<?php

namespace Database\Seeders\CodeSystem\Location;

use App\Models\Master\CodeSystem\Location\MasterLocationContactPointSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ContactPointSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $json = File::get(database_path('seeders/CodeSystem/Files/Location/CodeSystem-contact-point-system.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        foreach ($datas as $key => $value) {

        MasterLocationContactPointSystem::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'comments'   => isset($value['comments']) ? $value['comments'] : null,
            //  'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);
        }
    }
}
