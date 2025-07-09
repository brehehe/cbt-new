<?php

namespace App\Models\Encounter\OneHealth;

use App\Models\Company\Company;
use App\Models\Location\OneHealth\OneHealthLocation;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthEncounterLocation extends Model
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
     * Get the OHEncounter that owns the OneHealthEncounterLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHEncounter(): BelongsTo
    {
        return $this->belongsTo(OneHealthEncounter::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get the OHLocation that owns the OneHealthEncounterLocation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHLocation(): BelongsTo
    {
        return $this->belongsTo(OneHealthLocation::class, 'one_health_location_id', 'id');
    }
}
