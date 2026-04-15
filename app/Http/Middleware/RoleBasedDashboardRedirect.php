<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RoleBasedDashboardRedirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Always allow assets and internal tools
        if (
            $request->is('_debugbar/*') ||
            $request->is('vendor/*') ||
            $request->is('build/*') ||
            $request->is('assets/*') ||
            $request->is('css/*') ||
            $request->is('js/*') ||
            $request->is('storage/*') ||
            str_contains($request->path(), '.') // Most asset files have dots
        ) {
            return $next($request);
        }

        if (Auth::check()) {
            $user = Auth::user();

            // Check if user is a student (either by role or type_study)
            $isStudent = ($user->type_study === 'mahasiswa' || $user->hasRole('Mahasiswa'));

            if ($isStudent && !$user->user_check) {
                // Allow specific routes to avoid infinite loop
                if (
                    $request->routeIs('student.onboarding') ||
                    $request->routeIs('logout') ||
                    $request->is('livewire/*') ||
                    $request->is('*/livewire/*') ||
                    $request->is('admin/security/log') ||
                    $request->ajax() ||
                    $request->expectsJson()
                ) {
                    return $next($request);
                }

                // Redirect to onboarding
                return redirect()->route('student.onboarding');
            }
        }

        return $next($request);
    }
}
