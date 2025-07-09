<?php

namespace App\Models\Master\CodeSystem\MedicationRequest;

use App\Models\Company\Company;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationRequestCourseOfTherapy extends Model
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
     * Get the OHMedicationReq that owns the MasterMedicationRequestCourseOfTherapy
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationReq(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationRequest::class, 'coding_code', 'code');
    }
}
