<?php

namespace App\Models\Icd;

use App\Models\Company\Company;
use App\Models\Condition\OneHealth\OneHealthConditionCode;
use App\Models\Medication\Medication;
use App\Models\MedicationRequest\MedicationRequest;
use App\Models\MedicationRequest\OneHealth\OneHealthMedicationRequestReasonCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Icd10 extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('display', 'like', '%' . $search . '%')
                ->orWhere('version', 'like', '%' . $search . '%');
        }
        return $query;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    /**
     * Get all of the medicationReqs for the Icd10
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqs(): HasMany
    {
        return $this->hasMany(MedicationRequest::class, 'reason_code', 'code');
    }

    /**
     * Get all of the OHMedicationReqReasonCode for the Icd10
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationReqReasonCode(): HasMany
    {
        return $this->hasMany(OneHealthMedicationRequestReasonCode::class, 'coding_code', 'code');
    }

    /**
     * Get all of the OHConditionCode for the Icd10
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHConditionCode(): HasMany
    {
        return $this->hasMany(OneHealthConditionCode::class, 'coding_code', 'code');
    }
}
