<?php

namespace App\Traits\Pos;

use App\Helpers\AlertHelper;
use App\Models\Branch\Branch;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStock;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use Illuminate\Support\Str;

trait PosTrait
{
    public function choiceProductChange()
    {
        $product = Product::where('sku_number', $this->search_sku)->first();

        if ($product) {

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

            if (!$productPrice) {
                return AlertHelper::error('Gagal', 'Harga produk tidak ditemukan.');
            }

            $transactionItem = TransactionDetail::where('transaction_id', $this->transaction_id)
                ->where('product_id', $product->id)
                ->first();

            if ($transactionItem) {
                $transactionItem->increment('quantity', 1);
                $transactionItem->price = $productPrice->price;
                $transactionItem->sub_total_price = $productPrice->price * $transactionItem->quantity;

                $transactionItem->save();
            } else {
                TransactionDetail::create([
                    'transaction_id' => $this->transaction_id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $productPrice->price,
                    'sub_total_price' => $productPrice->price,
                ]);
            }

            $this->details();
            $this->updateTotal();
            $this->reset('search_sku');
            return AlertHelper::success('Berhasil', 'Produk berhasil ditambahkan ke keranjang.');
        } else {
            $this->reset('search_sku');
            return AlertHelper::error('Gagal', 'Produk tidak ditemukan.');
        }
    }

    public function details() {
        $this->transaction_details = [];

        $transactionDetails = TransactionDetail::where('transaction_id', $this->transaction_id)
            ->orderBy('order', 'asc')
            ->get();

            foreach ($transactionDetails as $key => $transactionDetail) {
                $this->transaction_details[] = [
                    'id' => $transactionDetail->id,
                    'product_id' => $transactionDetail->product_id,
                    'product_name' => $transactionDetail->product->name,
                    'quantity' => $transactionDetail->quantity,
                    'price' => $transactionDetail->price,
                    'sub_total_price' => $transactionDetail->sub_total_price,
                ];
            }
    }

       public function updateTotal()
    {
        $transaction = Transaction::find($this->transaction_id);

        if ($transaction) {
            $total = TransactionDetail::where('transaction_id', $this->transaction_id)
                ->sum('sub_total_price');

            $transaction->sub_total_price = $total;

            // Hitung diskon
            if ($total >= 1) {
                if ($this->discount_type == 'percentage') {
                    $transaction->discount = Str::replace(',', '.', $this->discount);
                    $transaction->discount_value = ($total * $transaction->discount) / 100;
                } else {
                    $discount = intval(str_replace('.', '', $this->discount));
                    $discount = $total < $discount ? $total : $discount;
                    $transaction->discount = $discount;
                    $transaction->discount_value = $discount;
                }
            } else {
                $transaction->discount = 0;
                $transaction->discount_type = 'rupiah';
                $transaction->discount_value = 0;
            }

            $this->discount = $this->discount_type == 'rupiah'
                ? number_format($transaction->discount, 0, ',', '.')
                : Str::replace(',', '.', $this->discount);

            $transaction->discount_type = $this->discount_type;

            // Set sub_total_price_before_rounding
            $total = $transaction->sub_total_price_before_rounding = $total;

            // Hitung grand total sebelum pembulatan
            $grandTotal = $total - $transaction->discount_value;

            // Pembulatan
            $rounding = 0;
            $roundedTotal = 0;
            $remainder = 0;

            if ($grandTotal <= 0) {
                $roundedTotal = 0;
                $rounding = -$grandTotal;
                $remainder = 0;
            } else {
                $remainder = $grandTotal % 1000;

                if ($remainder < 250) {
                    $roundedTotal = $grandTotal - $remainder;
                    $rounding = -$remainder;
                } elseif ($remainder < 750) {
                    $roundedTotal = $grandTotal - $remainder + 500;
                    $rounding = 500 - $remainder;
                } else {
                    $roundedTotal = $grandTotal - $remainder + 1000;
                    $rounding = 1000 - $remainder;
                }
            }

            $transaction->rounding = $rounding;
            $transaction->grand_total_price = $roundedTotal;
            $transaction->rounding_remainder = $remainder; // ✅ disimpan di field baru
            $transaction->payment_amount = $transaction->transactionPayments()->sum('payment_amount');
            $transaction->payment_change = $transaction->payment_amount < $transaction->grand_total_price ? 0 : $transaction->payment_amount - $transaction->grand_total_price;
            $transaction->remaining_bill = $transaction->grand_total_price - $transaction->payment_amount;
            $transaction->remaining_bill = $transaction->remaining_bill < 0 ? 0 : $transaction->remaining_bill;
            $transaction->grand_total_price_admin_fee = $transaction->grand_total_price + $transaction->single_payment_admin_fee;
            $transaction->save();
            $this->reset('transaction');
            $this->transaction = $transaction;
        }
    }

}
