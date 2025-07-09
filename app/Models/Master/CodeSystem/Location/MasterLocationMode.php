<?php

namespace App\Models\Master\CodeSystem\Location;

use App\Models\Location\OneHealth\OneHealthLocation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterLocationMode extends Model
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
     * Get all of the OHLocations for the MasterLocationMode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHLocations(): HasMany
    {
        return $this->hasMany(OneHealthLocation::class, 'one_health_location_id', 'id');
    }
}
