<?php

namespace App\Models\PurchaseOrder;

use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrderItem extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productUnit()
    {
        return $this->belongsTo(ProductUnit::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('purchase_order_id', 'ilike', '%' . $search . '%')
                ->orWhere('product_id', 'ilike', '%' . $search . '%')
                ->orWhere('quantity', 'ilike', '%' . $search . '%')
                ->orWhere('price', 'ilike', '%' . $search . '%')
                ->orWhere('hna', 'ilike', '%' . $search . '%')
                ->orWhere('ppn', 'ilike', '%' . $search . '%')
                ->orWhere('hna_ppn', 'ilike', '%' . $search . '%')
                ->orWhere('sub_total', 'ilike', '%' . $search . '%')
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'ilike', '%' . $search . '%');
                })
                ->orWhereHas('productUnit', function ($query) use ($search) {
                    $query->where('name', 'ilike', '%' . $search . '%');
                });
        }
    }
}
