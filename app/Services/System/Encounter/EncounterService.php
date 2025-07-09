<?php

namespace App\Services\System\Encounter;

use App\Models\Encounter\Encounter;

class EncounterService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateEncounter($request)
    {
        $encounter = Encounter::updateOrCreate(
            [
                'id' => $request?->id
            ],
            [
                'company_id'              => $request?->company_id,
                'location_id'             => $request?->location_id,
                'patient_id'              => $request?->patient_id,
                'transaction_id'          => $request?->transaction_id,
                'type'                    => $request?->type,
                'status'                  => $request?->status ?? 'unknown',
                'class_code'              => $request?->class_code ?? 'AMB',
                'hospital_discharge_text' => $request?->hospital_discharge_text
            ]
        );

        // dd($request?->practitioner_id);
        if ($request?->practitioner_id) {
            $encounter->encounterPractitiont()->updateOrCreate(
                [
                    'encounter_id' => $encounter?->id
                ],
                [
                    'practitioner_id' => $request?->practitioner_id
                ]
            );
        }

        return $encounter;
    }

    public function updateOrCreateOHEncounter($encounter)
    {
        $OHEncounter = $encounter->OHEncounter()->updateOrCreate(
            [
                'encounter_id' => $encounter?->id
            ],
            [
                'one_health_organization_id' => $encounter?->company?->OHOrganization?->id,
                'one_health_patient_id'      => $encounter?->patient?->OHPatient?->id,
                'status'                     => $encounter?->status,
                'class_code'                 => $encounter?->class_code
            ]
        );

        $OHEncounter->OHEncounterIdentifier()->updateOrCreate(
            [
                'one_health_encounter_id' => $OHEncounter?->id
            ],
            [
                'value' => $OHEncounter?->id
            ]
        );

        $OHEncounter->OHEncounterLocations()->updateOrCreate(
            [
                'one_health_encounter_id' => $OHEncounter?->id
            ],
            [
                'one_health_location_id' => $OHEncounter?->encounter?->location?->OHLocation?->id,
                'location_display'       => $OHEncounter?->encounter?->location?->OHLocation?->name ?? $OHEncounter?->encounter?->location?->OHLocation?->description
            ]
        );

        // dd($encounter?->encounterPractitiont?->typeCodingCode?->display);
        $OHEncounter->OHEncounterParticipants()->updateOrCreate(
            [
                'one_health_encounter_id'   => $OHEncounter?->id
            ],
            [
                'one_health_practitioner_id' => $encounter?->encounterPractitiont?->practitioner?->OHPractitioner?->id,
                'type_coding_code'          => $encounter?->encounterPractitiont?->type_coding_code,
            ]
        );

        if ($encounter?->hospital_discharge_text) {
            $OHEncounter->OHEncounterHospitalDischarge()->updateOrCreate(
            [
                'one_health_encounter_id'   => $OHEncounter?->id
            ],
            [
                'text' => $encounter?->hospital_discharge_text
            ]
        );
        }

        return $OHEncounter;
    }
}
