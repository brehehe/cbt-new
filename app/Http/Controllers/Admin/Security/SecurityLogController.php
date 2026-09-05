<?php

namespace App\Http\Controllers\Admin\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    /**
     * Store a security violation log.
     *
     * @return JsonResponse
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
