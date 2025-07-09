<?php

namespace App\Models\MedicationRequest;

use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\Icd\Icd10;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispanseInterval;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestIntent;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestPriority;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestStatus;
use App\Models\Medication\Medication;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest;
use App\Models\Patient\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationRequest extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'author_on' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder              = static::max('order');
            $modelCreate->order     = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->author_on = Carbon::now();
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the OHMedicationRequests associated with the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHMedicationReq(): HasOne
    {
        return $this->hasOne(OneHealthMedicationRequest::class, 'medication_request_id', 'id');
    }

    /**
     * Get the encounter that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'id');
    }

    /**
     * Get the status that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestStatus::class, 'status', 'code');
    }

    /**
     * Get the intent that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function intent(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestIntent::class, 'intent', 'code');
    }

    /**
     * Get the priority that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestPriority::class, 'priority', 'code');
    }

    /**
     * Get the medication that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class, 'medication_id', 'id');
    }

    /**
     * Get the encounter that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function requestable()
    {
        return $this->morphTo();
    }

    /**
     * Get the reasonCode that owns the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reasonCode(): BelongsTo
    {
        return $this->belongsTo(Icd10::class, 'reason_code', 'code');
    }

    /**
     * Get all of the dosageInstructions for the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dosageInstructions(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageInstruction::class, 'medication_request_id', 'id');
    }

    /**
     * Get the medicationReqDispanse associated with the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function medicationReqDispense(): HasOne
    {
        return $this->hasOne(MedicationRequestDispenseRequest::class, 'medication_request_id', 'id');
    }

    /**
     * Get all of the medicationDispenses for the MedicationRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationDispenses(): HasMany
    {
        return $this->hasMany(MedicationDispense::class, 'medication_request_id', 'id');
    }
}
