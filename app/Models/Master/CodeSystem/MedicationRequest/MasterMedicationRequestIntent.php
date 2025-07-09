<?php

namespace App\Models\Master\CodeSystem\MedicationRequest;

use App\Models\Company\Company;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationRequestIntent extends Model
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
     * Get all of the medicationReq for the MasterMedicationRequestIntent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqs(): HasMany
    {
        return $this->hasMany(MedicationRequest::class, 'intent', 'code');
    }

    /**
     * Get all of the OHMedicationReqs for the MasterMedicationRequestIntent
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqs(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequest::class, 'intent', 'code');
    }
}
