<?php

namespace App\Models\Master\CodeSystem\Patient;

use App\Models\Company\Company;
use App\Models\Patient\PatientContactRelationship;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPatientContactRelationship extends Model
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
     * Get all of the patientContactRelationships for the MasterPatientContactRelationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function patientContactRelationships(): HasMany
    {
        return $this->hasMany(PatientContactRelationship::class, 'relationship_coding_code', 'code');
    }
}
