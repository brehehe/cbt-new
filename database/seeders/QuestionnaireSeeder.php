<?php

namespace Database\Seeders;

use App\Models\Company\Company;
use App\Models\Master\Questionnaire\Questionnaire;
use App\Models\Master\Questionnaire\QuestionnaireQuestion;
use App\Models\Master\Questionnaire\QuestionnaireSection;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $companyId = $company?->id;
        $companyName = $company?->name ?? 'Institusi';

        $questionnaire = Questionnaire::withoutGlobalScopes()->updateOrCreate(
            ['slug' => 'evaluasi-modul-mahasiswa'],
            [
                'company_id' => $companyId,
                'title' => 'Form Evaluasi Modul Mahasiswa' . ($company?->name ? ' - ' . $company->name : ''),
                'description' => 'Evaluasi proses modul, tutor KKD, fasilitator diskusi kelompok, narasumber kuliah, dan saran pengembangan untuk ' . $companyName . '.',
                'academic_year' => '2025/2026',
                'semester' => 'Ganjil',
                'is_active' => true,
                'allow_guest' => true,
                'require_nim' => true,
            ]
        );

        // Section 1: Evaluasi terhadap Proses Modul
        $section1 = QuestionnaireSection::updateOrCreate(
            ['questionnaire_id' => $questionnaire->id, 'title' => 'Evaluasi terhadap Proses Modul'],
            ['description' => 'Penilaian terhadap penyelenggaraan dan efektivitas proses modul secara keseluruhan.', 'order' => 1]
        );

        $s1Questions = [
            [
                'text' => 'Secara umum, bagaimana kepuasan Anda terhadap proses modul?',
                'type' => 'rating_5',
                'min' => 'Sangat tidak puas',
                'max' => 'Sangat puas',
                'required' => true,
            ],
            [
                'text' => 'Anda memahami sasaran pembelajaran dari modul ini',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Hubungan anda dengan staf pengajar yang terlibat modul baik',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'PJ modul mendukung terselenggaranya modul dengan baik',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Soal ujian sumatif sesuai dengan materi modul',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Pemicu mampu mendorong anda untuk belajar mandiri dengan baik (self directed learning)',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Masalah dalam pemicu dapat mendorong diskusi berjalan aktif',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Anda merasa temu pakar bermanfaat untuk mendukung proses belajar',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Temu pakar menambah pemahaman materi modul',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Praktikum berjalan sesuai sasaran pembelajaran',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Slide praktikum tersedia lengkap',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Materi praktikum mudah dipahami',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Ujian praktikum sesuai materi modul',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
        ];

        foreach ($s1Questions as $idx => $q) {
            QuestionnaireQuestion::updateOrCreate(
                ['section_id' => $section1->id, 'question_text' => $q['text']],
                [
                    'question_type' => $q['type'],
                    'min_label' => $q['min'],
                    'max_label' => $q['max'],
                    'is_required' => $q['required'],
                    'order' => $idx + 1,
                ]
            );
        }

        // Section 2: Evaluasi Tutor KKD
        $section2 = QuestionnaireSection::updateOrCreate(
            ['questionnaire_id' => $questionnaire->id, 'title' => 'Evaluasi Tutor KKD'],
            ['description' => 'Penilaian terhadap kinerja dan kehadiran Tutor KKD.', 'order' => 2]
        );

        $s2Questions = [
            [
                'text' => 'Nama tutor KKD',
                'type' => 'input_name',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
            [
                'text' => 'Secara umum, bagaimana tingkat kepuasan Anda terhadap tutor KKD?',
                'type' => 'rating_5',
                'min' => 'Sangat buruk',
                'max' => 'Sangat baik',
                'required' => true,
            ],
            [
                'text' => 'Tutor datang saat KKD tepat waktu',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Tutor mengakhiri KKD tepat waktu',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Tutor selalu memberikan umpan balik kepada masing-masing mahasiswa selama KKD',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
        ];

        foreach ($s2Questions as $idx => $q) {
            QuestionnaireQuestion::updateOrCreate(
                ['section_id' => $section2->id, 'question_text' => $q['text']],
                [
                    'question_type' => $q['type'],
                    'min_label' => $q['min'],
                    'max_label' => $q['max'],
                    'is_required' => $q['required'],
                    'order' => $idx + 1,
                ]
            );
        }

        // Section 3: Evaluasi Fasilitator Diskusi Kelompok (DK)
        $section3 = QuestionnaireSection::updateOrCreate(
            ['questionnaire_id' => $questionnaire->id, 'title' => 'Evaluasi Fasilitator Diskusi Kelompok (DK)'],
            ['description' => 'Penilaian terhadap Fasilitator Diskusi Kelompok (DK).', 'order' => 3]
        );

        $s3Questions = [
            [
                'text' => 'Nama fasilitator diskusi kelompok (DK)',
                'type' => 'input_name',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
            [
                'text' => 'Secara umum, bagaimana tingkat kepuasan Anda terhadap Fasil DK tersebut?',
                'type' => 'rating_5',
                'min' => 'Sangat buruk',
                'max' => 'Sangat baik',
                'required' => true,
            ],
            [
                'text' => 'Fasilitator datang dan mengakhiri diskusi tepat waktu',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Fasilitator memberikan umpan balik kepada setiap mahasiswa',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Fasilitator menjaga dinamika diskusi dengan memotivasi mahasiswa untuk berperan aktif',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
        ];

        foreach ($s3Questions as $idx => $q) {
            QuestionnaireQuestion::updateOrCreate(
                ['section_id' => $section3->id, 'question_text' => $q['text']],
                [
                    'question_type' => $q['type'],
                    'min_label' => $q['min'],
                    'max_label' => $q['max'],
                    'is_required' => $q['required'],
                    'order' => $idx + 1,
                ]
            );
        }

        // Section 4: Evaluasi Narasumber (Kuliah)
        $section4 = QuestionnaireSection::updateOrCreate(
            ['questionnaire_id' => $questionnaire->id, 'title' => 'Evaluasi Narasumber (Kuliah)'],
            ['description' => 'Penilaian terhadap narasumber atau dosen pemberi kuliah pada modul ini.', 'order' => 4]
        );

        $s4Questions = [
            [
                'text' => 'Nama Narasumber (Kuliah)',
                'type' => 'input_name',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
            [
                'text' => 'Secara umum, bagaimana tingkat kepuasan Anda terhadap narasumber tersebut?',
                'type' => 'rating_5',
                'min' => 'Sangat buruk',
                'max' => 'Sangat baik',
                'required' => true,
            ],
            [
                'text' => 'Narasumber datang dan mengakhiri kuliah tepat waktu',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Narasumber mampu menggunakan alat penunjang pembelajaran secara baik (LCD, PC dll)',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
            [
                'text' => 'Narasumber dalam memberikan kuliah mampu menjelaskan secara sistematis, jelas, dan mudah dipahami',
                'type' => 'likert_5',
                'min' => 'Sangat tidak setuju/tidak pernah',
                'max' => 'Sangat setuju/selalu',
                'required' => true,
            ],
        ];

        foreach ($s4Questions as $idx => $q) {
            QuestionnaireQuestion::updateOrCreate(
                ['section_id' => $section4->id, 'question_text' => $q['text']],
                [
                    'question_type' => $q['type'],
                    'min_label' => $q['min'],
                    'max_label' => $q['max'],
                    'is_required' => $q['required'],
                    'order' => $idx + 1,
                ]
            );
        }

        // Section 5: Saran / Hal Lain
        $section5 = QuestionnaireSection::updateOrCreate(
            ['questionnaire_id' => $questionnaire->id, 'title' => 'Saran / Hal Lain'],
            ['description' => 'Saran, masukan, dan kritik konstruktif untuk unit-unit terkait.', 'order' => 5]
        );

        $s5Questions = [
            [
                'text' => 'Saran/hal lain yang ingin disampaikan ke Prodi:',
                'type' => 'text',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
            [
                'text' => 'Saran/hal lain yang ingin disampaikan ke MEU (Medical Education Unit):',
                'type' => 'text',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
            [
                'text' => 'Saran/hal lain yang ingin disampaikan ke CSU (Clinical Simulation Unit):',
                'type' => 'text',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
            [
                'text' => 'Saran/hal lain yang ingin disampaikan ke MIT (Medical Information and Technology):',
                'type' => 'text',
                'min' => null,
                'max' => null,
                'required' => false,
            ],
        ];

        foreach ($s5Questions as $idx => $q) {
            QuestionnaireQuestion::updateOrCreate(
                ['section_id' => $section5->id, 'question_text' => $q['text']],
                [
                    'question_type' => $q['type'],
                    'min_label' => $q['min'],
                    'max_label' => $q['max'],
                    'is_required' => $q['required'],
                    'order' => $idx + 1,
                ]
            );
        }
    }
}
