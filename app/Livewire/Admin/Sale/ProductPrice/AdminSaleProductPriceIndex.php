<?php

namespace App\Livewire\Admin\Sale\ProductPrice;

use App\Helpers\AlertHelper;
use App\Models\Product\ProductPrice;
use App\Traits\Product\ProductPriceTrait;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class AdminSaleProductPriceIndex extends Component
{
    use WithPagination, ProductPriceTrait;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $data_id;
    public $data_name;
    public $hpp_average;
    public $price;
    public $recipe;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function edit($id)
    {
        $productPrice = ProductPrice::find($id);
        if ($productPrice) {
            $this->data_id = $productPrice->id;
            $this->data_name = $productPrice->product->name;
            $this->hpp_average = number_format($productPrice->hpp_average, 0, ',', '.');
            $this->price = number_format($productPrice->price, 0, ',', '.');
            $this->recipe = number_format($productPrice->recipe, 0, ',', '.');
        }
        $this->dispatch('open-modal',['id'=>'modal']);
    }

    public function closeModal() {
        $this->reset(['data_id', 'data_name', 'hpp_average', 'price', 'recipe']);
        $this->dispatch('close-modal',['id'=>'modal']);
    }

    public function save() {
        $this->validate([
            'data_id' => 'required|exists:product_prices,id',
            'hpp_average' => 'required',
            'price' => 'required',
            'recipe' => 'required',
        ]);

        $hpp_average = intval(Str::replace('.', '', $this->hpp_average));
        $price = intval(Str::replace('.', '', $this->price));
        $recipe = intval(Str::replace('.', '', $this->recipe));

        if ($hpp_average <= 0 || $price <= 0 || $recipe < 0) {
            return AlertHelper::error('Gagal', 'Nilai HPP, Harga, dan Resep harus lebih besar dari 0.');
        }

        if ($price < $hpp_average) {
            return AlertHelper::error('Gagal', 'Harga tidak boleh lebih kecil dari HPP.');
        }

        if ($recipe > $hpp_average) {
            return AlertHelper::error('Gagal', 'Harga Resep tidak boleh lebih besar dari Hpp Average.');
        }

        $productPrice = ProductPrice::find($this->data_id);
        if ($productPrice) {
            $productPrice->update([
                'is_updated' => true,
                'hpp_average' => $hpp_average,
                'price' => $price,
                'recipe' => $recipe,
            ]);
        }

        $this->closeModal();
        return AlertHelper::success('Berhasil', 'Harga produk berhasil diperbarui.');
    }

    public function render()
    {
        return view('livewire.admin.sale.product-price.admin-sale-product-price-index',[
            'productPrices' => $this->getProductPriceUpdates()->paginate($this->perPage),
        ])
        ->extends('layout.app')
        ->section('content');
    }
}
