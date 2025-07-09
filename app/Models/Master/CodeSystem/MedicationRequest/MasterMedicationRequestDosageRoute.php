<?php

namespace App\Models\Master\CodeSystem\MedicationRequest;

use App\Models\Company\Company;
use App\Models\MedicationRequest\MedicationRequestDosageInstruction;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestDosageInstruction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationRequestDosageRoute extends Model
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
     * Get all of the medicationReqDosageInstructions for the MasterMedicationRequestDosageRoute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqDosageInstructions(): HasMany
    {
        return $this->hasMany(MedicationRequestDosageInstruction::class, 'route_coding_code', 'code');
    }

    /**
     * Get all of the OHMedicationReqDosageInstructions for the MasterMedicationRequestDosageRoute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqDosageInstructions(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequestDosageInstruction::class, 'route_coding_code', 'code');
    }

    public function getCodeDisplayAttribute(): string
    {
        return $this->code . ' - ' . $this->display;
    }
}
