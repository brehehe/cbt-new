<?php

namespace App\Services;

use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Patient\Patient;
use App\Models\Practitiont\OneHealth\OneHealthPractitioner;
use App\Models\Practitiont\Practitioner;
use App\Models\Transaction\Transaction;
use App\Models\User;
use Exception;
use Http;
use Log;
use Carbon\Carbon;

/**
 * Class PhysicalExamService.
 */
class PhysicalExamService
{
    public $url;

    public function __construct()
    {
        $this->url = config('app.one_health.url') . '/fhir-r4/v1';
    }

    // Method utama untuk membuat vital signs observation
    private function createVitalSignObservation($value, $transactionId, $vitalConfig)
    {
        // Get required entities
        $entities = $this->getRequiredEntities($transactionId);

        // Build observation data
        $data = $this->buildObservationData($value, $entities, $vitalConfig);

        // Send to OneHealth API
        return $this->sendToOneHealthAPI($data, $entities['company'], $vitalConfig['logName']);
    }

    private function getRequiredEntities($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            throw new \Exception('Transaction not found for ID: ' . $transactionId);
        }

        $encounter = Encounter::where('transaction_id', $transaction->id)->first();
        if (!$encounter) {
            throw new \Exception('Encounter not found for transaction ID: ' . $transaction->id);
        }

        $OHEncounter = OneHealthEncounter::where('encounter_id', $encounter->id)->first();
        if (!$OHEncounter) {
            throw new \Exception('OneHealthEncounter not found for encounter ID: ' . $encounter->id);
        }

        $practitioner = Practitioner::where('user_id', $transaction->doctor_id)->first();
        if (!$practitioner) {
            throw new \Exception('Practitioner not found for user ID: ' . $transaction->doctor_id);
        }

        $oneHealthPractitioner = OneHealthPractitioner::where('practitioner_id', $practitioner->id)->first();
        if (!$oneHealthPractitioner) {
            throw new \Exception('OneHealthPractitioner not found for practitioner ID: ' . $practitioner->id);
        }

        $patient = Patient::where('user_id', $transaction->patient_id)->first();
        if (!$patient) {
            throw new \Exception('Patient not found for user ID: ' . $transaction->patient_id);
        }

        $oneHealthPatient = OneHealthPatient::where('patient_id', $patient->id)->first();
        if (!$oneHealthPatient) {
            throw new \Exception('OneHealthPatient not found for patient ID: ' . $patient->id);
        }

        $company = Company::find($transaction->company_id);

