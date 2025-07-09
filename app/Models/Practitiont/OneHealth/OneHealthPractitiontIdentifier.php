<?php

namespace App\Models\Practitiont\OneHealth;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthPractitiontIdentifier extends Model
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
     * Get the OHPractitiont that owns the OneHealthPractitiontIdentifier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHPractitiont(): BelongsTo
    {
        return $this->belongsTo(OneHealthPractitioner::class, 'one_health_practitiont_id', 'id');
    }
}
