<?php

namespace App\Models\MedicationDispense;

use App\Models\Company\Company;
use App\Models\Encounter\Encounter;
use App\Models\Location\Location;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseStatus;
use App\Models\Medication\Medication;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispense;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\Patient\Patient;
use App\Models\Practitiont\Practitioner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationDispense extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'when_prepare'   => 'date',
        'when_hand_over' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the location that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    /**
     * Get the practitioner that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class, 'practitioner_id', 'id');
    }

    /**
     * Get the encounter that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'id');
    }

    /**
     * Get the medication that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class, 'medication_id', 'id');
    }

    /**
     * Get the medicationReq that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicationReq(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'medication_request_id', 'id');
    }

    /**
     * Get the patient that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    public function performerable()
    {
        return $this->morphTo();
    }

    /**
     * Get all of the medicationDispenseInstructions for the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispenseDosageInstructions(): HasMany
    {
        return $this->hasMany(MedicationDispenseDosageInstruction::class, 'medication_dispense_id', 'id');
    }

    /**
     * Get the OHMedicationDispense associated with the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHMedicationDispense(): HasOne
    {
        return $this->hasOne(OneHealthMedicationDispense::class, 'medication_dispense_id', 'id');
    }

    /**
     * Get the status that owns the MedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationDispenseStatus::class, 'status', 'code');
    }
}
