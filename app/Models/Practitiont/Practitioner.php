<?php

namespace App\Models\Practitiont;

use App\Models\Company\Company;
use App\Models\Encounter\EncounterPractitiont;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\Practitiont\OneHealth\OneHealthPractitioner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Practitioner extends Model
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
     * Get the OHPractitiont associated with the Practitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHPractitioner(): HasOne
    {
        return $this->hasOne(OneHealthPractitioner::class, 'practitioner_id', 'id');
    }

    /**
     * Get all of the practitionts for the Practitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounterPractitionts(): HasMany
    {
        return $this->hasMany(EncounterPractitiont::class, 'practitioner_id', 'id');
    }

    public function requestMedicationReqs()
    {
        return $this->morphMany(MedicationRequest::class, 'requestable');
    }

    public function performerMedicationDispenses()
    {
        return $this->morphMany(MedicationDispense::class, 'performerable');
    }

    /**
     * Get all of the medicationDispenses for the Practitioner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationDispenses(): HasMany
    {
        return $this->hasMany(MedicationDispense::class, 'practitioner_id', 'id');
    }
}
