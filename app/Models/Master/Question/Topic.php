<?php

namespace App\Models\Master\Question;

use App\Models\Company\Company;
use App\Models\Study\Study;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Topic extends Model
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

        static::addGlobalScope('company_scope', function (Builder $builder) {
            $user = Auth::user();

            if (! $user || ! $user->hasRole('Anonymous')) {
                $builder->where('company_id', optional($user?->company)?->id)->orderBy('order', 'asc');
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%'.$term.'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'description'], 'ILIKE', $term);
        });
    }

    /**
     * Get the study that owns the Topic
     */
    public function study(): BelongsTo
    {
        return $this->belongsTo(Study::class, 'study_id', 'id');
    }

    /**
     * Get all of the materialCategories for the Topic
     */
    public function materialCategories(): HasMany
    {
        return $this->hasMany(MaterialCategory::class, 'topic_id', 'id');
    }

    /**
     * Get all of the questions for the Topic
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'topic_id', 'id');
    }
}
