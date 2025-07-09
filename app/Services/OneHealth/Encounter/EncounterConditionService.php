<?php

namespace App\Services\OneHealth\Encounter;

use App\Models\Company\Company;
use App\Models\Company\OneHealthy;
use App\Models\Encounter\Encounter;
use App\Models\Encounter\EncounterCondition;
use App\Models\Encounter\EncounterConditionIcd10;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Encounter\OneHealth\OneHealthEncounterCategory;
use App\Models\Encounter\OneHealth\OneHealthEncounterClinicalStatus;
use App\Models\Encounter\OneHealth\OneHealthEncounterCode;
use App\Models\Encounter\OneHealth\OneHealthEncounterNote;
use App\Models\Master\CodeSystem\Condition\MasterConditionCategory;
use App\Models\Master\CodeSystem\Condition\MasterConditionClinicalStatus;
use App\Models\Master\CodeSystem\Condition\MasterConditionVerificationStatus;
use App\Models\Master\CodeSystem\Consultation\MasterConsultationSnomedCT;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Patient\Patient;
use App\Models\Practitiont\OneHealth\OneHealthPractitioner;
use App\Models\Practitiont\Practitioner;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionIcd10;
use App\Models\Transaction\TransactionPrimary;
use App\Models\User;
use Exception;
use Http;
use Log;
use Carbon\Carbon;

class EncounterConditionService
{
    public $url;

    public function __construct()
    {
        //
        $this->url = config('app.one_health.url') . '/fhir-r4/v1';
    }

    public function updateOrCreateEncounterCondition($request)
    {
        $transactionPrimary = TransactionPrimary::where('transaction_id', $request->transaction_id)
            ->where('company_id', $request->company_id)
            ->first();

        $encounterCondition = EncounterCondition::updateOrCreate([
            'encounter_id' => $request->encounter_id,
            'company_id' => $request->company_id,
            'transaction_id' => $request->transaction_id,
            'transaction_primary_id' => $transactionPrimary?->id,
        ], [
            'description' => $transactionPrimary->description_primary,
            'verification_status' => $transactionPrimary->verification_status,
            'clinical_status' => $transactionPrimary->clinical_status,
            'snomed_code' => $transactionPrimary->snomed_code,
            'onset_datetime' => $transactionPrimary->onset_datetime,
        ]);

        $transactionIcds = TransactionIcd10::where('transaction_id', $request->transaction_id)
            ->where('company_id', $request->company_id)
            ->get();

        foreach ($transactionIcds as $key => $transactionIcd) {
            EncounterConditionIcd10::updateOrCreate([
                'encounter_id' => $request->encounter_id,
                'encounter_condition_id' => $encounterCondition->id,
                'transaction_icd10_id' => $transactionIcd->id,
                'company_id' => $request->company_id,
            ], [
                'icd10_id' => $transactionIcd->icd10_id,
            ]);
        }

        return $encounterCondition;
    }

    public function updateOrCreateOHEncounterCondition($encounterCondition)
    {
        $encounterCondition = EncounterCondition::where('encounter_id', $encounterCondition->encounter_id)
            ->where('company_id', $encounterCondition->company_id)
            ->first();

        $oneHealthEncounter = OneHealthEncounter::where('encounter_id', $encounterCondition->encounter_id)
            ->first();

        $masterConsultationClinicalStatus = MasterConditionClinicalStatus::where('code', $encounterCondition->clinical_status)
            ->first();

        if ($masterConsultationClinicalStatus) {
            OneHealthEncounterClinicalStatus::updateOrCreate([
                'one_health_encounter_id' => $oneHealthEncounter->id,
                'encounter_condition_id' => $encounterCondition->id,
                'company_id' => $encounterCondition->company_id,
            ], [
                'code' => $masterConsultationClinicalStatus->code,
                'display' => $masterConsultationClinicalStatus->display,
                'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
            ]);
        }

        OneHealthEncounterCategory::updateOrCreate([
            'one_health_encounter_id' => $oneHealthEncounter->id,
            'encounter_condition_id' => $encounterCondition->id,
            'company_id' => $encounterCondition->company_id,
        ], [
            'code' => 'chief-complaint',
            'display' => 'Chief Complaint',
            'system' => 'http://terminology.kemkes.go.id',
        ]);

        $masterConsultationSnomedCT = MasterConsultationSnomedCT::where('code', $encounterCondition->snomed_code)
            ->first();

        if ($masterConsultationSnomedCT) {
            OneHealthEncounterCode::updateOrCreate([
                'one_health_encounter_id' => $oneHealthEncounter->id,
                'encounter_condition_id' => $encounterCondition->id,
                'company_id' => $encounterCondition->company_id,
                'type' => 'snomed',
            ], [
                'code' => $masterConsultationSnomedCT->code,
                'display' => $masterConsultationSnomedCT->display,
                'system' => 'http://snomed.info/sct',
            ]);
        }

        $encounterConditionIcd10s = EncounterConditionIcd10::where('encounter_condition_id', $encounterCondition->id)
            ->where('company_id', $encounterCondition->company_id)
            ->get();

        if ($encounterConditionIcd10s->isNotEmpty()) {
            foreach ($encounterConditionIcd10s as $encounterConditionIcd10) {
                $transactionIcd10 = TransactionIcd10::where('id', $encounterConditionIcd10->transaction_icd10_id)
                    ->where('company_id', $encounterCondition->company_id)
                    ->first();

                if ($transactionIcd10) {
                    OneHealthEncounterCode::updateOrCreate([
                        'one_health_encounter_id' => $oneHealthEncounter->id,
                        'encounter_condition_id' => $encounterCondition->id,
                        'company_id' => $encounterCondition->company_id,
                        'encounter_condition_icd_10_id' => $encounterConditionIcd10->id,
                        'type' => 'icd',
                    ], [
                        'code' => $transactionIcd10->icd10?->code,
                        'display' => $transactionIcd10->icd10?->display,
                        'system' => 'http://hl7.org/fhir/sid/icd-10',
                    ]);
                }
            }
        }

        OneHealthEncounterNote::updateOrCreate([
            'one_health_encounter_id' => $oneHealthEncounter->id,
        ], [
            'description' => $encounterCondition->description,
        ]);

        return $oneHealthEncounter;
    }

