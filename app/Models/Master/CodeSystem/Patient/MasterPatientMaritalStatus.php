<?php

namespace App\Models\Master\CodeSystem\Patient;

use App\Models\Company\Company;
use App\Models\Patient\OneHealth\OneHealthPatient;
use App\Models\Patient\Patient;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterPatientMaritalStatus extends Model
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
     * Get all of the patient for the MasterPatientMaritalStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function patient(): HasMany
    {
        return $this->hasMany(Patient::class, 'marital_status', 'code');
    }

    /**
     * Get all of the OHPatient for the MasterPatientMaritalStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHPatient(): HasMany
    {
        return $this->hasMany(OneHealthPatient::class, 'marital_status', 'code');
    }

     protected $appends = ['display_ind']; // agar otomatis tampil di toArray()

    public function getDisplayIndAttribute()
    {
        $translations = [
            'Annulled' => 'Dibatalkan',
            'Divorced' => 'Bercerai',
            'Interlocutory' => 'Dalam Proses Perceraian',
            'Legally Separated' => 'Pisah Resmi',
            'Married' => 'Menikah',
            'Polygamous' => 'Poligami',
            'Domestic partner' => 'Pasangan Domestik',
            'unmarried' => 'Belum Menikah',
            'Widowed' => 'Duda/Janda',
        ];

        return $translations[$this->display] ?? $this->display;
    }
}
