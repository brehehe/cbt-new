<?php

namespace App\Models\Master\Questionnaire;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Questionnaire extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'allow_guest' => 'boolean',
        'require_nim' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(QuestionnaireSection::class, 'questionnaire_id', 'id')->orderBy('order', 'asc');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(QuestionnaireResponse::class, 'questionnaire_id', 'id')->orderBy('created_at', 'desc');
    }

    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(
            QuestionnaireQuestion::class,
            QuestionnaireSection::class,
            'questionnaire_id',
            'section_id',
            'id',
            'id'
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();
            if ($user && ! $user->hasRole(['Anonymous'])) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)->id)
                        ->orWhereNull('company_id');
                });
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && ! $model->company_id) {
                $model->company_id = Auth::user()->company_id;
            }
            if (Auth::check() && ! $model->created_by) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%' . $term . '%';
        $query->where(function ($q) use ($term) {
            $q->where('title', 'ilike', $term)
              ->orWhere('slug', 'ilike', $term)
              ->orWhere('description', 'ilike', $term)
              ->orWhere('academic_year', 'ilike', $term);
        });
    }
}
