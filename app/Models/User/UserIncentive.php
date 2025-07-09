<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserIncentive extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('amount', 'like', '%' . $search . '%')
        ->orWhere('month', 'like', '%' . $search . '%')
        ->orWhere('year', 'like', '%' . $search . '%')
        ->orWhere('status', 'like', '%' . $search . '%')
        ->orWhereHas('user', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('email', 'like', '%' . $search . '%');
        })
        ->orWhereHas('company', function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
        })
        ;
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
}
