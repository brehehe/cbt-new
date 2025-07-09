<?php

namespace App\Livewire\Admin\Master\Product\Package;

use App\Helpers\AlertHelper;
use App\Models\Product\Product;
use App\Models\Product\ProductType;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterProductPackageIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public $product_type_id;

    public function mount() {
        $this->product_type_id = ProductType::where('name', 'Paket')->first()?->id ?? null;
    }

    public function createProduct() {
        Session::forget('product_package_id');
        return redirect()->route('user.master.product.package.data');
    }

    public function edit($id) {
        Session::put('product_package_id', $id);
        return redirect()->route('user.master.product.package.data');
    }

    public function confirmDelete($id)
    {
       return AlertHelper::confirmDelete('delete','Apakah Anda yakin ingin menghapus paket produk ini?', $id);
    }

    public function delete($id)
    {
        $product = Product::find($id[0]);
        if ($product) {
            $product->productPrice()->delete();
            $product->productPackages()->delete();
            $product->delete();
            AlertHelper::success('Berhasil', 'Paket produk berhasil dihapus.');
        } else {
            AlertHelper::error('Gagal', 'Paket produk tidak ditemukan.');
        }
    }

    public function hydrate() {
        $this->resetPage();
    }

    public function render()
    {
         $products = Product::where('product_type_id', $this->product_type_id)
            ->select('id', 'name', 'description')
            ->with(['productPrice:product_id,hpp_average,price'])
            ->search($this->search)
            ->orderBy('name');

        return view('livewire.admin.master.product.package.admin-master-product-package-index',[
            'products' => $products->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
