<?php

namespace App\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Request;
use Stevebauman\Location\Facades\Location;

trait LogsSystemActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('audit')
            ->setDescriptionForEvent(
                fn(string $eventName) => "{$eventName} " . class_basename($this)
            );
    }
    
    /**
     * Define sensitivity masking for logs
     */
    public function getLogAttributesToIgnore(): array
    {
        return [
            'password',
            'remember_token',
            'two_factor_recovery_codes',
            'two_factor_secret',
        ];
    }
}