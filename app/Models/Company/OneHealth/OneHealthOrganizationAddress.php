<?php

namespace App\Models\Company\OneHealth;

use App\Models\Master\CodeSystem\General\AddressType;
use App\Models\Master\CodeSystem\Organization\MasterOrganizationAddressType;
use App\Models\Master\CodeSystem\Organization\MasterOrganizationAddressUse;
use App\Models\Master\CodeSystem\Organization\OrganizationType;
use App\Models\Master\CodeSystem\Patient\AddressUse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthOrganizationAddress extends Model
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
     * Get the OHOrganization that owns the OneHealthOrganizationAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the use that owns the OneHealthOrganizationAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function use(): BelongsTo
    {
        return $this->belongsTo(MasterOrganizationAddressUse::class, 'use', 'code');
    }

    /**
     * Get the type that owns the OneHealthOrganizationAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(MasterOrganizationAddressType::class, 'type', 'code');
    }

    /**
     * Get all of the extentions for the OneHealthOrganizationAddress
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function extentions(): HasMany
    {
        return $this->hasMany(OneHealthOrganizationAddressExtention::class, 'one_health_organization_address_id', 'id');
    }

}
