<?php

namespace App\Http\Controllers\API\OneHealth\Deployment;

use Exception;
use Throwable;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\API\OneHealth\Auth\AuthController;
use App\Models\Master\CodeSystem\Patient\IdentifierUse;
use App\Models\Patient\Patient;
use App\Services\System\Deployment\PatientService as System_PatientService;
use App\Services\OneHealth\Deployment\PatientService as OneHealth_PatientService;

class PatientController extends BaseController
{
    //
    public $url, $oneHealthPatientService, $systemPatientService;

    public function __construct()
    {
        $this->url = config('app.one_health.url'). '/fhir-r4/v1';
        $this->oneHealthPatientService = new OneHealth_PatientService();
        $this->systemPatientService = new System_PatientService();
    }

    public function createPatient(Request $request)
    {
        $body = $request->all();

        $rules = [
            'patient_id' => ['nullable'],

            // 'identifier_uses'         => ['required', 'array'],
            // 'identifier_uses.*.use'   => ['required', Rule::exists('identifier_uses', 'code')],
            // 'identifier_uses.*.value' => ['required'],

            'name'          => ['required'],
            'email'         => ['nullable'],
            'gender'        => ['required', Rule::exists('administrative_genders', 'code')],
            'birth_date'    => ['required', 'date'],
            'deceased_date' => ['nullable', 'date'],
            'blood_group'   => ['nullable'],

            'addresses'               => ['required', 'array'],
            'addresses.*.use'         => ['required', Rule::exists('address_uses', 'code')],
            'addresses.*.city'        => ['required'],
            'addresses.*.postal_code' => ['required'],
            'addresses.*.extension'   => ['required', 'array'],

            'marital_status' => ['required', Rule::exists('marital_statuses', 'display')],

            'contacts'                    => ['required', 'array'],
            'contacts.*.code'             => ['required', Rule::exists('contact_relationships', 'code')],
            'contacts.*.name.use'         => ['required', Rule::exists('identifier_uses', 'code')],
            'contacts.*.telecom.*.system' => ['required', Rule::exists('contact_point_systems', 'code')],
            'contacts.*.telecom.*.use'    => ['required', Rule::exists('contact_point_uses', 'code')],
        ];

        if ($request->has('identifier_uses') && is_string($request->identifier_uses)) {
            $body['identifier_uses'] = json_decode($request->identifier_uses, true);
        }

        if ($request->has('addresses') && is_string($request->addresses)) {
            $body['addresses'] = json_decode($request->addresses, true);
        }

        if ($request->has('contacts') && is_string($request->contacts)) {
            $body['contacts'] = json_decode($request->contacts, true);
        }

        $validator = Validator::make($body, $rules);

        if ($validator->fails()) {
            return $this->sendError("Input tidak sesuai dengan ketentuan.". json_encode($body) , $validator->errors(), 400);
        }

        $unique_email = User::where('email', Str::lower(isset($body['email']) ? $body['email'] : null))->first();

        if ($unique_email) {
            return $this->sendError('Maaf, Alamat Email sudah terdaftar di database !');
        }

        try {
            $proccess_1 = $this->systemPatientService->createPatient($body);

            if (!is_object($proccess_1)) {
                return $this->sendError("Ada kesalahan saat Service/OneHealth/Deployment/PatientService-createPatient");
            }

            $data = User::find($proccess_1?->id);

            $proccess_2 = $this->oneHealthPatientService->createByNik($data);

        } catch (Exception | Throwable $th) {
            $error = [
                'error_message' => json_decode($th->getMessage(),true),
                'file'          => $th->getFile(),
                'line'          => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat createPatient', $error);
            return $this->sendError('Ada Kesalahaan saat createPatient', $error);
        }

        return $this->sendSuccess("Create Patient $this->saved_msg");
    }

    public function getPatient($patient_id)
    {
        $patient = Patient::find($patient_id);

        $user = User::find($patient?->user_id);

        if (!$user && !$user?->hasRole('Patient')) {
            return $this->sendError("Data Pasien $this->notfound_msg");
        }

        try {
            $request = [
                "name"      => $user?->name,
                "birthdate" => $user?->patient?->birth_date?->toDateString(),
                "gender"    => $user?->patient?->administrative_gender,
            ];

            $access_token = Cache::get('accessToken');

            $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
            ->withOptions(['verify' => false])
            ->get($this->url .'/Patient', $request);

            if ($response->unauthorized() || empty(Cache::get('accessToken'))) {
                (new AuthController)->accessToken();

                $access_token = Cache::get('accessToken');

                $response = Http::withToken(isset($access_token['access_token']) ? $access_token['access_token'] : '')
                ->withOptions(['verify' => false])
                ->get($this->url .'/Patient', $request);
            }

            if ($response->failed()) {
                throw new Exception(json_encode($response->json()), 500);
            }

        } catch (Exception | Throwable $th) {
            $error = [
                'error_message' => json_decode($th->getMessage(),true),
                'file'          => $th->getFile(),
                'line'          => $th->getLine(),
            ];
            Log::error($error);
            return $this->sendError('Ada Kesalahaan saat getPatient', $error);
        }

        if (isset($response->json()['entry'][0]['resource']['identifier'])) {
            try {
                $identifier = $response->json()['entry'][0]['resource']['identifier'];

                $collectionIdentifier = collect($identifier)->firstWhere('system', 'https://fhir.kemkes.go.id/id/ihs-number');

                DB::beginTransaction();
                    $user->patient()->update([
                        "ihs_number" => isset($collectionIdentifier['value']) ? $collectionIdentifier['value'] : null
                    ]);
                DB::commit();
            } catch (Exception | Throwable $th) {
                DB::rollBack();
                $error = [
                    'error_message' => json_decode($th->getMessage(),true),
                    'file'          => $th->getFile(),
                    'line'          => $th->getLine(),
                ];
                Log::error($error);
                return $this->sendError('Ada Kesalahaan saat getPatient', $error);
            }
        }

        return $this->sendResponse($response->json(), "Data getPatient $this->found_msg");
    }
}
