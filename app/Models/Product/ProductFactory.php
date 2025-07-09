<?php

namespace App\Models\Product;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFactory extends Model
{
    //
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'ilike', '%'.$search.'%')
                ->orWhere('description', 'ilike', '%'.$search.'%');
        }

        return $query;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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
