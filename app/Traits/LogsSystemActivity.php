<?php

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
                fn (string $eventName) => "{$eventName} ".class_basename($this)
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
