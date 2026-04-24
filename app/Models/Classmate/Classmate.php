<?php

namespace App\Models\Classmate;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Classmate extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if ($user && ! $user->hasRole('Anonymous') && $user->company_id) {
                $builder->where('company_id', $user->company_id);
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;

            if (auth()->check()) {
                $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
            }
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        if (empty($term)) {
            return;
        }

        $term = '%'.$term.'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'description'], 'ILIKE', $term);
        });
    }

    public function classmateStudents()
    {
        return $this->hasMany(ClassmateStudent::class, 'classmate_id', 'id');
    }
}
