<?php

namespace App\Models\Master\DigitalBook;

use App\Models\Company\Company;
use App\Models\Master\Timetable\TimetableDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DigitalBook extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DigitalBookCategory::class, 'digital_book_category_id', 'id');
    }

    public function timetableDetails(): HasMany
    {
        return $this->hasMany(TimetableDetail::class, 'digital_book_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();
            if ($user && ! $user->hasRole('Anonymous')) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)?->id)
                        ->orWhereNull('company_id');
                });
            }
            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($model) {
            $lastOrder = static::withoutGlobalScope('user_scope')->max('order');
            $model->order = $lastOrder ? $lastOrder + 1 : 1;
            if (Auth::check() && ! $model->company_id) {
                $model->company_id = Auth::user()->company_id;
            }
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%' . $term . '%';
        $query->where(function ($q) use ($term) {
            $q->where('title', 'ilike', $term)
              ->orWhere('description', 'ilike', $term)
              ->orWhere('content_type', 'ilike', $term)
              ->orWhereHas('category', function ($catQuery) use ($term) {
                  $catQuery->where('name', 'ilike', $term);
              });
        });
    }
}
