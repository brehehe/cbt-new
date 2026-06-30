<?php

namespace App\Models\Master\Question;

use App\Models\Category\CategoryQuestion;
use App\Models\Company\Company;
use App\Models\Study\Study;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User;
use App\Traits\LogsSystemActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{
    //
    use HasFactory, HasUuids, LogsSystemActivity, SoftDeletes;

    protected $guarded = ['id'];

    const TYPE_SINGLE = 'single';

    const TYPE_MULTIPLE = 'multiple';

    const TYPE_ESSAY = 'essay';

    protected $casts = [
        'images' => 'array',
    ];

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
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)?->id)
                        ->orWhere('is_simulation', 'true')
                        ->orWhereNull('company_id');
                });
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });

        static::saved(function ($model) {
            \App\Models\Timetable\TimetableQuestion::withoutGlobalScope('user_scope')
                ->where('question_id', $model->id)
                ->update([
                    'study_id' => $model->study_id,
                    'user_id' => $model->user_id,
                    'topic_id' => $model->topic_id,
                    'material_category_id' => $model->material_category_id,
                    'material_id' => $model->material_id,
                    'question_type_id' => $model->question_type_id,
                    'category_question_id' => $model->category_question_id,
                    'difficulty' => $model->difficulty ?? 'default',
                    'order' => $model->order ?? 0,
                    'question' => $model->question,
                    'images' => $model->images,
                    'description' => $model->description,
                    'latex' => $model->latex,
                    'latex_preview_pdf' => $model->latex_preview_pdf,
                    'latex_preview_png' => $model->latex_preview_png,
                    'weight_correct' => $model->weight_correct,
                    'weight_incorrect' => $model->weight_incorrect,
                    'type' => $model->type,
                ]);
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%'.$term.'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'question', 'description'], 'ILIKE', $term);
        });
    }

    /**
     * Get the topic that owns the Question
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    /**
     * Get the materialCategory that owns the Question
     */
    public function materialCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id', 'id');
    }

    /**
     * Get the material that owns the Question
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }

    /**
     * Get the questionType that owns the Question
     */
    public function questionType(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id', 'id');
    }

    /**
     * Get all of the answers for the Question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    /**
     * Get all of the moduleQuestion for the Question
     */
    public function moduleQuestions(): HasMany
    {
        return $this->hasMany(ModuleQuestion::class, 'question_id', 'id');
    }

    /**
     * Get the categoryQuestion that owns the Question
     */
    public function categoryQuestion(): BelongsTo
    {
        return $this->belongsTo(CategoryQuestion::class, 'category_question_id', 'id');
    }

    /**
     * Get the timetableQuestion associated with the Question
     */
    public function timetableQuestion(): HasOne
    {
        return $this->hasOne(TimetableQuestion::class, 'question_id', 'id');
    }

    public function study()
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
