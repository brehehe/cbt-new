<?php

namespace App\Services\OneHealth\Patient;

use App\Models\Company\Company;
use App\Models\Company\OneHealth\OneHealthOrganization;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Traits\Company\CompanyTrait;
use App\Traits\Encryption;
use App\Traits\OneHealth\AuthenticateTrait;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PatientService
{
    /**
     * Create a new class instance.
     */

    use CompanyTrait, AuthenticateTrait, Encryption;

    public $url;

    public function __construct()
    {
        //
        $this->url = config('app.one_health.url'). '/fhir-r4/v1';
    }

    public function postPutPatient($OHPatient, $OHOrganization)
    {
        $OHPatient->refresh();
        $OHOrganization->refresh();

        if ($OHPatient?->id_patient) {
            return $this->putPatient($OHPatient, $OHOrganization);
        } else {
            return $this->postPatient($OHPatient, $OHOrganization);
        }
    }

    //POST
    private function postPatient($OHPatient, $OHOrganization)
    {
        $request = [
            "resourceType" => "Patient",
            "meta"         => [
                "profile" => [$OHPatient?->meta_profile]
            ],
            "identifier" => $this->getIdentifier($OHPatient),
            "active"     => $OHPatient?->active,
            "name"       => [
                [
                    "use" => $OHPatient?->name_use,
                    "text" => $OHPatient?->name_text,
                ]
            ],
            "gender"          => $OHPatient?->gender,
            "birthDate"       => $OHPatient?->birth_date?->format('Y-m-d'),
            "deceasedBoolean" => $OHPatient?->deceased_boolean,
            "address" => [
                [
                    "use" => $OHPatient?->OHPatientAddress?->use,
                    "line" => [
                        $OHPatient?->OHPatientAddress?->line,
                    ],
                    "city"       => $OHPatient?->OHPatientAddress?->city,
                    "postalCode" => $OHPatient?->OHPatientAddress?->postal_code,
                    "country"    => $OHPatient?->OHPatientAddress?->country,
                    "extension" => [
                        [
                            "url"       => $OHPatient?->OHPatientAddress?->extention_url,
                            "extension" => $this->getAddressExtension($OHPatient?->OHPatientAddress)
                        ]
                    ]
                ]
            ],
            "maritalStatus" => [
                "coding" => [
                    [
                        "system"  => $OHPatient?->marital_status_coding_system,
                        "code"    => $OHPatient?->marital_status_coding_code,
                        "display" => $OHPatient?->marital_status_coding_display,
                    ]
                ],
                "text" => $OHPatient?->marital_status_coding_display
            ],
            "multipleBirthInteger" => 0,
            "contact" => [
                [
                    "relationship" => [
                        [
                            "coding" => [
                                [
                                    "system" => $OHPatient?->OHPatientContactRelationship?->relationship_coding_system,
                                    "code"   => $OHPatient?->OHPatientContactRelationship?->relationship_coding_code,
                                ]
                            ]
                        ]
                    ],
                    "name" => [
                        "use"  => $OHPatient?->OHPatientContactRelationship?->name_use,
                        "text" => $OHPatient?->OHPatientContactRelationship?->name_text,
                    ],
                    "telecom" => $this->getContactTelecom($OHPatient?->OHPatientContactRelationship)
                ]
            ],
            "communication" => [
                [
                    "language" => [
                        "coding" => [
                            [
                                "system"  => "urn:ietf:bcp:47",
                                "code"    => "id-ID",
                                "display" => "Indonesian"
                            ]
                        ],
                        "text" => "Indonesian"
                    ],
                    "preferred" => true
                ]
            ]
        ];

        unset($request['contact']);

        // going to API
        $company  = Company::find($OHOrganization?->company?->id);
        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url .'/Patient', $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHOrganization?->company);
            $company = Company::find($OHOrganization?->company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->post($this->url .'/Patient', $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        $OHPatient->updateQuietly([
            'id_patient' => $responseBody['id'] ?? null
        ]);

        if (config('app.name') != 'production') Log::info('Successfully PatientService->postPatient', $responseBody);

        return $responseBody;
    }

    private function getIdentifier($OHPatient)
    {
        $identifiers = [];
        //set identifier
        foreach ($OHPatient->OHPatientIdentifiers ?? [] as $key => $identifier) {
            $identifiers [] = [
                "use"    => $identifier->use,
                "system" => $identifier->system,
                "value"  => $this->decrypted($identifier->value),
            ];
        }

        return $identifiers;
    }

    private function getAddressExtension($OHPatientAddress)
    {
        $extentions = [];

        foreach ($OHPatientAddress?->extensions ?? [] as $key => $extention) {
            $extentions[] = [
                "url"       => $extention?->url,
                "valueCode" => $extention?->value_code,
            ];
        }

        return $extentions;
    }

    private function getContactTelecom($OHPatientContactRelationship)
    {
        $telecoms = [];

        foreach ($OHPatientContactRelationship?->contactTelecoms ?? [] as $key => $telecom) {
            $telecoms[] = [
                "system" => $telecom?->system,
                "value"  => $telecom?->value,
                "use"    => $telecom?->use,
            ];
        }

        return $telecoms;
    }

    //PUT
    private function putPatient($OHPatient, $OHOrganization)
    {
        $request = [
            [
                "op" => "test"
            ]
        ];

        dd($request);
    }

    //GET
    public function getPatient($request, $company)
    {
        // going to API
        $company  = Company::find($company?->id);

        $param = [
            'identifier' => 'https://fhir.kemkes.go.id/id/nik|'. $request['nik']
        ];

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/Patient', $param);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            $company = Company::find($company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/Patient', $param);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully PatientService->getPatient', $responseBody);

        $response = [
            'success' => true,
            'message' => 'Successfully OneHealthPatientService->getPatient',
            'data'    => $responseBody
        ];

        return $response;
    }
}
