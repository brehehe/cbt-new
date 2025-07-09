<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\GetCompany;
use App\Http\Requests\Company\UpdateCreateCompany;
use App\Http\Requests\Condition\UpdateCreateCondition;
use App\Http\Requests\Encounter\UpdateCreateEncounter;
use App\Http\Requests\Location\UpdateCreateLocation;
use App\Http\Requests\Medication\UpdateCreateMedication;
use App\Http\Requests\MedicationDispense\UpdateCreateMedicationDispense;
use App\Http\Requests\MedicationDispense\ßUpdateCreateMedicationDispense;
use App\Http\Requests\MedicationRequest\UpdateCreateMedicationRequest;
use App\Http\Requests\Patient\GetPatient;
use App\Http\Requests\Patient\UpdateCreatePatient;
use App\Http\Requests\Practitiont\GetPractitiont;
use App\Models\Company\Company;
use App\Models\Condition\Condition;
use App\Models\Encounter\Encounter;
use App\Models\Location\Location;
use App\Models\Medication\Medication;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;
use App\Models\Practitiont\Practitiont;
use App\Models\Transaction\Transaction;
use App\Services\OneHealth\Condition\ConditionService;
use App\Services\System\Organization\OrganizationService as System_OrganizationService;
use App\Services\OneHealth\Organization\OrganizationService as OneHealth_OrganizationService;
use App\Services\OneHealth\Encounter\EncounterService as OneHealth_EncounterService;
use App\Services\OneHealth\Location\OneHealthLocationService as OneHealth_OneHealthLocationService;
use App\Services\OneHealth\Medication\MedicationService as OneHealth_MedicationService;
use App\Services\OneHealth\MedicationDispense\MedicationDispenseService as OneHealth_MedicationDispenseService;
use App\Services\OneHealth\MedicationRequest\MedicationRequestService as OneHealth_MedicationRequestService;
use App\Services\OneHealth\Organizaion\OneHealthOrganizationService as Onehealth_OneHealthOrganizationService;
use App\Services\OneHealth\Patient\PatientService as OneHealth_PatientService;
use App\Services\OneHealth\Practitiont\PractitiontService as OneHealth_PractitiontService;
use App\Services\System\Condition\ConditionService as System_ConditionService;
use App\Services\System\Encounter\EncounterService as System_EncounterService;
use App\Services\System\Location\LocationService as System_LocationService;
use App\Services\System\Medication\MedicationService as System_MedicationService;
use App\Services\System\MedicationDispence\MedicationDispenseService as System_MedicationDispenseService;
use App\Services\System\MedicationRequest\MedicationRequestService as System_MedicationRequestService;
use App\Services\System\Patient\PatientService as System_PatientService;
use App\Services\System\Practitiont\PractitiontService as System_PractitiontService;
use App\Traits\Encryption;
use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

use function PHPUnit\Framework\throwException;

class TestingController extends BaseController
{
    use Encryption;

    // Company
    public function postPutCompany(UpdateCreateCompany $request)
    {
        $validatedData = $request->validated();

        // check if id is set
        if (isset($validatedData['id'])) {
            $company = Company::find($validatedData['id']);
            if (!$company) {
                return $this->sendError('Perusahaan tidak ditemukan', [
                    'id' => $validatedData['id']
                ], 404);
            }
        }

        // check if company_id is set
        if (isset($validatedData['company_id'])) {
            $company = Company::find($validatedData['company_id']);
            if (!$company) {
                return $this->sendError('Perusahaan cabang tidak ditemukan', [
                    'company_id' => $validatedData['company_id']
                ], 404);
            }
        }

        // unique company code
        $unique_company_code = Company::where('code', $validatedData['code'])->first();
        if ($unique_company_code && !isset($validatedData['id'])) {
            return $this->sendError('Kode perusahaan sudah tersedia', [
                'code' => $validatedData['code'],
                'id'   => $unique_company_code?->id
            ], 409);
        }

        try {
            DB::beginTransaction();
            $company = app(System_OrganizationService::class)->updateOrCreateCompany($validatedData);
            if (!$company) {
                throw new Exception("Ada kesalahaan saat System_OrganizationService => updateOrCreateCompany", 500);
            }

            $OHOrganization = app(System_OrganizationService::class)->updateOrCreateOHOrganization($company);
            if (!$OHOrganization) {
                throw new Exception("Ada kesalahaan saat System_OrganizationService => updateOrCreateOHOrganization", 500);
            }

            $reponseBody = app(OneHealth_OrganizationService::class)->postPutOrganization($OHOrganization);
            // if (!isset($responseBody['id'])) {
            //     throw new Exception("Ada kesalahaan saat OneHealth_OrganizationService => postPutOrganization", 500);
            // }

            DB::commit();
        } catch (ConnectionException $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan connetion postPutCompany', $error);
            return $this->sendError('Ada Kesalahaan connetion postPutCompany', $error, 500);
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutCompany', $error);
            return $this->sendError('Ada Kesalahaan saat postPutCompany', $error, 500);
        }

        return $this->sendResponse("Successfully postPutCompany", $reponseBody);
    }

