<?php

namespace App\Models\Defecta;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\Product\Product;
use App\Models\Product\ProductStock;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Defecta extends Model
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function productStock() {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = "%$term%";

        $query->where(function ($query) use ($term) {
            $query->where('minimum_stock', 'ilike', $term)
                ->orWhereHas('product', function ($query) use ($term) {
                    $query->where('name', 'ilike', $term)
                        ->orWhere('sku_number', 'ilike', $term);
                })
                ->orWhereHas('branch', function ($query) use ($term) {
                    $query->where('name', 'ilike', $term);
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
