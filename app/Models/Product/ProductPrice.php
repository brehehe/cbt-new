<?php

namespace App\Models\Product;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query
            ->where('hpp_average', 'ilike', "%{$search}%")
            ->orWhere('price', 'ilike', "%{$search}%")
            ->orWhere('recipe', 'ilike', "%{$search}%")
            ->orWhereHas('product', function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%")
                    ->orWhere('sku_number', 'ilike', "%{$search}%");
            })
            ->orWhereHas('product.productType', function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orWhereHas('branch', function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orWhereHas('company', function ($query) use ($search) {
                $query->where('name', 'ilike', "%{$search}%");
            });
        });
    }
}
