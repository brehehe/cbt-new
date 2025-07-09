<?php

namespace App\Livewire\Admin\Report\StockOpnameProduct;

use App\Models\StockOpname\StockOpnameItem;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportStockOpnameProductIndex extends Component
{
    use WithPagination;

    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $startDate;
    public $endDate;

    public function render()
    {
        $stockOpnameItems = StockOpnameItem::search($this->search)
            ->when($this->startDate, function ($query) {
                $query->whereDate('created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($query) {
                $query->whereDate('created_at', '<=', $this->endDate);
            })
            ->with(['stockOpname', 'product', 'productExpiredDate'])
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.report.stock-opname-product.admin-report-stock-opname-product-index', [
            'stockOpnameItems' => $stockOpnameItems,
        ])->extends('layout.app')
            ->section('content');
    }
}
