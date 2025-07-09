<?php

namespace App\Models\Master\CodeSystem\MedicationRequest;

use App\Models\Company\Company;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\MedicationRequest\MedicationRequestDosageInstruction;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDosageInstruction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationRequestDosageDoseRate extends Model
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
     * Get all of the medicationReqs for the MasterMedicationRequestDosageDoseRate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqDosageInstructions(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageInstruction::class, 'dose_rate_type_coding_code', 'code');
    }

    /**
     * Get all of the  for the MasterMedicationRequestDosageDoseRate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqDosageInstructions(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequestDosageInstruction::class, 'dose_rate_type_coding_code', 'code');
    }
}
