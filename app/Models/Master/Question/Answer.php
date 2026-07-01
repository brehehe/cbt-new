<?php

namespace App\Models\Master\Question;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Answer extends Model
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

            if (! $user || ! $user->hasRole('Anonymous')) {
                $builder->where('company_id', optional($user?->company)?->id)->orderBy('order', 'asc');
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            if (! empty($modelCreate->order)) {
                return;
            }

            $lastOrder = static::withoutGlobalScope('user_scope')
                ->where('question_id', $modelCreate->question_id)
                ->max('order');

            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });

        static::saved(function ($model) {
            if ($model->is_correct) {
                Answer::where('question_id', $model?->question_id)
                    ->whereNot('id', $model?->id)
                    ->update(['is_correct' => false]);
            }

            // Sync with all TimetableAnswer records
            \App\Models\Timetable\TimetableAnswer::where('answer_id', $model->id)
                ->update([
                    'alphabet' => $model->alphabet,
                    'context' => $model->context,
                    'images' => $model->images,
                    'latex' => $model->latex,
                    'latex_preview_pdf' => $model->latex_preview_pdf,
                    'latex_preview_png' => $model->latex_preview_png,
                    'is_correct' => $model->is_correct,
                    'order' => $model->order,
                ]);

            // If this answer was marked as correct, sync all sibling answers of this question in the active timetable exams!
            if ($model->is_correct) {
                $tqIds = \App\Models\Timetable\TimetableQuestion::withoutGlobalScope('user_scope')
                    ->where('question_id', $model->question_id)
                    ->pluck('id')
                    ->toArray();

                if (!empty($tqIds)) {
                    \App\Models\Timetable\TimetableAnswer::whereIn('timetable_question_id', $tqIds)
                        ->whereNot('answer_id', $model->id)
                        ->update(['is_correct' => false]);
                }
            }
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%'.$term.'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'alphabet', 'context', 'is_correct'], 'like', $term);
        });
    }

    /**
     * Get the question that owns the Answer
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }
}
