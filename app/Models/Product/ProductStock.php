<?php

namespace App\Models\Product;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productStockHistories()
    {
        return $this->hasMany(ProductStockHistory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query
        ->where('quantity', 'ilike', "%$search%")
        ->orWhere('quantity_lock', 'ilike', "%$search%")
        ->orWhere('quantity_real', 'ilike', "%$search%")
        ->whereHas('product', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
            $q->where('sku_number', 'ilike', "%$search%");
        })->orWhereHas('branch', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
        })->orWhereHas('company', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
        });
    }
}
