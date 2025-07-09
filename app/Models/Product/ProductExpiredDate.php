<?php

namespace App\Models\Product;

use App\Models\Branch\Branch;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductExpiredDate extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
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
