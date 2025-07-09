<?php

namespace App\Livewire\Admin\Consultation\History;

use App\Helpers\AlertHelper;
use App\Models\Transaction\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Session;

class AdminConsultationHistoryIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $perPage = 5;
    protected $queryString = [
        'search' => ['except' => ''],
    ];

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

    public function confirmCall($id) {
        return AlertHelper::confirmWarning('warning','Apakah Anda Yakin Memanggil Pasien Ini?', $id);
    }

    public function confirmCallPatient($id) {
        return AlertHelper::confirmWarning('callPatient','Apakah Anda yakin ingin mengkonfirmasi panggilan pasien ini?', $id);
    }

    public function confirmCancelled($id) {
        return AlertHelper::confirmDelete('deleteCancel','Apakah Anda yakin ingin membatalkan konsultasi pasien ini?', $id);
    }

    public function confirmConsultation($id) {
        return AlertHelper::confirmWarning('konsultasi','Apakah Anda yakin ingin memulai konsultasi pasien ini?', $id);
    }

    public function confirmDetail($id) {
        return AlertHelper::confirmInfo('detail','Apakah Anda yakin ingin melihat detail konsultasi pasien ini?', $id);
    }

    public function deleteCancel($id) {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            $transaction->update([
                'status' => 'canceled',
            ]);

            return AlertHelper::success('Berhasil', 'Konsultasi pasien '.$transaction->patient_name.' berhasil dibatalkan.');
        } else {
            return AlertHelper::alertError('error', 'Data tidak ditemukan');
        }
    }

    public function callPatient($id) {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            $transaction->update([
                'status' => 'call_consultation',
            ]);

            return AlertHelper::success('Berhasil', 'Pasien '.$transaction->patient_name.' berhasil dipanggil.');
        } else {
            return AlertHelper::alertError('error', 'Data tidak ditemukan');
        }
    }

    public function warning($id) {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            $transaction->update([
                'status' => 'confirmation_call',
            ]);

            // $text = 'Pasien atas nama '.$transaction->patient_name.', silahkan masuk ke '.$transaction->location_name.' bertemu '.$transaction->doctor->name.'.';

            // $this->dispatch('callPasienAlert', $text);
        } else {
            return AlertHelper::alertError('error', 'Data tidak ditemukan');
        }
    }

    public function konsultasi($id) {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {

            $transaction->update([
                'status' => 'consultation',
            ]);

            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.consultation.consultation.detail');
        } else {
            return AlertHelper::alertError('error', 'Data tidak ditemukan');
        }
    }

    public function detail($id) {
        $transaction = Transaction::find($id[0]);

        if ($transaction) {
            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.consultation.consultation.detail');
        } else {
            return AlertHelper::alertError('error', 'Data tidak ditemukan');
        }
    }

    public function render()
    {
        $transactions = Transaction::search($this->search)
            ->where('consultation', 'yes')
            ->whereNotIn('status', ['draft_consultation', 'call_consultation', 'confirmation_call', 'consultation'])
            ->orderBy('created_at', 'desc')
            ->where('company_id', auth()->user()->company_id)
            ->paginate($this->perPage);

        return view('livewire.admin.consultation.history.admin-consultation-history-index',
            [
                'transactions' => $transactions,
            ]
        )
        ->extends('layout.app')
        ->section('content');
    }
}
