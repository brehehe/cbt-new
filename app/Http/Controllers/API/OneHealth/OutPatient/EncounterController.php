<?php

namespace App\Http\Controllers\API\OneHealth\OutPatient;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\OutPatient\Encounter\CreateRequest as Encounter_CreateRequest;
use App\Models\Company\Company;
use App\Models\Patient\Patient;
use App\Models\User;
use App\Services\System\OutPatient\EncounterService as System_OutPatient_EncounterService;
use App\Services\OneHealth\OutPatient\EncounterService as OneHealth_OutPatient_EncounterService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class EncounterController extends BaseController
{
    //
    public $url, $oneHealthOutPatientEncounterService, $systemOutPatientEncounterService;

    public function __construct()
    {
        $this->url                                 = config('app.one_health.url'). '/fhir-r4/v1';
        $this->systemOutPatientEncounterService    = new System_OutPatient_EncounterService();
        $this->oneHealthOutPatientEncounterService = new OneHealth_OutPatient_EncounterService();
    }

    public function createEncounter(Encounter_CreateRequest $request)
    {
        $body = $request->all();

        // $rules = [
        //     'patient_id' => ['required'],
        //     'company_id' => ['required'],

        //     'status'         => ['required'],
        //     'period_start'   => ['required', 'date'],
        //     'class_act_code' => ['required', Rule::exists('act_codes', 'code')],
        //     // 'type'           => ['nullable', Rule::exists('encounter_types', 'code')],
        //     // 'service_type'   => ['nullable', Rule::exists('service_types', 'code')],

        //     'location' => ['required'],
        // ];

        // $validator = Validator::make($body, $rules);

        // if ($validator->fails()) {
        //     return $this->sendError("Input tidak sesuai dengan ketentuan.". json_encode($body) , $validator->errors(), 400);
        // }

        // $patient = Patient::find($body['patient_id']);

        // if (!$patient || !$patient?->user) {
        //     return $this->sendError("Data Pasien $this->notfound_msg, id Patient : ". $body['patient_id']);
        // }

        // $company = Company::find($body['company_id']);
        // if (!$company || !$company?->user) {
        //     return $this->sendError("Data Klinik $this->notfound_msg, id Clinic : ". $body['company_id']);
        // }

        try {
            // $request->validated();
            // return $this->systemOutPatientEncounterService->createEncounter($body);

            $response = $this->oneHealthOutPatientEncounterService->createEncounter($body);


        } catch (Exception | Throwable $th) {
            $error = [
                'message' => json_decode($th->getMessage(),true),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat createEncounter', $error);
            return $this->sendError('Ada Kesalahaan saat createEncounter', $error, 500);
        }

        return $this->sendResponse("Berhasil daftar kunjungan baru !", $response);
    }
}
