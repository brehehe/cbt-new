<?php

namespace App\Models\Master\CodeSystem\Location;

use App\Models\Location\OneHealth\OneHealthLocationAddress;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterLocationAddressUse extends Model
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
     * Get all of the OHLocationAddresses for the MasterLocationAddressUse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHLocationAddresses(): HasMany
    {
        return $this->hasMany(OneHealthLocationAddress::class, 'use', 'code');
    }
}
