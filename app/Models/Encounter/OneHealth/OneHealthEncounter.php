<?php

namespace App\Models\Encounter\OneHealth;

use App\Models\Company\Company;
use App\Models\Company\OneHealth\OneHealthOrganization;
use App\Models\Condition\OneHealth\OneHealthCondition;
use App\Models\Encounter\Encounter;
use App\Models\Encounter\Onehealth\OneHealthEnconterParticipant;
use App\Models\Location\OneHealth\OneHealthLocation;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispense;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Practitiont\OneHealth\OneHealthPractitiont;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthEncounter extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;

            $modelCreate->setAutomic();
        });

        static::saved(function ($model) {
            $model->setAutomic();
        });
    }

    function setAutomic ()
    {
        $this->class_display   = $this->classCode->display;
        $this->subject_display = $this->OHPatient->name_text;
        $this->period_start    = $this->encounter->period_start;
        $this->period_end      = $this->encounter->period_end;
    }

    /**
     * Get the encounter that owns the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'id');
    }

    /**
     * Get the OHCounterIdentifier associated with the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHEncounterIdentifier(): HasOne
    {
        return $this->hasOne(OneHealthEncounterIdentifier::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get the OHOrganization that owns the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the OHPatient that owns the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPatient(): BelongsTo
    {
        return $this->belongsTo(OneHealthPatient::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get the status that owns the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterEncounterStatus::class, 'status', 'code');
    }

    /**
     * Get the classCode that owns the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classCode(): BelongsTo
    {
        return $this->belongsTo(MasterEncounterActCode::class, 'class_code', 'code');
    }

    /**
     * Get all of the OHEncounterParticipants for the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHEncounterParticipants(): HasMany
    {
        return $this->hasMany(OneHealthEnconterParticipant::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get all of the OHEncounterLocations for the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHEncounterLocations(): HasMany
    {
        return $this->hasMany(OneHealthEncounterLocation::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get all of the OHMedicationReqs for the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqs(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequest::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get all of the OHMedicationDispenses for the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenses(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispense::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get all of the OHConditions for the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHConditions(): HasMany
    {
        return $this->hasMany(OneHealthCondition::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get the OHEncounterHospitalDischarge associated with the OneHealthEncounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHEncounterHospitalDischarge(): HasOne
    {
        return $this->hasOne(OneHealthEncounterHospitalDischarge::class, 'one_health_encounter_id', 'id');
    }
}
