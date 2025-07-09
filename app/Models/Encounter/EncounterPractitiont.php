<?php

namespace App\Models\Encounter;

use App\Models\Company\Company;
use App\Models\Practitiont\Practitioner;
use App\Models\Practitiont\Practitiont;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncounterPractitiont extends Model
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
     * Get the encounter that owns the EncounterPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'id');
    }

    /**
     * Get the practitiont that owns the EncounterPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class, 'practitioner_id', 'id');
    }


}
