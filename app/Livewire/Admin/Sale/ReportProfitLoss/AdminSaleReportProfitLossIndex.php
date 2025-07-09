<?php

namespace App\Livewire\Admin\Sale\ReportProfitLoss;

use App\Models\Product\Product;
use App\Models\Transaction\TransactionProduct;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSaleReportProfitLossIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date, $end_date, $type, $products= [], $product_id;

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
        $this->products = Product::select('id', 'name', 'sku_number')
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();
    }

    public function hydrate()
    {
        $this->resetPage();
    }


    public function render()
    {

        $transactionProduct = TransactionProduct::search($this->search)
        ->selectRaw('
            product_id,
            product_name,
            SUM(quantity) as total_quantity,
            SUM(price) as total_price,
            SUM(total) as total_penjualan,
            SUM(hpp_average) as total_hpp_average,
            SUM(hpp_total) as total_hpp_total,
            SUM(profit) as total_profit,
            AVG(margin) as average_margin
        ')
        ->where('company_id', auth()->user()->company_id)
        ->with('product:id,name,sku_number,product_type_id','product.productType:id,name')
        ->whereHas('transaction', function ($query) {
            $query->where('status', 'completed');

            if ($this->type) {
                $query->where('type', $this->type);
            }
        })
        ->when($this->start_date && $this->end_date, function ($query) {
            $query->whereBetween('created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        })
        ->when($this->product_id, function ($query) {
            $query->where('product_id', $this->product_id);
        })
        ->groupBy('product_id', 'product_name')
        ->orderBy('total_quantity', 'desc');

        return view('livewire.admin.sale.report-profit-loss.admin-sale-report-profit-loss-index', [
            'transactionProducts' => $transactionProduct->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
