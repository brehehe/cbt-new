<?php

namespace App\Models\Company\OneHealth;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthOrganizationAddressExtention extends Model
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
     * Get the OHAddress that owns the OneHealthOrganizationAddressExtention
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganizationAddress(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganizationAddress::class, 'one_health_organization_address_id', 'id');
    }
}
