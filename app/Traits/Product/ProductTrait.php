<?php

namespace App\Traits\Product;

use App\Models\Company\Company;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductExpiredDate;
use App\Models\Product\ProductFactory;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductPriceHistory;
use App\Models\Product\ProductRack;
use App\Models\Product\ProductStock;
use App\Models\Product\ProductStockHistory;
use App\Models\Product\ProductType;
use App\Models\Product\ProductUnit;
use App\Models\PurchaseRequisition\PurchaseRequisitionItem;
use App\Models\Unit\Unit;
use App\Traits\Branch\BranchTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\WithPagination;

trait ProductTrait
{
    use WithPagination, BranchTrait;

    public function getProductCategorys()
    {
        return ProductCategory::select('id', 'name')->orderBy('name', 'asc')->where('company_id', Auth::user()->company_id)->get()->toArray();
    }

    public function getProductFactorys()
    {
        return ProductFactory::select('id', 'name')->orderBy('name', 'asc')->where('company_id', Auth::user()->company_id)->get()->toArray();
    }

    public function getProductRacks()
    {
        return ProductRack::select('id', 'name')->orderBy('name', 'asc')->where('company_id', Auth::user()->company_id)->get()->toArray();
    }

    public function getProductTypes()
    {
        return ProductType::select('id', 'name')->orderBy('name', 'asc')->get()->toArray();
    }

    public function getProductTypeWithoutTindakans()
    {
        return ProductType::whereNotIn('name', ['Tindakan', 'Paket', 'Resep'])->select('id', 'name')->orderBy('name', 'asc')->get()->toArray();
    }

    public function getUnits()
    {
        return Unit::select('id', 'name')->orderBy('name', 'asc')->get()->toArray();
    }

    public function getProductPaginates()
    {
        $products = Product::search($this->searchProduct)
            ->select('id', 'sku_number', 'name', 'description', 'company_id')
            ->with('company:id,name', 'productStock:id,product_id,quantity', 'productPrice:id,product_id,price,recipe')
            ->where('company_id', Auth::user()->company_id);

        return $products->orderBy('name', 'asc')->paginate($this->perPageProduct, ['*'], 'pageProduct');
    }

    public function getProducts()
    {
        $products = Product::search($this->searchProduct)
            ->select('id', 'sku_number', 'name', 'description', 'company_id')
            ->with('company:id,name', 'productStock:id,product_id,quantity', 'productPrice:id,product_id,price,recipe')
            ->where('company_id', Auth::user()->company_id);

        return $products->orderBy('name', 'asc')->get();
    }

    public function getProductSelects()
    {
        $products = Product::select('id', 'name');

        $company = Company::where('id', Auth::user()->company_id)->first();

        $products->where('company_id', $company->id);

        return $products->orderBy('name', 'asc')->get()->toArray();
    }

    public function getProductsProperty()
    {
        return $this->getProductPaginates(); // gunakan method reusable
    }

    public function generateSkuNumber()
    {
        do {
            $this->sku_number = random_int(00000000000001, 99999999999999);
        } while (strlen(strval($this->sku_number)) < 14 || Product::where('company_id', Auth::user()->company_id)->where('sku_number', $this->sku_number)->first());
    }

    public function generateUpdatePurchaseUnit()
    {
        $purchaseRequisitionItems = PurchaseRequisitionItem::whereNotNull('product_unit_id')->where('company_id', Auth::user()->company_id)->where('status', 'draft')->get();

        if (count($purchaseRequisitionItems) < 1) {
            return;
        }
        foreach ($purchaseRequisitionItems as $purchaseRequisitionItem) {
            $quantity_detail = 0;
            $quantity_real = 0;

            $productUnit = ProductUnit::findOrFail($purchaseRequisitionItem->product_unit_id);
            if ($purchaseRequisitionItem->product_unit_id) {

                $quantityProdukUnit = $productUnit->quantity;
                $quantityProdukPurchaseRequisitionItem = $purchaseRequisitionItem->quantity;

                $quantity_detail = ceil($quantityProdukPurchaseRequisitionItem / $quantityProdukUnit);
                $quantity_real = $quantity_detail * $quantityProdukUnit;
            }

            // $purchaseRequisitionItem->product_unit_id = $productUnit->id;
            $purchaseRequisitionItem->quantity_detail = $quantity_detail;
            $purchaseRequisitionItem->quantity_real = $quantity_real;
            $purchaseRequisitionItem->save();
        }
    }

