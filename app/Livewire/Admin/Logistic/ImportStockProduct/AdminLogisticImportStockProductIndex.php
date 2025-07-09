<?php

namespace App\Livewire\Admin\Logistic\ImportStockProduct;

use App\Helpers\AlertHelper;
use App\Imports\Product\StockProductImport;
use App\Models\Branch\Branch;
use App\Models\Product\Product;
use App\Models\Product\ProductImportStock;
use App\Models\Product\ProductType;
use App\Models\Unit\Unit;
use App\Services\Product\ProductService;
use App\Traits\Product\ProductTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class AdminLogisticImportStockProductIndex extends Component
{
    use WithFileUploads, ProductTrait;
    public $importStockProducts = [], $import;
    public $productCategories = [], $productFactories = [], $productTypes = [], $productRacks = [];

    public function mount() {
        Session::forget('importStockProducts');
        $this->productTypes = $this->getProductTypes();
        $this->detailImport();
    }

    public function openModal($modal) {
    $this->dispatch('open-modal', ['id' => $modal]);
    }

    public function closeModal($modal) {
        $this->reset('importStockProducts','import');
        $this->dispatch('close-modal', ['id' => $modal]);
    }

    public function saveImport() {
        $this->validate([
            'import' => 'required|mimes:xlsx,csv',
        ]);

        Excel::import(new StockProductImport, $this->import);
        $this->closeModal('modal');

        if (Session::has('importStockProducts')) {
                $this->reset('import','importStockProducts');

                $this->detailImport();
                $this->reset('import');
                $this->dispatch('close-modal', ['id' => 'modal']);
        } else {
            return AlertHelper::error('Gagal', 'Gagal mengimpor data produk');
        }
    }

    public function detailImport() {
        $importStockProducts = Session::get('importStockProducts');

            if (!empty($importStockProducts)) {
                foreach ($importStockProducts as $key => $value) {
                $productType = ProductType::where('name', $value['tipe_produk'] ?? 'Obat')->first();

                $quantity = $value['quantity'] ? intval(Str::replace('.', '', $value['quantity'])) : 0;
                $hpp_average = $value['hpp_average'] ? intval(Str::replace('.', '', $value['hpp_average'])) : 0;
                $selling_price = $value['selling_price'] ? intval(Str::replace('.', '', $value['selling_price'])) : 0;
                $selling_price_recipe = isset($value['selling_price_recipe'])
                ? intval(Str::replace('.', '', $value['selling_price_recipe']))
                : 0;

                $this->importStockProducts[] = [
                    // 'product_id' => $product->id,
                    'product_type_id' => $productType ? $productType->id : ProductType::where('name', 'Obat')->first()->id,
                    'sku_number' => $value['sku_number'],
                    'name' => $value['name'],
                    'batch_number' => $value['batch_number'] ?? null,
                    'expired_date' => $value['expired_date'] ?? null,
                    'quantity' => number_format($quantity, 0, ',', '.'),
                    'hpp_average'=> number_format($hpp_average, 0, ',', '.'),
                    'selling_price' => number_format($selling_price, 0, ',', '.'),
                    'selling_price_recipe' => $selling_price_recipe ? number_format($selling_price_recipe, 0, ',', '.') : 0,
                ];
            }
        }

        Session::forget('importStockProducts');
        Session::put('importStockProducts', $this->importStockProducts);
    }

    public function confirmDelete($index) {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda Yakin Untuk Menghapus Data Ini?', $index);
    }

    public function delete($index) {
        unset($this->importStockProducts[$index[0]]);
        $this->importStockProducts = array_values($this->importStockProducts); // reindex biar rapi
        Session::forget('importStockProducts');
        Session::put('importStockProducts', $this->importStockProducts);
        return AlertHelper::success('Berhasil', 'Data berhasil dihapus.');
    }

    public function confirmSave() {
        return AlertHelper::confirmSave('save','Apakah Anda Yakin Untuk Menyimpan Data Ini?');
    }


    public function updatedImportStockProducts() {
        foreach ($this->importStockProducts as $key => $value) {

            $quantity = $value['quantity'] ? intval(Str::replace('.', '', $value['quantity'])) : 0;
            $hpp_average = $value['hpp_average'] ? intval(Str::replace('.', '', $value['hpp_average'])) : 0;
            $selling_price = $value['selling_price'] ? intval(Str::replace('.', '', $value['selling_price'])) : 0;
            $selling_price_recipe = $value['selling_price_recipe'] ? intval(Str::replace('.', '', $value['selling_price_recipe'])) : 0;

            $this->importStockProducts[$key]= [
                // 'product_id' => $product->id,
                'product_type_id' => $value['product_type_id'],
                'sku_number' => $value['sku_number'] ?? null,
                'name' => $value['name'] ?? null,
                'batch_number' => $value['batch_number'] ?? null,
                'expired_date' => $value['expired_date'] ?? null,
                'quantity' => number_format($quantity, 0, ',', '.'),
                'hpp_average'=> number_format($hpp_average, 0, ',', '.'),
                'selling_price' => number_format($selling_price, 0, ',', '.'),
                'selling_price_recipe' => number_format($selling_price_recipe, 0, ',', '.'),
            ];
        }

        Session::forget('importStockProducts');
        Session::put('importStockProducts', $this->importStockProducts);
    }


    public function save() {
        try {
            DB::beginTransaction();

            foreach ($this->importStockProducts as $key => $value) {

                $product = Product::where('sku_number', $value['sku_number'])
                ->orWhere('name', $value['name'])
                ->first();

                if (!$product) {
                    $product = Product::create([
                        'sku_number' => $value['sku_number'],
                        'product_type_id' => $value['product_type_id'] ?? ProductType::where('name', 'Obat')->first()->id,
                        'name' => $value['name'],
                        'company_id' => auth()->user()->company_id,
                        'registration_path' => 'import',
                        'unit_id'=>Unit::where('name', 'Pcs')->first()->id,
                        'is_narcotic' => rand(0, 1), // Simulate random narcotic status
                    ]);
                }

                $quantity = $value['quantity'] ? intval(Str::replace('.', '', $value['quantity'])) : 0;
                $hpp_average = $value['hpp_average'] ? intval(Str::replace('.', '', $value['hpp_average'])) : 0;
                $selling_price = $value['selling_price'] ? intval(Str::replace('.', '', $value['selling_price'])) : 0;
                $selling_price_recipe = $value['selling_price_recipe'] ? intval(Str::replace('.', '', $value['selling_price_recipe'])) : 0;

                // if ($value['batch_number'] == null) {
                //     DB::rollBack();
                //     return AlertHelper::error('Gagal', 'Batch number tidak boleh kosong');
                // }

                // if ($value['expired_date'] == null) {
                //     DB::rollBack();
                //     return AlertHelper::error('Gagal', 'Tanggal expired tidak boleh kosong');
                // }

                if ($quantity <= 0) {
                    DB::rollBack();
                    return AlertHelper::error('Gagal', 'Quantitas tidak boleh kurang dari 1');
                }

                if ($hpp_average <= 0) {
                    DB::rollBack();
                    return AlertHelper::error('Gagal', 'HPP rata-rata tidak boleh kurang dari 1');
                }

                if ($selling_price <= 0) {
                    DB::rollBack();
                    return AlertHelper::error('Gagal', 'harga jual tidak boleh kurang dari 1');
                }

                // if ($selling_price_recipe <= 0) {
                //     DB::rollBack();
                //     return AlertHelper::error('Gagal', 'harga jual resep tidak boleh kurang dari 1');
                // }

                $productImportStock = ProductImportStock::create([
                    'product_id' => $product->id,
                    'product_type_id' => $value['product_type_id'] ?? ProductType::where('name', 'Obat')->first()->id,
                    'batch_number' => $value['batch_number']?? null,
                    'expired_date' => $value['expired_date']?? null,
                    'quantity' => $quantity,
                    'hpp_average' => $hpp_average,
                    'selling_price' => $selling_price,
                    'selling_price_recipe' => $selling_price_recipe ?? 0,
                    'branch_id'=> Branch::where('company_id', auth()->user()->company_id)->first()->id,
                    'company_id' => auth()->user()->company_id,
                ]);

                $productService = new ProductService();

                $productService->createProductIncrement(
                    $product->id,
                    $quantity,
                    $value['batch_number'] ?? null,
                    $value['expired_date'] ?? null,
                    $hpp_average,
                    null,
                    null,
                    null,
                    $productImportStock->id
                );

                $productService->createProductPrice(
                    $product->id,
                    null,
                    $quantity,
                    $hpp_average,
                    $selling_price,
                    $selling_price_recipe
                );
            }

            DB::commit();

            Session::forget('importStockProducts');
            $this->reset('importStockProducts','import');
            return AlertHelper::success('Berhasil', 'Data berhasil diimport');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal import produk stok: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat mengimport data. Silakan coba lagi.');
        }
    }

    public function render()
    {
        return view('livewire.admin.logistic.import-stock-product.admin-logistic-import-stock-product-index')
        ->extends('layout.app')
        ->section('content');
    }
}
