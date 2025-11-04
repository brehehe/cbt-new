<?php

namespace App\Models\Master\Question;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Module extends Model
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
                $builder->where('company_id', optional($user?->company)?->id)->orderBy('order', 'desc');
            }

            $builder->orderBy('order', 'desc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%' . $term . '%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'duration', 'description', 'random_question'], 'ILIKE', $term);
        });
    }

    /**
     * Get the questionType that owns the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function questionType(): BelongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id', 'id');
    }

    /**
     * Get all of the moduleQuestions for the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function moduleQuestions(): HasMany
    {
        return $this->hasMany(ModuleQuestion::class, 'module_id', 'id');
    }
}
