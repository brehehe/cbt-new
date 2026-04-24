<?php

namespace App\Models\Company;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        // 'one_health_access_token' => 'encrypted',
    ];

    protected static function booted()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * Get the user associated with the Company
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'company_id', 'id');
    }

    /**
     * Get the companyDetail associated with the Company
     */
    public function companyDetail(): HasOne
    {
        return $this->hasOne(CompanyDetail::class, 'company_id', 'id');
    }
}
