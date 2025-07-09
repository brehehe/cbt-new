<?php

namespace App\Services\OneHealth\Medication;

use App\Models\Company\Company;
use App\Models\Medication\Medication;
use App\Traits\Company\CompanyTrait;
use App\Traits\Encryption;
use App\Traits\OneHealth\AuthenticateTrait;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

class MedicationService
{
    use AuthenticateTrait, CompanyTrait, Encryption;
    /**
     * Create a new class instance.
     */
    public $url, $url_kfa_v2;

    public function __construct()
    {
        //
        $this->url        = config('app.one_health.url') . '/fhir-r4/v1';
        $this->url_kfa_v2 = config('app.one_health.url') . '/kfa-v2/products';
    }

    public function getProductDetailV2($request)
    {
        $company = Company::find($request?->company_id);

        $params = [
            'identifier' => 'kfa',
            'code'       => $request?->code_coding_code,
        ];

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->get($this->url_kfa_v2, $params);

        if ($response->unauthorized()) {
            $this->accessToken($company);
            $company = Company::find($request?->company_id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->get($this->url_kfa_v2, $params);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_MedicationService->getProductDetailV2', $responseBody);

        return $responseBody;
    }

    public function postPutMedication($OHMedication)
    {
        $OHMedication->refresh();

        $one_health = $this->getOneHealth($OHMedication?->medication?->company);

        $one_health_organization_id = $one_health[0];

        $request = [
            "resourceType" => "Medication",
            "meta" => [
                "profile" => [
                    $OHMedication?->meta_profile
                ]
            ],
            "identifier" => [
                [
                    "system" => $OHMedication?->OHMedicationIdentifier?->system . $one_health_organization_id,
                    "use"    => $OHMedication?->OHMedicationIdentifier?->use,
                    "value"  => $OHMedication?->OHMedicationIdentifier?->value
                ]
            ],
            "code" => [
                "coding" => [
                    [
                        "system"  => $OHMedication?->OHMedicationCode?->coding_system,
                        "code"    => $OHMedication?->OHMedicationCode?->coding_code,
                        "display" => $OHMedication?->OHMedicationCode?->coding_display,
                    ]
                ]
            ],
            "status" => $OHMedication?->status,
            "manufacturer" => [
                "reference" => "Organization/" . $this->decrypted($OHMedication?->manufacturer_reference)
            ],
            "form" => [
                "coding" => [
                    [
                        "system"  => $OHMedication?->OHMedicationForm?->system,
                        "code"    => $OHMedication?->OHMedicationForm?->code,
                        "display" => $OHMedication?->OHMedicationForm?->display,
                    ]
                ]
            ],
            "ingredient" => $this->getIngredient($OHMedication),
            "extension" => [
                [
                    "url" => $OHMedication?->OHExtension?->url,
                    "valueCodeableConcept" => [
                        "coding" => [
                            [
                                "system"  => $OHMedication?->OHExtension?->value_coding_system,
                                "code"    => $OHMedication?->OHExtension?->value_coding_code,
                                "display" => $OHMedication?->OHExtension?->value_coding_display,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // dd($request);
        if ($OHMedication?->id_medication) {
            $request['id'] = $OHMedication?->id_medication;

            return $this->putMedication($OHMedication, $request);
        } else {
            return $this->postMedication($OHMedication, $request);
        }
    }

    private function getIngredient($OHMedication)
    {
        $OHIngredients = $OHMedication?->OHIngredients()->select('item_coding_system', 'item_coding_code', 'item_coding_display', 'is_active', 'strength_numerator_value', 'strength_numerator_system', 'strength_numerator_code', 'strength_denominator_value', 'strength_denominator_system', 'strength_denominator_code')->get();

        $dataOHIngredients = [];
        foreach ($OHIngredients ?? [] as $key => $ingredient) {
            $dataOHIngredients[] = [
                "itemCodeableConcept" => [
                    "coding" => [
                        [
                            "system"  => $ingredient?->item_coding_system,
                            "code"    => $ingredient?->item_coding_code,
                            "display" => $ingredient?->item_coding_display,
                        ]
                    ]
                ],
                "isActive" => $ingredient?->is_active,
                "strength" => [
                    "numerator" => [
                        "value"  => $ingredient?->strength_numerator_value,
                        "system" => $ingredient?->strength_numerator_system,
                        "code"   => $ingredient?->strength_numerator_code,
                    ],
                    "denominator" => [
                        "value"  => $ingredient?->strength_denominator_value,
                        "system" => $ingredient?->strength_denominator_system,
                        "code"   => $ingredient?->strength_denominator_code,
                    ]
                ]
            ];
        }

        return $dataOHIngredients;
    }

    private function postMedication($OHMedication, $request)
    {
        $company = Company::find($OHMedication?->medication?->company?->id);

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->post($this->url . '/Medication', $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHMedication?->medication?->company);
            $company = Company::find($OHMedication?->medication?->company?->id);

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->post($this->url . '/Medication', $request);
        }

        $responseBody = $response->json();
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        $OHMedication->updateQuietly([
            'id_medication' => isset($responseBody['id']) ? $responseBody['id'] : null
        ]);

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_MedicationService->postMedication', $responseBody);

        return $responseBody;
    }

    private function putMedication($OHMedication, $request)
    {
        $company = $OHMedication?->medication?->company;

        $response = Http::withToken($company?->one_health_access_token ?? '')
            ->withOptions(['verify' => false])
            ->put($this->url . '/Medication/' . $OHMedication?->id_medication, $request);

        if ($response->unauthorized()) {
            $this->accessToken($OHMedication?->medication?->company);
            $company = $OHMedication?->medication?->company;

            $response = Http::withToken($company?->one_health_access_token ?? '')
                ->withOptions(['verify' => false])
                ->put($this->url . '/Medication/' . $OHMedication?->id_medication, $request);
        }

        $responseBody = $response->json();                                       // sudah array
        if (!$response->successful()) {
            $message      = $responseBody['message'] ?? json_encode($responseBody);
            throw new Exception($message, 500);
        }

        if (config('app.name') != 'production') Log::info('Successfully OneHealth_MedicationService->putMedication', $responseBody);

        return $responseBody;
    }
}
