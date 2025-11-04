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

class MaterialCategory extends Model
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
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%'. $term .'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'description'], 'ILIKE', $term)
            ->orWhereHas('topic', function ($query) use ($term) {
                $query->whereAny(['company_id', 'name', 'description'], 'ILIKE', $term);
            });
        });
    }

    /**
     * Get all of the childs for the MaterialCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs(): HasMany
    {
        return $this->hasMany(MaterialCategory::class, 'material_category_id', 'id');
    }

    /**
     * Get the parent that owns the MaterialCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class, 'material_category_id', 'id');
    }

    /**
     * Get the topic that owns the MaterialCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    /**
     * Get all of the materials for the MaterialCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'material_category_id', 'id');
    }

    /**
     * Get all of the questions for the Topic
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'topic_id', 'id');
    }
}
