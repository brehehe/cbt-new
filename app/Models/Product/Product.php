<?php

namespace App\Models\Product;

use App\Models\Company\Company;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function scopeSearch($query, $search)
    {
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('sku_number', 'ilike', "%{$search}%")
                    ->orWhere('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('unit', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('productCategory', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('productFactory', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('productRack', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('productType', function ($q) use ($search) {
                        $q->where('name', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('productStock', function ($q) use ($search) {
                        $q->where('quantity', 'ilike', "%{$search}%")
                            ->orWhere('quantity_lock', 'ilike', "%{$search}%")
                            ->orWhere('quantity_real', 'ilike', "%{$search}%");
                    })
                    ->orWhereHas('productPrice', function ($q) use ($search) {
                        $q->where('price', 'ilike', "%{$search}%")
                            ->orWhere('hpp_average', 'ilike', "%{$search}%")
                            ->orWhere('recipe', 'ilike', "%{$search}%");
                    });
            });
        }

        return $query;
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function productStock()
    {
        return $this->hasOne(ProductStock::class);
    }

    public function productStockHistories()
    {
        return $this->hasMany(ProductStockHistory::class);
    }

    public function productPrice()
    {
        return $this->hasOne(ProductPrice::class);
    }

    public function productPriceHistories()
    {
        return $this->hasMany(ProductPriceHistory::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function productFactory()
    {
        return $this->belongsTo(ProductFactory::class, 'product_factory_id');
    }
    public function productRack()
    {
        return $this->belongsTo(ProductRack::class, 'product_rack_id');
    }
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function productPackages()
    {
        return $this->hasMany(ProductPackage::class);
    }

    public function getNameSkuAttribute()
    {
        $sku_number = $this->sku_number ? $this->sku_number . ' - ' : null;
        $name = $this->name;

        return $sku_number . $name;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id();
        });
    }
}
