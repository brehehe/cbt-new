<?php

namespace App\Http\Requests\Encounter;

use App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCreateEncounter extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'id'                      => 'nullable|uuid',
            'company_id'              => 'required|uuid',
            'location_id'             => 'required|uuid',
            'practitioner_id'         => 'required|uuid',
            'patient_id'              => 'required|uuid',
            'transaction_id'          => 'nullable|uuid',
            'type'                    => 'required|in:outpatient, inpatient',
            'status'                  => 'required|exists:master_encounter_statuses,code',
            'class_code'              => 'required|exists:master_encounter_act_codes,code',
            'hospital_discharge_text' => 'nullable'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        //status
        $statuses = MasterEncounterStatus::pluck('display')->toArray();
        $statuses = implode(', ', $statuses);

        //clas code
        $class_codes = MasterEncounterActCode::pluck('display')->toArray();
        $class_codes = implode(', ', $class_codes);

        return [
            //
            'id.required'              => 'ID wajib diisi.',
            'id.uuid'                  => 'ID harus berupa UUID.',
            'company_id.required'      => 'Data organisasi wajib diisi.',
            'company_id.uuid'          => 'Data organisasi harus berupa UUID.',
            'location_id.required'     => 'Data lokasi wajib diisi.',
            'location_id.uuid'         => 'Data lokasi harus berupa UUID.',
            'practitioner_id.required' => 'Data praktisi wajib diisi.',
            'practitioner_id.uuid'     => 'Data praktisi harus berupa UUID.',
            'patient_id.required'      => 'Data pasien wajib diisi.',
            'patient_id.uuid'          => 'Data pasien harus berupa UUID.',
            'transaction_id.uuid'      => 'Data transaksi harus berupa UUID.',
            'type.required'            => 'Tipe kunjungan pasien wajib diisi.',
            'type.in'                  => 'Tipe kunjungan hanya bernilai : rawat jalan, rawat inap',
            'status.required'          => 'Status kunjungan wajib diisi.',
            'status.exists'            => 'Status kunjungan hanya bernilai : ' .$statuses,
            'class_code.required'      => 'Klasifikasi dari pertemuan pasien wajib diisi.',
            'class_code.exists'        => 'Klasifikasi dari pertemuan pasien hanya bernilai : ' .$class_codes,
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Input tidak sesuai dengan ketentuan.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
