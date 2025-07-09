<?php

namespace App\Models\Product;

use App\Models\Company\Company;
use App\Models\Unit\Unit;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductUnit extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function scopeSearch($query, $term)
    {
        if (! empty($term)) {
            $query->where(function ($query) use ($term) {
                $query->where('quantity', 'ilike', "%{$term}%")
                    ->orWhereHas('product', function ($q) use ($term) {
                        $q->where('name', 'ilike', "%{$term}%")
                            ->orWhere('sku_number', 'ilike', "%{$term}%");
                    })
                    ->orWhereHas('unit', function ($q) use ($term) {
                        $q->where('name', 'ilike', "%{$term}%");
                    })
                    ->orWhereHas('company', function ($q) use ($term) {
                        $q->where('name', 'ilike', "%{$term}%");
                    });
            });
        }

        return $query;
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