    public function postPutEncounterCondition($request, $encounterCondition, $OHEncounter)
    {
        $encounter = Encounter::find($request->encounter_id);

        if (!$encounter) {
            Log::error('Encounter not found', [
                'encounter_id' => $request->encounter_id,
            ]);
            return response()->json([
                'error' => 'Encounter not found',
                'encounter_id' => $request->encounter_id,
            ], 404);
        }

        $company = Company::find($request->company_id);

        if (!$company) {
            Log::error('Company not found', [
                'company_id' => $request->company_id,
            ]);
            return response()->json([
                'error' => 'Company not found',
                'company_id' => $request->company_id,
            ], 404);
        }

        $transaction = Transaction::find($request->transaction_id);

        $patient = Patient::where('user_id', $transaction->patient_id)
            ->first();

        $oneHealthPatient = OneHealthPatient::where('patient_id', $patient->id)
            ->first();

        $practitioner = Practitioner::where('user_id', $transaction->doctor_id)
            ->first();

        $oneHealthPractitioner = OneHealthPractitioner::where('practitioner_id', $practitioner->id)
            ->first();


        if (!$transaction) {
            Log::error('Transaction not found', [
                'transaction_id' => $request->transaction_id,
            ]);
            return response()->json([
                'error' => 'Transaction not found',
                'transaction_id' => $request->transaction_id,
            ], 404);
        }

        // $oneHealthEncounterClinicalStatus = OneHealthEncounterClinicalStatus::where('one_health_encounter_id', $OHEncounter->id)
        //     ->where('encounter_condition_id', $encounterCondition->id)
        //     ->first();

        $oneHealthEncounterCategory = OneHealthEncounterCategory::where('one_health_encounter_id', $OHEncounter->id)
            ->where('encounter_condition_id', $encounterCondition->id)
            ->first();

        $oneHealthEncounterNote = OneHealthEncounterNote::where('one_health_encounter_id', $OHEncounter->id)
            ->first();

        $data = [
            "resourceType" => "Condition",
            "clinicalStatus" => [
                "coding" => [
                    [
                        "system" => $masterConsultationClinicalStatus->system ?? "http://terminology.hl7.org/CodeSystem/condition-clinical",
                        "code" => $masterConsultationClinicalStatus->code ?? "active",
                        "display" => $masterConsultationClinicalStatus->display ?? "Active",
                    ]
                ]
            ],
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => $oneHealthEncounterCategory->system ?? "http://terminology.kemkes.go.id",
                            "code" => $oneHealthEncounterCategory->code ?? "chief-complaint",
                            "display" => $oneHealthEncounterCategory->display ?? "Chief Complaint"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => $this->getConditionCode($encounterCondition, $OHEncounter, $company, ['snomed', 'icd']),
            ],
            "subject" => [
                "reference" => "Patient/" . $oneHealthPatient->id_patient,
                "display" => $patient->name ?? $transaction->patient_name ?? "Unknown Patient"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $OHEncounter->id_encounter
            ],
            "onsetDateTime" => Carbon::now()->toIso8601String(),
            "recordedDate" => Carbon::now()->toIso8601String(),
            "recorder" => [
                "reference" => "Practitioner/" . $oneHealthPractitioner->id_practitiont,
                "display" => $practitioner->name ?? $transaction->doctor_name ?? "Unknown Practitioner"
            ],
            "note" => [
                [
                    "text" => $oneHealthEncounterNote->description ?? "Pasien mengeluhkan demam disertai menggigil",
                ]
            ]
        ];
        // Log::info($data);

        $company  = Company::find($request->company_id);
        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url . '/Condition', $data);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            Log::info('Unauthorized access to OneHealth API, refreshing access token.', [$company]);
            $company = Company::find($request->company_id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->post($this->url . '/Condition', $data);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully EncounterConditionService->postCondition', $responseBody);

        return $responseBody;
    }

    private function getConditionCode($encounterCondition, $OHEncounter, $company, $types)
    {
        $details = [];
        $oneHealthEncounterCodes = OneHealthEncounterCode::where('one_health_encounter_id', $OHEncounter->id)
            ->where('encounter_condition_id', $encounterCondition->id)
            ->where('company_id', $company->id)
            ->whereIn('type', $types)
            ->get();

        foreach ($oneHealthEncounterCodes as $oneHealthEncounterCode) {
            $details[] = [
                "system" => $oneHealthEncounterCode->system ?? "http://snomed.info/sct",
                "code" => $oneHealthEncounterCode->code ?? "274640006",
                "display" => $oneHealthEncounterCode->display ?? "Fever with chills"
            ];
        }

        return $details;
    }
}