    public function getCompany(GetCompany $request)
    {
        $company = Company::find($request?->id);
        if (!$company) {
            return $this->sendError('Perusahaan tidak ditemukan', [
                'id' => $request?->id
            ], 404);
        }

        try {

            $response = app(OneHealth_OrganizationService::class)->getOrganization($company);
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat getCompany', $error);
            return $this->sendError('Ada Kesalahaan saat getCompany', $error, 500);
        }

        return $this->sendResponse('Successfully getCompany', $response);
    }

    // Location
    public function postPutLocation(UpdateCreateLocation $request)
    {
        $validatedData = $request->validated();

        // check if company_id is set
        if (isset($validatedData['company_id'])) {
            $company = Company::find($validatedData['company_id']);
            if (!$company) {
                return $this->sendError('Perusahaan tidak ditemukan', [
                    'company_id' => $validatedData['company_id']
                ], 404);
            }
        }

        // check if location_id is set
        if (isset($validatedData['location_id'])) {
            $location = Location::find($validatedData['location_id']);
            if (!$location) {
                return $this->sendError('Lokasi tidak ditemukan', [
                    'location_id' => $validatedData['location_id']
                ], 404);
            }
        }

        try {
            DB::beginTransaction();
            $location = app(System_LocationService::class)->updateOrCreateLocation($validatedData);
            if (!$location) {
                throw new Exception("Ada kesalahaan saat System_LocationService => updateOrCreateLocation", 500);
            }

            $reponseBody = app(OneHealth_OneHealthLocationService::class)->postPutLocation($location?->OHLocation);

            DB::commit();
        } catch (ConnectionException $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan connetion postPutLocation', $error);

            //logic menyimpan connection api yang gagal
            return $this->sendError('Ada Kesalahaan connetion postPutLocation', $error, 500);
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutLocation', $error);
            return $this->sendError('Ada Kesalahaan saat postPutLocation', $error, 500);
        }

        return $this->sendResponse("Successfully postPutLocation", $reponseBody);
    }

