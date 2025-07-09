<?php

namespace App\Livewire\Admin\Master\PaymentMethod;

use App\Helpers\AlertHelper;
use App\Models\Account\Account;
use App\Models\Company\Company;
use App\Models\PaymentMethod\PaymentMethod;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterPaymentMethodIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $perPage = 5;
    public $search = '';
    public $data_id;
    public $name;
    public $code;
    public $type;
    public $value;
    public $type_admin_fee;
    public $value_admin_fee;
    public $is_offline_payment;
    public $is_single_payment;
    public $account_id;
    public $accounts = [];

    public function mount()
    {
        $this->accounts = Account::where('company_id', auth()->user()->company_id)
            ->where('is_cash', true)
            ->orderBy('order', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function openModal()
    {
        $this->type = $this->type ?? 'rupiah';
        $this->value = $this->value ?? 0;
        $this->type_admin_fee = $this->type_admin_fee ?? 'rupiah';
        $this->value_admin_fee = $this->value_admin_fee ?? 0;
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->reset(['data_id', 'name', 'code', 'type', 'value', 'type_admin_fee', 'value_admin_fee', 'is_offline_payment', 'is_single_payment', 'account_id']);
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $this->data_id = $paymentMethod->id;
        $this->name = $paymentMethod->name;
        $this->code = $paymentMethod->code;
        $this->is_offline_payment = $paymentMethod->is_offline_payment;
        $this->is_single_payment = $paymentMethod->is_single_payment;
        $this->type = $paymentMethod->type;
        $this->value = $paymentMethod->type == 'rupiah' ? number_format($paymentMethod->value, 0, ',', '.') : $paymentMethod->value;
        $this->type_admin_fee = $paymentMethod->type_admin_fee;
        $this->value_admin_fee = $paymentMethod->type_admin_fee == 'rupiah' ? number_format($paymentMethod->value_admin_fee, 0, ',', '.') : $paymentMethod->value_admin_fee;
        $this->account_id = $paymentMethod->account_id;

        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda Yakin Menghapus Data Ini?', $id);
    }

    public function delete($id)
    {
        try {
            $paymentMethod = PaymentMethod::findOrFail($id[0]);
            $paymentMethod->delete();
            AlertHelper::success('Berhasil', 'Data Berhasil Dihapus');
        } catch (\Exception $e) {
            AlertHelper::error('Gagal', 'Data Gagal Dihapus');
        }
    }

    public function updatedType()
    {
        $this->reset('value');
    }

    public function updatedTypeAdminFee()
    {
        $this->reset('value_admin_fee');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'code' => 'nullable',
            'type' => 'required',
            'value' => 'nullable',
            'type_admin_fee' => 'required',
            'value_admin_fee' => 'nullable',
            'account_id' => 'required|exists:accounts,id',
        ]);

        try {
            PaymentMethod::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'code' => $this->code,
                    'type' => $this->type,
                    'is_offline_payment' => $this->is_offline_payment ?? false,
                    'is_single_payment' => $this->is_single_payment ?? false,
                    'value' => $this->type == 'percentage' ? $this->value : ($this->type == 'rupiah' ? str_replace('.', '', $this->value) : $this->value),
                    'type_admin_fee' => $this->type_admin_fee,
                    'value_admin_fee' => $this->type_admin_fee == 'percentage' ? $this->value_admin_fee : ($this->type_admin_fee == 'rupiah' ? str_replace('.', '', $this->value_admin_fee) : $this->value_admin_fee),
                    'account_id' => $this->account_id,
                ]
            );
            AlertHelper::success('Berhasil', 'Data Berhasil Disimpan');

            $this->closeModal();
        } catch (\Exception $e) {
            AlertHelper::error('Gagal', 'Data Gagal Disimpan');
            Log::error("message: {$e->getMessage()}");
        }
    }

    public function render()
    {
        $paymentMethod = PaymentMethod::search($this->search)
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('order', 'asc');

        return view('livewire.admin.master.payment-method.admin-master-payment-method-index', [
            'paymentMethods' => $paymentMethod->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
