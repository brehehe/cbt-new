<?php

namespace App\Models\Company;

use App\Models\Service\Service;
use App\Models\Service\ServiceMonth;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyService extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function serviceMonth()
    {
        return $this->belongsTo(ServiceMonth::class);
    }

    public function companyServiceMonths()
    {
        return $this->hasMany(CompanyServiceMonth::class);
    }
}
