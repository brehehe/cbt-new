<?php

namespace App\Services\OneHealth\MedicationDispense;

use Exception;
use App\Traits\Encryption;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Log;
use App\Traits\Company\CompanyTrait;
use Illuminate\Support\Facades\Http;
use App\Traits\OneHealth\AuthenticateTrait;

class MedicationDispenseService
{
    use AuthenticateTrait, CompanyTrait, Encryption;
    /**
     * Create a new class instance.
     */
    public $url;

    public function __construct()
    {
        //
        $this->url = config('app.one_health.url') . '/fhir-r4/v1';
    }

    public function postPutOHMedicationDispense($OHMedicationDispense)
    {
        $OHMedicationDispense->refresh();

        $request = [
            "resourceType" => "MedicationDispense",
            "identifier"   => $this->getIdentifier($OHMedicationDispense),
            "status"       => $OHMedicationDispense?->status,
            "category" => [
                "coding" => [
                    [
                        "system"  => $OHMedicationDispense?->OHMedicationDispenseCategory?->coding_system,
                        "code"    => $OHMedicationDispense?->OHMedicationDispenseCategory?->coding_code,
                        "display" => $OHMedicationDispense?->OHMedicationDispenseCategory?->coding_display,
                    ]
                ]
            ],
            "medicationReference" => [
                "reference" => $OHMedicationDispense?->medication_reference . $OHMedicationDispense?->OHMedication?->id_medication,
                "display"   => $OHMedicationDispense?->medication_display
            ],
            "subject" => [
                "reference" => $OHMedicationDispense?->subject_reference . $OHMedicationDispense?->OHPatient?->id_patient,
                "display"   => $OHMedicationDispense?->subject_display,
            ],
            "context" => [
                "reference" => $OHMedicationDispense?->context_reference . $OHMedicationDispense?->OHEncounter?->id_encounter
            ],
            'performer' => [
                [
                    "actor" => [
                        "reference" => $OHMedicationDispense?->OHMedicationDispensePerformer?->actor_reference . $OHMedicationDispense?->OHMedicationDispensePerformer?->actor_reference_id,
                        "display"   => $OHMedicationDispense?->OHMedicationDispensePerformer?->actor_display
                    ]
                ]
            ],
            "location" => [
                "reference" => $OHMedicationDispense?->location_reference . $OHMedicationDispense?->OHLocation?->id_location,
                "display"   => $OHMedicationDispense?->location_display
            ],
            "authorizingPrescription" => [
                [
                    "reference" => $OHMedicationDispense?->authorizing_reference . $OHMedicationDispense?->OHMedicationReq?->id_medication_request,
                ]
            ],
            "quantity" => [
                "system" => $OHMedicationDispense?->quantity_system,
                "value"  => $OHMedicationDispense?->quantity_value,
                "code"   => $OHMedicationDispense?->quantity_code,
            ],
            "daysSupply" => [
                "system" => $OHMedicationDispense?->day_system,
                "value"  => $OHMedicationDispense?->day_value,
                "code"   => $OHMedicationDispense?->day_code,
                "unit"   => $OHMedicationDispense?->day_unit,
            ],
            "whenPrepared"      => $OHMedicationDispense?->when_prepare,
            "whenHandedOver"    => $OHMedicationDispense?->when_hand_over,
            "dosageInstruction" => $this->getDosageInstruction($OHMedicationDispense)
        ];

        // dd($request);

        if ($OHMedicationDispense?->id_medication_dispense) {
            $request['id'] = $OHMedicationDispense?->id_medication_dispense;
            return $this->putMedicationDispense($request, $OHMedicationDispense);
        } else {
            return $this->postMedicationDispense($request, $OHMedicationDispense);
        }
    }

    private function getIdentifier($OHMedicationDispense)
    {
        $identifiers = [];

        foreach ($OHMedicationDispense?->OHMedicationDispenseIdentifiers ?? [] as $key => $OHIdentifier) {
            $one_health = $this->getOneHealth($OHIdentifier?->OHOrganization?->company);

            $one_health_organization_id = $one_health[0];

            $identifiers[] = [
                "system" => $OHIdentifier?->system . $one_health_organization_id,
                "use"    => $OHIdentifier?->use,
                "value"  => $OHIdentifier?->value,
            ];
        }

        return $identifiers;
    }

    private function getDosageInstruction($OHMedicationDispense)
    {
        $dosage_instructions = [];

        foreach ($OHMedicationDispense?->OHMedicationDosageInstructions ?? [] as $key => $dosage_instruction) {
            $dosage_instructions[] = [
                "sequence"              => $dosage_instruction?->sequence,
                "text"                  => $dosage_instruction?->text,
                // "additionalInstruction" => [
                //     [
                //         "text" => $dosage_instruction?->additional_text
                //     ]
                // ],
                // "patientInstruction" => $dosage_instruction?->patient_instruction,
                "timing" => [
                    "repeat" => [
                        "frequency"  => $dosage_instruction?->timing_repeat_frequency,
                        "period"     => $dosage_instruction?->timing_repeat_period,
                        "periodUnit" => $dosage_instruction?->timing_repeat_period_unit,
                    ]
                ],
                // "route" => [
                //     "coding" => [
                //         [
                //             "system"  => $dosage_instruction?->route_coding_system,
                //             "code"    => $dosage_instruction?->route_coding_code,
                //             "display" => $dosage_instruction?->route_coding_display,
                //         ]
                //     ]
                // ],
                "doseAndRate" => [
                    [
                        "type" => [
                            "coding" => [
                                [
                                    "system"  => $dosage_instruction?->dose_rate_type_coding_system,
                                    "code"    => $dosage_instruction?->dose_rate_type_coding_code,
                                    "display" => $dosage_instruction?->dose_rate_type_coding_display,
                                ]
                            ]
                        ],
                        "doseQuantity" => [
                            "value"  => $dosage_instruction?->dose_rate_quantity_value,
                            "unit"   => $dosage_instruction?->dose_rate_quantity_unit,
                            "system" => $dosage_instruction?->dose_rate_quantity_system,
                            "code"   => $dosage_instruction?->dose_rate_quantity_code,
                        ]
                    ]
                ]
            ];
        }

        return $dosage_instructions;
    }

    private function postMedicationDispense($request, $OHMedicationDispense)
    {
        $company = Company::find($OHMedicationDispense?->OHOrganization?->company?->id);

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url . '/MedicationDispense', $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHMedicationDispense?->OHOrganization?->company);
            $company = Company::find($OHMedicationDispense?->OHOrganization?->company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->post($this->url . '/MedicationDispense', $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        $OHMedicationDispense->updateQuietly([
            'id_medication_dispense' => $responseBody['id'] ?? null
        ]);

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_MedicationDispenseService->postMedicationDispense', $responseBody);

        return $responseBody;
    }

    private function putMedicationDispense($request, $OHMedicationDispense)
    {
        // dd($this->url . '/MedicationDispense/' . $OHMedicationDispense?->id_medication_dispense, $request);
        $company = Company::find($OHMedicationDispense?->OHOrganization?->company?->id);

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->put($this->url . '/MedicationDispense/' . $OHMedicationDispense?->id_medication_dispense, $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHMedicationDispense?->OHOrganization?->company);
            $company = Company::find($OHMedicationDispense?->OHOrganization?->company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->put($this->url . '/MedicationDispense/' . $OHMedicationDispense?->id_medication_dispense, $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_MedicationDispenseService->putMedicationDispense', $responseBody);

        return $responseBody;
    }
}
