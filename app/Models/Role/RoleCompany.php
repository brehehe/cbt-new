<?php

namespace App\Models\Role;

use App\Models\Company\Company;
use App\Models\Spatie\Role;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoleCompany extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->whereHas('role', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })->orWhereHas('company', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        return $query;

    }
}
