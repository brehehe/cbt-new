<?php

namespace App\Models\Master\CodeSystem\Organization;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOrganizationContactPointUse extends Model
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
     * Get all of the use for the MasterOrganizationContactPointUse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function use(): HasMany
    {
        return $this->hasMany(MasterOrganizationContactPointUse::class, 'use', 'code');
    }
}
