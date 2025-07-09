<?php

namespace App\Models\Encounter;

use App\Models\Company\Company;
use App\Models\Master\CodeSystem\Encounter\MasterEncounterActCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EncounterClassHistory extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    /**
     * Get the encounter that owns the EncounterClassHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class, 'encounter_id', 'id');
    }

    /**
     * Get the classCode that owns the EncounterClassHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classCode(): BelongsTo
    {
        return $this->belongsTo(MasterEncounterActCode::class, 'class_code', 'code');
    }
}