    public function getProductIncrement($product_id, $product_unit_id, $batch_numbers, $quantity, $price, $purchase_order_item_id = null, $invoice_item_id = null, $product_unit_quantity = null)
    {
        $branch = $this->getBranchOne();
        $productUnit = ProductUnit::where('product_id', $product_id)->find($product_unit_id);

        $productUnitQuantity = $quantity * $productUnit->quantity;

        $productUnitPrice = $price / $productUnitQuantity;

        $productStock = ProductStock::where('product_id', $product_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $branch->id)
            ->first();

        if ($productStock) {
            $productStock->quantity += $productUnitQuantity;
            $productStock->save();
        } else {
            $productStock = new ProductStock();
            $productStock->product_id = $product_id;
            $productStock->branch_id = $branch->id;
            $productStock->company_id = Auth::user()->company_id;
            $productStock->quantity = $productUnitQuantity;
            $productStock->save();
        }

        if (!empty($batch_numbers)) {
            foreach ($batch_numbers as $key_batch_number => $batch_number) {
                // Validasi format tanggal expired_date
                try {
                    $formattedExpiredDate = Carbon::parse($batch_number['expired_date'])->format('Y-m-d');
                } catch (Exception $e) {
                    // Log error atau tambahkan lebih banyak informasi untuk debugging
                    throw new Exception("Invalid date format for batch number: {$batch_number['batch_number']} - {$e->getMessage()}");
                }

                // Query untuk mencari data yang sudah ada
                $productExpiredDate = ProductExpiredDate::where('product_id', $product_id)
                    ->where('branch_id', $branch->id)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('batch_number', $batch_number['batch_number'])
                    ->where('product_stock_id', $productStock->id)
                    ->where('expired_date', $formattedExpiredDate)
                    ->first();

                if ($productExpiredDate) {
                    // Update jumlah stok jika data ditemukan
                    $productExpiredDate->quantity += $batch_number['stok'];
                    $productExpiredDate->save();
                } else {
                    // Buat data baru jika tidak ditemukan
                    ProductExpiredDate::create([
                        'product_stock_id' => $productStock->id,
                        'product_id' => $product_id,
                        'branch_id' => $branch->id,
                        'company_id' => Auth::user()->company_id,
                        'expired_date' => $formattedExpiredDate,
                        'batch_number' => $batch_number['batch_number'],
                        'quantity' => $batch_number['stok'],
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }
        }

        // Generate code: IN/YYYYMMDD/00001
        $today = date('ymd'); // Tahun 2 digit
        $prefix = 'IN/' . $today . '/';

        $lastHistory = ProductStockHistory::where('code', 'like', $prefix . '%')
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $branch->id)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastHistory && preg_match('/(\d{4})$/', $lastHistory->code, $matches)) {
            $lastNumber = intval($matches[1]);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $code = $prefix . $nextNumber;

        $description = "Barang masuk: {$productUnitQuantity} unit pada " . date('d-m-Y') . " (Kode: {$code}), harga per unit: {$productUnitPrice}.";

        ProductStockHistory::create([
            'product_id' => $product_id,
            'product_stock_id' => $productStock->id,
            'branch_id' => $branch->id,
            'code' => $code,
            'date' => Carbon::now(),
            'product_unit_id' => $product_unit_id,
            'purchase_order_item_id' => $purchase_order_item_id,
            'invoice_item_id' => $invoice_item_id,
            'description' => $description,
            'company_id' => Auth::user()->company_id,
            'quantity' => $productUnitQuantity,
            'price' => $productUnitPrice,
            'sub_total_price' => $productUnitPrice * $productUnitQuantity,
            'type' => 'in',
            'user_id' => Auth::user()->id,
        ]);
    }
    public function getProductDecrement($product_id, $product_unit_id, $batch_numbers, $quantity, $price, $purchase_order_item_id = null, $invoice_item_id, $product_unit_quantity = null)
    {
        $branch = $this->getBranchOne();

        $productUnit = ProductUnit::where('product_id', $product_id)->find($product_unit_id);

        $productUnitQuantity = $quantity * $productUnit->quantity;

        $productUnitPrice = $price / $productUnitQuantity;

        $productStock = ProductStock::where('product_id', $product_id)->where('company_id', Auth::user()->company_id)->where('branch_id', $branch->id)->first();

        if ($productStock) {
            $productStock->quantity -= $productUnitQuantity;
            $productStock->save();
        } else {
            $productStock = new ProductStock();
            $productStock->product_id = $product_id;
            $productStock->branch_id = $branch->id;
            $productStock->company_id = Auth::user()->company_id;
            $productStock->quantity = $productUnitQuantity;
            $productStock->save();
        }

        if (!empty($batch_numbers)) {
            foreach ($batch_numbers as $key_batch_number => $batch_number) {
                // Validasi format tanggal expired_date
                try {
                    $formattedExpiredDate = Carbon::parse($batch_number['expired_date'])->format('Y-m-d');
                } catch (Exception $e) {
                    // Log error atau tambahkan lebih banyak informasi untuk debugging
                    throw new Exception("Invalid date format for batch number: {$batch_number['batch_number']} - {$e->getMessage()}");
                }

                // Query untuk mencari data yang sudah ada
                $productExpiredDate = ProductExpiredDate::where('product_id', $product_id)
                    ->where('branch_id', $branch->id)
                    ->where('company_id', Auth::user()->company_id)
                    ->where('batch_number', $batch_number['batch_number'])
                    ->where('product_stock_id', $productStock->id)
                    ->where('expired_date', $formattedExpiredDate)
                    ->first();

                if ($productExpiredDate) {
                    // Update jumlah stok jika data ditemukan
                    $productExpiredDate->quantity -= $batch_number['stok'];
                    $productExpiredDate->save();
                } else {
                    // Buat data baru jika tidak ditemukan
                    ProductExpiredDate::create([
                        'product_stock_id' => $productStock->id,
                        'product_id' => $product_id,
                        'branch_id' => $branch->id,
                        'company_id' => Auth::user()->company_id,
                        'expired_date' => $formattedExpiredDate,
                        'batch_number' => $batch_number['batch_number'],
                        'quantity' => $batch_number['stok'],
                        'user_id' => Auth::user()->id,
                    ]);
                }
            }
        }

        // Generate code: OUT/YYYYMMDD/00001
        $today = date('ymd'); // Tahun 2 digit
        $prefix = 'OUT/' . $today . '/';

        $lastHistory = ProductStockHistory::where('code', 'like', $prefix . '%')
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $branch->id)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastHistory && preg_match('/(\d{4})$/', $lastHistory->code, $matches)) {
            $lastNumber = intval($matches[1]);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $code = $prefix . $nextNumber;
        $description = "Barang keluar: {$productUnitQuantity} unit pada " . date('d-m-Y') . " (Kode: {$code}), harga per unit: {$productUnitPrice}.";
        ProductStockHistory::create([
            'product_id' => $product_id,
            'product_stock_id' => $productStock->id,
            'branch_id' => $branch->id,
            'date' => Carbon::now(),
            'product_unit_id' => $product_unit_id,
            'purchase_order_item_id' => $purchase_order_item_id,
            'invoice_item_id' => $invoice_item_id,
            'code' => $code,
            'description' => $description,
            'company_id' => Auth::user()->company_id,
            'quantity' => $productUnitQuantity,
            'price' => $productUnitPrice,
            'sub_total_price' => $productUnitPrice * $productUnitQuantity,
            'type' => 'out',
            'user_id' => Auth::user()->id,
        ]);
    }

    public function createProductPrice($product_id, $product_unit_id, $price, $quantity, $product_unit_quantity = null)
    {
        $productUnit = ProductUnit::where('product_id', $product_id)->find($product_unit_id);

        $productUnitQuantity = $product_unit_quantity ? $product_unit_quantity : $quantity * $productUnit->quantity;

        $productUnitPrice = $price / $productUnitQuantity;

        $productPrice = ProductPrice::where('product_id', $product_id)->where('company_id', Auth::user()->company_id)->where('branch_id', $this->getBranchOne()->id)->first();

        if ($productPrice) {
            $productPrice->price_generate = 0;
            $productPrice->recipe_generate = 0;
            $productPrice->hpp_average = 0;
            $productPrice->is_updated = false;
            $productPrice->save();
        } else {
            $productPrice = new ProductPrice();
            $productPrice->product_id = $product_id;
            $productPrice->branch_id = $this->getBranchOne()->id;
            $productPrice->company_id = Auth::user()->company_id;
            $productPrice->price_generate = 0;
            $productPrice->recipe_generate = 0;
            $productPrice->hpp_average = 0;
            $productPrice->is_updated = false;
            $productPrice->save();
        }

        ProductPriceHistory::create([
            'product_id' => $product_id,
            'product_price_id' => $productPrice->id,
            'branch_id' => $this->getBranchOne()->id,
            'company_id' => Auth::user()->company_id,
            'price' => $productUnitPrice,
            'quantity' => $productUnitQuantity,
            'sub_total_price' => $productUnitPrice * $productUnitQuantity,
            'hpp_average' => ($productUnitPrice * $productUnitQuantity) / $productUnitQuantity,
            'is_updated' => false,
            'user_id' => Auth::user()->id,
        ]);

        $productPriceHistorys = ProductPriceHistory::where('product_id', $product_id)->where('company_id', Auth::user()->company_id)->where('branch_id', $this->getBranchOne()->id)->get();

        $sumQuantity = $productPriceHistorys->sum('quantity');
        $sumSubTotalPrice = $productPriceHistorys->sum('sub_total_price');
        $hppAverage = $sumSubTotalPrice / $sumQuantity;

        $productPrice->hpp_average = $hppAverage;
        $productPrice->save();
    }
}
