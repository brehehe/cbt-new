<?php

namespace App\Models\Master\CodeSystem\Patient;

use App\Models\Company\Company;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPatientAdministrativeGender extends Model
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
     * Get all of the patient for the MasterPatientAdministrativeGender
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function patient(): HasMany
    {
        return $this->hasMany(Patient::class, 'gender', 'code');
    }

    /**
     * Get all of the OHPatient for the MasterPatientAdministrativeGender
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHPatient(): HasMany
    {
        return $this->hasMany(OneHealthPatient::class, 'gender', 'code');
    }
}
