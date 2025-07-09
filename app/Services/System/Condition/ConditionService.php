<?php

namespace App\Services\System\Condition;

use App\Models\Condition\Condition;

class ConditionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateCondition($request)
    {
        $condition = Condition::updateOrCreate(
            [
                'id' => $request?->id
            ],
            [
                'transaction_condition_id' => $request?->transaction_condition_id,
                'company_id'               => $request?->company_id,
                'patient_id'               => $request?->patient_id,
                'encounter_id'             => $request?->encounter_id,
                'clinical_status'          => $request?->clinical_status,
                'category'                 => $request?->category,
                'code'                     => $request?->code,
                'onset_date_time'          => $request?->onset_date_time,
                'notes'                    => $request?->notes,
            ]
        );

        return $condition;
    }

    public function updateOrCreateOHCondition($condition)
    {
        $condition->refresh();

        $OHCondition = $condition->OHCondition()->updateOrCreate(
            [
                'condition_id' => $condition?->id
            ],
            [
                'one_health_organization_id' => $condition?->company?->OHOrganization?->id,
                'one_health_patient_id'      => $condition?->patient?->OHPatient?->id,
                'one_health_encounter_id'    => $condition?->encounter?->OHEncounter?->id,
                'subject_display'            => $condition?->patient?->name,
            ]
        );

        $OHCondition->OHConditionClinicalStatus()->updateOrCreate(
            [
                'one_health_condition_id' => $OHCondition?->id
            ],
            [
                'coding_code' => $condition?->clinical_status
            ]
        );

        $OHCondition->OHConditionCategory()->updateOrCreate(
            [
                'one_health_condition_id' => $OHCondition?->id
            ],
            [
                'coding_system' => in_array($condition?->category, ['encounter-diagnosis', 'problem-list-item']) ? 'http://terminology.hl7.org/CodeSystem/condition-category' : 'http://terminology.kemkes.go.id',
                'coding_code'   => $condition?->category
            ]
        );

        $OHCondition->OHConditionCode()->updateOrCreate(
            [
                'one_health_condition_id' => $OHCondition?->id
            ],
            [
                'coding_system' => $OHCondition->OHConditionCode?->icd10 ? 'http://hl7.org/fhir/sid/icd-10' : 'http://snomed.info/sct',
                'coding_code'   => $condition?->code
            ]
        );

        $OHCondition?->OHConditionNotes()->delete();
        foreach ($condition?->notes ?? [] as $key => $note) {
            $OHCondition?->OHConditionNotes()->updateOrCreate(
                [
                     'one_health_condition_id' => $OHCondition?->id
                ],
                [
                    'text' => $note
                ]
            );
        }

        return $OHCondition;
    }
}
