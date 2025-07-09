<?php

namespace App\Livewire\Admin\Finance\StockOpname;

use App\Models\Finance\Finance;
use Livewire\Component;
use Livewire\WithPagination;
use Session;

class AdminFinanceStockOpnameIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function mount()
    {
        Session::forget('finance_stock_opname_id');
    }

    public function confirmDetail($financeId)
    {
        Session::put('finance_stock_opname_id', $financeId);
        return redirect()->route('user.finance.stock-opname.detail');
    }

    public function render()
    {
        $finance = Finance::search($this->search)
            ->select('id', 'code', 'date', 'type', 'sub_total', 'discount', 'tax', 'grand_total', 'company_id', 'total_loss_value', 'total_excess_value')
            ->where('company_id', auth()->user()->company_id)
            ->where('type', 'stock-opname')
            ->orderBy('order', 'desc');

        return view('livewire.admin.finance.stock-opname.admin-finance-stock-opname-index', [
            'finances' => $finance->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
