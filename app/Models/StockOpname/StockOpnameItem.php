<?php

namespace App\Models\StockOpname;

use App\Models\Product\Product;
use App\Models\Product\ProductExpiredDate;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockOpnameItem extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function productExpiredDate()
    {
        return $this->hasOne(ProductExpiredDate::class, 'id', 'product_expired_date_id');
    }

    public function stockOpname()
    {
        return $this->hasOne(StockOpname::class, 'id', 'stock_opname_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('quantity', 'ilike', '%' . $search . '%')
                ->orWhere('description', 'ilike', '%' . $search . '%')
                ->orWhere('quantity', 'ilike', '%' . $search . '%')
                ->orWhere('quantity_system', 'ilike', '%' . $search . '%')
                ->orWhere('quantity_difference', 'ilike', '%' . $search . '%')
                ->orWhere('hpp_average', 'ilike', '%' . $search . '%')
                ->orWhereHas('product', function ($query) use ($search) {
                    $query->where('name', 'ilike', '%' . $search . '%');
                });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }
}
