<?php

namespace App\Services\Product;

use App\Models\Branch\Branch;
use App\Models\Product\Product;
use App\Models\Product\ProductExpiredDate;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductPriceHistory;
use App\Models\Product\ProductStock;
use App\Models\Product\ProductStockHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProductService.
 */
class ProductService
{

    public $branch;

    public function __construct()
    {
        $this->branch = Branch::where('company_id', auth()->user()->company_id)->first();
    }

    public function createProductIncrement($product_id, $quantity, $batch_number = null, $expired_date = null, $price, $product_unit_id = null, $purchase_order_item_id = null, $transaction_detail_id = null,$transaction_recipe_id = null, $product_import_stock_id = null, $dead_stock_id = null) {
        $product = Product::find($product_id);

        $quantity = $quantity ? intval(str_replace('.', '', $quantity)) : 0;

        $productStock = ProductStock::where('product_id', $product_id)
        ->where('company_id', Auth::user()->company_id)
        ->where('branch_id', $this->branch->id)
        ->first();

        if ($productStock) {
            if ($product->is_non_stock === false) {
                // Jika produk bukan non-stock, tambahkan kuantitas
                $productStock->quantity += $quantity;
                $productStock->save();
            }
        } else {
            $productStock = new ProductStock();
            $productStock->product_id = $product_id;
            $productStock->branch_id = $this->branch->id;
            $productStock->company_id = Auth::user()->company_id;
            $productStock->quantity = $quantity;
            $productStock->save();
        }

        if ($batch_number && $expired_date) {
                // Validasi format tanggal expired_date
            try {
                $formattedExpiredDate = Carbon::parse($expired_date)->format('Y-m-d');
            } catch (Exception $e) {
                // Log error atau tambahkan lebih banyak informasi untuk debugging
                throw new Exception("Invalid date format for batch number: {$batch_number} - {$e->getMessage()}");
            }

            $productExpiredDate = ProductExpiredDate::where('product_id', $product_id)
                ->where('branch_id', $this->branch->id)
                ->where('company_id', Auth::user()->company_id)
                ->where('batch_number', $batch_number)
                ->where('product_stock_id', $productStock->id)
                ->where('expired_date', $formattedExpiredDate)
                ->first();

            if ($productExpiredDate) {
                // Update jumlah stok jika data ditemukan
                $productExpiredDate->quantity += $quantity;
                $productExpiredDate->save();
            } else {
                // Buat data baru jika tidak ditemukan
                ProductExpiredDate::create([
                    'product_stock_id' => $productStock->id,
                    'product_id' => $product_id,
                    'branch_id' => $this->branch->id,
                    'company_id' => Auth::user()->company_id,
                    'expired_date' => $formattedExpiredDate,
                    'batch_number' => $batch_number,
                    'quantity' => $quantity,
                    'user_id'=>Auth::user()->id,
                ]);
            }
        }

         // Generate code: IN/YYYYMMDD/00001
        $today = date('ymd'); // Tahun 2 digit
        $prefix = 'IN/' . $today . '/';

        $lastHistory = ProductStockHistory::where('code', 'like', $prefix . '%')
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $this->branch->id)
            ->orderBy('code', 'desc')
            ->first();

        if ($lastHistory && preg_match('/(\d{4})$/', $lastHistory->code, $matches)) {
            $lastNumber = intval($matches[1]);
            $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        $code = $prefix . $nextNumber;

        $description = "Barang masuk: {$quantity} unit pada " . date('d-m-Y') . " (Kode: {$code}), harga per unit: {$price}.";

        ProductStockHistory::create([
            'product_id' => $product_id,
            'product_stock_id' => $productStock->id,
            'branch_id' => $this->branch->id,
            'code' => $code,
            'date' => Carbon::now(),
            'product_unit_id' => $product_unit_id,
            'purchase_order_item_id' => $purchase_order_item_id,
            'transaction_detail_id' => $transaction_detail_id,
            'transaction_recipe_id' => $transaction_recipe_id,
            'product_import_stock_id' => $product_import_stock_id,
            'dead_stock_id' => $dead_stock_id,
            'description' => $description,
            'company_id' => Auth::user()->company_id,
            'quantity' => $quantity,
            'price' => $price,
            'sub_total_price' => $price * $quantity,
            'type' => 'in',
            'user_id'=>Auth::user()->id,
        ]);
    }

