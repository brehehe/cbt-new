<?php

namespace App\Models\Condition;

use App\Models\Company\Company;
use App\Models\Condition\OneHealth\OneHealthCondition;
use App\Models\Encounter\Encounter;
use App\Models\Patient\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condition extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'notes' => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder                  = static::max('order');
            $modelCreate->order         = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->recorded_date = Carbon::now();
        });
    }

    /**
     * Get the OHCondition associated with the Condition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHCondition(): HasOne
    {
        return $this->hasOne(OneHealthCondition::class, 'condition_id', 'id');
    }

    /**
     * Get the patient that owns the Condition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'id');
    }

    /**
     * Get the encounter that owns the Condition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'id');
    }
}
