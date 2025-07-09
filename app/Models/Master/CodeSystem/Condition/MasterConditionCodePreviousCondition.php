<?php

namespace App\Models\Master\CodeSystem\Condition;

use App\Models\Company\Company;
use App\Models\Condition\OneHealth\OneHealthConditionCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterConditionCodePreviousCondition extends Model
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
     * Get all of the OHConditionCode for the Icd10
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHConditionCode(): HasMany
    {
        return $this->hasMany(OneHealthConditionCode::class, 'coding_code', 'code');
    }
}
