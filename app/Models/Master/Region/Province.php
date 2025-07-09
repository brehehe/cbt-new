<?php

namespace App\Models\Master\Region;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    //
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    /**
     * Get all of the cities for the Province
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'parent_code', 'code');
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
