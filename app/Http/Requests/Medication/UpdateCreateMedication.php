<?php

namespace App\Http\Requests\Medication;

use App\Models\Master\CodeSystem\Medication\MasterMedicationForm;
use App\Models\Master\CodeSystem\Medication\MasterMedicationStatus;
use App\Models\Master\CodeSystem\Medication\MasterMedicationType;
use App\Models\Master\CodeSystem\Medication\MasterMedicationValueQuantity;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class UpdateCreateMedication extends FormRequest
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
            'id'                              => 'nullable|uuid',
            'company_id'                      => 'required|uuid',
            'code_coding_code'                => 'required|numeric',
            'status'                          => 'required|exists:master_medication_statuses,code',
            'manufacturer_reference'          => 'required',
            'form_coding_code'                => 'required|exists:master_medication_forms,code',
            'ingredients'                     => 'required|array',
            'ingredients.*.item_code'         => 'required',
            'ingredients.*.item_display'      => 'required',
            'ingredients.*.is_active'         => 'required|boolean',
            'ingredients.*.numerator_value'   => 'required|numeric',
            'ingredients.*.numerator_code'    => 'required|exists:master_medication_value_quantities,code',
            'ingredients.*.denominator_value' => 'required|numeric',
            'ingredients.*.denominator_code'  => [
                                                    'required',
                                                    function ($attribute, $value, $fail) {
                                                        $existsInTable1 = DB::table('master_medication_value_quantities')
                                                            ->where('code', $value)->exists();
                                                        $existsInTable2 = DB::table('master_medication_orderable_drug_forms')
                                                            ->where('code', $value)->exists();

                                                        if (!$existsInTable1 && !$existsInTable2) {
                                                            $fail('Nilai dominator tidak sesuai ketentuan ');
                                                        }
                                                    }
                                                ],
            'medication_type_code' => 'required|exists:master_medication_types,code'
        ];
    }

    public function messages()
    {
        //status
        $statuses = MasterMedicationStatus::pluck('display')->toArray();
        $statuses = implode(', ', $statuses);

        //forms
        $forms = MasterMedicationForm::pluck('display')->toArray();
        $forms = implode(', ', $forms);

        //numerator_code
        $numerator_codes = MasterMedicationValueQuantity::pluck('display')->toArray();
        $numerator_codes = implode(', ', $numerator_codes);

        //types
        $types = MasterMedicationType::pluck('definition')->toArray();
        $types = implode(', ', $types);

        return [
            //
            'id.uuid'                                  => 'ID harus berupa UUID.',
            'company_id.required'                      => 'Data organisasi wajib diisi.',
            'company_id.uuid'                          => 'Data organisasi harus berupa UUID.',
            'code_coding_code.required'                => 'Kode obat KFA wajib diisi.',
            'code_coding_code.numeric'                 => 'Kode obat KFA hanya bernilai angka.',
            'status.required'                          => 'Status obat wajib diisi.',
            'status.exists'                            => 'Status obat hanya bernilai : '. $statuses,
            'manufacturer_reference.required'          => 'Data Organisai yang menyimpan data pabrik obat wajib diisi.',
            'form_coding_code.required'                => 'Kode bentuk dari sediaan obat wajib diisi.',
            'form_coding_code.exists'                  => 'Kode bentuk dari sediaan obat hanya bernilai : '. $forms,
            'ingredients.*.item_code.required'         => 'Kode zat aktif atau kode obat template wajib diisi.',
            'ingredients.*.item_display.required'      => 'Nama zat aktif atau nama obat template wajib diisi.',
            'ingredients.*.is_active.required'         => 'Informasi merupakan zat aktif wajib diisi.',
            'ingredients.*.is_active.boolean'          => 'Informasi merupakan zat aktif hanya bernilai true atau false.',
            'ingredients.*.numerator_value.required'   => 'Nilai jumlah komposisi zat dalam obat wajib diisi.',
            'ingredients.*.numerator_value.numeric'    => 'Nilai jumlah komposisi zat dalam obat hanya bernilai angka.',
            'ingredients.*.numerator_code.required'    => 'Satuan kode komposisi zat dalam obat wajib diisi.',
            'ingredients.*.numerator_code.exists'      => 'Satuan kode komposisi zat dalam obat hanya bernilai '. $numerator_codes,
            'ingredients.*.denominator_value.required' => 'Nilai dominator obat wajib diisi.',
            'ingredients.*.denominator_value.numeric'  => 'Nilai dominator obat hanya bernilai angka.',
            'ingredients.*.denominator_code.required'  => 'Nilai dominator obat wajib diisi.',
            // 'ingredients.*.denominator_code.exists'    => 'Nilai dominator obat hanya bernilai '. $numerator_codes,
            'medication_type_code.required' => 'Kode informasi obat wajib diisi.',
            'medication_type_code.exists'   => 'Kode informasi obat hanya bernilai : '. $types,
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
