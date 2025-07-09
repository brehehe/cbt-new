<?php

namespace App\Models\Master\CodeSystem\MedicationDispanse;

use App\Models\Company\Company;
use App\Models\MedicationDispense\OneHealth\OneHealthMedicationDispense;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationDispenseCategory extends Model
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
     * Get all of the OHMedicationDispenseCategory for the MasterMedicationDispenseCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHMedicationDispenseCategory(): HasMany
    {
        return $this->hasMany(OneHealthMedicationDispense::class, 'coding_code', 'code');
    }
}
