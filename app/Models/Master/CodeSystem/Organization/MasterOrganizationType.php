<?php

namespace App\Models\Master\CodeSystem\Organization;

use App\Models\Company\OneHealth\OneHealthOrganization;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterOrganizationType extends Model
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
     * Get the OHOrganizationIdentifier that owns the MasterOrganizationIndentifierUse
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function OHOrganization(): HasMany
    {
        return $this->hasMany(OneHealthOrganization::class, 'type_coding_code', 'code');
    }
}
