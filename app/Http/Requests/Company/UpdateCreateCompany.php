<?php

namespace App\Http\Requests\Company;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCreateCompany extends FormRequest
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

        $isUpdate = $this->filled('id'); // atau $this->id !== null

        return [
            //
            'id'         => 'nullable|uuid',
            'company_id' => 'nullable|uuid',
            'code'       => $isUpdate ? 'nullable|string|size:6' : 'required|string|size:6',
            'name'       => $isUpdate ? 'nullable|string|max:255' : 'required|string|max:255',
            'email'      => $isUpdate ? 'nullable|email|max:255' : 'required|email|max:255',
            'phone'      => $isUpdate ? 'nullable|string|max:20' : 'required|string|max:20',
            'website'    => 'nullable|string|max:255',
            'is_active'  => 'required|boolean',

            'pic.name'     => $isUpdate ? 'nullable|string|max:255' : 'required|string|max:255',
            'pic.position' => $isUpdate ? 'nullable|string|max:100' : 'required|string|max:100',
            'pic.email'    => $isUpdate ? 'nullable|email|max:255' : 'required|email|max:255',
            'pic.phone'    => $isUpdate ? 'nullable|numeric|max_digits:15' : 'required|numeric|max_digits:15',

            'company_detail.province.code'     => $isUpdate ? 'nullable' : 'required',
            'company_detail.city.code'         => $isUpdate ? 'nullable' : 'required',
            'company_detail.district.code'     => $isUpdate ? 'nullable' : 'required',
            'company_detail.sub_district.code' => $isUpdate ? 'nullable' : 'required',
            'company_detail.address'           => $isUpdate ? 'nullable' : 'required',
            'company_detail.postal_code'       => $isUpdate ? 'nullable' : 'required',
            'company_detail.rt'                => $isUpdate ? 'nullable' : 'required',
            'company_detail.rw'                => $isUpdate ? 'nullable' : 'required',
            'company_detail.longitude'         => 'nullable',
            'company_detail.latitude'          => 'nullable',
            'company_detail.altitude'          => 'nullable',

            'one_health.organization_id' => $this->id == null && $this->company_id == null ? 'required' : 'nullable',
            'one_health.client_id'       => $this->id == null && $this->company_id == null ? 'required' : 'nullable',
            'one_health.client_secret'   => $this->id == null && $this->company_id == null ? 'required' : 'nullable',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.uuid'            => 'Id harus berupa UUID.',
            'company_id.uuid'    => 'Id harus berupa UUID.',
            'code.required'      => 'Kode wajib diisi.',
            'code.size'          => 'Kode harus maksimal 6 karakter.',
            'name.required'      => 'Nama wajib diisi.',
            'email.required'     => 'Alamat Email wajib diisi.',
            'email.email'        => 'Alamat Email harus berformat email.',
            'phone.required'     => 'Nomor telepon wajib diisi.',
            'is_active.required' => 'Status aktif wajib diisi.',
            'is_active.boolean'  => 'Status aktif hanya berformat true atau false',

            'pic.name.required'     => 'Nama PIC wajib diisi.',
            'pic.position.required' => 'Jabatan PIC wajib diisi.',
            'pic.email.required'    => 'Email PIC wajib diisi.',
            'pic.email.email'       => 'Email PIC harus berformat email.',
            'pic.phone.required'    => 'Nomor telepon PIC wajib diisi.',

            'company_detail.province.code.required'     => 'Kode provinsi wajib diisi.',
            'company_detail.city.code.required'         => 'Kode kota wajib diisi.',
            'company_detail.district.code.required'     => 'Kode kecamatan wajib diisi.',
            'company_detail.sub_district.code.required' => 'Kode kelurahan wajib diisi.',
            'company_detail.address.required'           => 'Alamat wajib diisi.',
            'company_detail.postal_code.required'       => 'Kode pos wajib diisi.',
            'company_detail.rt.required'                => 'Nomor RT wajib diisi.',
            'company_detail.rw.required'                => 'Nomor RW wajib diisi.',

            'one_health.organization_id.required' => 'Kode API organizatin ID wajib diisi',
            'one_health.client_id.required'       => 'Kode API client ID wajib diisi',
            'one_health.client_secret.required'   => 'Kode API client secret wajib diisi',
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
