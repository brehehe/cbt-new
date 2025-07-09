<?php

namespace App\Models\Patient\OneHealth;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthPatientAddressExtension extends Model
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
     * Get the OHPatientAddress that owns the OneHealthPatientAddressExtension
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPatientAddress(): BelongsTo
    {
        return $this->belongsTo(OneHealthPatientAddress::class, 'one_health_patient_address_id', 'id');
    }
}
