<?php

namespace App\Models\Condition\OneHealth;

use App\Models\Company\Company;
use App\Models\Company\OneHealth\OneHealthOrganization;
use App\Models\Condition\Condition;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Patient\OneHealth\OneHealthPatient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthCondition extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder                  = static::max('order');
            $modelCreate->order         = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->recorded_date = Carbon::now();
        });
    }

    /**
     * Get the condition that owns the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function condition(): BelongsTo
    {
        return $this->belongsTo(Condition::class, 'condition_id', 'id');
    }

    /**
     * Get the OHOrganization that owns the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the OHPatient that owns the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPatient(): BelongsTo
    {
        return $this->belongsTo(OneHealthPatient::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get the OHEncounter that owns the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHEncounter(): BelongsTo
    {
        return $this->belongsTo(OneHealthEncounter::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get the OHConditionClinicalStatus associated with the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHConditionClinicalStatus(): HasOne
    {
        return $this->hasOne(OneHealthConditionClinicalStatus::class, 'one_health_condition_id', 'id');
    }

    /**
     * Get the OHConditionCategory associated with the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHConditionCategory(): HasOne
    {
        return $this->hasOne(OneHealthConditionCategory::class, 'one_health_condition_id', 'id');
    }

    /**
     * Get the OHConditionCode associated with the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHConditionCode(): HasOne
    {
        return $this->hasOne(OneHealthConditionCode::class, 'one_health_condition_id', 'id');
    }

    /**
     * Get all of the OHConditionNotes for the OneHealthCondition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHConditionNotes(): HasMany
    {
        return $this->hasMany(OneHealthConditionNote::class, 'one_health_condition_id', 'id');
    }
}
