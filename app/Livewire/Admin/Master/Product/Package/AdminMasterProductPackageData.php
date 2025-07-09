<?php

namespace App\Livewire\Admin\Master\Product\Package;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Product\Product;
use App\Models\Product\ProductPackage;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Support\Str;

class AdminMasterProductPackageData extends Component
{
    public $product_id;
    public $data_id = null;
    public $name;
    public $description;
    public $hpp_average;
    public $hpp_average_total;
    public $price_generate;
    public $price;
    public $sub_total;
    public $sub_total_final;
    public $product_type_id;
    public $product_packages = [];
    public $products = [];

    public function mount()
    {
        $this->data_id = Session::get('product_package_id', null);
        $this->product_type_id = ProductType::where('name', 'Paket')->first()?->id ?? null;

        $this->products = Product::select('id', 'name')
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->get()
            ->toArray();

        if ($this->data_id) {
            $product = Product::find($this->data_id);
            $this->data_id = $product->id;
            $this->name = $product->name;
            $this->description = $product->description;
            $this->sub_total_final = number_format($product->productPrice?->price ?? 0, 0, ',', '.');
            $this->getProductPackages();
        } else {
            $this->createProductPackage();
        }
    }

    public function getProductPackages()
    {
        $this->reset(['product_packages']);

        $product_packages = ProductPackage::where('product_id', $this->data_id)
            ->where('company_id', auth()->user()->company_id)
            ->select('id', 'product_id', 'product_child_id', 'name', 'quantity')
            ->with(['productChild:id,name'])
            ->get();

        foreach ($product_packages as $key => $product_package) {
            $this->product_packages[] = [
                'product_package_id' => $product_package->id,
                'product_id' => $product_package->product_child_id,
                'product_name' => $product_package->productChild->name ?? null,
                'quantity' => $product_package->quantity,
                'hpp_average' => 0, // Will be updated later
                'hpp_average_total' => 0, // Will be updated later
                'price' => 0, // Will be updated later
                'sub_total_price' => 0, // Will be updated later
            ];
        }

        $this->updatedProductPackages();
    }

    public function createProductPackage()
    {
        $this->product_packages[] = [
            'product_package_id' => null,
            'product_id' => null,
            'product_name' => null,
            'quantity' => 1,
            'hpp_average' => 0,
            'hpp_average_total' => 0,
            'price' => 0,
            'sub_total_price' => 0,
        ];
    }

    public function updatedProductPackages()
    {
        foreach ($this->product_packages as $key => $product_package) {
            $product = Product::find($product_package['product_id']);

            $productPrice = ProductPrice::where('product_id', $product_package['product_id'])
                ->where('company_id', auth()->user()->company_id)
                ->first();

            $quantity = intval(Str::replace('.', '', $product_package['quantity'] ?? 1));
            $quantity = $quantity > 0 ? $quantity : 1;

            $this->product_packages[$key] = [
                'product_package_id' => $product_package['product_package_id'] ?? null,
                'product_id' => $product_package['product_id'],
                'product_name' => $product ? $product->name : null,
                'quantity' => $quantity,
                'hpp_average' => $productPrice ? ($productPrice->hpp_average) : 0,
                'hpp_average_total' => $productPrice ? ($productPrice->hpp_average * $quantity) : 0,
                'price' => $productPrice ? ($productPrice->price) : 0,
                'sub_total_price' => $productPrice ? ($productPrice->price * $quantity) : 0,
            ];
        };
        $this->updateTotalPrice();
    }

    public function confirmDelete($key)
    {
        return AlertHelper::confirmDelete('deleteProductPackage', 'Anda yakin ingin menghapus paket produk ini?', $key);
    }

    public function deleteProductPackage($key)
    {
        if ($this->product_packages[$key[0]]['product_package_id']) {
            ProductPackage::where('id', $this->product_packages[$key[0]]['product_package_id'])
                ->where('company_id', auth()->user()->company_id)
                ->delete();
        }

        unset($this->product_packages[$key[0]]);
        $this->product_packages = array_values($this->product_packages);
        AlertHelper::success('Berhasil', 'Paket produk berhasil dihapus.');

        if ($this->data_id) {
            $this->getProductPackages();
        }

        $this->updateTotalPrice();
    }

