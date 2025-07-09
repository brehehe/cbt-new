<?php

namespace App\Http\Requests\OutPatient\Encounter;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'patient_id'     => ['required'],
            'company_id'     => ['required'],
            'status'         => ['required'],
            'period_start'   => ['required', 'date_format:Y-m-d H:i'],
            'location'       => ['required'],
            // 'class_act_code' => ['required', Rule::exists('act_codes', 'code')],
        ];
    }

    public function attributes()
    {
        return [
            'patient_id'   => 'Patient',
            'company_id'   => 'klinik',
            'status'       => 'Status',
            'period_start' => 'Waktu Mulai Kunjungan',
            'location'     => 'Waktu Mulai Kunjungan',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required'      => 'Data Pasien wajib diisi !',
            'company_id.required'      => 'Data Klinik wajib diisi !',
            'status.required'          => 'Data Status wajib diisi !',
            'period_start.required'    => 'Data Waktu Mulai Kunjungan wajib diisi !',
            'period_start.date_format' => 'Data Waktu Mulai Kunjungan berformat Y-m-d H:i !',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'success' => false,
            'message' => "Input tidak sesuai dengan ketentuan !",
            'data'    => $validator->errors()
        ];
        throw new HttpResponseException(response()->json($response, 422));
    }
}
