<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\Master\Question\Answer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Timetable\TimetableAnswer;
use App\Models\Timetable\TimetableModule;
use App\Models\Timetable\TimetableQuestion;

class UserModuleQuestion extends Model
{
    //
    use SoftDeletes, HasUuids;
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
        $term = '%' . $term . '%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id'], 'ILIKE', $term);
        });
    }

    public function moduleQuestion()
    {
        return $this->belongsTo(ModuleQuestion::class, 'module_question_id', 'id');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id', 'id');
    }

    public function timetableModule()
    {
        return $this->belongsTo(TimetableModule::class, 'timetable_module_id', 'id');
    }

    public function timetableQuestion()
    {
        return $this->belongsTo(TimetableQuestion::class, 'timetable_question_id', 'id');
    }

    public function timetableAnswer()
    {
        return $this->belongsTo(TimetableAnswer::class, 'timetable_answer_id', 'id');
    }

    public function userTimetable()
    {
        return $this->belongsTo(UserTimetable::class, 'user_timetable_id', 'id');
    }
}
