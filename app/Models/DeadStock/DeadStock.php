<?php

namespace App\Models\DeadStock;

use App\Models\Company\Company;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeadStock extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'ilike', "%{$search}%")
                ->orWhere('quantity_old', 'ilike', "%{$search}%")
                ->orWhere('quantity', 'ilike', "%{$search}%")
                ->orWhere('price', 'ilike', "%{$search}%")
                ->orWhere('total', 'ilike', "%{$search}%")
                ->orWhereHas('product', function ($qd) use ($search) {
                    $qd->where('name', 'ilike', "%{$search}%")
                        ->orWhere('sku_number', 'ilike', "%{$search}%");
                });
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }
}
