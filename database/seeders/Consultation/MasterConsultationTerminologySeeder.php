<?php

namespace Database\Seeders\Consultation;

use App\Models\Master\CodeSystem\Consultation\MasterConsultationTerminology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterConsultationTerminologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            [
                "Code" => "CC000024",
                "Display" => "Perubahan nilai laboratorium terkait gizi",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000007",
                "Display" => "Malnutrisi pada penyakit akut",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000008",
                "Display" => "Malnutrisi pada anak (tidak terkait penyakit)",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000009",
                "Display" => "Malnutrisi pada anak (terkait penyakit)",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000010",
                "Display" => "Keterbatasan/ ketidakpatuhan dalam menjalankan rekomendasi diet",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000019",
                "Display" => "Riwayat diare",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000020",
                "Display" => "Riwayat infeksi saluran pernapasan akut",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000021",
                "Display" => "Riwayat kecacingan",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000022",
                "Display" => "Riwayat defisiensi vitamin A",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000023",
                "Display" => "Riwayat xeroftalmia",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000016",
                "Display" => "Riwayat glomerulonefritis",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000017",
                "Display" => "Riwayat penyakit tubulointerstisial",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ],
            [
                "Code" => "CC000018",
                "Display" => "Riwayat obstruksi saluran kemih",
                "Code System" => "http=>//terminology.kemkes.go.id/CodeSystem/clinical-term",
                "Keterangan" => ""
            ]
        ];

        foreach ($datas as $data) {
            MasterConsultationTerminology::updateOrCreate(
                [
                    'code' => $data['Code'],
                ],
                [
                    'display' => $data['Display'],
                    'code_system' => $data['Code System'],
                    'keterangan' => $data['Keterangan'],
                ]
            );
        }
    }
}
