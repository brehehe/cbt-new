<?php

namespace App\service;

use App\Models\Company\Company;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;
use Auth;
use Crypt;
use Http;
use Log;

class apiservice
{
    use \App\Traits\Company\CompanyTrait;
    protected $url;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // $this->url = 'https://mediction.test/api';
        $this->url = config('app.url') . '/api';
    }

    public function getPratition($request)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->get($this->url . '/testing/practitiont/get-by-nik', $request);
        return $response->json();
    }

    public function createUser($user)
    {
        // Langkah 1: Cek apakah sudah ada pasien berdasarkan company_id, nik, dan name
        $checkResponse = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get($this->url . '/testing/patient/get-nik', [
            'company_id' => $user->company_id,
            'nik' => $user->userDetail->identity_card,
            'name' => $user->name,
            'user_id' => $user->id,
        ]);

        // Jika pasien sudah ditemukan di Satu Sehat, hentikan proses
        if ($checkResponse->ok() && $checkResponse->json('data')) {
            Log::info('Pasien sudah terdaftar di Satu Sehat.', $checkResponse->json());
            return [
                'message' => 'Pasien sudah terdaftar di Satu Sehat.',
                'status' => 'exists',
                'data' => $checkResponse->json('data')
            ];
        }

        // Jika belum ada, lanjut membuat pasien
        $patient = Patient::where('user_id', $user->id)->select('id')->first();

        $data = [
            "id" => $patient->id ?? null,
            "user_id" => $user->id,
            "company_id" => $user->company_id,
            "name" => $user->name,
            "email" => $user->email,
            "gender" => $user->userDetail->administrative_gender,
            "birth_date" => $user->userDetail->birth_date,
            "deceased_date" => $user->userDetail->deceased_date,
            "identity_card" => $user->userDetail->identity_card,
            "passport_number" => $user?->userDetail?->passport_number,
            "family_card_number" => $user?->userDetail?->family_card_number,
            "marital_status" => $user->userDetail->marital_status,
            "status" => 'active',
            "patient_detail" => [
                "province" => [
                    "code" => $user->userDetail->province_code,
                ],
                "city" => [
                    "code" => $user->userDetail->city_code
                ],
                "district" => [
                    "code" => $user->userDetail->district_code
                ],
                "sub_district" => [
                    "code" => $user->userDetail->sub_district_code
                ],
                "address" => $user->userDetail->address,
                "postal_code" => $user->userDetail->postal_code,
                "country" => "ID",
                "rt" => $user->userDetail->rt,
                "rw" => $user->userDetail->rw,
                "longitude" => 0,
                "latitude" => 0,
                "altitude" => 0,
            ],
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->post($this->url . '/testing/patient/post-put', $data);

        Log::info('API Response Patient: ', $response->json());

        return $response->json();
    }

    public function syncCompany($company)
    {
        $data = [
            "id" => $company->id,
            "company_id" => $company->company_id,
            "code" => $company->code,
            "name" => $company->name,
            "email" => $company->email,
            "phone" => $company->phone,
            "website" => $company->website,
            "is_active" => $company->is_active,
            "pic" => [
                "name" => $company->pic_name,
                "position" => $company->pic_position,
                "email" => $company->pic_email,
                "phone" => $company->pic_phone,
            ],
            "company_detail" => [
                "province" => [
                    "code" => $company->companyDetail->province_code,
                ],
                "city" => [
                    "code" => $company->companyDetail->city_code,
                ],
                "district" => [
                    "code" => $company->companyDetail->district_code,
                ],
                "sub_district" => [
                    "code" => $company->companyDetail->sub_district_code,
                ],
                "address" => $company->companyDetail->address,
                "postal_code" => $company->companyDetail->postal_code,
                "country" => $company->companyDetail->country,
                "rt" => $company->companyDetail->rt,
                "rw" => $company->companyDetail->rw,
                "longitude" => $company->companyDetail->longitude,
                "latitude" => $company->companyDetail->latitude,
                "altitude" => $company->companyDetail->altitude,
            ],
            "one_health" => [
                "organization_id" => Crypt::decryptString($company->oneHealthy->organization_id),
                "client_id" => Crypt::decryptString($company->oneHealthy->client_id),
                "client_secret" => Crypt::decryptString($company->oneHealthy->client_secret),
            ]
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/company/post-put', $data);

        Log::info('API Response: ', $response->json());
        return $response->json();
    }

    public function syncLocation($location)
    {
        $data = [
            'id' => $location->id,
            'company_id' => $location->company_id,
            'location_id' => null,
            'status' => $location->status,
            'name' => $location->name,
            'description' => $location->description,
            'mode' => $location->mode,
            'physical_type' => $location->physical_type,
        ];


        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/location/post-put', $data);

        Log::info('API Response: ', $response->json());
        return $response->json();
    }

    public function createTransaction($data)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/encounter/post-put', $data);
        Log::info('API Response Encounter: ', $response->json());
        return $response->json();
    }

    public function createConditionPrimary($data)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/condition/post-put', $data);
        Log::info('API Response Condition Primary: ', $response->json());
        return $response->json();
    }

    public function createMedictation($data)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/medication/post-put', $data);
        Log::info('API Response Medication: ', $response->json());
        return $response->json();
    }

    public function createMedicationRequest($data)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/medication-request/post-put', $data);
        Log::info('API Response Medication Request: ', $response->json());
        return $response->json();
    }

    public function createMedicationDispense($data)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/medication-dispense/post-put', $data);
        Log::info('API Response Medication Dispense: ', $response->json());
        return $response->json();
    }

    public function createCompany($company): array
    {

        $data = [
            "id" => "0197db07-894c-70be-89ae-72ed7cbb1feb",
            "company_id" => $company->id,
            "code" => "1Br0ck",
            "name" => "Burningroom Mediction",
            "email" => "burningroommediction@gmail.com",
            "phone" => "08961280948",
            "website" => "https://burningroom.co.id",
            "is_active" => true,
            "pic" => [
                "name" => "Eleven",
                "position" => "CEO",
                "email" => "eleven@gmail.com",
                "phone" => "0812321312"
            ],
            "company_detail" => [
                "province" => [
                    "code" => 35
                ],
                "city" => [
                    "code" => 3578
                ],
                "district" => [
                    "code" => 357803
                ],
                "sub_district" => [
                    "code" => 3578031006
                ],
                "address" => "jl. raya utama medokan raya",
                "postal_code" => 34345,
                "country" => "ID",
                "rt" => "1",
                "rw" => "8",
                "longitude" => 8.123,
                "latitude" => -0.177,
                "altitude" => 3.77
            ],
            "one_health" => [
                "organization_id" => "3e1a2508-04ef-43da-ac34-ff7a8ad6bc88",
                "client_id" => "gAMGybjyc0atZ2R6gpRBYspiWv5aExDcKGqlV6uSalUPswLN",
                "client_secret" => "5z2rqwLmSv7XdttW45Et6Vk8ez5NyHexLXMSAtInKz2NfhtcGSKhIVWPbKVXoca2"
            ]
        ];

        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/company/post-put', $data);
        Log::info('API Response Company: ', $response->json());
        return $response->json();
    }

    public function createCondition($data)
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])
            ->post($this->url . '/testing/condition/postput', $data);
        Log::info('API Response Condition: ', $response->json());
        return $response->json();
    }
}
