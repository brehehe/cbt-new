<?php

namespace App\Models\Master\CodeSystem\MedicationRequest;

use App\Models\Company\Company;
use App\Models\MedicationRequest\MedicationRequestDosageInstruction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationRequestDosagePeriodUnit extends Model
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
     * Get all of the medicationReqDosageInstructions for the MasterMedicationRequestDosagePeriodUnit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqDosageInstructions(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageInstruction::class, 'timing_repeat_period_unit', 'code');
    }
}
