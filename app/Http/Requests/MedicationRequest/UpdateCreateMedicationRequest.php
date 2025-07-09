<?php

namespace App\Http\Requests\MedicationRequest;

use App\Models\Icd\Icd10;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestCategory;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestCourseOfTherapy;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispanseInterval;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseExpect;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseInterval;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageDoseRate;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosagePeriodUnit;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestIntent;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestPriority;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class UpdateCreateMedicationRequest extends FormRequest
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
            'id'                                               => 'nullable|uuid',
            'transaction_detail_id'                            => 'nullable|uuid',
            'company_id'                                       => 'required|uuid',
            'patient_id'                                       => 'required|uuid',
            'encounter_id'                                     => 'required|uuid',
            'medication_id'                                    => 'required|uuid',
            'requester_id'                                     => 'required|uuid',
            'status'                                           => 'required|exists:master_medication_request_statuses,code',
            'intent'                                           => 'required|exists:master_medication_request_intents,code',
            'category'                                         => 'required|exists:master_medication_request_categories,code',
            'priority'                                         => 'required|exists:master_medication_request_priorities,code',
            // 'reason_code'                                      => 'required|exists:icd10s,code',
            'course_of_therapy'                                => 'required|exists:master_medication_request_course_of_therapies,code',
            'dosage_instructions.*.sequence'                   => 'required|numeric',
            'dosage_instructions.*.text'                       => 'required',
            'dosage_instructions.*.additional_text'            => 'required',
            'dosage_instructions.*.patient_instruction'        => 'required',
            'dosage_instructions.*.timing_repeat_frequency'    => 'required|numeric',
            'dosage_instructions.*.timing_repeat_period'       => 'required|numeric',
            'dosage_instructions.*.timing_repeat_period_unit'  => 'required|exists:master_medication_request_dosage_period_units,code',
            'dosage_instructions.*.route_coding_code'          => 'required|exists:master_medication_request_dosage_routes,code',
            'dosage_instructions.*.dose_rate_type_coding_code' => 'required|exists:master_medication_request_dosage_dose_rates,code',
            'dosage_instructions.*.dose_rate_quantity_value'   => 'required|numeric',
            'dosage_instructions.*.dose_rate_quantity_code'   =>   [
                'required',
                function ($attribute, $value, $fail) {
                    $existsInTable1 = DB::table('master_medication_value_quantities')
                        ->where('code', $value)->exists();
                    $existsInTable2 = DB::table('master_medication_orderable_drug_forms')
                        ->where('code', $value)->exists();

                    if (!$existsInTable1 && !$existsInTable2) {
                        $fail('Jenis jumlah obat yang diberikan perdosis');
                    }
                }
            ],
            'dispense_request.interval_value' => 'required|numeric',
            'dispense_request.interval_code'  => 'required|exists:master_medication_request_dispense_intervals,code',
            'dispense_request.validity_start' => 'required|date',
            'dispense_request.validity_end'   => 'required|date',
            'dispense_request.number_repeat'  => 'required|numeric',
            'dispense_request.quantity_value' => 'required|numeric',
            'dispense_request.quantity_code' => [
                'required',
                function ($attribute, $value, $fail) {
                    $existsInTable1 = DB::table('master_medication_value_quantities')
                        ->where('code', $value)->exists();
                    $existsInTable2 = DB::table('master_medication_orderable_drug_forms')
                        ->where('code', $value)->exists();

                    if (!$existsInTable1 && !$existsInTable2) {
                        $fail('Jenis jumlah obat yang diberikan dalam 1 kali resep');
                    }
                }
            ],
            'dispense_request.expect_value' => 'required|numeric',
            'dispense_request.expect_code'  => 'required|exists:master_medication_request_dispense_expects,code',
        ];
    }

    public function messages()
    {
        //status
        $statuses = MasterMedicationRequestStatus::pluck('display')->toArray();
        $statuses = implode(', ', $statuses);

        //intent
        $intents = MasterMedicationRequestIntent::pluck('display')->toArray();
        $intents = implode(', ', $intents);

        //intent
        $categories = MasterMedicationRequestCategory::pluck('display')->toArray();
        $categories = implode(', ', $categories);

        //priority
        $priorities = MasterMedicationRequestPriority::pluck('display')->toArray();
        $priorities = implode(', ', $priorities);

        //reason code
        $reason_codes = Icd10::take(100)->pluck('display')->toArray();
        $reason_codes = implode(', ', $reason_codes);

        //course of therapies
        $course_of_therapies = MasterMedicationRequestCourseOfTherapy::pluck('display')->toArray();
        $course_of_therapies = implode(', ', $course_of_therapies);

        //course of therapies
        $dosage_period_units = MasterMedicationRequestDosagePeriodUnit::pluck('display')->toArray();
        $dosage_period_units = implode(', ', $dosage_period_units);

        //route coding codes
        $route_coding_codes = MasterMedicationRequestDosageRoute::pluck('display')->toArray();
        $route_coding_codes = implode(', ', $route_coding_codes);

        //dose rate type coding codes
        $dose_rate_type_coding_codes = MasterMedicationRequestDosageDoseRate::pluck('display')->toArray();
        $dose_rate_type_coding_codes = implode(', ', $dose_rate_type_coding_codes);

        //dispense interval code
        $dispense_interval_codes = MasterMedicationRequestDispenseInterval::pluck('display')->toArray();
        $dispense_interval_codes = implode(', ', $dispense_interval_codes);

        //dispense expect code
        $dispense_expect_codes = MasterMedicationRequestDispenseExpect::pluck('display')->toArray();
        $dispense_expect_codes = implode(', ', $dispense_expect_codes);

        return [
            'id.uuid'                                                  => 'ID harus berupa UUID.',
            'company_id.required'                                      => 'Data organisasi wajib diisi.',
            'company_id.uuid'                                          => 'Data organisasi harus berupa UUID.',
            'patient_id.required'                                      => 'Data pasien wajib diisi.',
            'patient_id.uuid'                                          => 'Data pasien harus berupa UUID.',
            'encounter_id.required'                                    => 'Data kunjungan pasien wajib diisi.',
            'encounter_id.uuid'                                        => 'Data kunjungan pasien harus berupa UUID.',
            'medication_id.required'                                   => 'Data peresepan obat wajib diisi.',
            'medication_id.uuid'                                       => 'Data peresepan obat harus berupa UUID.',
            'requester_id.required'                                    => 'Pihak yang melakukan peresepan obat wajib diisi.',
            'requester_id.uuid'                                        => 'Pihak yang melakukan peresepan obat harus berupa UUID.',
            'status.required'                                          => 'Status pengobatan wajib diisi',
            'status.exists'                                            => 'Status pengobatan hanya bernilai : ' . $statuses,
            'intent.required'                                          => 'Tujuan pengobatan wajib diisi',
            'intent.exists'                                            => 'Tujuan pengobatan hanya bernilai : ' . $intents,
            'category.required'                                        => 'Tipe permintaan pengobatan wajib diisi',
            'category.exists'                                          => 'Tipe permintaan pengobatan hanya bernilai : ' . $categories,
            'priority.required'                                        => 'Seberapa cepat permintaan pengobatan wajib diisi',
            'priority.exists'                                          => 'Seberapa cepat permintaan pengobatan hanya bernilai : ' . $priorities,
            'reason_code.required'                                     => 'Alasan atau indikasi untuk permintaan pengobatan wajib diisi',
            'reason_code.exists'                                       => 'Alasan atau indikasi untuk permintaan pengobatan hanya bernilai : ' . $reason_codes . ', .....',
            'course_of_therapy.required'                               => 'Pola pemberian obat pada pasien wajib diisi',
            'course_of_therapy.exists'                                 => 'Pola pemberian obat pada pasien hanya bernilai : ' . $course_of_therapies,

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
            'dosage_instructions.*.route_coding_code.required'          => 'Cara untuk memasukkan obat ke dalam tubuh pasien wajib diisi',
            'dosage_instructions.*.route_coding_code.exists'            => 'Cara untuk memasukkan obat ke dalam tubuh pasien hanya bernilai : ' . $route_coding_codes,
            'dosage_instructions.*.dose_rate_type_coding_code.required' => 'Jenis pengobatan yang diresepkan wajib diisi',
            'dosage_instructions.*.dose_rate_type_coding_code.exists'   => 'Jenis pengobatan yang diresepkan hanya bernilai : ' . $dose_rate_type_coding_codes,
            'dosage_instructions.*.dose_rate_quantity_value.required'   => 'Jumlah obat yang diberikan perdosis wajib diisi',
            'dosage_instructions.*.dose_rate_quantity_value.numeric'    => 'Jumlah obat yang diberikan perdosis hanya bernilai angka',
            'dosage_instructions.*.dose_rate_quantity_code.required'    => 'Jenis jumlah obat yang diberikan perdosis wajib diisi',

            'dispense_request.interval_value.required' => 'Periode waktu minimal pengeluaran obat wajib diisi.',
            'dispense_request.interval_value.numeric'  => 'Periode waktu minimal pengeluaran obat hanya bernilai angka.',
            'dispense_request.interval_code.required'  => 'Jenis periode waktu minimal pengeluaran obat wajib diisi.',
            'dispense_request.interval_code.exists'    => 'Jenis periode waktu minimal pengeluaran obat hanya bernilai : ' . $dispense_interval_codes,
            'dispense_request.validity_start.required' => 'Periode waktu mulai peresepan obat wajib diisi.',
            'dispense_request.validity_start.date'     => 'Periode waktu mulai peresepan obat hanya bernilai tanggal.',
            'dispense_request.validity_end.required'   => 'Periode waktu berakhir peresepan obat wajib diisi.',
            'dispense_request.validity_end.date'       => 'Periode waktu berakhir peresepan obat hanya bernilai tanggal.',
            'dispense_request.number_repeat.required'  => 'Berapa kali resep obat dapat diulang wajib diisi.',
            'dispense_request.number_repeat.numeric'   => 'Berapa kali resep obat dapat diulang hanya bernilai angka.',
            'dispense_request.quantity_value.required' => 'Jumlah obat yang diberikan dalam 1 kali resep wajib diisi.',
            'dispense_request.quantity_value.numeric'  => 'Jumlah obat yang diberikan dalam 1 kali resep hanya bernilai angka.',
            'dispense_request.quantity_code.required'  => 'Jenis jumlah obat yang diberikan dalam 1 kali resep wajib diisi.',
            'dispense_request.expect_value.required'   => 'Periode waktu selama produk yang diberikan wajib diisi.',
            'dispense_request.expect_value.numeric'    => 'Periode waktu selama produk yang diberikan hanya bernilai angka.',
            'dispense_request.expect_code.required'    => 'Jenis periode waktu selama produk yang diberikan wajib diisi.',
            'dispense_request.expect_code.exists'      => 'Jenis periode waktu selama produk yang diberikan hanya bernilai : ' . $dispense_expect_codes,
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
