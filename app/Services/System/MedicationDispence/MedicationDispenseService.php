<?php

namespace App\Services\System\MedicationDispence;

use App\Models\Company\Company;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;

class MedicationDispenseService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateMedicationDispense($request)
    {
        $medication_dispense = MedicationDispense::updateOrCreate(
            [
                'id' => $request?->id
            ],
            [
                'transaction_detail_id' => $request?->transaction_detail_id,
                'company_id'            => $request?->company_id,
                'location_id'           => $request?->location_id,
                'practitioner_id'       => $request?->practitioner_id,
                'patient_id'            => $request?->patient_id,
                'encounter_id'          => $request?->encounter_id,
                'medication_id'         => $request?->medication_id,
                'medication_request_id' => $request?->medication_request_id,
                'performerable_type'    => $request?->performerable_type,
                'performerable_id'      => $request?->performerable_id,
                'status'                => $request?->status,
                'category'              => $request?->category,
                'quantity_value'        => $request?->quantity_value,
                'quantity_code'         => $request?->quantity_code,
                'day_value'             => $request?->day_value,
                'day_code'              => $request?->day_code,
                'when_prepare'          => $request?->when_prepare,
                'when_hand_over'        => $request?->when_hand_over,
            ]
        );

        $medication_dispense->dispenseDosageInstructions()->delete();
        foreach ($request?->dosage_instructions as $key => $dosage_instruction) {
            $medication_dispense->dispenseDosageInstructions()->updateOrCreate(
                [
                    'medication_dispense_id' => $medication_dispense?->id
                ],
                [
                    'sequence'                   => $dosage_instruction['sequence'] ?? null,
                    'text'                       => $dosage_instruction['text'] ?? null,
                    // 'additional_text'            => $dosage_instruction['additional_text'] ?? null,
                    // 'patient_instruction'        => $dosage_instruction['patient_instruction'] ?? null,
                    'timing_repeat_frequency'    => $dosage_instruction['timing_repeat_frequency'] ?? null,
                    'timing_repeat_period'       => $dosage_instruction['timing_repeat_period'] ?? null,
                    'timing_repeat_period_unit'  => $dosage_instruction['timing_repeat_period_unit'] ?? null,
                    // 'route_coding_code'          => $dosage_instruction['route_coding_code'] ?? null,
                    'dose_rate_type_coding_code' => $dosage_instruction['dose_rate_type_coding_code'] ?? null,
                    'dose_rate_quantity_value'   => $dosage_instruction['dose_rate_quantity_value'] ?? null,
                    'dose_rate_quantity_code'    => $dosage_instruction['dose_rate_quantity_code'] ?? null,
                ]
            );
        }

        return $medication_dispense;
    }

    public function updateOrCreateOHMedicationDispense($medication_dispense)
    {
        $medication_dispense->refresh();

        $OHMedicationDispense = $medication_dispense?->OHMedicationDispense()->updateOrCreate(
            [
                'medication_dispense_id' => $medication_dispense?->id
            ],
            [
                'one_health_organization_id'       => $medication_dispense?->company?->OHOrganization?->id,
                'one_health_location_id'           => $medication_dispense?->location?->OHLocation?->id,
                'one_health_patient_id'            => $medication_dispense?->patient?->OHPatient?->id,
                'one_health_practitioner_id'       => $medication_dispense?->practitioner?->OHPractitioner?->id,
                'one_health_encounter_id'          => $medication_dispense?->encounter?->OHEncounter?->id,
                'one_health_medication_id'         => $medication_dispense?->medication?->OHMedication?->id,
                'one_health_medication_request_id' => $medication_dispense?->medicationReq?->OHMedicationReq?->id,
                'status'                           => $medication_dispense?->status,
                'medication_display'               => $medication_dispense?->medication?->OHMedication?->OHMedicationCode?->coding_display,
                'subject_display'                  => $medication_dispense?->patient?->name,
                'location_display'                 => $medication_dispense?->location?->name,
                'quantity_value'                   => $medication_dispense?->quantity_value,
                'quantity_code'                    => $medication_dispense?->quantity_code,
                'day_value'                        => $medication_dispense?->day_value,
                'day_code'                         => $medication_dispense?->day_code,
                'when_prepare'                     => $medication_dispense?->when_prepare,
                'when_hand_over'                   => $medication_dispense?->when_hand_over,

            ]
        );

        $OHMedicationDispense->OHMedicationDispenseCategory()->updateOrCreate(
            [
                'one_health_medication_dispense_id' => $OHMedicationDispense?->id
            ],
            [
                'coding_code' => $medication_dispense?->category
            ]
        );

        $OHMedicationDispense->OHMedicationDispensePerformer()->updateOrCreate(
            [
                'one_health_medication_dispense_id' => $OHMedicationDispense?->id
            ],
            [
                'performerable_type' => $medication_dispense?->performerable_type,
                'performerable_id'   => $medication_dispense?->performerable_id,
                'actor_reference'    => $this->getPerformer($medication_dispense?->performerable_type,  $medication_dispense?->performerable_id)[0],
                'actor_reference_id' => $this->getPerformer($medication_dispense?->performerable_type,  $medication_dispense?->performerable_id)[1],
                'actor_display'      => $this->getPerformer($medication_dispense?->performerable_type,  $medication_dispense?->performerable_id)[2],
            ]
        );

        $OHMedicationDispense->OHMedicationDosageInstructions()->delete();
        foreach ($medication_dispense->dispenseDosageInstructions ??  [] as $key => $dosage_instruction) {
            $OHMedicationDispense->OHMedicationDosageInstructions()->create([
                'sequence'                   => $dosage_instruction?->sequence,
                'text'                       => $dosage_instruction?->text,
                // 'additional_text'            => $dosage_instruction?->additional_text,
                // 'patient_instruction'        => $dosage_instruction?->patient_instruction,
                'timing_repeat_frequency'    => $dosage_instruction?->timing_repeat_frequency,
                'timing_repeat_period'       => $dosage_instruction?->timing_repeat_period,
                'timing_repeat_period_unit'  => $dosage_instruction?->timing_repeat_period_unit,
                // 'route_coding_code'          => $dosage_instruction?->route_coding_code,
                'dose_rate_type_coding_code' => $dosage_instruction?->dose_rate_type_coding_code,
                'dose_rate_quantity_value'   => $dosage_instruction?->dose_rate_quantity_value,
                'dose_rate_quantity_code'    => $dosage_instruction?->dose_rate_quantity_code,
                'dose_rate_quantity_unit'    => $dosage_instruction?->dose_rate_quantity_code,
            ]);
        }

        return $OHMedicationDispense;
    }

    private function getPerformer($performerable_type, $performerable_id)
    {
        $actor_reference = $actor_reference_id = $actor_display = null;
        $datas = [
            Practitioner::class => "Practitioner/",
            Company::class      => "Organization/",
            Patient::class      => "Patient/",
        ];

        $actor_reference    = $datas[$performerable_type] ?? null;
        $actor_reference_id = app($performerable_type)->find($performerable_id)?->OHPractitioner?->id_practitiont ?? app($performerable_type)->find($performerable_id)?->OHOrganization?->id_organization ??  app($performerable_type)->find($performerable_id)?->OHPatient?->id_patient;
        $actor_display      = app($performerable_type)->find($performerable_id)?->OHPractitioner?->name_text ?? app($performerable_type)->find($performerable_id)?->OHOrganization?->type_coding_display ??  app($performerable_type)->find($performerable_id)?->OHPatient?->name_text;

        return [$actor_reference, $actor_reference_id, $actor_display];
    }
}
