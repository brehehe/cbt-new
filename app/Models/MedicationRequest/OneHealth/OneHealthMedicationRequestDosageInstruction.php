<?php

namespace App\Models\MedicationRequest\OneHealth;

use App\Models\Company\Company;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageDoseRate;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthMedicationRequestDosageInstruction extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder                                  = static::max('order');
            $modelCreate->order                         = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->route_coding_display          = $modelCreate?->routeCodingCode?->display;
            $modelCreate->dose_rate_type_coding_display = $modelCreate?->doseRateType?->display;
        });

        static::updating(function ($model) {
            $model->route_coding_display          = $model?->routeCodingCode?->display;
            $model->dose_rate_type_coding_display = $model?->doseRateType?->display;
        });
    }

    /**
     * Get the OHMedicationreq that owns the OneHealthMedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationReq(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationRequest::class, 'one_health_medication_request_id', 'id');
    }

    /**
     * Get the routeCodingCode that owns the OneHealthMedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function routeCodingCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDosageRoute::class, 'route_coding_code', 'code');
    }

    /**
     * Get the doseRateType that owns the OneHealthMedicationRequestDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doseRateType(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDosageDoseRate::class, 'dose_rate_type_coding_code', 'code');
    }
}
