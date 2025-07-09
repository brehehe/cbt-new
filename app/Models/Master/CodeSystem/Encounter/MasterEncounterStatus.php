<?php

namespace App\Models\Master\CodeSystem\Encounter;

use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\Encounter\EncounterStatusHistory;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterEncounterStatus extends Model
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
     * Get all of the encounter for the MasterEncounterStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class, 'status', 'code');
    }

    /**
     * Get all of the encounterStatusHistories for the MasterEncounterStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounterStatusHistories(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class, 'status', 'code');
    }

    /**
     * Get all of the OHEncounter for the MasterEncounterStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHEncounters(): HasMany
    {
        return $this->hasMany(OneHealthEncounter::class, 'status', 'code');
    }
}
