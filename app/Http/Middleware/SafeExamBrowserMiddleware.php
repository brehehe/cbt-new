<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SafeExamBrowserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if request comes from Safe Exam Browser
        $userAgent = $request->header('User-Agent', '');
        $isSEB = $this->isSafeExamBrowser($userAgent);

        // Store SEB status in session
        session(['is_seb' => $isSEB]);

        // Validate SEB Browser Exam Key (BEK) if configured
        if (config('seb.require_browser_exam_key')) {
            $browserExamKey = $request->header('X-SafeExamBrowser-RequestHash');

            if ($isSEB && ! $this->validateBrowserExamKey($browserExamKey, $request)) {
                return response()->view('errors.seb-invalid', [
                    'message' => 'Invalid Safe Exam Browser configuration. Please download the correct configuration file.',
                ], 403);
            }
        }

        return $next($request);
    }

    /**
     * Check if user agent is from Safe Exam Browser
     */
    private function isSafeExamBrowser(string $userAgent): bool
    {
        return str_contains($userAgent, 'SEB/') ||
               str_contains($userAgent, 'SafeExamBrowser');
    }

    /**
     * Validate Browser Exam Key
     */
    private function validateBrowserExamKey(?string $key, Request $request): bool
    {
        if (! $key) {
            return false;
        }

        // Get configured browser exam key from config
        $configuredKey = config('seb.browser_exam_key');

        if (! $configuredKey) {
            return true; // No key configured, allow
        }

        // Generate expected hash
        $url = $request->fullUrl();
        $expectedHash = hash('sha256', $url.$configuredKey);

        return hash_equals($expectedHash, $key);
    }
}
