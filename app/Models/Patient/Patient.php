<?php

namespace App\Models\Patient;

use App\Models\Condition\Condition;
use App\Models\Encounter\Encounter;
use App\Models\Master\CodeSystem\Patient\MasterPatientAdministrativeGender;
use App\Models\Master\CodeSystem\Patient\MasterPatientMaritalStatus;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Patient\PatientAddress;
use App\Models\Patient\PatientContact;
use Illuminate\Database\Eloquent\Model;
use App\Models\Patient\PatientIdentifier;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Patient\PatientRelationshipContact;
use App\Traits\Encryption;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;
use Throwable;

class Patient extends Model
{
    use HasFactory, HasUuids, SoftDeletes, Encryption {
        Encryption::encrypted as Encrypted;
        Encryption::decrypted as Decrypted;
    }

    protected $guarded = ['id'];

    protected $casts = [
        'identity_card'      => 'encrypted',
        'passport_number'    => 'encrypted',
        'family_card_number' => 'encrypted',
        'birth_date'         => 'date',
        'deceased_date'      => 'date',
    ];

    /**
     * Get the user that owns the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
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

                // $model->updateOrCreateOHPatient();
            } catch (Exception | Throwable $th) {
                $error = [
                    'message' => $th->getMessage(),
                    'file'    => $th->getFile(),
                    'line'    => $th->getLine(),
                ];

                Log::error('Ada kesalahan saat boot Patient', $error);
            }
        });
    }

    /**
     * Get all of the patientCompany for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function patientCompany(): HasMany
    {
        return $this->hasMany(PatientCompany::class, 'patient_id', 'id');
    }

    /**
     * Get the gender that owns the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(MasterPatientAdministrativeGender::class, 'gender', 'code');
    }

    /**
     * Get the maritalStatus that owns the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function maritalStatus(): BelongsTo
    {
        return $this->belongsTo(MasterPatientMaritalStatus::class, 'marital_status', 'code');
    }

    /**
     * Get the OHPatient associated with the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHPatient(): HasOne
    {
        return $this->hasOne(OneHealthPatient::class, 'patient_id', 'id');
    }

    /**
     * Get the patientDetail associated with the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function patientDetail(): HasOne
    {
        return $this->hasOne(PatientDetail::class, 'patient_id', 'id');
    }

    /**
     * Get all of the patientContacts for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function patientContactRelationship(): hasOne
    {
        return $this->hasOne(PatientContactRelationship::class, 'patient_id', 'id');
    }

    public static function findByIdentityCard($identity_card)
    {
        $model = new Patient();
        $found = null;

        static::chunk(100, function ($users) use ($identity_card, &$found, $model) {
            foreach ($users as $user) {
                if ($model->decrypted($user->identity_card) === $identity_card) {
                    $found = $user;
                    return false;
                }
            }
        });

        return $found;
    }

    /**
     * Get all of the encounters for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class, 'patient_id', 'id');
    }

    /**
     * Get all of the medicationReqs for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqs(): HasMany
    {
        return $this->hasMany(MedicationRequest::class, 'patient_id', 'id');
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
     * Get all of the medicationDispenses for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationDispenses(): HasMany
    {
        return $this->hasMany(MedicationDispense::class, 'patient_id', 'id');
    }

    /**
     * Get all of the conditions for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class, 'patient_id', 'id');
    }
}
