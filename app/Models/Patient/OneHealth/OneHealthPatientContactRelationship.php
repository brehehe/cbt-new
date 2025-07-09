<?php

namespace App\Models\Patient\OneHealth;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthPatientContactRelationship extends Model
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
     * Get the OHPatient that owns the OneHealthPatientContactRelationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPatient(): BelongsTo
    {
        return $this->belongsTo(OneHealthPatient::class, 'one_health_patient_id', 'id');
    }

    /**
     * Get all of the contactTelecoms for the OneHealthPatientContactRelationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contactTelecoms(): HasMany
    {
        return $this->hasMany(OneHealthPatientContactTelecom::class, 'one_health_patient_contact_relationship_id', 'id');
    }
}