    // Practitiont
    public function getPractitiont(GetPractitiont $request)
    {
        //check company
        $company = Company::find($request?->company_id);
        if (!$company) {
            return $this->sendError('Perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        try {
            DB::beginTransaction();

            $response = app(OneHealth_PractitiontService::class)->getPractitiont($request);

            if ($response['success'] != true) throw new Exception($response['message'], 500);

            $data = $response['data']['entry'][0] ?? [];

            $OHPractitioner = app(System_PractitiontService::class)->updateOrCreateOHPractitiont($data, $request);

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat getPractitiont', $error);
            return $this->sendError('Ada Kesalahaan saat getPractitiont', $error, 500);
        }

        $response = app(System_PractitiontService::class)->__responseUpdateOrCreate($OHPractitioner, $response['data']);
        return $this->sendResponse('Succeessfully getPractitiont', $response);
    }

    // Patient
    public function postPutPatient(UpdateCreatePatient $request)
    {
        $validatedData = $request->validated();

        // check if id is set
        if (isset($validatedData['id'])) {
            $patient = Patient::find($validatedData['id']);
            if (!$patient) {
                return $this->sendError('Data pasien tidak ditemukan', [
                    'id' => $validatedData['id']
                ], 404);
            }
        }

        // check if company_id is set
        if (isset($validatedData['company_id'])) {
            $company = Company::find($validatedData['company_id']);
            if (!$company) {
                return $this->sendError('Perusahaan tidak ditemukan', [
                    'company_id' => $validatedData['company_id']
                ], 404);
            }
        }

        try {

            DB::beginTransaction();
            $patient = app(System_PatientService::class)->updateOrCreatePatient($validatedData);
            if (!$patient) {
                throw new Exception("Ada kesalahaan saat System_PatientService => updateOrCreatePatient", 500);
            }

            $OHPatient = app(System_PatientService::class)->updateOrCreateOHPatient($patient);
            if (!$OHPatient) {
                throw new Exception("Ada kesalahaan saat System_PatientService => updateOrCreateOHPatient", 500);
            }

            $reponseBody = app(OneHealth_PatientService::class)->postPutPatient($OHPatient, $company?->OHOrganization);

            DB::commit();
        } catch (ConnectionException $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan connetion postPutPatient', $error);

            //logic menyimpan connection api yang gagal
            return $this->sendError('Ada Kesalahaan connetion postPutPatient', $error, 500);
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutPatient', $error);
            return $this->sendError('Ada Kesalahaan saat postPutPatient', $error, 500);
        }

        return $this->sendResponse("Successfully postPutPatient", $reponseBody);
    }

    public function getpatient(GetPatient $request)
    {
        $validatedData = $request->validated();

        // check if company_id is set
        if (isset($validatedData['company_id'])) {
            $company = Company::find($validatedData['company_id']);
            if (!$company) {
                return $this->sendError('Perusahaan tidak ditemukan', [
                    'company_id' => $validatedData['company_id']
                ], 404);
            }
        }

        //get patient by identity card
        $get_patient = Patient::findByIdentityCard($validatedData['nik']);

        try {
            DB::beginTransaction();
            $response = app(OneHealth_PatientService::class)->getPatient($validatedData, $company);

            if ($response['success'] != true) new Exception("Ada kesalahaan saat OneHealth_PatientService => getPatient", 500);

            $data = $response['data']['entry'][0] ?? null;

            $body = [
                "id"                 => $get_patient?->id ?? null,
                "user_id"            => $validatedData['user_id'] ?? null,
                "company_id"         => $company?->id,
                "id_patient"         => $data['resource']['id'] ?? null,
                "name"               => $validatedData['name'] ?? $data['resource']['name'][0]['text'],
                "email"              => null,
                "gender"             => null,
                "birth_date"         => null,
                "deceased_date"      => null,
                "identity_card"      => $validatedData['nik'],
                "passport_number"    => null,
                "family_card_number" => null,
                "marital_status"     => null,
                "status"             => isset($data['resource']['active']) && $data['resource']['active'] == true ? 'active' : 'non-active',
                "patient_detail"     => [
                    "province" => [
                        "code" => null
                    ],
                    "city" => [
                        "code" => null
                    ],
                    "district" => [
                        "code" => null
                    ],
                    "sub_district" => [
                        "code" => null
                    ],
                    "address"     => null,
                    "postal_code" => null,
                    "country"     => "ID",
                    "rt"          => null,
                    "rw"          => null,
                    "longitude"   => null,
                    "latitude"    => null,
                    "altitude"    => null
                ],
                "contact_relationship" => [
                    "name"  => null,
                    "code"  => null,
                    "phone" => null,
                    "email" => null
                ]
            ];

            $patient = app(System_PatientService::class)->updateOrCreatePatient($body);
            if (!$patient) {
                throw new Exception("Ada kesalahaan saat System_PatientService => updateOrCreatePatient", 500);
            }

            $OHPatient = app(System_PatientService::class)->updateOrCreateOHPatient($patient, $body);
            if (!$OHPatient) {
                throw new Exception("Ada kesalahaan saat System_PatientService => updateOrCreateOHPatient", 500);
            }
            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat getPatient', $error);
            return $this->sendError('Ada Kesalahaan saat getPatient', $error, 500);
        }

        return $this->sendResponse('Successfully getPatient', $data);
    }

    //Encounter
    public function postPutEncounter(UpdateCreateEncounter $request)
    {
        // check encounter
        $encounter = Encounter::find($request?->id);
        if ($request?->id && !$encounter) {
            return $this->sendError('Data kunjungan tidak ditemukan', [
                'encounter_id' => $request?->encounter_id
            ], 404);
        }

        $transaction = Transaction::find($request?->transaction_id);
        if ($request?->transaction_id && !$transaction) {
            return $this->sendError('Data Transaksi tidak ditemukan', [
                'transaction_id' => $request?->transaction_id
            ], 404);
        }

        // check company
        $company = Company::find($request?->company_id);
        if (!$request?->company_id || !$company) {
            return $this->sendError('Data perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        // check location
        $location = Location::find($request?->location_id);
        if (!$request?->location_id || !$location) {
            return $this->sendError('Data lokasi perusahaan tidak ditemukan', [
                'location_id' => $request?->location_id
            ], 404);
        }

        // check practiont
        $practitiont = Practitioner::find($request?->practitioner_id);
        if (!$request?->practitioner_id || !$practitiont) {
            return $this->sendError('Data praktisi tidak ditemukan', [
                'practitiont_id' => $request?->practitioner_id
            ], 404);
        }

        // check patient
        $patient = Patient::find($request?->patient_id);
        if (!$request?->patient_id || !$patient) {
            return $this->sendError('Data pasien tidak ditemukan', [
                'patient_id' => $request?->patient_id
            ], 404);
        }

        try {
            DB::beginTransaction();

            $encounter = app(System_EncounterService::class)->updateOrCreateEncounter($request);
            if (!$encounter) {
                throw new Exception("Ada kesalahaan saat System_EncounterService => updateOrCreateEncounter", 500);
            }

            $OHEncounter = app(System_EncounterService::class)->updateOrCreateOHEncounter($encounter);
            if (!$OHEncounter) {
                throw new Exception("Ada kesalahaan saat System_EncounterService => updateOrCreateOHEncounter", 500);
            }

            $reponseBody = app(OneHealth_EncounterService::class)->postPutEncounter($OHEncounter);

            DB::commit();
        } catch (ConnectionException $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine()
            ];
            Log::error('Ada Kesalahaan connetion postPutEncounter', $error);

            //logic menyimpan connection api yang gagal
            return $this->sendError('Ada Kesalahaan connetion postPutEncounter', $error, 500);
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutEncounter', $error);
            return $this->sendError('Ada Kesalahaan saat postPutEncounter', $error, 500);
        }

        return $this->sendResponse('Successfully postPutEncounter', $reponseBody);
    }

    //Medication
    public function postPutMedication(UpdateCreateMedication $request)
    {

        // check medication
        $medication = Medication::find($request?->id);
        if ($request?->id && !$medication) {
            return $this->sendError('Data peresepan obat tidak ditemukan', [
                'id' => $request?->id
            ], 404);
        }

        // check company
        $company = Company::find($request?->company_id);
        if (!$request?->company_id || !$company) {
            return $this->sendError('Data perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        try {
            $product_detail = app(OneHealth_MedicationService::class)->getProductDetailV2($request);

            if (!isset($product_detail['result']['name'])) {
                return $this->sendError("Pencarian kode obat KFA tidak ditemukan", ['kode_kfa' => $request?->code_coding_code], 404);
            }

            $request->merge([
                'code_coding_display' => $product_detail['result']['name'],
                'id_organization'     => $request?->manufacturer_reference,
            ]);

            //check organization of medication product
            $organizaton_by_id = app(OneHealth_OrganizationService::class)->getOrganizationId($company, $request);

            if (!isset($organizaton_by_id['id'])) {
                return $this->sendError("Pencarian data organisasi yang menyimpan data pabrik obat tidak ditemukan", ['Organization ID (satu sehat)' => $request?->id_organization], 404);
            }

            DB::beginTransaction();
            $medication = app(System_MedicationService::class)->updateOrCreateMedication($request);
            if (!$medication) {
                throw new Exception("Ada kesalahaan saat System_MedicationService => updateOrCreateMedication", 500);
            }

            $OHMedication = app(System_MedicationService::class)->updateOrCreateOHMedication($medication);
            if (!$OHMedication) {
                throw new Exception("Ada kesalahaan saat System_MedicationService => updateOrCreateOHMedication", 500);
            }

            $responseBody = app(OneHealth_MedicationService::class)->postPutMedication($OHMedication);
            DB::commit();
        } catch (ConnectionException $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan connetion postPutMedication', $error);

            //logic menyimpan connection api yang gagal
            return $this->sendError('Ada Kesalahaan connetion postPutMedication', $error, 500);
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutMedication', $error);
            return $this->sendError('Ada Kesalahaan saat postPutMedication', $error, 500);
        }

        return $this->sendResponse("Successfully postPutMedication", $responseBody);
    }

    //Medication Request
    public function postPutMedicationRequest(UpdateCreateMedicationRequest $request)
    {
        // check medication
        $medication_request = MedicationRequest::find($request?->id);
        if ($request?->id && !$medication_request) {
            return $this->sendError('Data permintaan peresepan obat tidak ditemukan', [
                'id' => $request?->id
            ], 404);
        }

        // check company
        $company = Company::find($request?->company_id);
        if (!$request?->company_id || !$company) {
            return $this->sendError('Data perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        // check patient
        $patient = Patient::find($request?->patient_id);
        if (!$request?->patient_id || !$patient) {
            return $this->sendError('Data pasien tidak ditemukan', [
                'patient_id' => $request?->patient_id
            ], 404);
        }

        // check encounter
        $encounter = Encounter::find($request?->encounter_id);
        if (!$request?->encounter_id || !$encounter) {
            return $this->sendError('Data kunjungan pasien tidak ditemukan', [
                'encounter_id' => $request?->encounter_id
            ], 404);
        }

        // check medication
        $medication = Medication::find($request?->medication_id);
        if (!$request?->medication_id || !$medication) {
            return $this->sendError('Data peresepan obat tidak ditemukan', [
                'medication_id' => $request?->medication_id
            ], 404);
        }

        // check of Practitioner/Organization/Patient
        $requester = Company::find($request?->requester_id) ?? Practitioner::find($request?->requester_id) ?? Patient::find($request?->requester_id);
        if (!$request?->requester_id || !$requester) {
            return $this->sendError('Pihak yang melakukan peresepan obat tidak ditemukan', [
                'requester_id' => $request?->requester_id
            ], 404);
        }

        $request->merge([
            'requestable_type' => get_class($requester),
            'requestable_id' => $requester?->id,
        ]);

        try {
            DB::beginTransaction();
            $medication_request = app(System_MedicationRequestService::class)->updateOrCreateMedicationReq($request);
            if (!$medication_request) {
                throw new Exception("Ada kesalahaan saat System_MedicationRequestService => updateOrCreateMedicationReq", 500);
            }

            $OHMedicationReq = app(System_MedicationRequestService::class)->updateOrCreateOHMedicationReq($medication_request);
            if (!$OHMedicationReq) {
                throw new Exception("Ada kesalahaan saat System_MedicationRequestService => updateOrCreateOHMedicationReq", 500);
            }

            $responseBody = app(OneHealth_MedicationRequestService::class)->postPutOHMedicationRequest($OHMedicationReq);

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutMedicationRequest', $error);
            return $this->sendError('Ada Kesalahaan saat postPutMedicationRequest', $error, 500);
        }

        return $this->sendResponse("Successfully postPutMedicationRequest", $responseBody);
    }

    //Medication Dispense
    public function postPutMedicationDispense(UpdateCreateMedicationDispense $request)
    {
        // check medication Dispense
        $medication_dispense = MedicationDispense::find($request?->id);
        if ($request?->id && !$medication_dispense) {
            return $this->sendError('Data pengiriman pengeluaran/dispense obat tidak ditemukan', [
                'id' => $request?->id
            ], 404);
        }

        // check company
        $company = Company::find($request?->company_id);
        if (!$request?->company_id || !$company) {
            return $this->sendError('Data perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        // check location
        $location = Location::find($request?->location_id);
        if (!$request?->location_id || !$location) {
            return $this->sendError('Data lokasi perusahaan tidak ditemukan', [
                'location_id' => $request?->location_id
            ], 404);
        }

        // check practiont
        $practitiont = Practitioner::find($request?->practitioner_id);
        if (!$request?->practitioner_id || !$practitiont) {
            return $this->sendError('Data praktisi tidak ditemukan', [
                'practitiont_id' => $request?->practitioner_id
            ], 404);
        }

        // check patient
        $patient = Patient::find($request?->patient_id);
        if (!$request?->patient_id || !$patient) {
            return $this->sendError('Data pasien tidak ditemukan', [
                'patient_id' => $request?->patient_id
            ], 404);
        }

        // check encounter
        $encounter = Encounter::find($request?->encounter_id);
        if (!$request?->encounter_id || !$encounter) {
            return $this->sendError('Data kunjungan pasien tidak ditemukan', [
                'encounter_id' => $request?->encounter_id
            ], 404);
        }

        // check medication
        $medication = Medication::find($request?->medication_id);
        if (!$request?->medication_id || !$medication) {
            return $this->sendError('Data peresepan obat tidak ditemukan', [
                'medication_id' => $request?->medication_id
            ], 404);
        }

        // check medication request
        $medication_request = MedicationRequest::find($request?->medication_request_id);
        if (!$request?->medication_request_id || !$medication_request) {
            return $this->sendError('Data permintaan peresepan obat tidak ditemukan', [
                'medication_request_id' => $request?->medication_request_id
            ], 404);
        }

        // check of Practitioner/Organization/Patient
        $performer = Company::find($request?->performer_id) ?? Practitioner::find($request?->performer_id) ?? Patient::find($request?->performer_id);
        if (!$request?->performer_id || !$performer) {
            return $this->sendError('Pihak yang memberikan obat tidak ditemukan', [
                'performer_id' => $request?->performer_id
            ], 404);
        }

        try {
            DB::beginTransaction();
                $request->merge([
                    'performerable_type' => get_class($performer),
                    'performerable_id'   => $performer?->id
                ]);

                $medication_dispense = app(System_MedicationDispenseService::class)->updateOrCreateMedicationDispense($request);
                if (!$medication_dispense) {
                    throw new Exception("Ada kesalahaan saat System_MedicationDispenseService => updateOrCreateMedicationDispense", 500);
                }

                $OHMedicationDispense = app(System_MedicationDispenseService::class)->updateOrCreateOHMedicationDispense($medication_dispense);
                if (!$OHMedicationDispense) {
                    throw new Exception("Ada kesalahaan saat System_MedicationDispenseService => updateOrCreateOHMedicationDispense", 500);
                }

                $responseBody = app(OneHealth_MedicationDispenseService::class)->postPutOHMedicationDispense($OHMedicationDispense);


            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutMedicationDispense', $error);
            return $this->sendError('Ada Kesalahaan saat postPutMedicationDispense', $error, 500);
        }

        return $this->sendResponse("Successfully postPutMedicationDispense", $responseBody);
    }

    //Condition
    public function postPutCondition(UpdateCreateCondition $request)
    {
        // check condition
        $condition = Condition::find($request?->id);
        if ($request?->id && !$condition) {
            return $this->sendError('Data diagnosis pasien tidak ditemukan', [
                'id' => $request?->id
            ], 404);
        }

        // check company
        $company = Company::find($request?->company_id);
        if (!$request?->company_id || !$company) {
            return $this->sendError('Data perusahaan tidak ditemukan', [
                'company_id' => $request?->company_id
            ], 404);
        }

        // check patient
        $patient = Patient::find($request?->patient_id);
        if (!$request?->patient_id || !$patient) {
            return $this->sendError('Data pasien tidak ditemukan', [
                'patient_id' => $request?->patient_id
            ], 404);
        }

        // check encounter
        $encounter = Encounter::find($request?->encounter_id);
        if (!$request?->encounter_id || !$encounter) {
            return $this->sendError('Data kunjungan pasien tidak ditemukan', [
                'encounter_id' => $request?->encounter_id
            ], 404);
        }

        try {
            DB::beginTransaction();
                $condition = app(System_ConditionService::class)->updateOrCreateCondition($request);
                if (!$condition) {
                    throw new Exception("Ada kesalahaan saat System_ConditionService => updateOrCreateCondition", 500);
                }

                $OHCondition = app(System_ConditionService::class)->updateOrCreateOHCondition($condition);
                if (!$OHCondition) {
                    throw new Exception("Ada kesalahaan saat System_ConditionService => updateOrCreateOHCondition", 500);
                }

                $responseBody = app(ConditionService::class)->postPutCondition($OHCondition);

            DB::commit();
        } catch (Exception | Throwable $th) {
            DB::rollBack();
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat postPutCondition', $error);
            return $this->sendError('Ada Kesalahaan saat postPutCondition', $error, 500);
        }

        return $this->sendResponse("Successfully postPutCondition", $responseBody);
    }
}