    public function createProductDecrement(
        $product_id,
        $quantity,
        $batch_number = null,
        $expired_date = null,
        $price,
        $product_unit_id = null,
        $purchase_order_item_id = null,
        $transaction_detail_id = null,
        $transaction_recipe_id = null,
        $product_import_stock_id = null,
        $dead_stock_id = null
    ) {
        $product = Product::find($product_id);

        $quantity = $quantity ? intval(str_replace('.', '', $quantity)) : 0;

        $productStock = ProductStock::where('product_id', $product_id)
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $this->branch->id)
            ->first();

        if (!$productStock || $productStock->quantity < $quantity) {
            throw new \Exception('Stok tidak mencukupi untuk barang keluar.');
        }

        if ($product->is_non_stock === false) {
            $productStock->quantity -= $quantity;
            $productStock->save();
        }

        if ($batch_number && $expired_date) {
            try {
                $formattedExpiredDate = Carbon::parse($expired_date)->format('Y-m-d');
            } catch (Exception $e) {
                throw new Exception("Format tanggal tidak valid untuk batch: {$batch_number} - {$e->getMessage()}");
            }

            $productExpiredDate = ProductExpiredDate::where('product_id', $product_id)
                ->where('branch_id', $this->branch->id)
                ->where('company_id', Auth::user()->company_id)
                ->where('batch_number', $batch_number)
                ->where('product_stock_id', $productStock->id)
                ->where('expired_date', $formattedExpiredDate)
                ->first();

            if ($productExpiredDate) {
                if ($productExpiredDate->quantity < $quantity) {
                    throw new \Exception('Stok berdasarkan batch/expired tidak mencukupi.');
                }

                $productExpiredDate->quantity -= $quantity;
                $productExpiredDate->save();
            } else {
                throw new \Exception('Data batch dan tanggal kedaluwarsa tidak ditemukan.');
            }
        }

        $today = date('ymd');
        $prefix = 'OUT/' . $today . '/';

        $lastHistory = ProductStockHistory::where('code', 'like', $prefix . '%')
            ->where('company_id', Auth::user()->company_id)
            ->where('branch_id', $this->branch->id)
            ->orderBy('code', 'desc')
            ->first();

        $nextNumber = '0001';
        if ($lastHistory && preg_match('/(\d{4})$/', $lastHistory->code, $matches)) {
            $nextNumber = str_pad(intval($matches[1]) + 1, 4, '0', STR_PAD_LEFT);
        }

        $code = $prefix . $nextNumber;

        $description = "Barang keluar: {$quantity} unit pada " . date('d-m-Y') . " (Kode: {$code}), harga per unit: {$price}.";

        ProductStockHistory::create([
            'product_id' => $product_id,
            'product_stock_id' => $productStock->id,
            'branch_id' => $this->branch->id,
            'code' => $code,
            'date' => Carbon::now(),
            'product_unit_id' => $product_unit_id,
            'purchase_order_item_id' => $purchase_order_item_id,
            'transaction_detail_id' => $transaction_detail_id,
            'transaction_recipe_id' => $transaction_recipe_id,
            'product_import_stock_id' => $product_import_stock_id,
            'dead_stock_id' => $dead_stock_id,
            'description' => $description,
            'company_id' => Auth::user()->company_id,
            'quantity' => $quantity,
            'price' => $price,
            'sub_total_price' => $price * $quantity,
            'type' => 'out', // ✅ ini yang benar
            'user_id' => Auth::user()->id,
        ]);
    }

    public function createProductPrice($product_id, $product_unit_id = null, $quantity, $hpp_average, $price, $price_recipe) {

        $productPrice = ProductPrice::where('product_id', $product_id)->where('company_id', Auth::user()->company_id)->where('branch_id', $this->branch->id)->first();

        if ($productPrice) {
            $productPrice->price = $price;
            $productPrice->recipe = $price_recipe;
            $productPrice->hpp_average = $hpp_average;
            $productPrice->is_updated = true;
            $productPrice->save();
        } else {
            $productPrice = new ProductPrice();
            $productPrice->product_id = $product_id;
            $productPrice->branch_id = $this->branch->id;
            $productPrice->company_id = Auth::user()->company_id;
            $productPrice->price = $price;
            $productPrice->recipe = $price_recipe;
            $productPrice->hpp_average = $hpp_average;
            $productPrice->is_updated = true;
            $productPrice->save();
        }

        ProductPriceHistory::create([
            'product_id' => $product_id,
            'product_price_id' => $productPrice->id,
            'branch_id' => $this->branch->id,
            'company_id' => Auth::user()->company_id,
            'price' => $hpp_average,
            'quantity' => $quantity,
            'sub_total_price' => $hpp_average * $quantity,
            'hpp_average' => $hpp_average,
            'is_updated' => false,
            'user_id'=>Auth::user()->id,
        ]);
    }
}
