<?php

use App\Http\Controllers\Admin\Exam\LiveStreamController;
use App\Http\Controllers\Api\RealTimeMetricsController;
use App\Models\Exam\ExamLiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Live session monitoring endpoints
Route::post('/end-live-session', function (Request $request) {
    $sessionToken = $request->input('session_token');

    if ($sessionToken) {
        ExamLiveSession::where('session_token', $sessionToken)
            ->update([
                'is_active' => false,
                'connection_status' => 'disconnected',
            ]);
    }

    return response()->json(['success' => true]);
});

Route::post('/log-alert', function (Request $request) {
    // Handle alert logging from client side
    Log::info('Client alert received', $request->all());

    return response()->json(['success' => true]);
});

// Live streaming endpoints
Route::post('/stream/offer', function (Request $request) {
    $sessionToken = $request->input('session_token');
    $offer = $request->input('offer');

    // In real implementation, this would relay the offer to supervisors
    // For now, we'll just log it
    Log::info('WebRTC offer received', [
        'session_token' => $sessionToken,
        'offer_type' => $offer['type'] ?? null,
    ]);

    return response()->json(['success' => true]);
});

Route::post('/stream/answer', function (Request $request) {
    $sessionToken = $request->input('session_token');
    $answer = $request->input('answer');

    // Relay answer back to student
    Log::info('WebRTC answer received', [
        'session_token' => $sessionToken,
        'answer_type' => $answer['type'] ?? null,
    ]);

    return response()->json(['success' => true]);
});

Route::post('/stream/ice-candidate', function (Request $request) {
    $sessionToken = $request->input('session_token');
    $candidate = $request->input('candidate');

    // Relay ICE candidate
    Log::info('ICE candidate received', [
        'session_token' => $sessionToken,
        'candidate_type' => $candidate['type'] ?? null,
    ]);

    return response()->json(['success' => true]);
});

Route::get('/stream/sessions', function () {
    // Get active streaming sessions for supervisors
    $realSessions = ExamLiveSession::where('is_active', true)
        ->with(['user', 'timetable.module'])
        ->get();

    $sessions = $realSessions->map(function ($session) {
        return [
            'id' => $session->id,
            'session_token' => $session->session_token,
            'user_name' => $session->user->name,
            'module_name' => $session->timetable->module->name ?? 'Unknown',
            'connection_status' => $session->connection_status,
            'camera_status' => $session->camera_status,
            'progress' => $session->progress_percentage,
            'alerts' => $session->alert_count,
        ];
    });

    // If no real sessions, create demo data for testing
    if ($sessions->isEmpty()) {
        $sessions = collect([
            [
                'id' => 1,
                'session_token' => 'demo_session_001',
                'user_name' => 'Ahmad Burningroom',
                'module_name' => 'Burningroom Technology Module 1',
                'connection_status' => 'connected',
                'camera_status' => 'active',
                'progress' => 45,
                'alerts' => 2,
            ],
            [
                'id' => 2,
                'session_token' => 'demo_session_002',
                'user_name' => 'Siti Rahayu',
                'module_name' => 'Computer Based Test Module 2',
                'connection_status' => 'connected',
                'camera_status' => 'active',
                'progress' => 67,
                'alerts' => 0,
            ],
            [
                'id' => 3,
                'session_token' => 'demo_session_003',
                'user_name' => 'Budi Santoso',
                'module_name' => 'Web Development Module 3',
                'connection_status' => 'unstable',
                'camera_status' => 'active',
                'progress' => 23,
                'alerts' => 5,
            ],
            [
                'id' => 4,
                'session_token' => 'demo_session_004',
                'user_name' => 'Dewi Lestari',
                'module_name' => 'Database Design Module 4',
                'connection_status' => 'connected',
                'camera_status' => 'active',
                'progress' => 89,
                'alerts' => 1,
            ],
        ]);
    }

    return response()->json($sessions);
});

// Get real camera streams
Route::get('/stream/real-streams', [LiveStreamController::class, 'getRealStreams'])
    ->name('api.stream.real-streams');

// Connect to specific student stream
Route::get('/stream/connect/{sessionToken}', [LiveStreamController::class, 'connectToStudentStream'])
    ->name('api.stream.connect');

// WebRTC signaling endpoint
Route::post('/stream/signaling/{sessionToken}', [LiveStreamController::class, 'handleSignaling'])
    ->name('api.stream.signaling');

// Update peer_id for live session
Route::post('/stream/update-peer-id', [LiveStreamController::class, 'updatePeerId'])
    ->name('api.stream.update-peer-id');

// Real-time monitoring endpoints
Route::get('/metrics/system', [RealTimeMetricsController::class, 'getSystemMetrics'])
    ->name('api.metrics.system');

Route::get('/metrics/livestream', [RealTimeMetricsController::class, 'getLiveStreamMetrics'])
    ->name('api.metrics.livestream');
