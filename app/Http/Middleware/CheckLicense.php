<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Lewati pengecekan jika di local atau testing (opsional)
        if (app()->environment('local', 'testing')) {
            return $next($request);
        }

        $licenseKey = env('LICENSE_KEY');
        $serverUrl = env('LICENSE_SERVER_URL', 'http://license.procbt.id');
        $fingerprint = env('LICENSE_FINGERPRINT');

        if (!$licenseKey) {
            return response()->json([
                'success' => false,
                'message' => 'Lisensi tidak ditemukan. Silakan jalankan setup.sh atau hubungi administrator.'
            ], 403);
        }

        // Cek cache agar tidak memberatkan server lisensi di setiap request
        $cacheKey = 'license_valid_' . $licenseKey;
        $isValid = Cache::remember($cacheKey, 86400, function () use ($licenseKey, $serverUrl, $fingerprint) {
            try {
                $response = Http::post($serverUrl . '/api/license/verify', [
                    'license_key' => $licenseKey,
                    'domain'      => config('app.url'),
                    'fingerprint' => $fingerprint,
                ]);

                return $response->json('valid') === true;
            } catch (\Exception $e) {
                // Jika server lisensi down, kita izinkan dulu agar web tidak mati total
                // Atau bisa juga diblokir tergantung kebijakan Anda.
                return true; 
            }
        });

        if (!$isValid) {
            // Jika tidak valid, hapus cache agar request berikutnya langsung ngecek lagi
            Cache::forget($cacheKey);
            
            return response()->json([
                'success' => false,
                'message' => 'Lisensi tidak valid atau sudah kadaluarsa.'
            ], 403);
        }

        return $next($request);
    }
}
