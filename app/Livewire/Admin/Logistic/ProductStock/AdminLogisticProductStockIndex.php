<?php

namespace App\Livewire\Admin\Logistic\ProductStock;

use App\Traits\Product\ProductStockTrait;
use Livewire\Component;
use Livewire\WithPagination;

class AdminLogisticProductStockIndex extends Component
{
    use ProductStockTrait, WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function render()
    {
        return view('livewire.admin.logistic.product-stock.admin-logistic-product-stock-index',
            [
                'products' => $this->getProductStocks(),
            ])
        ->extends('layout.app')
        ->section('content');
    }
}
