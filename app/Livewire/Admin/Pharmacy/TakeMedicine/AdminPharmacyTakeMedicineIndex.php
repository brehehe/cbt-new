<?php

namespace App\Livewire\Admin\Pharmacy\TakeMedicine;

use App\Helpers\AlertHelper;
use App\Models\Transaction\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Session;

class AdminPharmacyTakeMedicineIndex extends Component
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

    public function confirmDetail($id)
    {
        return AlertHelper::confirmInfo('detail', 'Apakah Anda Yakin Mengkonfirmasi Pengambilan Obat?', $id);
    }

    public function detail($id) {
        $transaction = Transaction::find($id[0]);
        if ($transaction) {
            Session::put('transaction_id', $transaction->id);
            return redirect()->route('user.pharmacy.take-medicine.detail');
        } else {
            AlertHelper::error('Error', 'Transaksi tidak ditemukan.');
        }
    }

    public function render()
    {
        $transactions = Transaction::search($this->search)
        // ->where('consultation', 'yes')
        ->whereIn('status', ['take_medicine', 'completed'])
        ->where('is_take_medicine', true)
        ->orderBy('created_at', 'desc')
        ->where('company_id', auth()->user()->company_id)
        ->paginate($this->perPage);

        return view('livewire.admin.pharmacy.take-medicine.admin-pharmacy-take-medicine-index',[
            'transactions' => $transactions
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
