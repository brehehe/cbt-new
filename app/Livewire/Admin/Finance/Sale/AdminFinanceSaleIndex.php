<?php

namespace App\Livewire\Admin\Finance\Sale;

use App\Models\Finance\Finance;
use Livewire\Component;
use Livewire\WithPagination;
use Session;

class AdminFinanceSaleIndex extends Component
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
        Session::forget('finance_sale_id');
        $this->changeStatus('draft');
    }

    public function changeStatus($status)
    {
        $this->status = $status;
    }

    public function editFinance($financeId)
    {
        Session::put('finance_sale_id', $financeId);
        return redirect()->route('user.finance.sale.detail');
    }

    public function render()
    {
        $finance = Finance::search($this->search)
            ->select('id', 'code', 'date', 'type', 'description', 'sub_total', 'discount', 'tax', 'grand_total', 'company_id')
            ->where('company_id', auth()->user()->company_id)
            ->where('type', 'sale')
            ->orderBy('order', 'desc');

        if ($this->status) {
            $finance = $finance->where('status', $this->status);
        }

        return view('livewire.admin.finance.sale.admin-finance-sale-index', [
            'finances' => $finance->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
