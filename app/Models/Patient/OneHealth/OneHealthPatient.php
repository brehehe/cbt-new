<?php

namespace App\Models\Patient\OneHealth;

use App\Models\Company\Company;
use App\Models\Condition\OneHealth\OneHealthCondition;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender;
use App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispense;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest;
use App\Models\Patient\Patient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Patient\OneHealth\OneHealthPatientIdentifier;
use Exception;
use Throwable;

class OneHealthPatient extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'birth_date' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;

            $modelCreate->marital_status_coding_display = $modelCreate?->maritalStatus?->display;
        });

        static::saved(function ($model) {
            try {
                // Force commit any pending transactions before proceeding
                $initialTransactionLevel = DB::transactionLevel();
                if ($initialTransactionLevel > 0) {

                    while (DB::transactionLevel() > 0) {
                        DB::commit();
                    }
                }

                $model->setMaritalStatus();
            } catch (Exception | Throwable $th) {
                $error = [
                    'message' => $th->getMessage(),
                    'file'    => $th->getFile(),
                    'line'    => $th->getLine(),
                ];

                Log::error('Ada kesalahan saat boot OneHealthPatient', $error);
            }
        });
    }

    /**
     * Get the patient that owns the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Get the gender that owns the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(MasterPatientAdministrativeGender::class, 'gender', 'code');
    }

    /**
     * Get the maritalStatus that owns the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MasterPatientMaritalStatus::class, 'marital_status_coding_code', 'code');
    }

    function setMaritalStatus()
    {
        $this->updateQuietly([
            'marital_status_coding_display' => $this?->maritalStatus?->display
        ]);
    }

    /**
     * Get the OHPatientIdentifiers associated with the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHPatientIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthPatientIdentifier::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get the OHPatientAddress associated with the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHPatientAddress(): HasOne
    {
        return $this->hasOne(OneHealthPatientAddress::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get the OHPatientContactRelationship associated with the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHPatientContactRelationship(): HasOne
    {
        return $this->hasOne(OneHealthPatientContactRelationship::class, 'one_health_patient_id', 'id');
    }

     /**
     * Get all of the OHEncounter for the OneHealthOrganization
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHEncounter(): HasMany
    {
        return $this->hasMany(OneHealthEncounter::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get all of the OHMedicationReqs for the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqs(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequest::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get all of the OHMedicationDispenses for the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenses(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispense::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get all of the OHConditions for the OneHealthPatient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHConditions(): HasMany
    {
        return $this->hasMany(OneHealthCondition::class, 'one_health_patient_id', 'id');
    }
}
