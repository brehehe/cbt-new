<?php

namespace App\Models\Master\Exam;

use App\Models\Company\Company;
use App\Models\Master\Timetable\Timetable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ExamSession extends Model
{
    //
    use SoftDeletes, HasUuids, \App\Traits\LogsSystemActivity;
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

            if (!$user || !$user->hasRole('Anonymous')) {
                $builder->where('company_id', optional($user?->company)?->id)->orderBy('order', 'asc');
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%'. $term .'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'code', 'description'], 'ILIKE', $term);
        });
    }
     /**
     * Get all of the timeTables for the ExamRoom
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeTables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'exam_session_id', 'id');
    }
}
