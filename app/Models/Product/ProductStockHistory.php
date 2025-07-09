<?php

namespace App\Models\Product;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStockHistory extends Model
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
    public function productStock()
    {
        return $this->belongsTo(ProductStock::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, $search) {
        return $query
        ->where('code', 'ilike', "%$search%")
        ->orWhere('description', 'ilike', "%$search%")
        ->orWhere('quantity', 'ilike', "%$search%")
        ->orWhere('price', 'ilike', "%$search%")
        ->orWhere('sub_total_price', 'ilike', "%$search%")
        ->whereHas('product', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
            $q->where('sku_number', 'ilike', "%$search%");
        })->orWhereHas('branch', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
        })->orWhereHas('company', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
        })
        ->orWhereHas('user', function ($q) use ($search) {
            $q->where('name', 'ilike', "%$search%");
        });
    }
}
