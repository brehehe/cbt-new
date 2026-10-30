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

        public static function isLemes(): bool
        {
            return (bool) config('app.is_lemes', false);
        }

        /**
         * Convert Google Drive sharing/view/preview/open URL into direct image stream URL.
         */
        public static function resolveGoogleDriveDirectUrl(string $url): string
        {
            if (! str_contains($url, 'drive.google.com') && ! str_contains($url, 'docs.google.com')) {
                return $url;
            }

            // Pattern 1: /file/d/{id}
            if (preg_match('/(?:drive|docs)\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
                return 'https://lh3.googleusercontent.com/d/' . $matches[1];
            }

            // Pattern 2: ?id={id} or &id={id}
            if (preg_match('/(?:drive|docs)\.google\.com\/.*[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
                return 'https://lh3.googleusercontent.com/d/' . $matches[1];
            }

            return $url;
        }

        /**
         * Resolves any image/media path into a valid URL (handles local storage, external URLs, and Google Drive URLs).
         */
        public static function mediaUrl(?string $path): string
        {
            if (empty($path) || ! is_string($path)) {
                return '';
            }

            $path = trim($path);

            if (str_contains($path, 'drive.google.com') || str_contains($path, 'docs.google.com')) {
                return self::resolveGoogleDriveDirectUrl($path);
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            $cleanPath = ltrim(\Illuminate\Support\Str::after($path, 'storage/'), '/');
            return asset('storage/' . $cleanPath);
        }

        /**
         * Try to download an external image URL (or Google Drive URL) to local storage.
         * Returns relative path with leading slash (e.g. '/question/2026/10/xxx.png') or direct URL on fallback.
         */
        public static function downloadExternalImage(string $url, string $folder = 'question'): string
        {
            $directUrl = self::resolveGoogleDriveDirectUrl($url);
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(10)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0'])
                    ->get($directUrl);

                if ($response->successful()) {
                    $contentType = strtolower($response->header('Content-Type') ?? '');
                    if (str_contains($contentType, 'image/') || str_contains($contentType, 'application/octet-stream')) {
                        $ext = 'jpg';
                        if (str_contains($contentType, 'png')) {
                            $ext = 'png';
                        } elseif (str_contains($contentType, 'webp')) {
                            $ext = 'webp';
                        } elseif (str_contains($contentType, 'gif')) {
                            $ext = 'gif';
                        } elseif (str_contains($contentType, 'svg')) {
                            $ext = 'svg';
                        }

                        $subFolder = trim($folder, '/') . '/' . date('Y/m');
                        $filename = \Illuminate\Support\Str::random(40) . '.' . $ext;
                        $storedPath = $subFolder . '/' . $filename;

                        \Illuminate\Support\Facades\Storage::disk('public')->put($storedPath, $response->body());
                        return '/' . $storedPath;
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Gagal download external image: ' . $e->getMessage(), ['url' => $url]);
            }

            return $directUrl;
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

    if (! function_exists('is_lemes')) {
        function is_lemes(): bool
        {
            return (bool) config('app.is_lemes', false);
        }
    }

    if (! function_exists('media_url')) {
        function media_url(?string $path): string
        {
            return \App\Helpers\FormatHelper::mediaUrl($path);
        }
    }
}
