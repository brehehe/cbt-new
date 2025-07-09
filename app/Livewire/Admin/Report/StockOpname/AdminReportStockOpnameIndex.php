<?php

namespace App\Livewire\Admin\Report\StockOpname;

use App\Helpers\AlertHelper;
use App\Models\StockOpname\StockOpname;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportStockOpnameIndex extends Component
{
    use WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function mount()
    {
        Session::put('stock_opname_id', null);

        if (session()->has('saved')) {
            AlertHelper::success(session('saved.title'), session('saved.text'));
            session()->forget('saved');

            return;
        }
    }

    public function createOpname()
    {
        Session::put('stock_opname_id', null);

        return redirect()->route('user.logistic.stock-product.detail');
    }

    public function edit($stockOpnameId)
    {
        Session::put('stock_opname_id', $stockOpnameId);

        return redirect()->route('user.logistic.stock-product.detail');
    }

    public function render()
    {
        return view('livewire.admin.report.stock-opname.admin-report-stock-opname-index', [
            'stockOpnames' => StockOpname::with(['user', 'branch', 'approvedBy', 'company'])
                ->search($this->search)
                ->latest()
                ->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
