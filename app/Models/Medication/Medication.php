<?php

namespace App\Models\Medication;

use App\Models\Company\Company;
use App\Models\Master\CodeSystem\Medication\MasterMedicationForm;
use App\Models\Master\CodeSystem\Medication\MasterMedicationStatus;
use App\Models\Master\CodeSystem\Medication\MasterMedicationType;
use App\Models\Medication\OneHealth\OneHealthMedication;
use App\Models\MedicationDispense\MedicationDispense;
use App\Models\MedicationRequest\MedicationRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medication extends Model
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
            $modelCreate->setAutomatic();
        });
        static::saved(function ($model) {
            $model->setAutomatic();
        });
    }

    function setAutomatic()
    {
        $this->form_coding_display = $this?->formCodingCode?->display;
        $this->medication_type_display = $this?->medicationType?->display;
    }

    /**
     * Get the company that owns the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the OHMedication associated with the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function OHMedication(): HasOne
    {
        return $this->hasOne(OneHealthMedication::class, 'medication_id', 'id');
    }

    /**
     * Get the status that owns the OneHealthMedication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationStatus::class, 'status', 'code');
    }

    /**
     * Get all of the medicationIngredients for the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationIngredients(): HasMany
    {
        return $this->hasMany(MedicationIngredient::class, 'medication_id', 'id');
    }

    /**
     * Get the medicationType that owns the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function medicationType(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationType::class, 'medication_type_code', 'code');
    }

    /**
     * Get the formCodingCode that owns the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function formCodingCode(): BelongsTo
    {
        return $this->belongsTo(MasterMedicationForm::class, 'form_coding_code', 'code');
    }

    /**
     * Get all of the medicationReqs for the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationReqs(): HasMany
    {
        return $this->hasMany(MedicationRequest::class, 'medication_id', 'id');
    }

    /**
     * Get all of the medicationDispenses for the Medication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function medicationDispenses(): HasMany
    {
        return $this->hasMany(MedicationDispense::class, 'medication_id', 'id');
    }
}
