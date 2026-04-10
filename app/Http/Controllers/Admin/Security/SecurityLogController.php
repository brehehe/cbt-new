<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SecurityLogController extends Controller
{
    /**
     * Store a security violation log.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_type' => 'required|string',
            'description' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        activity('security')
            ->event($request->event_type)
            ->withProperties(array_merge($request->metadata ?? [], [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => url()->previous(),
            ]))
            ->log($request->description);

        return response()->json(['status' => 'success']);
    }
}
