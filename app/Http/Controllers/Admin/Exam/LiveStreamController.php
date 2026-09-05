<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamLiveSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LiveStreamController extends Controller
{
    /**
     * Get real camera streams from students
     */
    public function getRealStreams(Request $request)
    {
        try {
            // Get active exam sessions with camera enabled (including pending status)
            $query = ExamLiveSession::where('is_active', true)
                ->whereIn('camera_status', ['active', 'pending'])
                ->with(['user', 'timetable.module']);

            // Filter by timetable_id if provided
            if ($request->has('timetable_id') && $request->input('timetable_id')) {
                $query->where('timetable_id', $request->input('timetable_id'));
            }

            $activeSessions = $query->get();

            Log::info('Found active sessions', [
                'total_sessions' => $activeSessions->count(),
                'timetable_filter' => $request->input('timetable_id') ?? 'none',
                'sessions' => $activeSessions->map(function ($s) {
                    return [
                        'id' => $s->id,
                        'user' => $s->user->name ?? 'No User',
                        'camera_status' => $s->camera_status,
                        'timetable_id' => $s->timetable_id,
                        'is_active' => $s->is_active,
                    ];
                })->toArray(),
            ]);

            $streams = [];

            foreach ($activeSessions as $session) {
                // Check if student has active WebRTC connection
                $hasRealStream = $this->checkStudentCameraConnection($session);

                $streamData = [
                    'session_id' => $session->id,
                    'session_token' => $session->session_token,
                    'user_name' => $session->user->name,
                    'module_name' => $session->timetable->module->name ?? 'Unknown',
                    'has_real_camera' => $hasRealStream,
                    'camera_status' => $session->camera_status,
                    'connection_status' => $session->connection_status,
                    'last_seen' => $session->updated_at,
                    'webrtc_endpoint' => $request->getSchemeAndHttpHost().'/api/stream/connect/'.$session->session_token,
                    'peer_id' => $session->peer_id ?? null, // PeerJS ID for direct connection
                ];

                // 🔥 DETAILED DEBUG LOGGING
                Log::info('📹 Stream data for '.$session->user->name, [
                    'peer_id' => $session->peer_id ?? 'NULL',
                    'has_real_camera' => $hasRealStream ? 'YES' : 'NO',
                    'camera_status' => $session->camera_status,
                    'updated_at' => $session->updated_at->toDateTimeString(),
                    'seconds_ago' => $session->updated_at->diffInSeconds(now()),
                ]);

                $streams[] = $streamData;
            }

            $realCameras = 0;
            foreach ($streams as $stream) {
                if ($stream['has_real_camera']) {
                    $realCameras++;
                }
            }

            return response()->json([
                'success' => true,
                'streams' => $streams,
                'total_active' => count($streams),
                'real_cameras' => $realCameras,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting real camera streams: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to get camera streams',
                'streams' => [],
            ], 500);
        }
    }

    /**
     * Connect to student camera stream
     */
    public function connectToStudentStream(Request $request, $sessionToken)
    {
        try {
            $session = ExamLiveSession::where('session_token', $sessionToken)
                ->where('is_active', true)
                ->first();

            if (! $session) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session not found or not active',
                ], 404);
            }

            // Check if student has camera active or pending
            if (! in_array($session->camera_status, ['active', 'pending'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Student camera is not available',
                    'camera_status' => $session->camera_status,
                ], 400);
            }

            // Return WebRTC connection details
            return response()->json([
                'success' => true,
                'session_token' => $sessionToken,
                'connection_type' => 'webrtc',
                'ice_servers' => [
                    ['urls' => 'stun:stun.l.google.com:19302'],
                    ['urls' => 'stun:stun1.l.google.com:19302'],
                ],
                'signaling_url' => config('app.url').'/api/stream/signaling/'.$sessionToken,
                'peer_id' => $session->peer_id, // PeerJS ID
            ]);
        } catch (\Exception $e) {
            Log::error('Error connecting to student stream: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to connect to student camera',
            ], 500);
        }
    }

    /**
     * Handle WebRTC signaling
     */
    public function handleSignaling(Request $request, $sessionToken)
    {
        try {
            $session = ExamLiveSession::where('session_token', $sessionToken)->first();

            if (! $session) {
                return response()->json(['error' => 'Session not found'], 404);
            }

            $type = $request->input('type');
            $data = $request->input('data');

            Log::info("WebRTC signaling for session {$sessionToken}: {$type}");

            // Handle different signaling messages
            switch ($type) {
                case 'offer':
                    // Store offer and relay to supervisor
                    $session->update(['webrtc_offer' => json_encode($data)]);
                    break;

                case 'answer':
                    // Store answer and relay to student
                    $session->update(['webrtc_answer' => json_encode($data)]);
                    break;

                case 'ice-candidate':
                    // Relay ICE candidates
                    Log::info("ICE candidate for session {$sessionToken}");
                    break;

                case 'camera-status':
                    // Update camera status
                    $session->update(['camera_status' => $data['status']]);
                    break;

                case 'peer-id':
                    // Store PeerJS ID
                    $session->update(['peer_id' => $data['peer_id']]);
                    Log::info("PeerJS ID stored for session {$sessionToken}: ".$data['peer_id']);
                    break;
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Signaling error: '.$e->getMessage());

            return response()->json(['error' => 'Signaling failed'], 500);
        }
    }

    /**
     * Update peer ID for student's live session
     */
    public function updatePeerId(Request $request)
    {
        try {
            $validated = $request->validate([
                'session_token' => 'required|string',
                'peer_id' => 'required|string',
            ]);

            $session = ExamLiveSession::where('session_token', $validated['session_token'])
                ->where('is_active', true)
                ->first();

            if (! $session) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session not found or inactive',
                ], 404);
            }

            $session->update([
                'peer_id' => $validated['peer_id'],
                'connection_status' => 'connected',
            ]);

            Log::info('Peer ID updated', [
                'session_id' => $session->id,
                'user' => $session->user->name,
                'peer_id' => $validated['peer_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Peer ID updated successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating peer ID: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to update peer ID',
            ], 500);
        }
    }

    /**
     * Check if student has active camera connection
     */
    private function checkStudentCameraConnection($session)
    {
        // Check if session was updated recently (within last 30 seconds)
        $lastActivity = $session->updated_at;
        $now = now();

        return $lastActivity->diffInSeconds($now) < 30;
    }
}
