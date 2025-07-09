<?php

namespace App\Models\MedicationRequest\OneHealth;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthMedicationRequestRequester extends Model
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
     * Get the OHMedicationReq that owns the OneHealthMedicationRequestRequester
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHMedicationReq(): BelongsTo
    {
        return $this->belongsTo(OneHealthMedicationRequest::class, 'one_health_medication_request_id', 'id');
    }
}
