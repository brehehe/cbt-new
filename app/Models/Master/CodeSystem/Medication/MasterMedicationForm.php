<?php

namespace App\Models\Master\CodeSystem\Medication;

use App\Models\Company\Company;
use App\Models\Medication\Medication;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterMedicationForm extends Model
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
     * Get all of the medications for the MasterMedicationForm
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class, 'form_coding_code', 'code');
    }

    public function getCodeDisplayAttribute()
    {
        return $this->code . ' - ' . $this->display;
    }
}
