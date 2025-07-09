<?php

namespace App\Services\System\Practitiont;

use App\Models\Practitiont\OneHealth\OneHealthPractitioner;
use App\Models\Practitiont\OneHealth\OneHealthPractitiont;
use App\Models\Practitiont\Practitioner;
use App\Models\Practitiont\Practitiont;
use Illuminate\Support\Facades\DB;

class PractitiontService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreateOHPractitiont($data, $request = null)
    {
        // dd($data);
        $OHPractitiont = OneHealthPractitioner::updateOrCreate(
            [
                'id_practitiont' => $data['resource']['id'] ?? null
            ],
            [
                'name_text'  => $request?->name ?? $data['resource']['name'][0]['text'],
                'name_use'   => $data['resource']['name'][0]['use'] ?? null,
                'birth_date' => $data['resource']['birthDate'] ?? null,
                'gender'     => $data['resource']['gender'] ?? null,
                'full_url'   => $data['fullUrl'] ?? null,
            ]
        );

        $practitioner = $OHPractitiont->practitioner == null ? Practitioner::create() : $OHPractitiont->practitioner;

        $OHPractitiont->update([
            'practitioner_id' => $practitioner?->id
        ]);

        foreach ($data['resource']['identifier'] ?? [] as $key => $identifier) {
            $OHPractitiont->OHPractitiontIdentifiers()->updateOrCreate(
                [
                    'system' => $identifier['system']
                ],
                [
                    'use'   => $identifier['use'] ?? null,
                    'value' => $identifier['value'] ?? null,
                ]
            );
        }

        $OHPractitiontAddress = $OHPractitiont->OHPractitiontAddress()->updateOrCreate(
            [
                'one_health_practitiont_id' => $OHPractitiont?->id
            ],
            [
                'use'           => $data['resource']['address'][0]['use'] ?? 'home',
                'line'          => $data['resource']['address'][0]['line'][0] ?? null,
                'city'          => $data['resource']['address'][0]['city'] ?? null,
                'country'       => $data['resource']['address'][0]['country'] ?? 'ID',
                'postal_code'   => $data['resource']['address'][0]['postalCode'] ?? null,
                'extention_url' => $data['resource']['address'][0]['extension'][0]['url'] ?? 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
            ]
        );

        foreach ($data['resource']['address'][0]['extension'][0]['extension'] ?? [] as $key => $extension) {
            $OHPractitiontAddress->extensions()->updateOrCreate(
                [
                    'url' => $extension['url']
                ],
                [
                    'value_code' => $extension['valueCode']
                ]
            );
        }

        foreach ($data['resource']['qualification'][0]['code']['coding'] ?? [] as $key => $qualification_code_coding) {
            $OHPractitiont->OHPractitiontQualificationCodeCodings()->updateOrCreate(
                [
                    'code' => $qualification_code_coding['code']
                ],
                [
                    'system'  => $qualification_code_coding['system'],
                    'display' => $qualification_code_coding['display'],
                ]
            );
        }

        foreach ($data['resource']['qualification'][0]['identifier'] ?? [] as $key => $qualification_identifier) {
            $OHPractitiont->OHPractitiontQualificationIdentifiers()->updateOrCreate(
                [
                    'system' => $qualification_identifier['system']
                ],
                [
                    'value' => $qualification_identifier['value']
                ]
            );
        }

        return $OHPractitiont;
    }

    public function __responseUpdateOrCreate($OHPractitiont, $request)
    {
        $datas = [
            'id_practitioner'  => $OHPractitiont?->id_practitiont,
            'gender'           => $OHPractitiont?->gender,
            'birth_date'       => $OHPractitiont?->birth_date?->format('Y-m-d'),
            'name'             => $OHPractitiont?->name_text,
            'practitioner_id' => $OHPractitiont?->practitioner?->id,
            'address'          => [
                'city_name'     => $OHPractitiont?->OHPractitiontAddress?->city,
                'address'       => $OHPractitiont?->OHPractitiontAddress?->line,
                'country'       => $OHPractitiont?->OHPractitiontAddress?->country,
                'province_code' => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'province')->first()?->value_code,
                'city_code'     => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'city')->first()?->value_code,
                'district_code' => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'district')->first()?->value_code,
                'village_code'  => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'village')->first()?->value_code,
                'rt_code'       => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'rt')->first()?->value_code,
                'rw_code'       => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'rw')->first()?->value_code,
                'longitude'     => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'longitude')->first()?->value_code ?? 0,
                'latititude'    => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'latititude')->first()?->value_code ?? 0,
                'altitude'      => $OHPractitiont?->OHPractitiontAddress?->extensions()->where('url', 'altitude')->first()?->value_code ?? 0,
            ]
        ];

        return $datas;
    }
}