    public function updateTotalPrice()
    {
        $hpp_average = 0;
        $hpp_average_total = 0;
        $price = 0;
        $sub_total = 0;
        foreach ($this->product_packages as $product_package) {
            $price += intval(Str::replace('.', '', number_format($product_package['price'], 0, '.', '')));
            $hpp_average_total += intval(Str::replace('.', '', number_format($product_package['hpp_average_total'], 0, '.', '')));
            $hpp_average += intval(Str::replace('.', '', number_format($product_package['hpp_average'], 0, '.', '')));
            $sub_total += intval(Str::replace('.', '', number_format($product_package['sub_total_price'], 0, '.', '')));
        }
        $this->price = number_format($price, 0, ',', '.');
        $this->hpp_average = number_format($hpp_average, 0, ',', '.');
        $this->hpp_average_total = number_format($hpp_average_total, 0, ',', '.');
        $this->sub_total = number_format($sub_total, 0, ',', '.');
    }

    public function Konfirmasi()
    {
        try {
            DB::beginTransaction();

            $product = Product::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_type_id' => $this->product_type_id,
                    'is_non_stock' => false,
                    'is_stock_ingredient' => true,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'company_id' => auth()->user()->company_id],
                [
                    'hpp_average' => intval(Str::replace('.', '', $this->hpp_average_total)),
                    'price' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'recipe' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'is_updated' => true,
                    'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()?->id,
                ]
            );

            foreach ($this->product_packages as $key => $product_package) {
                ProductPackage::updateOrCreate(
                    [
                        'id' => $product_package['product_package_id'] ?? null,
                        'product_id' => $product->id,
                        'product_child_id' => $product_package['product_id'],
                        'company_id' => auth()->user()->company_id,
                        'name' => $product_package['product_name'],
                    ],
                    [
                        'quantity' => intval(Str::replace('.', '', $product_package['quantity'])),
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan paket produk: ' . $e->getMessage());
            Log::info('Error saving product package', [
                'error' => $e->getMessage(),
                'data' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_packages' => $this->product_packages,
                ],
            ]);
            return;
        }

        AlertHelper::success('Berhasil', 'Paket produk berhasil disimpan.');
        return redirect()->route('user.master.product.package');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'sub_total_final' => 'required',
            'product_packages' => 'required|array|min:1',
            'product_packages.*.product_id' => 'required|exists:products,id',
            'product_packages.*.quantity' => 'required|integer|min:1',
        ]);

        $sub_total = intval(Str::replace('.', '', $this->sub_total));

        $sub_total_final = intval(Str::replace('.', '', $this->sub_total_final));

        if ($sub_total_final <= $sub_total) {
            return AlertHelper::confirmPublish('Konfirmasi', 'Total harga paket produk tidak boleh kurang dari atau sama dengan harga sub total. Silakan periksa kembali harga produk yang ditambahkan.');
        }

        try {
            DB::beginTransaction();

            $product = Product::updateOrCreate(
                ['id' => $this->data_id],
                [
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_type_id' => $this->product_type_id,
                    'is_non_stock' => false,
                    'is_stock_ingredient' => true,
                    'company_id' => auth()->user()->company_id,
                ]
            );

            ProductPrice::updateOrCreate(
                ['product_id' => $product->id, 'company_id' => auth()->user()->company_id],
                [
                    'hpp_average' => intval(Str::replace('.', '', $this->hpp_average_total)),
                    'price' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'recipe' => intval(Str::replace('.', '', $this->sub_total_final)),
                    'is_updated' => true,
                    'branch_id' => Branch::where('company_id', auth()->user()->company_id)->first()?->id,
                ]
            );

            foreach ($this->product_packages as $key => $product_package) {
                ProductPackage::updateOrCreate(
                    [
                        'id' => $product_package['product_package_id'] ?? null,
                        'product_id' => $product->id,
                        'product_child_id' => $product_package['product_id'],
                        'company_id' => auth()->user()->company_id,
                        'name' => $product_package['product_name'],
                    ],
                    [
                        'quantity' => intval(Str::replace('.', '', $product_package['quantity'])),
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            AlertHelper::error('Gagal', 'Terjadi kesalahan saat menyimpan paket produk: ' . $e->getMessage());
            Log::info('Error saving product package', [
                'error' => $e->getMessage(),
                'data' => [
                    'name' => $this->name,
                    'description' => $this->description,
                    'product_packages' => $this->product_packages,
                ],
            ]);
            return;
        }

        AlertHelper::success('Berhasil', 'Paket produk berhasil disimpan.');
        return redirect()->route('user.master.product.package');
    }

    public function render()
    {
        return view('livewire.admin.master.product.package.admin-master-product-package-data')
            ->extends('layout.app')
            ->section('content');
    }
}
