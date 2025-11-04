<?php

namespace App\Http\Middleware;

use App\Models\User\UserTimetable;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserTimetable
{
    public function handle(Request $request, Closure $next)
    {
        // Skip check jika sudah berada di route tujuan
        $currentRoute = $request->route()->getName();
        if (in_array($currentRoute, ['admin.exam.detail', 'admin.exam.warning'])) {
            return $next($request);
        }

        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        if ($userTimetable) {
            if ($userTimetable->status === 'exam') {
                return redirect()->route('admin.exam.detail');
            }

            if ($userTimetable->status === 'warning') {
                return redirect()->route('admin.exam.warning');
            }
        }

        return $next($request);
    }
}
