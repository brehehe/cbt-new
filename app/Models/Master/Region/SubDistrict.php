<?php

namespace App\Models\Master\Region;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubDistrict extends Model
{
    //
    use HasFactory, HasUuids;

    protected $guarded = ['id'];

    /**
     * Get the district that owns the SubDistrict
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'parent_code', 'code');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }
}
