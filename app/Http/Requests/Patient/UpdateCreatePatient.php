<?php

namespace App\Http\Requests\Patient;

use App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender;
use App\Models\Master\CodeSystem\Patient\MasterPatientContactRelationship;
use App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCreatePatient extends FormRequest
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
            'id'                 => 'nullable|uuid',
            'user_id'            => 'nullable|uuid',
            'company_id'         => 'required|uuid',
            'name'               => 'required|string|max:255',
            'email'              => 'nullable|email|max:255',
            'gender'             => 'required|exists:master_patient_administrative_genders,code',
            'birth_date'         => 'required|date|before_or_equal:today',
            'deceased_date'      => 'nullable|date',
            'identity_card'      => 'nullable|numeric',
            'passport_number'    => 'nullable|numeric',
            'family_card_number' => 'nullable|numeric',
            'marital_status'     => 'required|exists:master_patient_marital_statuses,code',
            'status'             => 'required|in:active,non-active,block',

            'patient_detail.province.code'     => 'required',
            'patient_detail.city.code'         => 'required',
            'patient_detail.district.code'     => 'required',
            'patient_detail.sub_district.code' => 'required',
            'patient_detail.address'           => 'required',
            'patient_detail.postal_code'       => 'required',
            'patient_detail.rt'                => 'required',
            'patient_detail.rw'                => 'required',
            'patient_detail.longitude'         => 'nullable',
            'patient_detail.latitude'          => 'nullable',
            'patient_detail.altitude'          => 'nullable',

            'contact_relationship.name'  => 'nullable',
            'contact_relationship.code'  => 'nullable|exists:master_patient_contact_relationships,code',
            'contact_relationship.phone' => 'nullable|numeric',
            'contact_relationship.email' => 'nullable|email',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        $genders = MasterPatientAdministrativeGender::pluck('display')->toArray();
        $genders = implode(', ', $genders);

        $marital_statuses = MasterPatientMaritalStatus::pluck('display')->toArray();
        $marital_statuses = implode(', ', $marital_statuses);

        $contact_relationships = MasterPatientContactRelationship::pluck('display')->toArray();
        $contact_relationships = implode(', ', $contact_relationships);


        return [
            'id.uuid'                    => 'Id harus berupa UUID.',
            'user_id.required'           => 'Id user wajib diisi.',
            'company_id.required'        => 'Id perusahaan wajib diisi.',
            'company_id.uuid'            => 'Id perusahaan harus berupa UUID.',
            'name.required'              => 'Nama wajib diisi.',
            'email.email'                => 'Alamat Email harus berformat email.',
            'gender.required'            => 'Jenis kelamin wajib diisi.',
            'gender.exists'              => 'Jenis kelamin hanya bernilai : '. $genders,
            'birth_date.required'        => 'Tanggal lahir wajib diisi.',
            'birth_date.date'            => 'Tanggal lahir harus berformat date.',
            'birth_date.before_or_equal' => 'Tanggal lahir harus berisi sebelum '. Carbon::now()->addDay()->format('d F Y'),
            'deceased_date.date'         => 'Tanggal meninggal harus berformat date.',
            'identity_card.numeric'      => 'Nomor NIK harus berupa angka',
            'passport_number.numeric'    => 'Nomor paspor harus berupa angka',
            'family_card_number.numeric' => 'Nomor kartu keluarga harus berupa angka',
            'marital_status.exists'      => 'status perkawinan hanya bernilai : '. $marital_statuses,
            'status.required'            => 'Status wajib diisi.',
            'status.in'                  => 'Status hanya boleh: active, non-active, block.',

            'patient_detail.province.code.required'     => 'Kode provinsi wajib diisi.',
            'patient_detail.city.code.required'         => 'Kode kota wajib diisi.',
            'patient_detail.district.code.required'     => 'Kode kecamatan wajib diisi.',
            'patient_detail.sub_district.code.required' => 'Kode kelurahan wajib diisi.',
            'patient_detail.address.required'           => 'Alamat wajib diisi.',
            'patient_detail.postal_code.required'       => 'Kode pos wajib diisi.',
            'patient_detail.rt.required'                => 'Nomor RT wajib diisi.',
            'patient_detail.rw.required'                => 'Nomor RW wajib diisi.',

            'contact_relationship.name.required'  => 'Nama penjamin wajib diisi.',
            'contact_relationship.code.required'  => 'Kode penjamin wajib diisi.',
            'contact_relationship.code.exists'    => 'Kode penjamin hanya bernilai '. $contact_relationships,
            'contact_relationship.phone.required' => 'Nomor handphone penjamin wajib diisi.',
            'contact_relationship.phone.numeric'  => 'Nomor handphone penjamin harus berupa angka.',
            'contact_relationship.email.required' => 'Email penjamin wajib diisi.',
            'contact_relationship.email.email'    => 'Email penjamin harus berformat email.',
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
