<?php

namespace App\Services\OneHealth\Location;

use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Location\OneHealth\OneHealthLocation;
use App\Traits\Company\CompanyTrait;
use App\Traits\OneHealth\AuthenticateTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OneHealthLocationService
{
    use CompanyTrait, AuthenticateTrait;

    /**
     * Create a new class instance.
     */
    public $url;

    public function __construct()
    {
        //
        $this->url = config('app.one_health.url'). '/fhir-r4/v1';
    }

    public function postPutLocation($OHLocation)
    {
        $OHLocation->refresh();

        $one_health = $this->getOneHealth($OHLocation?->location?->company);

        $one_health_organization_id = $one_health[0];

        $telecoms = $address_extentions = [];

        // telecoms
        foreach ($OHLocation?->OHLocationTelecoms ?? [] as $key => $telecom) {
            $telecoms[] = [
                'system' => $telecom?->system,
                'value'  => $telecom?->value,
                'use'    => $telecom?->use,
            ];
        }

         // address_extentions
        foreach ($OHLocation?->OHLocationAddress?->extentions ?? [] as $key => $extention) {
            $address_extentions[] = [
                "url"       => $extention?->url,
                "valueCode" => $extention?->value_code,
            ];
        }

        $request = [
            "resourceType" => "Location",
            "identifier"   => [
                [
                    'system' => $OHLocation?->OHLIdentifier?->system .'/'. $one_health_organization_id,
                    'value'  => $OHLocation?->OHLIdentifier?->value
                ]
            ],
            "status"      => $OHLocation?->status,
            "name"        => $OHLocation?->name,
            "description" => $OHLocation?->description,
            "mode"        => $OHLocation?->mode,
            "telecom"     => $telecoms,
            "address"     => [
                "use"  => $OHLocation?->OHLocationAddress?->use,
                "line" => [
                    $OHLocation?->OHLocationAddress?->line
                ],
                "city"       => $OHLocation?->OHLocationAddress?->city,
                "postalCode" => $OHLocation?->OHLocationAddress?->postal_code,
                "country"    => $OHLocation?->OHLocationAddress?->country,
                "extension"  => [
                    [
                        "url"       => $OHLocation?->OHLocationAddress?->extention_url,
                        "extension" => $address_extentions
                    ]
                ]
            ],
            "physicalType" => [
                "coding" => [
                    [
                        "system"  => $OHLocation?->physicalType_coding_system,
                        "code"    => $OHLocation?->physicalType_coding_code,
                        "display" => $OHLocation?->physicalType_coding_display,
                    ]
                ]
            ],
            "position" => [
                "longitude" => (float)$OHLocation?->position_longitude,
                "latitude"  => (float)$OHLocation?->position_latitude,
                "altitude"  => (float)$OHLocation?->position_altitude,
            ],
            "managingOrganization" => [
                "reference" => "Organization/" .$OHLocation?->OHOrganization?->id_organization
            ]
        ];

        if (!$OHLocation?->OHOrganization?->id_organization) {
            unset($request['managingOrganization']);
        }

        if ($OHLocation?->part_of_reference) {
            $request['partOf'] = [
                "reference" => "Location/" .$OHLocation?->part_of_reference
            ];
        }

        if ($OHLocation?->id_location) {
            $request['id'] = $OHLocation?->id_location;

            if (config('app.name') != 'production') Log::info('Request OneHealtLocationService->postPutLocation', $request);

            return $this->putLocation($OHLocation, $request);
        } else {
            if (config('app.name') != 'production') Log::info('Request OneHealtLocationService->postPutLocation', $request);

            return $this->postLocation($OHLocation, $request);
        }
    }

    private function postLocation($OHLocation, $request)
    {
        $location = $OHLocation?->location;

        $company = Company::find($location?->company?->id);

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url .'/Location', $request);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            $company = Company::find($company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url .'/Location', $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        $OHLocation->updateQuietly([
            'id_location' => isset($responseBody['id']) ? $responseBody['id'] : null
        ]);

        if (config('app.name') != 'production') Log::info('Successfully OneHealthLocationService->postLocation', $responseBody);

        return $responseBody;
    }

    private function putLocation($OHLocation, $request)
    {
        $location = $OHLocation?->location;

        $company = Company::find($location?->company?->id);

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->put($this->url .'/Location/'. $OHLocation?->id_location, $request);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            $company = Company::find($company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->put($this->url .'/Location/'. $OHLocation?->id_location, $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        $OHLocation->updateQuietly([
            'id_location' => isset($responseBody['id']) ? $responseBody['id'] : null
        ]);

        if (config('app.name') != 'production') Log::info('Successfully OneHealthLocationService->putLocation', $responseBody);

        return $responseBody;
    }
}
