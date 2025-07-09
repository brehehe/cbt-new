<?php

namespace App\Livewire\Admin\Report\SaleProduct;

use App\Models\Product\Product;
use App\Models\Transaction\TransactionPayment;
use App\Models\Transaction\TransactionProduct;
use App\Traits\Product\ProductTrait;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportSaleProductIndex extends Component
{
    use WithPagination, ProductTrait;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date, $end_date, $type, $products = [], $product_id;

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
            ->select('id', 'transaction_id', 'product_id', 'product_name', 'quantity', 'price', 'total', 'hpp_average', 'hpp_total', 'profit', 'margin')
            ->where('company_id', auth()->user()->company_id)
            ->with([
                'transaction:id,code,branch_id,company_id,patient_name,sub_total_price,discount_value,grand_total_price,type,status',
                'product:id,name,sku_number'
            ])
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'completed');

                if ($this->type) {
                    $query->where('type', $this->type);
                }
            })
            ->orderBy('order', 'desc');

        if ($this->start_date && $this->end_date) {
            $transactionProduct->whereBetween('created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }

        if ($this->product_id) {
            $transactionProduct->where('product_id', $this->product_id);
        }

        return view('livewire.admin.report.sale-product.admin-report-sale-product-index', [
            'transactionProducts' => $transactionProduct->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content')
        ;
    }
}
