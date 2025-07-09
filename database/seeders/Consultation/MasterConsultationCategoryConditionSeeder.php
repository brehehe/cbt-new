<?php

namespace Database\Seeders\Consultation;

use App\Models\Master\CodeSystem\Consultation\MasterConsultationCategoryCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterConsultationCategoryConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'code' => 'problem-list-item',
                'display' => 'Problem List Item',
                'definition' => 'An item on a problem list that can be managed over time and can be expressed by a practitioner (e.g. physician, nurse), patient, or related person.',
            ],
            [
                'code' => 'encounter-diagnosis',
                'display' => 'Encounter Diagnosis',
                'definition' => 'A point in time diagnosis (e.g. from a physician or nurse) in context of an encounter.',
            ],
        ];

        foreach ($data as $item) {
            MasterConsultationCategoryCondition::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
