<?php

namespace App\Traits\Product;

use App\Models\Company\Company;
use App\Models\Product\Product;
use App\Models\Product\ProductStock;
use App\Models\Product\ProductStockHistory;
use Illuminate\Support\Facades\Auth;

trait ProductStockTrait
{
    //
    public function getProductStocks()
    {
        $products = Product::search($this->search)
            ->select('id', 'sku_number', 'name', 'description', 'company_id', 'unit_id', 'maximum_stock', 'minimun_stock', 'safety_stock')
            ->with('company:id,name', 'productStock:id,product_id,branch_id,quantity,quantity_lock,quantity_real,company_id', 'unit:id,name')
            ->where('company_id', Auth::user()->company_id);

        return $products->orderBy('name', 'asc')->paginate();
    }

    public function getProductStockHistorys($type)
    {
        $productStockHistorys = ProductStockHistory::search(trim($this->search))
            ->with('product:id,name,sku_number,unit_id', 'user:id,name', 'branch:id,name', 'company:id,name', 'product.unit:id,name')
            ->where('type', $type)
            ->where('company_id', Auth::user()->company_id)
            ->orderBy('order', 'desc');

        if ($this->start_date) {
            $productStockHistorys->whereDate('date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $productStockHistorys->whereDate('date', '<=', $this->end_date);
        }

        if ($this->product_id) {
            $productStockHistorys->where('product_id', $this->product_id);
        }

        return $productStockHistorys->paginate($this->perPage);
    }
}
