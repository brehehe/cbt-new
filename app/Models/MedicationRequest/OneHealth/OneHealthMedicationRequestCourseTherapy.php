<?php

namespace App\Models\MedicationRequest\OneHealth;

use App\Models\Company\Company;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestCourseOfTherapy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthMedicationRequestCourseTherapy extends Model
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
            $modelCreate->coding_display = $modelCreate?->codingCode?->display;
        });

        static::updating(function ($model) {
            $model->coding_display = $model->codingCode?->display;
        });
    }

    /**
     * Get the OHMedicationReq that owns the OneHealthMedicationRequestCourseTherapy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationReq(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationRequest::class, 'one_health_medication_request_id', 'id');
    }

    /**
     * Get the codingCode that owns the OneHealthMedicationRequestCourseTherapy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function codingCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationRequestCourseOfTherapy::class, 'coding_code', 'code');
    }
}
