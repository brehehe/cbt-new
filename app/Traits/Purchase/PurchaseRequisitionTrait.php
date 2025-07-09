<?php

namespace App\Traits\Purchase;

use App\Helpers\AlertHelper;
use App\Models\Company\Company;
use App\Models\Product\ProductUnit;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use App\Models\PurchaseRequisition\PurchaseRequisition;
use App\Models\PurchaseRequisition\PurchaseRequisitionItem;
use Illuminate\Support\Facades\Auth;

trait PurchaseRequisitionTrait
{
    public $supplier_id;

    // Tanggal
    public $start_date;

    public $end_date;

    public function getPurchaseRequisitionPaginates()
    {
        $purchaseRequisitions = PurchaseRequisition::search($this->search)
            ->select('id', 'number', 'status', 'company_id', 'grand_total', 'supplier_id','purchase_order_id')
            ->with('company:id,name', 'supplier:id,name','purchaseOrder:id,number,status,grand_total')
            ->where('company_id', Auth::user()->company_id);

        if ($this->supplier_id) {
            $purchaseRequisitions->where('supplier_id', $this->supplier_id);
        }

        if ($this->start_date) {
            $purchaseRequisitions->where('created_at', '>=', $this->start_date . ' 00:00:00');
        }

        if ($this->end_date) {
            $purchaseRequisitions->where('created_at', '<=', $this->end_date . ' 23:59:59');
        }

        return $purchaseRequisitions->orderBy('order','desc')->paginate($this->perPage);
    }

    public function getPurchaseRequisitionWithoutRejectWithSupplierPaginates()
    {
        $purchaseRequisitions = PurchaseRequisition::search($this->search)
            ->select('id', 'number', 'status', 'company_id', 'grand_total', 'supplier_id', 'purchase_order_id')
            ->with('company:id,name', 'supplier:id,name','purchaseOrder:id,number,status,grand_total')
            ->where('status', '!=', 'reject')
            ->where('company_id', Auth::user()->company_id)
            ->whereNotNull('supplier_id');

        if ($this->supplier_id) {
            $purchaseRequisitions->where('supplier_id', $this->supplier_id);
        }

        if ($this->start_date) {
            $purchaseRequisitions->where('created_at', '>=', $this->start_date . ' 00:00:00');
        }

        if ($this->end_date) {
            $purchaseRequisitions->where('created_at', '<=', $this->end_date . ' 23:59:59');
        }

        return $purchaseRequisitions->orderBy('order','desc')->paginate($this->perPage);
    }

    public function getPurchaseRequisitionItems($purchase_requisition_id)
    {
        $query = PurchaseRequisitionItem::with(
            'product:id,name,unit_id',
            'product.unit:id,name',
            'product.productUnits:id,product_id,unit_id,quantity',
            'product.productUnits.unit:id,name',
            'productUnit:id,quantity,unit_id',
            'productUnit.unit:id,name',
            'company:id,name',
        )->where('purchase_requisition_id', $purchase_requisition_id);

        return $query->orderBy('order', 'desc')->get();
    }

    public function getPurchaseRequisition($purchase_requisition_id)
    {
        $details = PurchaseRequisition::find($purchase_requisition_id);

        return $details;
    }

    public function createPurchaseOrder($purchase_requisition_id, $number) {
        $purchaseRequisition = PurchaseRequisition::find($purchase_requisition_id);

        $purchaseOrder = PurchaseOrder::create([
            'user_id' => $purchaseRequisition->user_id,
            'supplier_id' => $purchaseRequisition->supplier_id,
            'branch_id' => $purchaseRequisition->branch_id,
            'number' => $number,
            'status' => 'draft',
            'company_id' => $purchaseRequisition->company_id,
            'grand_total' => $purchaseRequisition->grand_total,
        ]);

        foreach ($purchaseRequisition->purchaseRequisitionItems as $item) {

            $productUnit = ProductUnit::find($item->product_unit_id);
            $productUnitQuantity = $productUnit->quantity * $item->quantity_detail;

            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item->product_id,
                'quantity'=>$item->quantity_detail,
                'quantity_less'=>$item->quantity_detail,
                'product_unit_quantity'=>$productUnitQuantity,
                'product_unit_id' => $item->product_unit_id,
                'product_name'=>$item->product_name,
                'purchase_requisition_item_id'=>$item->id,
                'company_id' => $item->company_id,
            ]);
        }

        $purchaseRequisition->update([
            'status' => 'open',
            'purchase_order_id' => $purchaseOrder->id,
        ]);

        return AlertHelper::success('Purchase Order', 'Purchase Order telah berhasil dibuat.');
    }

}
