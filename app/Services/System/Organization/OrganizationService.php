<?php

namespace App\Services\System\Organization;

use App\Models\Company\Company;
use App\Traits\Company\CompanyTrait;
use App\Traits\Encryption;

class OrganizationService
{
    /**
     * Create a new class instance.
     */
    use CompanyTrait, Encryption;

    public function __construct()
    {
        //
    }

    public function updateOrCreateCompany($validatedData)
    {
        $company = Company::updateOrCreate(
            [
                'id'         => $validatedData['id'] ?? null,
            ],
            [
                'company_id' => $validatedData['company_id'] ?? null,
                'code'       => $validatedData['code'] ?? null,
                'name'       => $validatedData['name'] ?? null,
                'email'      => $validatedData['email'] ?? null,
                'phone'      => $validatedData['phone'] ?? null,
                'website'    => $validatedData['website'] ?? null,

                'pic_name'     => $validatedData['pic']['name'] ?? null,
                'pic_position' => $validatedData['pic']['position'] ?? null,
                'pic_email'    => $validatedData['pic']['email'] ?? null,
                'pic_phone'    => $validatedData['pic']['phone'] ?? null,
            ]
        );

        $company->companyDetail()->updateOrCreate(
            [
                'company_id' => $company?->id ?? null,
            ],
            [
                'province_code'     => $validatedData['company_detail']['province']['code'] ?? null,
                'city_code'         => $validatedData['company_detail']['city']['code'] ?? null,
                'district_code'     => $validatedData['company_detail']['district']['code'] ?? null,
                'sub_district_code' => $validatedData['company_detail']['sub_district']['code'] ?? null,
                'address'           => $validatedData['company_detail']['address'] ?? null,
                'postal_code'       => $validatedData['company_detail']['postal_code'] ?? null,
                'country'           => $validatedData['company_detail']['country'] ?? 'ID',
                'rt'                => $validatedData['company_detail']['rt'] ?? null,
                'rw'                => $validatedData['company_detail']['rw'] ?? null,
                'longitude'         => $validatedData['company_detail']['longitude'] ?? null,
                'latitude'          => $validatedData['company_detail']['latitude'] ?? null,
                'altitude'          => $validatedData['company_detail']['altitude'] ?? null,
            ]
        );

        if (!isset($validatedData['company_id'])) {
            $one_health = $company->oneHealthy()->updateOrCreate(
                [
                    'company_id' => $company?->id ?? null,
                ],
                [
                    'organization_id' => $validatedData['one_health']['organization_id'] ? $this->encrypted($validatedData['one_health']['organization_id']): null,
                    'client_id'       => $validatedData['one_health']['client_id'] ? $this->encrypted($validatedData['one_health']['client_id']): null,
                    'client_secret'   => $validatedData['one_health']['client_secret'] ? $this->encrypted($validatedData['one_health']['client_secret']) : null,
                ]
            );
        }

        return $company;
    }

    public function updateOrCreateOHOrganization($company, $request = [])
    {
        $request = [
            'organization' => [
                // 'organization_id'     => null,
                'type_coding_code'    => null,
                'type_coding_display' => null,
                'name'                => null,
                'active'              => null,
                'part_of'             => null,
            ],
            'organization_identifier' => [
                'use'    => null,
                'system' => null,
                'value'  => null,
            ],
            'organization_telecoms' => $this->getOrganizationTelecom($company),
            'organization_address' => [
                'use'         => null,
                'type'        => null,
                'line'        => null,
                'city'        => null,
                'postal_code' => null,
                'extentions'  => $this->getAddressExtention($company),
            ]
        ];

        // update or create One health Organization
        $OHOrganization =  $company?->OHOrganization()?->updateOrCreate(
            [
                'company_id' => $company?->id
            ],
            [
                // 'organization_id'     => $request['organization']['organization_id'],
                'type_coding_code'    => $request['organization']['type_coding_code'] ?? 'dept',
                'type_coding_display' => $request['organization']['type_coding_display'] ?? $company?->name,
                'name'                => $request['organization']['name'] ?? $company?->name,
                'active'              => $request['organization']['active'] ?? 1,
                'part_of_reference'   => $company?->company?->OHOrganization?->id_organization
            ]
        );

        // update or create One Organization Identifier
        $OHOrganization?->OHOrganizationIdentifier()->updateOrCreate(
            [
                'one_health_organization_id' => $OHOrganization?->id
            ],
            [
                'use'   => $request['organization_identifier']['use'] ?? 'official',
                'value' => $request['organization_identifier']['value'] ?? $OHOrganization?->id,
            ]
        );

        // update or create One Organization Telecoms
        foreach ($request['organization_telecoms'] ?? [] as $key => $value) {
            $OHOrganization?->OHOrganizationTelecoms()->updateOrCreate(
                [
                    'system' => $value['system'],
                ],
                [
                    'value'  => $value['value'],
                ]
            );
        }

        // update or create One Organization Address
        $OHorganizationAddress = $OHOrganization?->OHOrganizationAddress()->updateOrCreate(
            [
                'one_health_organization_id' => $OHOrganization?->id
            ],
            [
                'use'         => $request['organization_address']['use'] ?? 'work',
                'type'        => $request['organization_address']['type'] ?? 'both',
                'line'        => $request['organization_address']['line'] ?? $company?->companyDetail?->address,
                'city'        => $request['organization_address']['city'] ?? $company?->companyDetail?->city,
                'postal_code' => $request['organization_address']['postal_code'] ?? $company?->companyDetail?->postal_code,
                'country'     => $request['organization_address']['country'] ?? $company?->companyDetail?->country,
            ]
        );

        // update or create One Organization Address Extention
        foreach ($request['organization_address']['extentions'] ?? [] as $key => $extention) {
            $OHorganizationAddress?->extentions()->updateOrCreate(
                [
                    'url' => $extention['url']
                ],
                [
                    'value_code' => $extention['value_code']
                ]
            );
        }

        return $OHOrganization;
    }

    private function getOrganizationTelecom($company)
    {
        $organization_telecoms = [];
        $categories = [
            'email'   => 'email',
            'phone'   => 'phone',
            'website' => 'url',
        ];

        foreach ($categories as $key => $category) {
            if ($company?->$key != null) {
                $organization_telecoms [] = [
                    'system' => $category,
                    'value'  => $company?->$key,
                ];
            }
        }

        return $organization_telecoms;
    }

    private function getAddressExtention($company)
    {
        $address_extentions = [];
        $datas = [
            'province_code'     => 'province',
            'city_code'         => 'city',
            'district_code'     => 'district',
            'sub_district_code' => 'village',
            'rt'                => 'rt',
            'rw'                => 'rw',
        ];

        // dd($company);

        foreach ($datas as $key => $data) {
            if ($company?->companyDetail?->$key != null) {
                $address_extentions [] = [
                    'url'        => $data,
                    'value_code' => $company?->companyDetail?->$key,
                ];
            }
        }

        // dd($address_extentions);
        return $address_extentions;
    }
}
