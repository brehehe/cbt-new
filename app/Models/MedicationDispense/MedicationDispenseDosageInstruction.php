<?php

namespace App\Models\MedicationDispense;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MedicationDispenseDosageInstruction extends Model
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
     * Get the medicationDispense that owns the MedicationDispenseDosageInstruction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicationDispense(): BelongsTo
    {
        return $this->belongsTo(MedicationDispenseDosageInstruction::class, 'medication_dispense_id', 'id');
    }
}
