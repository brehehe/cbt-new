<?php

namespace App\Helpers {
    class FormatHelper
    {
        public static function number($value)
        {
            return number_format($value, 0, ',', '.');
        }

        public static function studentLabel(string $prefix = '', string $suffix = ''): string
        {
            $label = config('app.is_shorinji', false) ? 'Kenshi' : 'Mahasiswa';
            return trim($prefix . ' ' . $label . ' ' . $suffix);
        }

        public static function lecturerLabel(string $prefix = '', string $suffix = ''): string
        {
            $label = config('app.is_shorinji', false) ? 'Pelatih' : 'Dosen';
            return trim($prefix . ' ' . $label . ' ' . $suffix);
        }

        public static function isShorinji(): bool
        {
            return (bool) config('app.is_shorinji', false);
        }
    }
}

namespace {
    if (! function_exists('student_label')) {
        function student_label(): string
        {
            return config('app.is_shorinji', false) ? 'Kenshi' : 'Mahasiswa';
        }
    }

    if (! function_exists('lecturer_label')) {
        function lecturer_label(): string
        {
            return config('app.is_shorinji', false) ? 'Pelatih' : 'Dosen';
        }
    }

    if (! function_exists('dosen_label')) {
        function dosen_label(): string
        {
            return config('app.is_shorinji', false) ? 'Pelatih' : 'Dosen';
        }
    }

    if (! function_exists('is_shorinji')) {
        function is_shorinji(): bool
        {
            return (bool) config('app.is_shorinji', false);
        }
    }
}
