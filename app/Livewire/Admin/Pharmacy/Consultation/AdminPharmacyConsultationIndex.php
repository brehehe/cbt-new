<?php

namespace App\Livewire\Admin\Pharmacy\Consultation;

use App\Helpers\AlertHelper;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminPharmacyConsultationIndex extends Component
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

    public function confirmPharmacy($id)
    {
        return AlertHelper::confirmInfo('pharmacy', 'Apakah Anda Yakin Melihat Resep Konsultasi?', $id);
    }

    public function pharmacy($id)
    {
        $transaction = Transaction::find($id[0]);
        if ($transaction) {
            $transaction->update([
                'status'=>'call_pharmacy',
            ]);
            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.pharmacy.consultation.detail');
        } else {
            AlertHelper::error('Error', 'Transaksi tidak ditemukan.');
        }
    }

    public function confirmDetail($id)
    {
        return AlertHelper::confirmInfo('detail', 'Apakah Anda Yakin Melihat Resep Konsultasi?', $id);
    }

    public function detail($id)
    {
        $transaction = Transaction::find($id[0]);
        if ($transaction) {
            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.pharmacy.consultation.detail');
        } else {
            AlertHelper::error('Error', 'Transaksi tidak ditemukan.');
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

        return view('livewire.admin.pharmacy.consultation.admin-pharmacy-consultation-index', [
            'transactions' => $transactions
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
