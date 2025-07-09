<?php

namespace App\Models\Icd;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Icd9 extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('code', 'like', '%' . $search . '%')
                ->orWhere('display', 'like', '%' . $search . '%')
                ->orWhere('version', 'like', '%' . $search . '%');
        }
        return $query;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }
}
