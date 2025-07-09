<?php

namespace App\Models\Master\CodeSystem\MedicationDispanse;

use App\Models\Company\Company;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispenseDosageInstruction;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationDispenseDosageDoseRate extends Model
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
     * Get all of the OHMedicationDispenseDosageInstructions for the MasterMedicationDispenseDosageDoseRate
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenseDosageInstructions(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispenseDosageInstruction::class, 'dose_rate_type_coding_code', 'code');
    }
}
