<?php

namespace App\Models\Company\OneHealth;

use App\Models\Master\CodeSystem\Organization\MasterOrganizationIndentifierUse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OneHealthOrganizationIdentifier extends Model
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
     * Get the OHOrganization that owns the OneHealthOrganizationIdentifier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHOrganization(): BelongsTo
    {
        return $this->belongsTo(OneHealthOrganization::class, 'one_health_organization_id', 'id');
    }

    /**
     * Get the masterorganizationIndentifierUse associated with the OneHealthOrganizationIdentifier
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function use(): BelongsTo
    {
        return $this->belongsTo(MasterOrganizationIndentifierUse::class, 'use', 'code');
    }
}
