<?php

namespace App\Models\Exam;

use App\Models\Company\Company;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExamLiveSession extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'timetable_id',
        'user_timetable_id',
        'user_id',
        'company_id',
        'session_token',
        'camera_device_id', // Selected camera device ID
        'peer_id', // PeerJS ID for direct connection
        'camera_stream_url',
        'screen_stream_url',
        'current_question_number',
        'total_questions',
        'answered_questions',
        'marked_questions',
        'camera_status',
        'screen_status',
        'connection_status',
        'last_activity',
        'warning_count',
        'alert_count',
        'is_active',
        'session_metadata',
        'browser_info',
        'device_info',
        'location_info',
        'end_time',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
        'is_active' => 'boolean',
        'session_metadata' => 'array',
        'browser_info' => 'array',
        'device_info' => 'array',
        'location_info' => 'array',
        'end_time' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if ($user && ! $user->hasRole('Anonymous')) {
                $builder->where('company_id', optional($user->company)->id);
            }
        });

        static::creating(function ($model) {
            $model->company_id = $model->company_id ?? (auth()->user() ? auth()->user()->company_id : Company::getCached()->id);
            $model->session_token = $model->session_token ?? Str::random(32);
        });
    }

    // Relationships
    public function timetable()
    {
        return $this->belongsTo(Timetable::class, 'timetable_id', 'id');
    }

    public function userTimetable()
    {
        return $this->belongsTo(UserTimetable::class, 'user_timetable_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function examRecordings()
    {
        return $this->hasMany(ExamRecording::class, 'user_timetable_id', 'user_timetable_id');
    }

    public function examAlerts()
    {
        return $this->hasMany(ExamAlert::class, 'user_timetable_id', 'user_timetable_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTimetable($query, $timetableId)
    {
        return $query->where('timetable_id', $timetableId);
    }

    public function scopeRecentActivity($query, $minutes = 5)
    {
        return $query->where('last_activity', '>=', now()->subMinutes($minutes));
    }

    // Methods
    public function updateActivity()
    {
        $this->update([
            'last_activity' => now(),
            'connection_status' => 'connected',
        ]);
    }

    public function markOffline()
    {
        $this->update([
            'connection_status' => 'disconnected',
            'is_active' => false,
        ]);
    }

    public function updateCameraStatus($status)
    {
        $this->update(['camera_status' => $status]);
    }

    public function updateScreenStatus($status)
    {
        $this->update(['screen_status' => $status]);
    }

    public function incrementWarning()
    {
        $this->increment('warning_count');
    }

    public function incrementAlert()
    {
        $this->increment('alert_count');
    }

    public function getStatusColorAttribute()
    {
        return match ($this->connection_status) {
            'connected' => 'green',
            'disconnected' => 'red',
            'unstable' => 'yellow',
            default => 'gray'
        };
    }

    public function getCameraStatusColorAttribute()
    {
        return match ($this->camera_status) {
            'active' => 'green',
            'inactive' => 'red',
            'error' => 'red',
            default => 'gray'
        };
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }

        return round(($this->answered_questions / $this->total_questions) * 100, 1);
    }

    public function getRiskLevelAttribute()
    {
        $alerts = $this->alert_count;
        $warnings = $this->warning_count;

        if ($alerts >= 5 || $warnings >= 10) {
            return 'high';
        }
        if ($alerts >= 3 || $warnings >= 5) {
            return 'medium';
        }
        if ($alerts >= 1 || $warnings >= 1) {
            return 'low';
        }

        return 'none';
    }

    public function getRiskColorAttribute()
    {
        return match ($this->risk_level) {
            'high' => 'red',
            'medium' => 'yellow',
            'low' => 'orange',
            'none' => 'green',
            default => 'gray'
        };
    }
}
