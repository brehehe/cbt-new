<?php

namespace App\Models\Master\CodeSystem\MedicationRequest;

use App\Models\Company\Company;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\MedicationRequest\MedicationRequestDispenseRequest;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDispenseRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationRequestDispenseInterval extends Model
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
     * Get all of the  for the MasterMedicationRequestDispanseInterval
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqDispenseRequest(): HasMany
    {
        return $this->hasMany(MedicationRequestDispenseRequest::class, 'dispense_interval_code', 'code');
    }

    /**
     * Get all of the medi for the MasterMedicationRequestDispenseInterval
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHedicationReqDispanseRequest(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequestDispenseRequest::class, 'dispense_interval_code', 'code');
    }
}
