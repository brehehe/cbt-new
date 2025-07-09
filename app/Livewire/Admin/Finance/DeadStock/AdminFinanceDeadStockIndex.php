<?php

namespace App\Livewire\Admin\Finance\DeadStock;

use App\Models\Finance\Finance;
use Livewire\Component;
use Livewire\WithPagination;
use Session;

class AdminFinanceDeadStockIndex extends Component
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
        Session::forget('finance_dead_stock_id');
    }

    public function editFinance($financeId)
    {
        Session::put('finance_dead_stock_id', $financeId);
        return redirect()->route('user.finance.dead-stock.detail');
    }

    public function render()
    {
        $finance = Finance::search($this->search)
            ->select('id', 'code', 'date', 'type', 'description', 'sub_total', 'discount', 'tax', 'grand_total', 'company_id')
            ->where('company_id', auth()->user()->company_id)
            ->where('type', 'dead-stock')
            ->orderBy('order', 'desc');

        return view('livewire.admin.finance.dead-stock.admin-finance-dead-stock-index', [
            'finances' => $finance->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
