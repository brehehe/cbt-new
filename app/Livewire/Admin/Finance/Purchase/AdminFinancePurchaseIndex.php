<?php

namespace App\Livewire\Admin\Finance\Purchase;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Finance\Finance;
use Session;

class AdminFinancePurchaseIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $get_statuss = ['draft', 'confirmed'];
    public $status;

    public function mount()
    {
        Session::forget('finance_purchase_id');
        $this->changeStatus('draft');
    }

    public function changeStatus($status)
    {
        $this->status = $status;
    }

    public function editFinance($financeId)
    {
        Session::put('finance_purchase_id', $financeId);
        return redirect()->route('user.finance.purchase.detail');
    }

    public function render()
    {
        $finance = Finance::search($this->search)
            ->select('id', 'code', 'date', 'type', 'description', 'sub_total', 'discount', 'tax', 'grand_total', 'company_id')
            ->where('company_id', auth()->user()->company_id)
            ->where('type', 'purchase')
            ->orderBy('order', 'desc');

        if ($this->status) {
            $finance = $finance->where('status', $this->status);
        }

        return view('livewire.admin.finance.purchase.admin-finance-purchase-index', [
            'finances' => $finance->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
