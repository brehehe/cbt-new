<?php

namespace App\Models\MedicationDispense\OneHealth;

use App\Models\Company\Company;
use App\Models\Company\OneHealth\OneHealthOrganization;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseIdentifierUse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthMedicationDispenseIdentifier extends Model
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
     * Get the OHMedicationDispense that owns the OneHealthMedicationDispenseIdentifier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationDispense(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationDispense::class, 'one_health_medication_dispense_id', 'id');
    }

    /**
     * Get the OHOrganization that owns the OneHealthMedicationDispenseIdentifier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the user that owns the OneHealthMedicationDispenseIdentifier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationDispenseIdentifierUse::class, 'use', 'code');
    }
}
