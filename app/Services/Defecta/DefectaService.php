<?php

namespace App\Services\Defecta;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Defecta\Defecta;
use App\Models\Product\ProductStock;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use App\Models\PurchaseRequisition\PurchaseRequisitionItem;
use Illuminate\Support\Facades\Auth;

/**
 * Class DefectaService.
 */
class DefectaService
{
    public function runDefecta() {
        $companys = Company::select('id')->get();

        foreach ($companys as $company) {
            $branchId = Branch::where('company_id', $company->id)->first()->id;

            $product_stocks = ProductStock::where('branch_id', $branchId)
            ->where('company_id', $company->id)
            ->where('quantity', '>', 0)
            ->get();

            foreach ($product_stocks as $key => $product_stock) {
                $product = $product_stock->product;

                $remaining_stock = $product->maximum_stock - $product_stock->quantity;

                $purchase_requisition_item = PurchaseRequisitionItem::where('product_id', $product->id)
                    // ->where('company_id', $company->id)
                    // ->where('status', 'draft')
                    ->first();


                if ($purchase_requisition_item) {
                    continue;
                }

                // dd('tesst');

                $purchaseOrderItem = PurchaseOrderItem::where('product_id', $product->id)
                    ->whereHas('purchaseOrder', function ($query) use ($branchId, $company) {
                        $query->where('status', 'draft')
                            ->where('company_id', $company->id)
                            ->where('branch_id', $branchId);
                    })
                    ->where('company_id', $company->id)
                    ->where('quantity_less', '>', 0)
                    ->first();

                if ($purchaseOrderItem) {
                    continue;
                }

                if ($product_stock->quantity <= $product->safety_stock || $product_stock->quantity <= $product->minimun_stock) {
                    $existingDefecta = Defecta::where('product_id', $product->id)
                        ->where('branch_id', $branchId)
                        ->where('company_id', $company->id)
                        ->where('status', 'new')
                        ->first();

                    if ($existingDefecta) {
                        $existingDefecta->minimum_stock = $remaining_stock;
                        $existingDefecta->status = 'new';
                        $existingDefecta->save();
                    } else {
                        Defecta::create([
                            'product_stock_id' => $product_stock->id,
                            'product_id' => $product->id,
                            'branch_id' => $branchId,
                            'company_id' => $company->id,
                            'minimum_stock' => $remaining_stock,
                            'status' => 'new',
                        ]);
                    }
                }
            }
        }
    }
}
