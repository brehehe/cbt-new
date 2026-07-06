<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceMonth extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('description', 'like', '%'.$search.'%');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
        });
    }

    public function serviceMonthDetails()
    {
        return $this->hasMany(ServiceMonthDetail::class);
    }
}
