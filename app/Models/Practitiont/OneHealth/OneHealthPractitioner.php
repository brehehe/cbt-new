<?php

namespace App\Models\Practitiont\OneHealth;

use App\Models\Company\Company;
use App\Models\Encounter\Onehealth\OneHealthEnconterParticipant;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispense;
use App\Models\Practitiont\Practitioner;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthPractitioner extends Model
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
        });
    }

    /**
     * Get the practitiont that owns the OneHealthPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner(): BelongsTo
    {
        return $this->belongsTo(Practitioner::class, 'practitioner_id', 'id');
    }

    /**
     * Get all of the OHPractitiontIdentifiers for the OneHealthPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHPractitiontIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthPractitiontIdentifier::class, 'one_health_practitiont_id', 'id');
    }

    /**
     * Get the OHPractitiont associated with the OneHealthPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHPractitiontAddress(): HasOne
    {
        return $this->hasOne(OneHealthPractitiontAddress::class, 'one_health_practitiont_id', 'id');
    }

    /**
     * Get all of the OHPractitiontQualificationCodeCodings for the OneHealthPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHPractitiontQualificationCodeCodings(): HasMany
    {
        return $this->hasMany(OneHealthPractitiontQualificationCodeCoding::class, 'one_health_practitiont_id', 'id');
    }

    /**
     * Get all of the OHPractitiontQualificationIdentifiers for the OneHealthPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHPractitiontQualificationIdentifiers(): HasMany
    {
        return $this->hasMany(OneHealthPractitiontQualificationIndentifier::class, 'one_health_practitiont_id', 'id');
    }

    /**
     * Get all of the OHEncounterParticipants for the OneHealthPractitiont
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHEncounterParticipants(): HasMany
    {
        return $this->hasMany(OneHealthEnconterParticipant::class, 'one_health_practitiont_id', 'id');
    }

    /**
     * Get all of the OHMedicationDispenses for the OneHealthPractitioner
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenses(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispense::class, 'one_health_practitioner_id', 'id');
    }
}
