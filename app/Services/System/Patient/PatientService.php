<?php

namespace App\Services\System\Patient;

use App\Models\Patient\Patient;
use App\Traits\Encryption;
use Illuminate\Support\Facades\DB;

class PatientService
{
    use Encryption;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updateOrCreatePatient($request)
    {
        $patient = Patient::updateOrCreate(
            [
                'id'      => $request['id'] ?? null,
            ],
            [
                'user_id' => $request['user_id'] ?? null,
                'ihs_number'         => $request['ihs_number'] ?? null,
                'blood_group'        => $request['blood_group'] ?? null,
                'name'               => $request['name'] ?? null,
                'birth_date'         => $request['birth_date'] ?? null,
                'gender'             => $request['gender'] ?? null,
                'deceased_date'      => $request['deceased_date'] ?? null,
                'identity_card'      => isset($request['identity_card']) ? $this->encrypted($request['identity_card']) : null,
                'passport_number'    => isset($request['passport_number']) ? $this->encrypted($request['passport_number']) : null,
                'family_card_number' => isset($request['family_card_number']) ? $this->encrypted($request['family_card_number']) : null,
                'marital_status'     => $request['marital_status'] ?? 'U',
                'status'             => $request['status'] ?? null,
                'phone' => $request['phone'] ?? null,
                'email' => $request['email'] ?? null,
            ]
        );

        $patient->patientCompany()->updateOrCreate(
            [
                'patient_id' => $patient?->id,
                'company_id' => $request['company_id'] ?? nullValue()
            ]
        );


        $patient->patientDetail()->updateOrCreate(
            [
                'patient_id' => $patient?->id
            ],
            [
                'province_code'     => $request['patient_detail']['province']['code'] ?? null,
                'city_code'         => $request['patient_detail']['city']['code'] ?? null,
                'district_code'     => $request['patient_detail']['district']['code'] ?? null,
                'sub_district_code' => $request['patient_detail']['sub_district']['code'] ?? null,
                'address'           => $request['patient_detail']['address'] ?? null,
                'postal_code'       => $request['patient_detail']['postal_code'] ?? null,
                'country'           => $request['patient_detail']['country'] ?? 'ID',
                'rt'                => $request['patient_detail']['rt'] ?? null,
                'rw'                => $request['patient_detail']['rw'] ?? null,
                'longitude'         => $request['patient_detail']['longitude'] ?? 0,
                'latitude'          => $request['patient_detail']['latitude'] ?? 0,
                'altitude'          => $request['patient_detail']['altitude'] ?? 0,
            ]
        );

        $patient->patientContactRelationship()->updateOrCreate(
            [
                'patient_id' => $patient?->id
            ],
            [
                'name'                     => $request['contact_relationship']['name'] ?? null,
                'relationship_coding_code' => $request['contact_relationship']['code'] ?? null,
                'phone'                    => $request['contact_relationship']['phone'] ?? null,
                'email'                    => $request['contact_relationship']['email'] ?? null,
            ]
        );

        return $patient;
    }

    public function updateOrCreateOHPatient($patient, $request = [])
    {
        $OHPatient = $patient->OHPatient()->updateOrCreate(
            [
                'patient_id' => $patient?->id
            ],
            [
                'id_patient'                 => $request['id_patient'] ?? $patient?->OHPatient?->id_patient,
                'name_text'                  => $patient?->name,
                'gender'                     => $patient?->gender,
                'birth_date'                 => $patient?->birth_date,
                'deceased_date'              => $patient?->deceased_date,
                'deceased_boolean'           => $patient?->deceased_date ? true : false,
                'active'                     => $patient?->status != 'active' ? true : false,
                'marital_status_coding_code' => $patient?->marital_status,
            ]
        );

        foreach ($this->getIdentifier() ?? [] as $key => $identifier) {
            if ($patient?->$key == null) continue;
            $OHPatient->OHPatientIdentifiers()->updateOrCreate(
                [
                    'system' => $identifier
                ],
                [
                    'value' => $patient?->$key
                ]
            );
        }

        if ($OHPatient?->id_patient) {
            $OHPatient->OHPatientIdentifiers()->updateOrCreate(
                [
                    'system' => 'https://fhir.kemkes.go.id/id/ihs-number'
                ],
                [
                    'value' => $OHPatient?->id_patient
                ]
            );

        }

        $OHPatientAddress = $OHPatient->OHPatientAddress()->updateOrCreate(
            [
                'one_health_patient_id' => $OHPatient?->id
            ],
            [
                'use'         => $request['organization_address']['use'] ?? 'home',
                'line'        => $request['organization_address']['line'] ?? $patient?->patientDetail?->address,
                'city'        => $request['organization_address']['city'] ?? $patient?->patientDetail?->city,
                'postal_code' => $request['organization_address']['postal_code'] ?? $patient?->patientDetail?->postal_code,
                'country'     => $request['organization_address']['country'] ?? $patient?->patientDetail?->country,
            ]
        );

        foreach ($this->getAddressExtension() ?? [] as $key => $extention) {
            $OHPatientAddress->extensions()->updateOrCreate(
                [
                    'url' => $extention
                ],
                [
                    'value_code' => $patient?->patientDetail?->$key
                ]
            );
        }

        $OHPatientContactRelationship = $OHPatient->OHPatientContactRelationship()->updateOrCreate(
            [
                'one_health_patient_id' => $OHPatient?->id
            ],
            [
                'name_text'                => $patient?->patientContactRelationship?->name,
                'relationship_coding_code' => $patient?->patientContactRelationship?->relationship_coding_code,
            ]
        );

        foreach ($this->getContactTelecom() ?? [] as $key => $telecom) {
            $OHPatientContactRelationship->contactTelecoms()->updateOrCreate(
                [
                    'system' => $telecom
                ],
                [
                    'value' => $patient?->patientContactRelationship?->$telecom
                ]
            );
        }

        return $OHPatient;
    }

    private function getIdentifier()
    {
        $codes = [
            'identity_card'      => 'https://fhir.kemkes.go.id/id/nik',
            'passport_number'    => 'https://fhir.kemkes.go.id/id/paspor',
            'family_card_number' => 'https://fhir.kemkes.go.id/id/kk',
        ];

        return $codes;
    }

    private function getAddressExtension()
    {
        $datas = [
            'province_code'     => 'province',
            'city_code'         => 'city',
            'district_code'     => 'district',
            'sub_district_code' => 'village',
            'rt'                => 'rt',
            'rw'                => 'rw',
        ];

        return $datas;
    }

    private function getContactTelecom()
    {
        $datas = ['phone', 'email'];

        return $datas;
    }
}
