<?php

namespace App\Livewire\Admin\Master\Account\Account;

use App\Helpers\AlertHelper;
use App\Models\Account\Account;
use App\Models\Account\CategoryAccount;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterAccountAccountIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $data_id = null;
    public $name;
    public $code;
    public $is_cash = false;
    public $category_account_id;

    // array
    public $category_accounts = [];

    public function mount()
    {
        $this->category_accounts = CategoryAccount::where('company_id', Auth::user()->company_id)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'cash_flow' => $item->cash_flow,
                ];
            })
            ->toArray();
    }

    public function openModal()
    {
        $this->dispatch('open-modal', ['id' => 'modal']);
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', ['id' => 'modal']);
        $this->reset(['data_id', 'name', 'code', 'is_cash', 'category_account_id']);
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $account = Account::findOrFail($id);
        $this->data_id = $account->id;
        $this->name = $account->name;
        $this->code = $account->code;
        $this->is_cash = $account->is_cash;
        $this->category_account_id = $account->category_account_id;

        $this->openModal();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'category_account_id' => 'required|exists:category_accounts,id',
        ]);

        $accountData = [
            'name' => $this->name,
            'code' => $this->code,
            'is_cash' => $this->is_cash,
            'category_account_id' => $this->category_account_id,
            'company_id' => Auth::user()->company_id,
        ];

        Account::updateOrCreate(['id' => $this->data_id], $accountData);

        $this->closeModal();

        return AlertHelper::success('Akun berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Anda yakin ingin menghapus akun ini?', $id);
    }

    public function delete($id)
    {
        $account = Account::findOrFail($id[0]);
        if ($account->delete()) {
            AlertHelper::success('Akun berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal menghapus akun.');
        }

        return;
    }

    public function render()
    {
        $accounts = Account::search($this->search)
            ->select('id', 'name', 'code', 'category_account_id')
            ->with(['categoryAccount' => function ($query) {
                $query->select('id', 'name');
            }])
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('code', 'asc');


        return view(
            'livewire.admin.master.account.account.admin-master-account-account-index',
            [
                'accounts' => $accounts->paginate($this->perPage),
            ]
        )->extends('layout.app')
            ->section('content');
    }
}
