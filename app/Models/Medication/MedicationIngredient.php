<?php

namespace App\Models\Medication;

use App\Models\Company\Company;
use App\Models\Medication\OneHealth\OneHealthMedicationIngredient;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationIngredient extends Model
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
     * Get the medication that owns the MedicationIngredient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class, 'medication_id', 'id');
    }

    /**
     * Get the OHMedictionIngredient associated with the MedicationIngredient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHMedictionIngredient(): HasOne
    {
        return $this->hasOne(OneHealthMedicationIngredient::class, 'medication_ingredient_id', 'id');
    }
}
