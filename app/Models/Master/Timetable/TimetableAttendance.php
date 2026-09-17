<?php

namespace App\Models\Master\Timetable;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TimetableAttendance extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'attended_at' => 'datetime',
    ];

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class, 'timetable_id', 'id');
    }

    public function timetableDetail(): BelongsTo
    {
        return $this->belongsTo(TimetableDetail::class, 'timetable_detail_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
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
        });

        static::creating(function ($model) {
            if (Auth::check() && ! $model->company_id) {
                $model->company_id = Auth::user()->company_id;
            }
            if (empty($model->attended_at)) {
                $model->attended_at = now();
            }
        });
    }
}
