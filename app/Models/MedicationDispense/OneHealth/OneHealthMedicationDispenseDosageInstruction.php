<?php

namespace App\Models\MedicationDispense\OneHealth;

use App\Models\Company\Company;
use App\Models\Master\CodeSystem\MedicationDispanse\MasterMedicationDispenseDosageDoseRate;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthMedicationDispenseDosageInstruction extends Model
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
            $modelCreate->dose_rate_type_coding_display = $modelCreate?->doseRateType?->display;
        });

        static::updating(function ($model) {
            $model->dose_rate_type_coding_display = $model?->doseRateType?->display;
        });
    }

    /**
     * Get the OHMedicationDispense that owns the OneHealthMedicationDispenseDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationDispense(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationDispense::class, 'one_health_medication_dispense_id', 'id');
    }

    /**
     * Get the doseRateType that owns the OneHealthMedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doseRateType(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationDispenseDosageDoseRate::class, 'dose_rate_type_coding_code', 'code');
    }
}
