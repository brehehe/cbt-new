<?php

namespace App\Livewire\Admin\Logistic\DeadStock;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\DeadStock\DeadStock;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Services\Product\ProductService;
use App\Traits\Product\ProductTrait;
use Illuminate\Console\View\Components\Alert;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminLogisticDeadStockIndex extends Component
{
    use WithPagination, ProductTrait;

    protected $queryString = [
        'pageProduct' => ['except' => 1],
        'searchProduct' => ['except' => ''],
    ];
    public $searchProduct = '', $perPageProduct = 5, $productOld, $search_sku, $deadStocks = [];

    public function mount()
    {
        $this->details();
    }

    public function openModal()
    {
        $this->productOld = true;
        $this->dispatch('open-modal', ['id' => 'modalProduct']);
    }

    public function updatedSearchSku()
    {
        $this->search_sku = ltrim($this->search_sku);

        $this->choiceProductChange();
    }

    public function choiceProductChange()
    {
        $product = Product::where('sku_number', $this->search_sku)->first();

        if ($product) {
            $this->reset('search_sku');

            $productStock = ProductStock::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->first();

            if (!$productStock || $productStock->quantity <= 0) {
                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            }

            $productPrice = ProductPrice::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->where('is_updated', true)
                ->first();

            // if (!$productPrice) {
            //     return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
            // }

            $deadStock = DeadStock::where('product_id', $product->id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->where('status', 'draft')
                ->first();

            if ($deadStock) {
                // $this->dispatch('open-modal', ['id' => 'modalProduct']);
                return AlertHelper::error('Gagal', 'Produk sudah ada di daftar dead stock.');
            }

            DeadStock::create([
                'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'quantity_old' => $productStock->quantity,
                'quantity' => 0,
                'price' => $productPrice?->hpp_average ?? 0,
                'total' => 0,
                'status' => 'draft',
                'company_id' => auth()->user()->company_id,
            ]);

            $this->details();
        } else {
            return AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
        }
    }

    public function closeModal()
    {
        $this->productOld = false;
        $this->dispatch('close-modal', ['id' => 'modalProduct']);
        $this->reset('searchProduct');
        $this->resetPage('pageProduct');
    }

    public function details()
    {
        $this->deadStocks = [];

        $deadStocks = DeadStock::where('company_id', auth()->user()->company_id)
            ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
            ->where('status', 'draft')
            ->orderBy('order', 'desc')
            ->get();

        foreach ($deadStocks as $key => $deadStock) {
            $productStock = ProductStock::where('product_id', $deadStock->product_id)
                ->where('company_id', auth()->user()->company_id)
                ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
                ->first();

            if (!$productStock || $productStock->quantity <= 0) {
                return AlertHelper::error('Gagal', 'Stok produk tidak ditemukan atau stok kosong.');
            }

            $this->deadStocks[] = [
                'id' => $deadStock->id,
                'product_id' => $deadStock->product_id,
                'name' => $deadStock->name,
                'sku_number' => $deadStock->product->sku_number ?? '',
                'quantity_old' => $productStock->quantity,
                'quantity' => $deadStock->quantity,
                'price' => $deadStock->price,
                'total' => $deadStock->price * $deadStock->quantity,
            ];
        }
    }

    public function updatedDeadStocks()
    {
        foreach ($this->deadStocks as $key => $deadStock) {

            $price = floatval(Str::replace('.', '', $deadStock['price']));

            $quantity = intval(Str::replace('.', '', $deadStock['quantity']));
            $quantity_old = intval(Str::replace('.', '', $deadStock['quantity_old']));

            $deadStockModel = DeadStock::find($deadStock['id']);
            $deadStockModel->quantity_old = $quantity_old;
            $deadStockModel->quantity = $quantity_old < $quantity ? $quantity_old : $quantity;
            $deadStockModel->total = $deadStockModel->quantity * $deadStockModel->price;
            $deadStockModel->save();
        }

        $this->details();
    }

    public function choiceProduct($product_id)
    {
        $product = Product::find($product_id);
        $this->search_sku = $product->sku_number;

        $this->choiceProductChange();
        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus dead stock ini?', $id);
    }

    public function delete($id)
    {
        $deadStock = DeadStock::find($id[0]);
        if ($deadStock) {
            $deadStock->delete();
            AlertHelper::success('Berhasil', 'Dead stock berhasil dihapus.');
            $this->details();
        } else {
            AlertHelper::error('Gagal', 'Dead stock tidak ditemukan.');
        }
    }

    public function confirmSave()
    {
        return AlertHelper::confirmSave('save', 'Apakah Anda yakin ingin menyimpan dead stock ini?');
    }

    public function save()
    {
        $deadStocks = DeadStock::where('company_id', auth()->user()->company_id)
            ->where('branch_id', Branch::where('company_id', auth()->user()->company_id)->first()->id)
            ->where('status', 'draft')
            ->get();

        if ($deadStocks->isEmpty()) {
            return AlertHelper::error('Gagal', 'Tidak ada dead stock yang dapat disimpan.');
        }

        foreach ($deadStocks as $deadStock) {
            $productService = new ProductService();

            $productService->createProductDecrement($deadStock->product_id, $deadStock->quantity, null, null, $deadStock->price, null, null, null, null, null, $deadStock->id);

            $deadStock->status = 'finish';
            $deadStock->save();
        }

        AlertHelper::success('Berhasil', 'Dead stock berhasil disimpan.');
        $this->details();
    }

    public function render()
    {
        return view('livewire.admin.logistic.dead-stock.admin-logistic-dead-stock-index', [
            'products' => $this->productOld ? $this->getProductPaginates() : [],
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
