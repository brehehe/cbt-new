<?php

namespace App\Models\Master\Region;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    //
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    /**
     * Get the user that owns the District
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'parent_code', 'code');
    }

    /**
     * Get all of the subDistricts for the District
     */
    public function subDistricts(): HasMany
    {
        return $this->hasMany(SubDistrict::class, 'parent_code', 'code');
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
