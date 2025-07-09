<?php

namespace App\Http\Requests\Condition;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Master\CodeSystem\Condition\MasterConditionCategory;
use App\Models\Master\CodeSystem\Condition\MasterConditionClinicalStatus;

class UpdateCreateCondition extends FormRequest
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
            'id'                       => 'nullable|uuid',
            'transaction_condition_id' => 'nullable|uuid',
            'company_id'               => 'required|uuid',
            'patient_id'               => 'required|uuid',
            'encounter_id'             => 'required|uuid',
            'clinical_status'          => 'required|exists:master_condition_clinical_statuses,code',
            'category'                 => 'required|exists:master_condition_categories,code',
            'code'                     => [
                                    'required',
                                    function ($attribute, $value, $fail) {
                                        $existsInTable1 = DB::table('icd10s')
                                            ->where('code', $value)->exists();
                                        $existsInTable2 = DB::table('master_condition_code_chief_complaints')
                                            ->where('code', $value)->exists();
                                        $existsInTable3 = DB::table('master_condition_code_previous_conditions')
                                            ->where('code', $value)->exists();

                                        if (!$existsInTable1 && !$existsInTable2 && !$existsInTable3) {
                                            $fail('Data kode diagnosis tidak valid');
                                        }
                                    }
                                ],
            'onset_date_time' => 'nullable|date_format:Y-m-d',
            'notes'           => 'nullable'
        ];
    }

    public function messages()
    {
        //status
        $statuses = MasterConditionClinicalStatus::pluck('display')->toArray();
        $statuses = implode(', ', $statuses);

        //category
        $categories = MasterConditionCategory::pluck('display')->toArray();
        $categories = implode(', ', $categories);

        return [
            'id.uuid'                       => 'ID harus berupa UUID.',
            'transaction_condition_id.uuid' => 'Data transaksi kondisi/diagnosa harus berupa UUID.',
            'company_id.required'           => 'Data organisasi wajib diisi.',
            'company_id.uuid'               => 'Data organisasi harus berupa UUID.',
            'patient_id.required'           => 'Data pasien wajib diisi.',
            'patient_id.uuid'               => 'Data pasien harus berupa UUID.',
            'encounter_id.required'         => 'Data kunjungan pasien wajib diisi.',
            'encounter_id.uuid'             => 'Data kunjungan pasien harus berupa UUID.',
            'clinical_status.required'      => 'Status klinis dari kondisi pasien wajib diisi.',
            'clinical_status.exists'        => 'Status klinis dari kondisi pasien hanya bernilai : ' .$statuses,
            'category.required'             => 'Kategori kondisi/keluhan/diagnosis pasien wajib diisi.',
            'category.exists'               => 'Kategori kondisi/keluhan/diagnosis pasien hanya bernilai : ' .$categories,
            'code.required'                 => 'Data kode diagnosis wajib diisi.',
            'onset_date_time.date_format'   => 'Kapan kondisi dimulai hanya bernilai tanggal dan jam',
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
