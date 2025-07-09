<?php

namespace App\Livewire\Admin\Report\PurchaseProduct;

use App\Models\PurchaseOrder\PurchaseOrderItem;
use App\Traits\Product\ProductStockTrait;
use App\Traits\Product\ProductTrait;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportPurchaseProductIndex extends Component
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

    public function mount()
    {
        $this->start_date = date('Y-m-d');
        $this->end_date = date('Y-m-d');
    }

    public function resetDates()
    {
        $this->reset(['start_date', 'end_date']);
    }

    public function render()
    {
        $purchaseProduct = PurchaseOrderItem::search($this->search)
            ->with(['product'])
            ->when($this->start_date, function ($query, $start_date) {
                $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($this->end_date, function ($query, $end_date) {
                $query->whereDate('created_at', '<=', $end_date);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.report.purchase-product.admin-report-purchase-product-index', [
            'purchaseProducts' => $purchaseProduct,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
