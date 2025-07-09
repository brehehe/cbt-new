<?php

namespace App\Http\Requests\MedicationDispense;

use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseStatus;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseCategory;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseDaysSupply;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseDosageDoseRate;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseDosagePeriodUnit;

class UpdateCreateMedicationDispense extends FormRequest
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
            'id'                    => 'nullable|uuid',
            'transaction_detail_id' => 'nullable|uuid',
            'company_id'            => 'required|uuid',
            'location_id'           => 'required|uuid',
            'practitioner_id'       => 'required|uuid',
            'patient_id'            => 'required|uuid',
            'encounter_id'          => 'required|uuid',
            'medication_id'         => 'required|uuid',
            'medication_request_id' => 'required|uuid',
            'performer_id'          => 'required|uuid',
            'status'                => 'required|exists:master_medication_dispense_statuses,code',
            'category'              => 'required|exists:master_medication_dispense_categories,code',
            'quantity_value'        => 'required|numeric',
            'quantity_code'         => [
                'required',
                function ($attribute, $value, $fail) {
                    $existsInTable1 = DB::table('master_medication_dispense_value_quantities')
                        ->where('code', $value)->exists();
                    $existsInTable2 = DB::table('master_medication_dispense_orderable_drug_forms')
                        ->where('code', $value)->exists();

                    if (!$existsInTable1 && !$existsInTable2) {
                        $fail('Jenis jumlah obat yang dikeluarkan tidak valid');
                    }
                }
            ],
            'day_value'      => 'required|numeric',
            'day_code'       => 'required|exists:master_medication_dispense_days_supplies,code',
            'when_prepare'   => 'required|date',
            'when_hand_over' => 'required|date',

            'dosage_instructions.*.sequence'                   => 'nullable|numeric',
            'dosage_instructions.*.text'                       => 'nullable',
            'dosage_instructions.*.additional_text'            => 'nullable',
            'dosage_instructions.*.patient_instruction'        => 'nullable',
            'dosage_instructions.*.timing_repeat_frequency'    => 'nullable|numeric',
            'dosage_instructions.*.timing_repeat_period'       => 'nullable|numeric',
            'dosage_instructions.*.timing_repeat_period_unit'  => 'nullable|exists:master_medication_request_dosage_period_units,code',
            'dosage_instructions.*.route_coding_code'          => 'nullable|exists:master_medication_request_dosage_routes,code',
            'dosage_instructions.*.dose_rate_type_coding_code' => 'nullable|exists:master_medication_request_dosage_dose_rates,code',
            'dosage_instructions.*.dose_rate_quantity_value'   => 'nullable|numeric',
            'dosage_instructions.*.dose_rate_quantity_code'   =>   [
                'nullable',
                function ($attribute, $value, $fail) {
                    $existsInTable1 = DB::table('master_medication_request_value_quantities')
                        ->where('code', $value)->exists();
                    $existsInTable2 = DB::table('master_medication_request_orderable_drug_forms')
                        ->where('code', $value)->exists();

                    if (!$existsInTable1 && !$existsInTable2) {
                        $fail('Jenis jumlah obat yang diberikan perdosis');
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        //status
        $statuses = MasterMedicationDispenseStatus::pluck('display')->toArray();
        $statuses = implode(', ', $statuses);

        //categories
        $categories = MasterMedicationDispenseCategory::pluck('display')->toArray();
        $categories = implode(', ', $categories);

        //days_supplies
        $days_supplies = MasterMedicationDispenseDaysSupply::pluck('display')->toArray();
        $days_supplies = implode(', ', $days_supplies);

        //course of therapies
        $dosage_period_units = MasterMedicationDispenseDosagePeriodUnit::pluck('display')->toArray();
        $dosage_period_units = implode(', ', $dosage_period_units);

        //dose rate type coding codes
        $dose_rate_type_coding_codes = MasterMedicationDispenseDosageDoseRate::pluck('display')->toArray();
        $dose_rate_type_coding_codes = implode(', ', $dose_rate_type_coding_codes);

        return [
            //
            'id.uuid'                        => 'ID harus berupa UUID.',
            'transaction_detail_id.uuid'     => 'Data detail transaksi harus berupa UUID.',
            'company_id.required'            => 'Data organisasi wajib diisi.',
            'company_id.uuid'                => 'Data organisasi harus berupa UUID.',
            'location_id.required'           => 'Data lokasi wajib diisi.',
            'location_id.uuid'               => 'Data lokasi harus berupa UUID.',
            'practitioner_id.required'       => 'Data praktisi wajib diisi.',
            'practitioner_id.uuid'           => 'Data praktisi harus berupa UUID.',
            'patient_id.required'            => 'Data pasien wajib diisi.',
            'patient_id.uuid'                => 'Data pasien harus berupa UUID.',
            'encounter_id.required'          => 'Data kunjungan pasien wajib diisi.',
            'encounter_id.uuid'              => 'Data kunjungan pasien harus berupa UUID.',
            'medication_id.required'         => 'Data peresepan obat wajib diisi.',
            'medication_id.uuid'             => 'Data peresepan obat harus berupa UUID.',
            'medication_request_id.required' => 'Data permintaan peresepan obat wajib diisi.',
            'medication_request_id.uuid'     => 'Data permintaan peresepan obat harus berupa UUID.',
            'performer_id.required'          => 'Pihak yang memberikan obat wajib diisi.',
            'performer_id.uuid'              => 'Pihak yang memberikan obat harus berupa UUID.',
            'status.required'                => 'Status pengobatan wajib diisi.',
            'status.exists'                  => 'Status pengobatan hanya bernilai : ' . $statuses,
            'category.required'              => 'Tipe permintaan pengobatan wajib diisi.',
            'category.exists'                => 'Tipe permintaan pengobatan hanya bernilai : ' . $categories,
            'quantity_value.required'        => 'Jumlah obat yang dikeluarkan wajib diisi.',
            'quantity_value.numeric'         => 'Jumlah obat yang dikeluarkan hanya bernilai angka.',
            'quantity_code.required'         => 'Jenis jumlah obat yang dikeluarkan wajib diisi.',
            'day_value.required'             => 'Jumlah pengobatan dengan satuan harian wajib diisi.',
            'day_value.numeric'              => 'Jumlah pengobatan dengan satuan harian hanya bernilai angka.',
            'day_code.required'              => 'Jenis jumlah pengobatan dengan satuan harian wajib diisi.',
            'day_code.exists'                => 'Jenis jumlah pengobatan dengan satuan harian hanya bernilai :' . $days_supplies,
            'when_prepare.required'          => 'Kapan obat dikemas dan dicek wajib diisi.',
            'when_prepare.date'              => 'Kapan obat dikemas dan dicek hanya bernilai tanggal.',
            'when_hand_over.required'        => 'Waktu pemberian obat kepada pasien wajib diisi.',
            'when_hand_over.date'            => 'Waktu pemberian obat kepada pasien hanya bernilai tanggal.',

            'dosage_instructions.*.sequence.required'                   => 'Aturan pakai dengan nilai sequence wajib diisi',
            'dosage_instructions.*.sequence.numeric'                    => 'Aturan pakai dengan nilai sequence hanya bernilai angka',
            'dosage_instructions.*.text.required'                       => 'Aturan pakai obat dalam bentuk naratif wajib diisi',
            'dosage_instructions.*.additional_text.required'            => 'Instruksi tambahan bagi pasien wajib diisi',
            'dosage_instructions.*.patient_instruction.required'        => 'Instruksi aturan pakai dengan orientasi pasien wajib diisi',
            'dosage_instructions.*.timing_repeat_frequency.required'    => 'Frekuensi pengulangan wajib diisi',
            'dosage_instructions.*.timing_repeat_frequency.numeric'     => 'Frekuensi pengulangan hanya bernilai angka',
            'dosage_instructions.*.timing_repeat_period.required'       => 'Jangka waktu repetisi wajib diisi',
            'dosage_instructions.*.timing_repeat_period.numeric'        => 'Jangka waktu repetisi hanya bernilai angka',
            'dosage_instructions.*.timing_repeat_period_unit.required'  => 'Data unit dari period wajib diisi',
            'dosage_instructions.*.timing_repeat_period_unit.exists'    => 'Data unit dari period hanya bernilai : ' . $dosage_period_units,
            // 'dosage_instructions.*.route_coding_code.required'          => 'Cara untuk memasukkan obat ke dalam tubuh pasien wajib diisi',
            // 'dosage_instructions.*.route_coding_code.exists'            => 'Cara untuk memasukkan obat ke dalam tubuh pasien hanya bernilai : '. $route_coding_codes,
            'dosage_instructions.*.dose_rate_type_coding_code.required' => 'Jenis pengobatan yang diresepkan wajib diisi',
            'dosage_instructions.*.dose_rate_type_coding_code.exists'   => 'Jenis pengobatan yang diresepkan hanya bernilai : ' . $dose_rate_type_coding_codes,
            'dosage_instructions.*.dose_rate_quantity_value.required'   => 'Jumlah obat yang diberikan perdosis wajib diisi',
            'dosage_instructions.*.dose_rate_quantity_value.numeric'    => 'Jumlah obat yang diberikan perdosis hanya bernilai angka',
            'dosage_instructions.*.dose_rate_quantity_code.required'    => 'Jenis jumlah obat yang diberikan perdosis wajib diisi',
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
