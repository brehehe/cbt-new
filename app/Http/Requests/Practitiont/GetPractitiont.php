<?php

namespace App\Http\Requests\Practitiont;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GetPractitiont extends FormRequest
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
            'company_id' => 'required|uuid',
            'nik'        => 'required|numeric',
            'name'       => 'required'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
            'company_id.required' => 'Id perusahaan wajib diisi.',
            'company_id.uuid'     => 'Id perusahaan harus berupa UUID.',
            'nik.required'        => 'Nomor NIK wajib diisi.',
            'nik.numeric'         => 'Nomor NIK harus berupa angka.',
            'name.required'       => 'Nama praktisi wajib diisi.',
        ];
    }

    // Override method untuk response error API
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Input tidak sesuai dengan ketentuan.',
            'errors'  => $validator->errors()
        ], 422));
    }
}
