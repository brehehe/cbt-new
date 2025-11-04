<?php

namespace App\Models\Master\Region;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    //
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    /**
     * Get the user that owns the City
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'parent_code', 'code');
    }

    /**
     * Get all of the comments for the City
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class, 'parent_code', 'code');
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
