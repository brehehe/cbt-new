<?php

namespace App\Models\Master\CodeSystem\Medication;

use App\Models\Company\Company;
use App\Models\Medication\Medication;
use App\Models\Medication\OneHealth\OneHealthMedication;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationStatus extends Model
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
     * Get all of the medication for the MasterMedicationStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medication(): HasMany
    {
        return $this->hasMany(Medication::class, 'status', 'id');
    }

    /**
     * Get all of the medication for the MasterMedicationStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedication(): HasMany
    {
        return $this->hasMany(OneHealthMedication::class, 'status', 'id');
    }
}
