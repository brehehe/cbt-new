<?php

namespace App\Models\User;

use App\Models\Company\Company;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserTimetable extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'is_recording' => 'boolean',
        'is_streaming' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userModuleQuestions()
    {
        return $this->hasMany(UserModuleQuestion::class, 'user_timetable_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if (! $user || ! $user->hasRole('Anonymous')) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)?->id)
                        ->orWhereHas('timetable', function ($q) {
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
            $modelCreate->company_id = $modelCreate->company_id ?? (auth()->user() ? auth()->user()->company_id : Company::first()->id);
        });
    }

    public function recalculateMark()
    {
        $questions = $this->userModuleQuestions()->get();
        $total = $questions->count();
        $correct = $questions->where('status', 'correct')->count();

        $mark = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        $this->update(['mark' => $mark]);

        return $mark;
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('start_process', 'ilike', "%{$search}%")
                ->orWhere('start_exam', 'ilike', "%{$search}%")
                ->orWhere('end_exam', 'ilike', "%{$search}%")
                ->orWhere('mark', 'ilike', "%{$search}%")
                ->orWhere('status', 'ilike', "%{$search}%")
                ->orWhereHas('user', function ($qd) use ($search) {
                    $qd->where('name', 'ilike', "%{$search}%")
                        ->orWhere('nim', 'ilike', "%{$search}%")
                        ->orWhere('username', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%");
                })
                ->orWhereHas('timetable', function ($qd) use ($search) {
                    $qd->where('name', 'ilike', "%{$search}%")
                        ->orWhere('description', 'ilike', "%{$search}%")
                        ->orWhere('start_time', 'ilike', "%{$search}%")
                        ->orWhere('end_time', 'ilike', "%{$search}%")
                        ->orWhereHas('module', function ($qd) use ($search) {
                            $qd->where('name', 'ilike', "%{$search}%")
                                ->orWhere('description', 'ilike', "%{$search}%");
                        });
                })
                ->orWhereHas('company', function ($qd) use ($search) {
                    $qd->where('name', 'ilike', "%{$search}%");
                });
        });
    }
}
