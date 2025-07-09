<?php

namespace App\Http\Requests\Location;

use App\Models\Master\CodeSystem\Location\MasterLocationMode;
use App\Models\Master\CodeSystem\Location\MasterLocationStatus;
use App\Models\Master\CodeSystem\Location\MasterLocationType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCreateLocation extends FormRequest
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
            'id'            => 'nullable|uuid',
            'company_id'    => 'required|uuid',
            'location_id'   => 'nullable|uuid',
            'name'          => 'required',
            'description'   => 'required',
            'status'        => 'required|exists:master_location_statuses,code',
            'mode'          => 'required|exists:master_location_modes,code',
            'physical_type' => 'required|exists:master_location_types,code',
        ];
    }

    public function messages(): array
    {
        //status
        $statuses = MasterLocationStatus::pluck('display')->toArray();
        $statuses = implode(', ', $statuses);

        //mode
        $modes = MasterLocationMode::pluck('display')->toArray();
        $modes = implode(', ', $modes);

        //physical_type
        $physical_types = MasterLocationType::pluck('display')->toArray();
        $physical_types = implode(', ', $physical_types);

        return [
            'id.uuid'                => 'Id harus berupa UUID.',
            'company_id.required'    => 'Id perusahaan wajib diisi.',
            'company_id.uuid'        => 'Id perusahaan harus berupa UUID.',
            'location_id.uuid'       => 'Id lokasi harus berupa UUID.',
            'name.required'          => 'Nama wajib diisi.',
            'description.required'   => 'Deskripsi wajib diisi.',
            'status.required'        => 'Status wajib diisi.',
            'status.exists'          => 'Status hanya bernilai : '. $statuses,
            'mode.required'          => 'Mode wajib diisi.',
            'mode.exists'            => 'Mode hanya bernilai : '. $modes,
            'physical_type.required' => 'Tipe fisik Lokasi wajib diisi.',
            'physical_type.exists'   => 'Tipe fisik lokasi hanya bernilai : '. $physical_types,
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