        return [
            'transaction' => $transaction,
            'encounter' => $encounter,
            'OHEncounter' => $OHEncounter,
            'practitioner' => $practitioner,
            'oneHealthPractitioner' => $oneHealthPractitioner,
            'patient' => $patient,
            'oneHealthPatient' => $oneHealthPatient,
            'company' => $company
        ];
    }

    private function buildObservationData($value, $entities, $vitalConfig)
    {
        // ✅ PERBAIKAN UTAMA: Pastikan value adalah numerik, bukan string
        $processedValue = $vitalConfig['valueProcessor']($value);

        // Log untuk debugging
        if (config('app.name') != 'production') {
            Log::info('Processing vital sign value', [
                'original_value' => $value,
                'processed_value' => $processedValue,
                'type' => gettype($processedValue),
                'vital_type' => $vitalConfig['logName']
            ]);
        }

        return [
            "resourceType" => "Observation",
            "status" => "final",
            "category" => [
                [
                    "coding" => [
                        [
                            "system" => "http://terminology.hl7.org/CodeSystem/observation-category",
                            "code" => "vital-signs",
                            "display" => "Vital Signs"
                        ]
                    ]
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system" => "http://loinc.org",
                        "code" => $vitalConfig['code'],
                        "display" => $vitalConfig['display']
                    ]
                ]
            ],
            "subject" => [
                "reference" => "Patient/" . $entities['oneHealthPatient']->id_patient,
                "display" => $entities['patient']->name ?? $entities['transaction']->patient_name ?? "Unknown Patient"
            ],
            "encounter" => [
                "reference" => "Encounter/" . $entities['OHEncounter']->id_encounter
            ],
            "effectiveDateTime" => Carbon::now()->toIso8601String(),
            "issued" => Carbon::now()->toIso8601String(),
            "performer" => [
                [
                    "reference" => "Practitioner/" . $entities['oneHealthPractitioner']->id_practitiont,
                    "display" => $entities['practitioner']->name ?? $entities['transaction']->doctor_name ?? "Unknown Practitioner"
                ]
            ],
            "valueQuantity" => [
                "value" => $processedValue, // ✅ Pastikan ini numerik
                "unit" => $vitalConfig['unit'],
                "system" => "http://unitsofmeasure.org",
                "code" => $vitalConfig['ucumCode']
            ]
        ];
    }

    private function sendToOneHealthAPI($data, $company, $logName)
    {
        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url . '/Observation', $data);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            Log::info('Unauthorized access to OneHealth API, refreshing access token.', [$company]);
            $company = Company::find($company->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->post($this->url . '/Observation', $data);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message = $responseBody['message'] ?? json_encode($responseBody);

            // Log error dengan detail untuk debugging
            Log::error('OneHealth API Error for ' . $logName, [
                'response_body' => $responseBody,
                'sent_data' => $data,
                'status_code' => $response->status()
            ]);

            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') {
            Log::info('Successfully VitalSignsService->create' . $logName, $responseBody);
        }

        return $responseBody;
    }

    // ✅ PERBAIKAN UTAMA: Value processor yang memastikan tipe data numerik
    private function getVitalSignConfigs()
    {
        return [
            'heartRate' => [
                'code' => '8867-4',
                'display' => 'Heart rate',
                'unit' => 'beats/minute',
                'ucumCode' => '{beats}/min',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0;
                    return (int) $value; // ✅ Cast ke integer
                },
                'logName' => 'HeartRate'
            ],
            'breathing' => [
                'code' => '9279-1',
                'display' => 'Respiratory rate',
                'unit' => 'breaths/min',
                'ucumCode' => '{breaths}/min',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0;
                    return (int) $value; // ✅ Cast ke integer
                },
                'logName' => 'Breathing'
            ],
            'systolic' => [
                'code' => '8480-6',
                'display' => 'Systolic blood pressure',
                'unit' => 'mm[Hg]',
                'ucumCode' => 'mm[Hg]',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0;
                    return (int) $value; // ✅ Cast ke integer
                },
                'logName' => 'SystolicBP'
            ],
            'diastolic' => [
                'code' => '8462-4',
                'display' => 'Diastolic blood pressure',
                'unit' => 'mm[Hg]',
                'ucumCode' => 'mm[Hg]',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0;
                    return (int) $value; // ✅ Cast ke integer
                },
                'logName' => 'DiastolicBP'
            ],
            'temperature' => [
                'code' => '8310-5',
                'display' => 'Body temperature',
                'unit' => 'Cel',
                'ucumCode' => 'Cel',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0.0;
                    return (float) $value; // ✅ Cast ke float untuk suhu
                },
                'logName' => 'BodyTemperature'
            ],
            'height' => [
                'code' => '8302-2',
                'display' => 'Body height',
                'unit' => 'cm',
                'ucumCode' => 'cm',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0.0;
                    return (float) $value; // ✅ Cast ke float untuk tinggi
                },
                'logName' => 'Height'
            ],
            'weight' => [
                'code' => '29463-7',
                'display' => 'Body weight',
                'unit' => 'kg',
                'ucumCode' => 'kg',
                'valueProcessor' => function ($value) {
                    if ($value === null || $value === '') return 0.0;
                    return (float) $value; // ✅ Cast ke float untuk berat badan
                },
                'logName' => 'Weight'
            ]
        ];
    }

    // Public methods untuk setiap vital signs
    public function createHeartRate($heartRate, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['heartRate'];
        return $this->createVitalSignObservation($heartRate, $transactionId, $config);
    }

    public function createBreathing($breathing, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['breathing'];
        return $this->createVitalSignObservation($breathing, $transactionId, $config);
    }

    public function createBloodPressureSistole($sistole, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['systolic'];
        return $this->createVitalSignObservation($sistole, $transactionId, $config);
    }

    public function createBloodPressureDiastole($diastole, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['diastolic'];
        return $this->createVitalSignObservation($diastole, $transactionId, $config);
    }

    public function createBodyTemperature($temperature, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['temperature'];
        return $this->createVitalSignObservation($temperature, $transactionId, $config);
    }

    public function createHeight($height, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['height'];
        return $this->createVitalSignObservation($height, $transactionId, $config);
    }

    public function createWeight($weight, $transactionId)
    {
        $config = $this->getVitalSignConfigs()['weight'];
        return $this->createVitalSignObservation($weight, $transactionId, $config);
    }
}
