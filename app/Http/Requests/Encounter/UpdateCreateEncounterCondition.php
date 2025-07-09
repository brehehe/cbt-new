<?php

namespace App\Http\Requests\Encounter;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCreateEncounterCondition extends FormRequest
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
            'id'              => 'nullable|uuid',
            'encounter_id'    => 'required|uuid',
            'company_id'      => 'required|uuid',
            'transaction_id'  => 'required|uuid',
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
        return [
            //
            'id.uuid'                  => 'ID harus berupa UUID.',
            'encounter_id.required'     => 'Encounter ID wajib diisi.',
            'encounter_id.uuid'         => 'Encounter ID harus berupa UUID.',
            'company_id.required'       => 'Data organisasi wajib diisi.',
            'company_id.uuid'           => 'Data organisasi harus berupa UUID.',
            'transaction_id.required'   => 'Data transaksi wajib diisi.',
            'transaction_id.uuid'       => 'Data transaksi harus berupa UUID.',
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
