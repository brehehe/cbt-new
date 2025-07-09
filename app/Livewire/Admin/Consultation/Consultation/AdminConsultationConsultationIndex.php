<?php

namespace App\Livewire\Admin\Consultation\Consultation;

use App\Helpers\AlertHelper;
use App\Models\Encounter\Encounter;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionPhysicalExamination;
use App\service\apiservice;
use App\Services\PhysicalExamService;
use DB;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;
use Log;

class AdminConsultationConsultationIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public $data_id;
    public $patient_name;
    public $doctor_name;
    public $heart_rate;
    public $breathing;
    public $blood_pressure_sistole;
    public $blood_pressure_diastole;
    public $body_temperature;
    public $height;
    public $weight;

    public function mount()
    {
        Session::forget('transaction_id');

        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function confirmCall($id)
    {
        return AlertHelper::confirmWarning('warning', 'Apakah Anda Yakin Memanggil Pasien Ini?', $id);
    }

    public function confirmCallPatient($id)
    {
        return AlertHelper::confirmWarning('callPatient', 'Apakah Anda yakin ingin mengkonfirmasi panggilan pasien ini?', $id);
    }

    public function confirmCancelled($id)
    {
        return AlertHelper::confirmDelete('deleteCancel', 'Apakah Anda yakin ingin membatalkan konsultasi pasien ini?', $id);
    }

    public function confirmConsultation($id)
    {
        return AlertHelper::confirmWarning('konsultasi', 'Apakah Anda yakin ingin memulai konsultasi pasien ini?', $id);
    }

    public function confirmDetail($id)
    {
        return AlertHelper::confirmInfo('detail', 'Apakah Anda yakin ingin melihat detail konsultasi pasien ini?', $id);
    }

    public function deleteCancel($id)
    {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            $encounter = Encounter::where('transaction_id', $transaction->id)->first();

            $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
            $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();

            $data = [
                'id' => $encounter->id ?? null,
                'transaction_id' => $transaction->id,
                'company_id' => $transaction->company_id,
                'location_id' => $transaction->location_id,
                'patient_id' => $patient->id ?? null,
                'practitioner_id' => $doctor->id ?? null,
                'type' => 'outpatient',
                'status' => 'cancelled',
                'class_code' => 'AMB'
            ];

            app(apiservice::class)->createTransaction($data);

            $transaction->update([
                'status' => 'canceled',
            ]);

            return AlertHelper::success('Berhasil', 'Konsultasi pasien ' . $transaction->patient_name . ' berhasil dibatalkan.');
        } else {
            return AlertHelper::error('error', 'Data tidak ditemukan');
        }
    }

    public function callPatient($id)
    {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            $transaction->update([
                'status' => 'call_consultation',
            ]);

            return AlertHelper::success('Berhasil', 'Pasien ' . $transaction->patient_name . ' berhasil dipanggil.');
        } else {
            return AlertHelper::error('error', 'Data tidak ditemukan');
        }
    }

    public function warning($id)
    {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            $transaction->update([
                'status' => 'confirmation_call',
            ]);

            // $text = 'Pasien atas nama '.$transaction->patient_name.', silahkan masuk ke '.$transaction->location_name.' bertemu '.$transaction->doctor->name.'.';

            // $this->dispatch('callPasienAlert', $text);
        } else {
            return AlertHelper::error('error', 'Data tidak ditemukan');
        }
    }

    public function konsultasi($id)
    {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {

            $encounter = Encounter::where('transaction_id', $transaction->id)->first();

            $patient = Patient::where('user_id', $transaction->patient_id)->select('id')->first();
            $doctor = Practitioner::where('user_id', $transaction->doctor_id)->select('id')->first();

            $data = [
                'id' => $encounter->id ?? null,
                'transaction_id' => $transaction->id,
                'company_id' => $transaction->company_id,
                'location_id' => $transaction->location_id,
                'patient_id' => $patient->id ?? null,
                'practitioner_id' => $doctor->id ?? null,
                'type' => 'outpatient',
                'status' => 'in-progress',
                'class_code' => 'AMB'
            ];

            app(apiservice::class)->createTransaction($data);

            $transaction->update([
                'status' => 'consultation',
            ]);

            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.consultation.consultation.detail');
        } else {
            return AlertHelper::error('error', 'Data tidak ditemukan');
        }
    }

    public function detail($id)
    {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.consultation.consultation.detail');
        } else {
            return AlertHelper::error('error', 'Data tidak ditemukan');
        }
    }

    public function createPhysicalExam($id)
    {
        $transaction = Transaction::find($id);
        $this->data_id = $transaction->id;
        $this->patient_name = $transaction->patient_name;
        $this->doctor_name = $transaction->doctor_name;

        return $this->dispatch('open-modal', ['id' => 'physical-exam-modal']);
    }

    public function closeModalPhysicalExam()
    {
        $this->data_id = null;
        $this->patient_name = null;
        $this->doctor_name = null;
        $this->heart_rate = null;
        $this->breathing = null;
        $this->blood_pressure_sistole = null;
        $this->blood_pressure_diastole = null;
        $this->body_temperature = null;
        $this->height = null;
        $this->weight = null;

        return $this->dispatch('close-modal', ['id' => 'physical-exam-modal']);
    }

    public function confirmSubmitPhysicalExam()
    {
        return AlertHelper::confirmSave('submitPhysicalExam', 'Apakah Anda yakin ingin menyimpan pemeriksaan fisik untuk pasien ' . $this->patient_name . '?', $this->data_id);
    }

    public function submitPhysicalExam()
    {
        $this->validate([
            'heart_rate' => 'nullable|string|max:255',
            'breathing' => 'nullable|string|max:255',
            'blood_pressure_sistole' => 'nullable|string|max:255',
            'blood_pressure_diastole' => 'nullable|string|max:255',
            'body_temperature' => 'nullable|string|max:255',
            'height' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
        ]);

        $transaction = Transaction::find($this->data_id);

        if (!$transaction) {
            AlertHelper::error('Gagal', 'Data transaksi tidak ditemukan.');
            Log::error('Transaction not found', [
                'transaction_id' => $this->data_id,
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $transactionPhysicalExam = TransactionPhysicalExamination::updateOrCreate(
                [
                    'transaction_id' => $transaction->id,
                    'company_id' => $transaction->company_id,
                ],
                [
                    'heart_rate' => $this->heart_rate,
                    'breathing' => $this->breathing,
                    'blood_pressure_sistole' => $this->blood_pressure_sistole,
                    'blood_pressure_diastole' => $this->blood_pressure_diastole,
                    'body_temperature' => $this->body_temperature,
                    'height' => $this->height,
                    'weight' => $this->weight,
                ]
            );

            if ($transactionPhysicalExam->heart_rate) {
                app(PhysicalExamService::class)->createHeartRate($transactionPhysicalExam->heart_rate, $transaction->id);
            }

            if ($transactionPhysicalExam->breathing) {
                app(PhysicalExamService::class)->createBreathing($transactionPhysicalExam->breathing, $transaction->id);
            }

            if ($transactionPhysicalExam->blood_pressure_sistole) {
                app(PhysicalExamService::class)->createBloodPressureSistole($transactionPhysicalExam->blood_pressure_sistole, $transaction->id);
            }

            if ($transactionPhysicalExam->blood_pressure_diastole) {
                app(PhysicalExamService::class)->createBloodPressureDiastole($transactionPhysicalExam->blood_pressure_diastole, $transaction->id);
            }

            if ($transactionPhysicalExam->body_temperature) {
                app(PhysicalExamService::class)->createBodyTemperature($transactionPhysicalExam->body_temperature, $transaction->id);
            }

            if ($transactionPhysicalExam->height) {
                app(PhysicalExamService::class)->createHeight($transactionPhysicalExam->height, $transaction->id);
            }

            if ($transactionPhysicalExam->weight) {
                app(PhysicalExamService::class)->createWeight($transactionPhysicalExam->weight, $transaction->id);
            }

            DB::commit();
            $this->closeModalPhysicalExam();
            AlertHelper::success('Berhasil', 'Pemeriksaan fisik untuk pasien ' . $transaction->patient_name . ' berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('error', 'Gagal menyimpan pemeriksaan fisik: ' . $e->getMessage());
            Log::error('Error saving physical examination', [
                'transaction_id' => $this->data_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->closeModalPhysicalExam();
        }

        return;
    }


    public function render()
    {
        $transactions = Transaction::search($this->search)
            ->where('consultation', 'yes')
            ->whereIn('status', ['draft_consultation', 'call_consultation', 'confirmation_call', 'consultation'])
            ->orderBy('created_at', 'desc')
            ->where('company_id', auth()->user()->company_id)
            ->paginate($this->perPage);

        return view('livewire.admin.consultation.consultation.admin-consultation-consultation-index', [
            'transactions' => $transactions,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
