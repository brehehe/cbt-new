<?php

namespace App\Models\Encounter\Onehealth;

use App\Models\Company\Company;
use App\Models\Encounter\OneHealth\OneHealthEncounter;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterParticipationType;
use App\Models\Practitiont\OneHealth\OneHealthPractitioner;
use App\Models\Practitiont\OneHealth\OneHealthPractitiont;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthEnconterParticipant extends Model
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
        // dd($this->OHPractitioner);
        $this->updateQuietly([
            'type_coding_display' => $this->typeCodingCode?->display,
            'individual_display'  => $this->OHPractitioner?->name_text,
        ]);
    }

    /**
     * Get the OHEncounter that owns the OneHealthEnconterParticipant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHEncounter(): BelongsTo
    {
        return $this->belongsTo(OneHealthEncounter::class, 'one_health_encounter_id', 'id');
    }

    /**
     * Get the OHPractitiiont that owns the OneHealthEnconterParticipant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPractitioner(): BelongsTo
    {
        return $this->belongsTo(OneHealthPractitioner::class, 'one_health_practitioner_id', 'id');
    }

    /**
     * Get the typeCodingCode that owns the OneHealthEnconterParticipant
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function typeCodingCode(): BelongsTo
    {
        return $this->belongsTo(MasterEncounterParticipationType::class, 'type_coding_code', 'code');
    }
}
