<?php

namespace App\Models\Master\Timetable;

use App\Models\Company\Company;
use App\Models\Master\DigitalBook\DigitalBook;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Master\Question\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TimetableDetail extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'supervisors' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'exam_date' => 'date',
        'allow_repeat' => 'boolean',
        'require_token' => 'boolean',
        'is_camera' => 'boolean',
        'is_recording' => 'boolean',
        'is_streaming' => 'boolean',
        'require_attendance' => 'boolean',
    ];

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(Timetable::class, 'timetable_id', 'id');
    }

    public function examRoom(): BelongsTo
    {
        return $this->belongsTo(ExamRoom::class, 'exam_room_id', 'id');
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id', 'id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function digitalBook(): BelongsTo
    {
        return $this->belongsTo(DigitalBook::class, 'digital_book_id', 'id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(TimetableAttendance::class, 'timetable_detail_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function isExam(): bool
    {
        return $this->type === 'exam' || $this->type === 'ujian';
    }

    public function isMaterial(): bool
    {
        return $this->type === 'material' || $this->type === 'materi';
    }

    public function getSupervisorAttribute()
    {
        $ids = is_array($this->supervisors) ? $this->supervisors : (json_decode($this->supervisors ?? '[]', true) ?: []);
        if (!empty($ids)) {
            return User::find($ids[0]);
        }
        return null;
    }

    public function getSupervisorNamesAttribute(): string
    {
        $ids = is_array($this->supervisors) ? $this->supervisors : (json_decode($this->supervisors ?? '[]', true) ?: []);
        if (!empty($ids)) {
            return User::whereIn('id', $ids)->pluck('name')->join(', ') ?: '-';
        }
        return '-';
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
            $lastOrder = static::withoutGlobalScope('user_scope')->where('timetable_id', $model->timetable_id)->max('order');
            $model->order = $lastOrder ? $lastOrder + 1 : 1;
            
            if (empty($model->code)) {
                $model->code = 'TTD-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }

            if (Auth::check() && ! $model->company_id) {
                $model->company_id = Auth::user()->company_id;
            }
        });
    }
}
