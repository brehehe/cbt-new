<?php

namespace App\Models\MedicationRequest;

use App\Models\Company\Company;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageDoseRate;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosagePeriodUnit;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestOrderableDrugForm;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestValueQuantity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationRequestDosageInstruction extends Model
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
     * Get the medicationReq that owns the MedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicationReq(): BelongsTo
    {
        return $this->belongsTo(MedicationRequest::class, 'medication_request_id', 'id');
    }

    /**
     * Get the timingRepeatCode that owns the MedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timingRepeatPeriodUnit(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDosagePeriodUnit::class, 'timing_repeat_period_unit', 'code');
    }

    /**
     * Get the  that owns the MedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function routeTimingCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDosageRoute::class, 'route_coding_code', 'code');
    }

    /**
     * Get the doseTypeCodingCode that owns the MedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doseTypeCodingCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDosageDoseRate::class, 'dose_rate_type_coding_code', 'code');
    }

    /**
     * Get the user that owns the MedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doseRateQuantityOrderable(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestOrderableDrugForm::class, 'dose_rate_quantity_code', 'code');
    }

    /**
     * Get the dosageRateQuantityValue that owns the MedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dosageRateQuantityValue(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestValueQuantity::class, 'dose_rate_quantity_code', 'code');
    }
}
