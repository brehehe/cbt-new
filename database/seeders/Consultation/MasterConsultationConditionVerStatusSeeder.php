<?php

namespace Database\Seeders\Consultation;

use App\Models\Master\CodeSystem\Consultation\MasterConsultationConditionVerStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterConsultationConditionVerStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'code' => 'unconfirmed',
                'display' => 'Unconfirmed',
                'definition' => 'There is not sufficient evidence to assert the presence of the subject\'s condition.',
            ],
            [
                'code' => 'provisional',
                'display' => 'Provisional',
                'definition' => 'This is a tentative diagnosis - still a candidate that is under consideration.',
            ],
            [
                'code' => 'differential',
                'display' => 'Differential',
                'definition' => 'One of a set of potential (and typically mutually exclusive) diagnoses asserted to further guide the diagnostic process and preliminary treatment.',
            ],
            [
                'code' => 'confirmed',
                'display' => 'Confirmed',
                'definition' => 'There is sufficient evidence to assert the presence of the subject\'s condition.',
            ],
            [
                'code' => 'refuted',
                'display' => 'Refuted',
                'definition' => 'This condition has been ruled out by subsequent diagnostic and clinical evidence.',
            ],
            [
                'code' => 'entered-in-error',
                'display' => 'Entered in Error',
                'definition' => 'The statement was entered in error and is not valid.',
            ],
        ];

        foreach ($data as $item) {
            MasterConsultationConditionVerStatus::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }
    }
}
