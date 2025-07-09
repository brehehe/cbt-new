<?php

namespace App\Models\Encounter;

use App\Models\Company\Company;
use App\Models\Condition\Condition;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterStatus;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\Patient\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Encounter extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->period_start = Carbon::now();

            //create status history
            $modelCreate->statusHistories()->create([
                'status'       => $modelCreate->status,
                'period_start' => Carbon::now(),
                'period_end'   => Carbon::now(),
            ]);

            //create class history
            $modelCreate->classHistories()->create([
                'class_code'   => $modelCreate->class_code,
                'period_start' => Carbon::now(),
                'period_end'   => Carbon::now(),
            ]);
        });

        static::updating(function ($model) {
            $model->createStatusHistory();
            $model->createClassHistory();
        });
    }

    /**
     * Get the patient that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Get the location that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    /**
     * Get all of the statusHistory for the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(EncounterStatusHistory::class, 'encounter_id', 'id');
    }

    function createStatusHistory()
    {
        if ($this->isDirty('status')) {
            $this->statusHistories()->create([
                'status'       => $this->status,
                'period_start' => Carbon::now(),
                'period_end'   => Carbon::now(),
            ]);
        }
    }

    /**
     * Get the status that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterEncounterStatus::class, 'status', 'code');
    }

    /**
     * Get all of the actClasses for the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classHistories(): HasMany
    {
        return $this->hasMany(EncounterClassHistory::class, 'encounter_id', 'id');
    }

    function createClassHistory()
    {
        if ($this->isDirty('class_code')) {
            $this->classHistories()->create([
                'class_code'   => $this->class_code,
                'period_start' => Carbon::now(),
                'period_end'   => Carbon::now(),
            ]);
        }
    }

    /**
     * Get the actCode that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classCode(): BelongsTo
    {
        return $this->belongsTo(MasterEncounterActCode::class, 'class_code', 'code');
    }

    /**
     * Get the parcicipant associated with the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function encounterPractitiont(): HasOne
    {
        return $this->hasOne(EncounterPractitiont::class, 'encounter_id', 'id');
    }

    /**
     * Get the OHEncounter associated with the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHEncounter(): HasOne
    {
        return $this->hasOne(OneHealthEncounter::class, 'encounter_id', 'id');
    }

    /**
     * Get all of the medicationRequests for the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqs(): HasMany
    {
        return $this->hasMany(MedicationRequest::class, 'encounter_id', 'id');
    }

    /**
     * Get the encounterConditon associated with the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function encounterConditon(): HasOne
    {
        return $this->hasOne(EncounterCondition::class, 'encounter_id', 'id');
    }

    /**
     * Get all of the medicationDispenses for the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationDispenses(): HasMany
    {
        return $this->hasMany(MedicationDispense::class, 'encounter_id', 'id');
    }

    /**
     * Get all of the conditions for the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'encounter_id', 'id');
    }
}
