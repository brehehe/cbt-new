<?php

namespace App\Models\Timetable;

use App\Models\Company\Company;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Question\Module;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TimetableModule extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id', 'id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(TimetableAnswer::class, 'timetable_module_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(TimetableQuestion::class, 'timetable_module_id', 'id');
    }

    public function module() {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !$user->hasRole('Anonymous')) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)?->id)
                        ->orWhereHas('timetable', function ($q) {
                            $q->where('is_simulation', 'true');
                        })
                        ->orWhereNull('company_id');
                });
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
        $term = '%' . $term . '%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id'], 'ILIKE', $term);
        });
    }
}
