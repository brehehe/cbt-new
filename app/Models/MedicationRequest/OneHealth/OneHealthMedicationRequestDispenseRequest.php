<?php

namespace App\Models\MedicationRequest\OneHealth;

use App\Models\Company\Company;
use App\Models\Company\OneHealth\OneHealthOrganization;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseExpect;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDispenseInterval;
use App\Models\MedicationRequest\MedicationRequestDispenseRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthMedicationRequestDispenseRequest extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'validity_start' => 'date',
        'validity_end'   => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->dispense_interval_unit = $modelCreate?->dispenseIntervalCode?->display;
            $modelCreate->expect_unit = $modelCreate?->expectCode?->display;
        });

        static::updating(function ($model) {
            $model->dispense_interval_unit = $model?->dispenseIntervalCode?->display;
            $model->expect_unit            = $model?->expectCode?->display;
        });
    }

    /**
     * Get the OHMedictionReq that owns the OneHealthMedicationRequestDispenseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedictionReq(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationRequest::class, 'one_health_medication_request_id', 'id');
    }

    /**
     * Get the OHOrganization that owns the OneHealthMedicationRequestDispenseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the dispenseIntervalCode that owns the OneHealthMedicationRequestDispenseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispenseIntervalCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDispenseInterval::class, 'dispense_interval_code', 'code');
    }

    /**
     * Get the medicationReqDispenseRequest that owns the OneHealthMedicationRequestDispenseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicationReqDispenseRequest(): BelongsTo
    {
        return $this->belongsTo(MedicationRequestDispenseRequest::class, 'medication_request_dispense_request_id', 'id');
    }

    /**
     * Get the user that owns the OneHealthMedicationRequestDispenseRequest
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function expectCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestDispenseExpect::class, 'expect_code', 'code');
    }
}
