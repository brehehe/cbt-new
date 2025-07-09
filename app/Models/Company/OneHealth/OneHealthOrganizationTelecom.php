<?php

namespace App\Models\Company\OneHealth;

use App\Models\Master\CodeSystem\Organization\MasterOrganizationContactPointSystem;
use App\Models\Master\CodeSystem\Organization\MasterOrganizationContactPointUse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthOrganizationTelecom extends Model
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
     * Get the user that owns the OneHealthOrganizationTelecom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the system that owns the OneHealthOrganizationTelecom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system(): BelongsTo
    {
        return $this->belongsTo(MasterOrganizationContactPointSystem::class, 'system', 'code');
    }
    
    /**
     * Get the use that owns the OneHealthOrganizationTelecom
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function use(): BelongsTo
    {
        return $this->belongsTo(MasterOrganizationContactPointUse::class, 'use', 'code');
    }

}
