<?php

namespace App\Models\Timetable;

use App\Models\Company\Company;
use App\Models\Master\Question\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Models\Category\CategoryQuestion;

class TimetableQuestion extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    const TYPE_SINGLE = 'single';
    const TYPE_MULTIPLE = 'multiple';
    const TYPE_ESSAY = 'essay';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function timetableModule()
    {
        return $this->belongsTo(TimetableModule::class, 'timetable_module_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(TimetableAnswer::class, 'timetable_question_id', 'id');
    }

    public function categoryQuestion(): BelongsTo
    {
        return $this->belongsTo(CategoryQuestion::class, 'category_question_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !$user->hasRole('Anonymous')) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)?->id)
                        ->orWhereHas('timetableModule.timetable', function ($q) {
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

    /**
     * Get the question that owns the TimetableQuestion
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
