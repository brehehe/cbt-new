<?php

namespace App\Traits\Purchase;

use App\Helpers\AlertHelper;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Traits\Product\ProductTrait;
use Illuminate\Support\Str;

trait PurchaseOrderTrait
{
    use ProductTrait;

    //
    public function getPurchaseOrder($purchase_order_id)
    {
        $details = PurchaseOrder::find($purchase_order_id);
        return $details;
    }

    public function getPurchaseOrderItem($purchase_order_id, $purchase_order_item_id)
    {
        $details = PurchaseOrder::find($purchase_order_id)->purchaseOrderItems()->where('id', $purchase_order_item_id)->first();
        return $details;
    }

    public function createGoodCome()
    {

        $quantity_arrival = $this->quantity_arrival ? intval(Str::replace('.', '', $this->quantity_arrival)) : 0;

        $quantity_arrival = $quantity_arrival ? intval(Str::replace('.', '', $this->quantity_arrival)) : 0;

        $price = $this->price ? intval(Str::replace('.', '', $this->price)) : 0;

        $purchaseOrderItem = $this->purchase_order_item;


        $this->getProductIncrement($purchaseOrderItem->product_id, $purchaseOrderItem->product_unit_id, $this->batch_numbers, $quantity_arrival, $price, $purchaseOrderItem->id, null, $purchaseOrderItem->product_unit_quantity);

        $this->createProductPrice($purchaseOrderItem->product_id, $purchaseOrderItem->product_unit_id, $price, $quantity_arrival, $purchaseOrderItem->product_unit_quantity);


        $purchaseOrderItem->price = $this->price ? intval(Str::replace('.', '', $this->price)) : 0;
        $purchaseOrderItem->hna = $this->hna ? intval(Str::replace('.', '', $this->hna)) : 0;
        $purchaseOrderItem->hna_ppn = $this->hna_ppn ? intval(Str::replace('.', '', $this->hna_ppn)) : 0;
        $purchaseOrderItem->ppn = $this->ppn ? intval(Str::replace('.', '', $this->ppn)) : 0;
        $purchaseOrderItem->sub_total = $this->sub_total ? intval(Str::replace('.', '', $this->sub_total)) : 0;
        $purchaseOrderItem->quantity_less = $purchaseOrderItem->quantity_less - $quantity_arrival;
        $purchaseOrderItem->quantity_accepted = $purchaseOrderItem->quantity_accepted + $quantity_arrival;
        $purchaseOrderItem->total = $purchaseOrderItem->price * $purchaseOrderItem->quantity_accepted;
        $purchaseOrderItem->save();

        $this->fixPurchaseOrder();
    }

    public function fixPurchaseOrder()
    {
        $purchaseOrder = $this->getPurchaseOrder($this->purchase_order_id);

        $quantity_less = $purchaseOrder->purchaseOrderItems->sum('quantity_less');

        if ($quantity_less == 0) {
            $purchaseOrder->status = 'success';
        } else {
            $purchaseOrder->status = 'pending';
        }

        $purchaseOrder->price = 0;
        $purchaseOrder->grand_total = 0;
        foreach ($purchaseOrder->purchaseOrderItems as $item) {
            $purchaseOrder->price += $item->price;
            $purchaseOrder->grand_total += $item->sub_total;
        }
        $purchaseOrder->save();
    }

    public function validateInputPurchaseOrder()
    {

        $hna = $this->hna ? intval(Str::replace('.', '', $this->hna)) : 0;
        $hna_ppn = $this->hna_ppn ? intval(Str::replace('.', '', $this->hna_ppn)) : 0;
        $price = $this->price ? intval(Str::replace('.', '', $this->price)) : 0;
        $sub_total = $this->sub_total ? intval(Str::replace('.', '', $this->sub_total)) : 0;
        $quantity_arrival = $this->quantity_arrival ? intval(Str::replace('.', '', $this->quantity_arrival)) : 0;

        $batch_numbers = $this->batch_numbers ?? [];

        if (!$hna_ppn || !$hna) {
            return AlertHelper::error('Gagal', 'HNA / HNA PPN tidak boleh kosong');
        }

        if (!$price) {
            return AlertHelper::error('Gagal', 'Harga tidak boleh kosong');
        }

        if (!$sub_total) {
            return AlertHelper::error('Gagal', 'Sub Total tidak boleh kosong');
        }

        if (!$quantity_arrival) {
            return AlertHelper::error('Gagal', 'Jumlah tidak boleh kosong');
        }

        // if (count($batch_numbers) == 0) {
        //     return AlertHelper::error('Gagal', 'Batch Number tidak boleh kosong');
        // }

        // foreach ($batch_numbers as $key => $batch) {
        //     if (!$batch['expired_date']) {
        //         return AlertHelper::error('Gagal', 'Tanggal Expired tidak boleh kosong');
        //     }

        //     if (!$batch['batch_number']) {
        //         return AlertHelper::error('Gagal', 'Batch Number tidak boleh kosong');
        //     }

        //     if (!$batch['stok']) {
        //         return AlertHelper::error('Gagal', 'Stok tidak boleh kosong');
        //     }
        // }

        return true;
    }
}
