<?php

namespace App\Models\MedicationDispense\OneHealth;

use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Patient\OneHealth\OneHealthPatient;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Location\OneHealth\OneHealthLocation;
use App\Models\MedicationDispense\MedicationDispense;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Company\OneHealth\OneHealthOrganization;
use App\Models\Medication\OneHealth\OneHealthMedication;
use App\Models\Practitiont\OneHealth\OneHealthPractitioner;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispenseCategory;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispensePerformer;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispenseIdentifier;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseStatus;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseDaysSupply;

class OneHealthMedicationDispense extends Model
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
            $modelCreate->createOHIdentifier();
            $modelCreate->day_unit = $modelCreate?->daysCode?->display;
        });

        static::saved(function ($model) {
            // Force commit any pending transactions before proceeding
            $initialTransactionLevel = DB::transactionLevel();
            if ($initialTransactionLevel > 0) {

                while (DB::transactionLevel() > 0) {
                    DB::commit();
                }
            }

            $model->updateQuietly([
                'day_unit' => $model?->daysCode?->display
            ]);
        });
    }

    /**
     * Get the medicationDispense that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicationDispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispense::class, 'medication_dispense_id', 'id');
    }

    /**
     * Get the OHOrganization that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the OHLocation that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHLocation(): BelongsTo
    {
        return $this->belongsTo(OneHealthLocation::class, 'one_health_location_id', 'id');
    }

    /**
     * Get the OHPatient that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPatient(): BelongsTo
    {
        return $this->belongsTo(OneHealthPatient::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get the OHPractitioner that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPractitioner(): BelongsTo
    {
        return $this->belongsTo(OneHealthPractitioner::class, 'one_health_practitioner_id', 'id');
    }

    /**
     * Get the OHEncounter that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHEncounter(): BelongsTo
    {
        return $this->belongsTo(OneHealthEncounter::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get the OHMedication that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedication(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedication::class, 'one_health_medication_id', 'id');
    }

    /**
     * Get the OHMedicationReq that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationReq(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationRequest::class, 'one_health_medication_request_id', 'id');
    }

    /**
     * Get the status that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationDispenseStatus::class, 'status', 'code');
    }

    /**
     * Get all of the OHMedicationDispenseIdentifiers for the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenseIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispenseIdentifier::class, 'one_health_medication_dispense_id', 'id');
    }

    function createOHIdentifier()
    {
        $this->OHMedicationDispenseIdentifiers()->create([
            'one_health_organization_id' => $this->medicationDispense?->company?->OHOrganization?->id,
            'value'                      => $this->id,
        ]);
    }

    /**
     * Get the OHMedicationDispenseCategory associated with the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHMedicationDispenseCategory(): HasOne
    {
        return $this->hasOne(OneHealthMedicationDispenseCategory::class, 'one_health_medication_dispense_id', 'id');
    }

    /**
     * Get the OHMedicationDispensePerformer associated with the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHMedicationDispensePerformer(): HasOne
    {
        return $this->hasOne(OneHealthMedicationDispensePerformer::class, 'one_health_medication_dispense_id', 'id');
    }

    /**
     * Get the daysCode that owns the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function daysCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationDispenseDaysSupply::class, 'day_code', 'code');
    }

    /**
     * Get all of the OHMedicationDosageInstructions for the OneHealthMedicationDispense
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDosageInstructions(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispenseDosageInstruction::class, 'one_health_medication_dispense_id', 'id');
    }

}
