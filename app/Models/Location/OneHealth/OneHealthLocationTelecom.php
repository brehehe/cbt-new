<?php

namespace App\Models\Location\OneHealth;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthLocationTelecom extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    /**
     * Get the OHLocation that owns the OneHealthLocationTelecom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHLocation(): BelongsTo
    {
        return $this->belongsTo(OneHealthLocation::class, 'one_health_location_id', 'id');
    }
}
