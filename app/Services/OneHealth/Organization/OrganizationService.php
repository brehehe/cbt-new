<?php

namespace App\Services\OneHealth\Organization;

use Exception;
use App\Models\Company\Company;
use App\Models\Company\OneHealth\OneHealthOrganization;
use Illuminate\Support\Facades\Log;
use App\Traits\Company\CompanyTrait;
use Illuminate\Support\Facades\Http;
use App\Traits\OneHealth\AuthenticateTrait;

class OrganizationService
{
    /**
     * Create a new class instance.
     */
    use CompanyTrait, AuthenticateTrait;

    public $url;

    public function __construct()
    {
        //
        $this->url = config('app.one_health.url'). '/fhir-r4/v1';
    }

    //POST PUT
    public function postPutOrganization($OHOrganization)
    {
        $OHOrganization->refresh();

        $one_health = $this->getOneHealth($OHOrganization?->company);

        $one_health_organization_id = $one_health[0];

        $telecoms = $address_extentions = [];

        // telecoms
        foreach ($OHOrganization?->OHOrganizationTelecoms ?? [] as $key => $telecom) {
            $telecoms[] = [
                'system' => $telecom?->system,
                'value'  => $telecom?->value,
                'use'    => $telecom?->use,
            ];
        }

        // address_extentions
        foreach ($OHOrganization?->OHOrganizationAddress?->extentions ?? [] as $key => $extention) {
            $address_extentions[] = [
                "url" => $extention?->url,
                "valueCode" => $extention?->value_code,
            ];
        }

        $request = [
            "resourceType" => "Organization",
            "active"       => $OHOrganization?->active == 1 ? true : false,
            "identifier"   => [
                [
                    "use"    => $OHOrganization?->OHOrganizationIdentifier?->use,
                    "system" => $OHOrganization?->OHOrganizationIdentifier?->system .'/'. $one_health_organization_id,
                    "value"  => $OHOrganization?->OHOrganizationIdentifier?->value
                ]
            ],
             "type" => [
                [
                    "coding" => [
                        [
                            "system"  => "http://terminology.hl7.org/CodeSystem/organization-type",
                            "code"    => $OHOrganization?->type_coding_code,
                            "display" => $OHOrganization?->type_coding_display
                        ]
                    ]
                ]
            ],
            "name"    => $OHOrganization?->name,
            "telecom" => $telecoms,
            "address" => [
                [
                    "use"  => $OHOrganization?->OHOrganizationAddress?->use,
                    "type" => $OHOrganization?->OHOrganizationAddress?->type,
                    "line" => [
                        $OHOrganization?->OHOrganizationAddress?->line
                    ],
                    "city"       => $OHOrganization?->OHOrganizationAddress?->city,
                    "postalCode" => $OHOrganization?->OHOrganizationAddress?->postal_code,
                    "country"    => $OHOrganization?->OHOrganizationAddress?->country,
                    "extension"  => [
                        [
                            "url"       => $OHOrganization?->OHOrganizationAddress?->extention_url,
                            "extension" => $address_extentions
                        ]
                    ]
                ]
            ],
        ];

        if ($OHOrganization?->part_of_reference) {
            $request["partOf"] = [
                // "reference" => "Organization/". $one_health_organization_id //no organization one heath
                "reference" => "Organization/". $OHOrganization?->part_of_reference //no organization one heath
            ];
        }

        if ($OHOrganization?->id_organization) {
            $request['id'] = $OHOrganization?->id_organization;

            if (config('app.name') != 'production') Log::info('Request OneOrganizationService->postPutOrganization', $request);
            return $this->putOrganization($OHOrganization, $request);
        } else {

            if (config('app.name') != 'production') Log::info('Request OneOrganizationService->postPutOrganization', $request);
            return $this->postOrganization($OHOrganization, $request);
        }

    }

    private function postOrganization($OHOrganization, $request)
    {
        $company = Company::find($OHOrganization?->company?->id);

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url .'/Organization', $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHOrganization?->company);
            $company = Company::find($OHOrganization?->company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url .'/Organization', $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        $OHOrganization->updateQuietly([
            'id_organization' => isset($responseBody['id']) ? $responseBody['id'] : null
        ]);

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_OrganizationService->postOrganization', $responseBody);

        return $responseBody;
    }

    private function putOrganization($OHOrganization, $request)
    {
        $company = $OHOrganization?->company;

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
        ->put($this->url .'/Organization/'. $OHOrganization?->id_organization, $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHOrganization?->company);
            $company = $OHOrganization?->company;

            $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->put($this->url .'/Organization/'. $OHOrganization?->id_organization, $request);
        }

        $responseBody = $response->json();                                       // sudah array
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_OrganizationService->putOrganization', $responseBody);

        return $responseBody;
    }

    //GET
    public function getOrganization($company)
    {
        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/Organization/'. $company?->OHOrganization?->id_organization);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            $company = Company::find($company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/Organization/'. $company?->OHOrganization?->id_organization);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_OrganizationService->getOrganization', $responseBody);

        return $responseBody;
    }

    //Get Organization by ID
    public function getOrganizationId($company, $param)
    {
        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/Organization/'. $param?->id_organization);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            $company = Company::find($company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/Organization/'. $param?->id_organization);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_OrganizationService->getOrganizationId', $responseBody);

        return $responseBody;
    }
}
