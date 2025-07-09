<?php

namespace Database\Seeders\CodeSystem\Encounter;

use App\Models\Master\CodeSystem\Encounter\MasterEncounterParticipationType;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ParticipantTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Ambil isi file JSON
        $json = File::get(database_path('seeders/CodeSystem/Files/Encounter/CodeSystem-v3-ParticipationType.json'));

        $datas = isset(json_decode($json, true)['concept']) ? json_decode($json, true)['concept'] : [];

        $this->_participationType($datas);
    }

    public function _participationType(array $datas)
    {
        foreach ($datas as $key => $value) {
            // dd($value);
            $property = collect($value['property'])->firstWhere('code', 'notSelectable');

            MasterEncounterParticipationType::create([
                'code'       => isset($value['code']) ? $value['code'] : null,
                'display'    => isset($value['display']) ? $value['display'] : null,
                'definition' => isset($value['definition']) ? $value['definition'] : null,
                'deleted_at' => $property != null ? Carbon::now()->toDateString() : null
            ]);

            if (isset($value['concept'])) {
                $this->_participationType($value['concept']);
            }
        }
    }
}
