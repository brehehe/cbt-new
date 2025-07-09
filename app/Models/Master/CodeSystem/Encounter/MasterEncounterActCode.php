<?php

namespace App\Models\Master\CodeSystem\Encounter;

use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\Encounter\EncounterClassHistory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterEncounterActCode extends Model
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
     * Get all of the encounter for the MasterEncounterActCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class, 'class_code', 'code');
    }

    /**
     * Get all of the classHistories for the MasterEncounterActCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classHistories(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class, 'class_code', 'code');
    }
}
