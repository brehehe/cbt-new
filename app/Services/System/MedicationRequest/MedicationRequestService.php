<?php

namespace App\Services\System\MedicationRequest;

use App\Models\MedicationRequest\MedicationRequest;

class MedicationRequestService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateMedicationReq($request)
    {
        $medication_request = MedicationRequest::updateOrCreate(
            [
                'id' => $request?->id
            ],
            [
                'transaction_detail_id' => $request?->transaction_detail_id,
                'company_id'            => $request?->company_id,
                'patient_id'            => $request?->patient_id,
                'encounter_id'          => $request?->encounter_id,
                'medication_id'         => $request?->medication_id,
                'status'                => $request?->status,
                'intent'                => $request?->intent,
                'category'              => $request?->category,
                'priority'              => $request?->priority,
                'requestable_type'      => $request?->requestable_type,
                'requestable_id'        => $request?->requestable_id,
                'reason_code'           => $request?->reason_code ?? "A15.0",
                'course_of_therapy'     => $request?->course_of_therapy,
            ]
        );

        $medication_request->dosageInstructions()->delete();
        foreach ($request?->dosage_instructions as $key => $dosage_instruction) {
            $medication_request->dosageInstructions()->updateOrCreate(
                [
                    'medication_request_id' => $medication_request?->id
                ],
                [
                    'sequence'                   => $dosage_instruction['sequence'] ?? null,
                    'text'                       => $dosage_instruction['text'] ?? null,
                    'additional_text'            => $dosage_instruction['additional_text'] ?? null,
                    'patient_instruction'        => $dosage_instruction['patient_instruction'] ?? null,
                    'timing_repeat_frequency'    => $dosage_instruction['timing_repeat_frequency'] ?? null,
                    'timing_repeat_period'       => $dosage_instruction['timing_repeat_period'] ?? null,
                    'timing_repeat_period_unit'  => $dosage_instruction['timing_repeat_period_unit'] ?? null,
                    'route_coding_code'          => $dosage_instruction['route_coding_code'] ?? null,
                    'dose_rate_type_coding_code' => $dosage_instruction['dose_rate_type_coding_code'] ?? null,
                    'dose_rate_quantity_value'   => $dosage_instruction['dose_rate_quantity_value'] ?? null,
                    'dose_rate_quantity_code'    => $dosage_instruction['dose_rate_quantity_code'] ?? null,
                ]
            );
        }

        $medication_request->medicationReqDispense()->updateOrCreate(
            [
                'medication_request_id' => $medication_request?->id
            ],
            [
                'company_id'              => $medication_request?->company_id,
                'dispense_interval_value' => $request?->dispense_request['interval_value'] ?? 0,
                'dispense_interval_code'  => $request?->dispense_request['interval_code'] ?? null,
                'validity_start'          => $request?->dispense_request['validity_start'] ?? null,
                'validity_end'            => $request?->dispense_request['validity_end'] ?? null,
                'number_repeat'           => $request?->dispense_request['number_repeat'] ?? 0,
                'quantity_value'          => $request?->dispense_request['quantity_value'] ?? null,
                'quantity_code'           => $request?->dispense_request['quantity_code'] ?? null,
                'expect_value'            => $request?->dispense_request['expect_value'] ?? null,
                'expect_code'             => $request?->dispense_request['expect_code'] ?? null,
            ]
        );

        return $medication_request;
    }

    public function updateOrCreateOHMedicationReq($medication_request)
    {
        $medication_request->refresh();

        $OHmedication_req = $medication_request->OHMedicationReq()->updateOrCreate(
            [
                'medication_request_id' => $medication_request?->id
            ],
            [
                'one_health_organization_id' => $medication_request?->company?->OHOrganization?->id,
                'one_health_patient_id'      => $medication_request?->patient?->OHPatient?->id,
                'one_health_encounter_id'    => $medication_request?->encounter?->OHEncounter?->id,
                'one_health_medication_id'   => $medication_request?->medication?->OHMedication?->id,
                'status'                     => $medication_request?->status,
                'intent'                     => $medication_request?->intent,
                'priority'                   => $medication_request?->priority,
                'medication_display'         => $medication_request?->medication?->OHMedication?->OHMedicationCode?->coding_display,
                'subject_display'            => $medication_request?->patient?->name,
                'author_on'                  => $medication_request?->author_on?->format('Y-m-d'),
            ]
        );

        $OHmedication_req->OHMedicationReqCategory()->updateOrCreate(
            [
                'one_health_medication_request_id' => $OHmedication_req?->id
            ],
            [
                'coding_code' => $medication_request?->category
            ]
        );

        $OHmedication_req->OHMedicationReqRequester()->updateOrCreate(
            [
                'one_health_medication_request_id' => $OHmedication_req?->id
            ],
            [
                'requestable_type' => $medication_request?->requestable_type,
                'requestable_id'   => $medication_request?->requestable_id,
                'reference'        => $this->getRequester($medication_request?->requestable_type, $medication_request?->requestable_id)[0],
                'reference_id'     => $this->getRequester($medication_request?->requestable_type, $medication_request?->requestable_id)[1],
                'display'          => $this->getRequester($medication_request?->requestable_type, $medication_request?->requestable_id)[2],
            ]
        );

        $OHmedication_req->OHMedicationReqReasonCode()->updateOrCreate(
            [
                'one_health_medication_request_id' => $OHmedication_req?->id
            ],
            [
                'coding_code' => $medication_request?->reason_code
            ]
        );

        $OHmedication_req->OHMedicationReqCourseTherapy()->updateOrCreate(
            [
                'one_health_medication_request_id' => $OHmedication_req?->id
            ],
            [
                'coding_code' => $medication_request?->course_of_therapy
            ]
        );

        $OHmedication_req->OHMedicationReqDosageInstructions()->delete();
        foreach ($medication_request?->dosageInstructions ?? [] as $key => $dosage_instruction) {
            $OHmedication_req->OHMedicationReqDosageInstructions()->create(
                [
                    'sequence'                   => $dosage_instruction?->sequence,
                    'text'                       => $dosage_instruction?->text,
                    'additional_text'            => $dosage_instruction?->additional_text,
                    'patient_instruction'        => $dosage_instruction?->patient_instruction,
                    'timing_repeat_frequency'    => $dosage_instruction?->timing_repeat_frequency,
                    'timing_repeat_period'       => $dosage_instruction?->timing_repeat_period,
                    'timing_repeat_period_unit'  => $dosage_instruction?->timing_repeat_period_unit,
                    'route_coding_code'          => $dosage_instruction?->route_coding_code,
                    'dose_rate_type_coding_code' => $dosage_instruction?->dose_rate_type_coding_code,
                    'dose_rate_quantity_value'   => $dosage_instruction?->dose_rate_quantity_value,
                    'dose_rate_quantity_code'    => $dosage_instruction?->dose_rate_quantity_code,
                    'dose_rate_quantity_unit'    => $dosage_instruction?->dose_rate_quantity_code,
                ]
            );
        }

        $OHmedication_req->OHMedicationReqDispenseRequest()->updateOrCreate(
            [
                'one_health_medication_request_id' => $OHmedication_req?->id
            ],
            [
                'one_health_organization_id' => $OHmedication_req?->one_health_organization_id,
                'dispense_interval_code'     => $medication_request?->medicationReqDispense?->dispense_interval_code,
                'validity_start'             => $medication_request?->medicationReqDispense?->validity_start,
                'validity_end'               => $medication_request?->medicationReqDispense?->validity_end,
                'number_repeat'              => $medication_request?->medicationReqDispense?->number_repeat,
                'quantity_value'             => $medication_request?->medicationReqDispense?->quantity_value,
                'quantity_unit'              => $medication_request?->medicationReqDispense?->quantity_code,
                'quantity_code'              => $medication_request?->medicationReqDispense?->quantity_code,
                'expect_value'               => $medication_request?->medicationReqDispense?->expect_value,
                'expect_code'                => $medication_request?->medicationReqDispense?->expect_code,
            ]
        );

        return $OHmedication_req;
    }

    private function getRequester($requestable_type, $requestable_id)
    {
        $reference = $reference_id = $display = null;
        $datas = [
            "App\Models\Practitiont\Practitioner" => "Practitioner/",
            "App\Models\Company\Company"          => "Organization/",
            "App\Models\Patient\Patient"          => "Patient/",
        ];

        $reference    = $datas[$requestable_type] ?? null;
        $reference_id = app($requestable_type)->find($requestable_id)?->OHPractitioner?->id_practitiont ?? app($requestable_type)->find($requestable_id)?->OHOrganization?->id_organization ??  app($requestable_type)->find($requestable_id)?->OHPatient?->id_patient;
        $display      = app($requestable_type)->find($requestable_id)?->OHPractitioner?->name_text ?? app($requestable_type)->find($requestable_id)?->OHOrganization?->type_coding_display ??  app($requestable_type)->find($requestable_id)?->OHPatient?->name_text;

        return [$reference, $reference_id, $display];
    }
}
