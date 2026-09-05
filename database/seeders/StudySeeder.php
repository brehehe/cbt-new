<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Study\Study;
use Illuminate\Database\Seeder;

class StudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            $studies = [
                // Fakultas Kedokteran & Kesehatan
                'Kedokteran',
                'Ilmu Kedokteran',
                'Kedokteran Gigi',
                'Keperawatan',
                'Farmasi',
                'Kebidanan',
                'Kesehatan Masyarakat',
                'Gizi',
                'Profesi Dokter',
                'Pendidikan Dokter Spesialis',
            ];

            foreach ($studies as $studyName) {
                if (! Study::where('company_id', $company->id)->where('name', $studyName)->exists()) {
                    Study::create([
                        'company_id' => $company->id,
                        'name' => $studyName,
                        'description' => 'Program Studi '.$studyName,
                    ]);
                }
            }
        }
    }
}
