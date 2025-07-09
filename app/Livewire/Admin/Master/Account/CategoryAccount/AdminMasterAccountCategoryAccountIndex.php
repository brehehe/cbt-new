<?php

namespace App\Livewire\Admin\Master\Account\CategoryAccount;

use App\Helpers\AlertHelper;
use App\Models\Account\CategoryAccount;
use App\Models\Account\DetailCategoryAccount;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterAccountCategoryAccountIndex extends Component
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
    public $cash_flow;
    public $detail_category_account_id;

    // Array
    public $detail_category_accounts = [];
    public $get_cash_flows = [
        'undefined',
        'operasi',
        'investasi',
        'pendanaan',
    ];

    public function mount()
    {
        $this->detail_category_accounts = DetailCategoryAccount::where('company_id', auth()->user()->company_id)
            ->orderBy('order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
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
        $this->reset(['data_id', 'name', 'cash_flow', 'detail_category_account_id']);
        $this->dispatch('close-modal', ['id' => 'modal']);
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $this->data_id = $id;
        $categoryAccount = CategoryAccount::findOrFail($id);
        $this->name = $categoryAccount->name;
        $this->cash_flow = $categoryAccount->cash_flow;
        $this->detail_category_account_id = $categoryAccount->detail_category_account_id;

        $this->openModal();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'cash_flow' => 'required|string|in:undefined,operasi,investasi,pendanaan',
            'detail_category_account_id' => 'required|exists:detail_category_accounts,id',
        ]);

        CategoryAccount::updateOrCreate(
            ['id' => $this->data_id],
            [
                'name' => $this->name,
                'cash_flow' => $this->cash_flow,
                'detail_category_account_id' => $this->detail_category_account_id,
                'company_id' => auth()->user()->company_id,
            ]
        );

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Kategori Akun Biaya berhasil disimpan.');
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus kategori akun biaya ini?', $id);
    }

    public function delete($id)
    {
        $categoryAccount = CategoryAccount::findOrFail($id);
        $categoryAccount->delete();

        return AlertHelper::success('Berhasil', 'Kategori Akun Biaya berhasil dihapus.');
    }

    public function render()
    {
        $categoryAccounts = CategoryAccount::where('company_id', auth()->user()->company_id)
            ->search($this->search)
            ->orderBy('order', 'asc')
            ->paginate($this->perPage);
        return view('livewire.admin.master.account.category-account.admin-master-account-category-account-index', [
            'category_accounts' => $categoryAccounts,
        ])->extends('layout.app')
            ->section('content');
    }
}
