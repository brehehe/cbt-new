<?php

namespace App\Livewire\Admin\Report\StockOut;

use App\Traits\Product\ProductStockTrait;
use App\Traits\Product\ProductTrait;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportStockOutIndex extends Component
{
    use ProductStockTrait, WithPagination, ProductTrait;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date;
    public $end_date;
    public $product_id;

    public function resetDates()
    {
        $this->reset(['start_date', 'end_date']);
    }

    public function render()
    {
        return view(
            'livewire.admin.report.stock-out.admin-report-stock-out-index',
            [
                'products' => $this->getProductSelects(),
                'productStockHistorys' => $this->getProductStockHistorys('out'),
            ]
        )
            ->extends('layout.app')
            ->section('content');
    }
}
