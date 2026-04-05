<?php

namespace App\Http\Controllers\Api\Exam;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use App\Services\Exam\RecordingFinalizer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;

class ExamApiController extends Controller
{
    /**
     * Get the initial state of the exam for the student.
     */
    public function getInitialState($userTimetableId)
    {
        $userTimetable = UserTimetable::select('id', 'user_id', 'status', 'start_exam', 'pause_total_seconds', 'is_recording', 'is_streaming', 'company_id', 'timetable_id')
            ->with([
                'user:id,name,nim,username',
                'timetable:id,module_id,company_id',
                'timetable.module:id,name,duration',
            ])
            ->where('id', $userTimetableId)
            ->firstOrFail();

        // 1. Calculate Remaining Time
        if (!$userTimetable->start_exam) {
            $userTimetable->update(['start_exam' => now()]);
        }
        $startTime = Carbon::parse($userTimetable->start_exam);
        $duration = $userTimetable->timetable->module->duration ?? 60;
        $pauseSeconds = (int) ($userTimetable->pause_total_seconds ?? 0);
        $endTime = $startTime->addMinutes($duration)->addSeconds($pauseSeconds);
        $remainingTime = max(0, $endTime->timestamp - now()->timestamp);

        // 2. Fetch Questions & Navigation
        $questions = UserModuleQuestion::select('id', 'is_mark', 'timetable_answer_id', 'timetable_question_id', 'order')
            ->with([
                'timetableQuestion:id,question,description,latex,latex_preview_png,images',
                'timetableQuestion.answers:id,timetable_question_id,context,images,latex,latex_preview_png,order'
            ])
            ->where('user_timetable_id', $userTimetableId)
            ->orderBy('order')
            ->get();

        $navigation = $questions->map(function($q) {
            return [
                'id' => $q->id,
                'isMarked' => (bool)$q->is_mark,
                'isAnswered' => !is_null($q->timetable_answer_id),
                'order' => $q->order
            ];
        });

        $alertCount = ExamAlert::where('user_timetable_id', $userTimetableId)->count();

        // 3. Initialize Live Session if not exists
        $liveSession = ExamLiveSession::where('user_timetable_id', $userTimetableId)
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        // 4. Initialize Recording if needed
        $currentRecording = ExamRecording::where('user_timetable_id', $userTimetableId)
            ->where('status', 'recording')
            ->first();

        return response()->json([
            'userTimetable' => $userTimetable,
            'remainingTime' => $remainingTime,
            'questions' => $questions,
            'navigation' => $navigation,
            'alertCount' => $alertCount,
            'liveSession' => $liveSession,
            'isRecordingEnabled' => (bool)$userTimetable->is_recording,
            'isStreamingEnabled' => (bool)$userTimetable->is_streaming,
        ]);
    }

    /**
     * Save answer for a specific question.
     */
    public function saveAnswer(Request $request)
    {
        $validated = $request->validate([
            'question_navigation_id' => 'required|exists:user_module_questions,id',
            'timetable_answer_id' => 'nullable',
            'is_mark' => 'boolean',
        ]);

        $userModuleQuestion = UserModuleQuestion::findOrFail($validated['question_navigation_id']);
        
        // Ensure student owns this question
        $userTimetable = UserTimetable::findOrFail($userModuleQuestion->user_timetable_id);
        if ($userTimetable->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userModuleQuestion->update([
            'timetable_answer_id' => $validated['timetable_answer_id'],
            'is_mark' => $validated['is_mark'] ?? $userModuleQuestion->is_mark,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Toggle Mark.
     */
    public function toggleMark(Request $request)
    {
        $validated = $request->validate([
            'question_navigation_id' => 'required|exists:user_module_questions,id',
        ]);

        $userModuleQuestion = UserModuleQuestion::findOrFail($validated['question_navigation_id']);
        $userModuleQuestion->update([
            'is_mark' => !$userModuleQuestion->is_mark
        ]);

        return response()->json(['is_mark' => $userModuleQuestion->is_mark]);
    }

    /**
     * Log a student alert (e.g. tab switch).
     */
    public function logAlert(Request $request)
    {
        $validated = $request->validate([
            'user_timetable_id' => 'required|exists:user_timetables,id',
            'alert_type' => 'required|string',
            'description' => 'required|string',
            'metadata' => 'nullable|array',
        ]);

        $userTimetable = UserTimetable::findOrFail($validated['user_timetable_id']);
        
        ExamAlert::create([
            'timetable_id' => $userTimetable->timetable_id,
            'user_timetable_id' => $userTimetable->id,
            'alert_type' => $validated['alert_type'],
            'description' => $validated['description'],
            'metadata' => $validated['metadata'] ?? []
        ]);

        $alertCount = ExamAlert::where('user_timetable_id', $userTimetable->id)->count();

        // Increment live session count
        $liveSession = ExamLiveSession::where('user_timetable_id', $userTimetable->id)->first();
        if ($liveSession) {
            $liveSession->incrementAlert();
        }

        return response()->json(['alertCount' => $alertCount]);
    }

    /**
     * Upload a recording chunk.
     */
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'user_timetable_id' => 'required',
            'chunkBlob' => 'required', // Handles both File and Base64
            'chunkNumber' => 'required|integer',
        ]);

        $userTimetableId = $request->user_timetable_id;
        $chunkBlob = $request->chunkBlob;
        $chunkNumber = $request->chunkNumber;

        $currentRecording = ExamRecording::where('user_timetable_id', $userTimetableId)
            ->where('status', 'recording')
            ->first();

        if (!$currentRecording) {
            $userTimetable = UserTimetable::find($userTimetableId);
            if (!$userTimetable) return response()->json(['error' => 'Timetable not found'], 404);
            $currentRecording = ExamRecording::create([
                'timetable_id' => $userTimetable->timetable_id,
                'user_timetable_id' => $userTimetableId,
                'start_time' => now(),
                'status' => 'recording'
            ]);
        }

        // Decode base64 OR handle file upload
        if ($request->hasFile('chunkBlob')) {
            $videoData = file_get_contents($request->file('chunkBlob')->getRealPath());
        } else {
            if (!preg_match('/^data:video\/[^;]+/', $chunkBlob)) {
                return response()->json(['error' => 'Invalid chunk format'], 400);
            }
            $videoData = base64_decode(preg_replace('#^data:video/[^;]+;.*base64,#i', '', $chunkBlob));
        }
        
        if ($videoData === false || empty($videoData)) {
             return response()->json(['error' => 'No video data received'], 400);
        }

        // Save file
        $baseDir = 'exam_recordings/chunks/' . $userTimetableId;
        $filename = $baseDir . '/' . $currentRecording->id . '_chunk_' . $chunkNumber . '.webm';
        Storage::disk('public')->put($filename, $videoData);

        $currentRecording->update([
            'chunk_number' => $chunkNumber,
            'file_size' => ($currentRecording->file_size ?? 0) + strlen($videoData),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Finalize the recording.
     */
    public function finalizeRecording($userTimetableId)
    {
        // Launch in background
        $artisan = base_path('artisan');
        $cmd = "nohup php {$artisan} tinker --execute=\"\\App\\Services\\Exam\\RecordingFinalizer::finalizeFullExamRecording('$userTimetableId')\" > /dev/null 2>&1 &";
        shell_exec($cmd);

        return response()->json([
            'success' => true, 
            'message' => 'Finalisasi rekaman sedang berjalan di latar belakang.'
        ]);
    }

    /**
     * Heartbeat to update live session activity.
     */
    public function updateLiveSession($userTimetableId, Request $request)
    {
        $isAdmin = Auth::user() && Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin']);

        $liveSession = ExamLiveSession::where('user_timetable_id', $userTimetableId)
            ->when(!$isAdmin, function($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();
        
        if ($liveSession) {
            $liveSession->update([
                'last_activity' => now(),
                'connection_status' => strtolower($request->connection_status ?? 'connected'),
                'camera_status' => $request->camera_status ?? $liveSession->camera_status,
                'peer_id' => $request->peer_id ?? $liveSession->peer_id,
                'answered_questions' => $request->answered_count ?? $liveSession->answered_questions,
                'current_question_number' => $request->current_number ?? $liveSession->current_question_number,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Finish the exam.
     */
    public function finishExam($userTimetableId)
    {
        $userTimetable = UserTimetable::where('id', $userTimetableId)
            ->whereIn('status', ['exam', 'warning'])
            ->firstOrFail();
        
        if ($userTimetable->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Calculate score
        $questions = UserModuleQuestion::where('user_timetable_id', $userTimetableId)
            ->with(['timetableAnswer'])
            ->get();
        
        $total = $questions->count();
        $correct = 0;

        foreach ($questions as $q) {
            if ($q->timetable_answer_id) {
                if ($q->timetableAnswer && $q->timetableAnswer->is_correct) {
                    $q->update(['status' => 'correct']);
                    $correct++;
                } else {
                    $q->update(['status' => 'wrong']);
                }
            } else {
                $q->update(['status' => 'unanswered']);
            }
        }

        $mark = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        $userTimetable->update([
            'status' => 'done',
            'end_exam' => now(),
            'mark' => $mark
        ]);

        // Close live session
        ExamLiveSession::where('user_timetable_id', $userTimetableId)->update([
            'is_active' => false,
            'end_time' => now()
        ]);

        // Finalize all recordings for this timetable in the background
        try {
            // Launch background process
            $artisan = base_path('artisan');
            $cmd = "nohup php {$artisan} tinker --execute=\"\\App\\Services\\Exam\\RecordingFinalizer::finalizeFullExamRecording('$userTimetableId')\" > /dev/null 2>&1 &";
            shell_exec($cmd);
        } catch (\Exception $e) {
            Log::error('Auto Finalize Recording Failed to launch background process', [
                'user_timetable_id' => $userTimetableId,
                'error' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.exam.timetable')
        ]);
    }

    /**
     * Get a LiveKit token for the student to stream their camera.
     */
    public function getLiveKitToken($userTimetableId)
    {
        $userTimetable = UserTimetable::findOrFail($userTimetableId);
        $user = Auth::user();

        $apiKey = config('services.livekit.api_key');
        $apiSecret = config('services.livekit.api_secret');
        $serverUrl = config('services.livekit.host');

        if (!$apiKey || !$apiSecret || !$serverUrl) {
            return response()->json(['error' => 'LiveKit credentials not configured'], 500);
        }

        $roomName = 'exam_' . $userTimetable->timetable_id;
        $identity = 'student_' . $user->id;

        // 1. Define participant and room details
        $tokenOptions = (new AccessTokenOptions())
            ->setIdentity($identity)
            ->setMetadata(json_encode([
                'name' => $user->name,
                'user_timetable_id' => $userTimetableId
            ]));

        // 2. Define the video grants
        $videoGrant = (new VideoGrant())
            ->setRoomJoin()
            ->setRoomName($roomName)
            ->setCanPublish(true)
            ->setCanSubscribe(false); // Only publish for proctoring

        // 3. Initialize and generate the JWT Token
        try {
            $token = (new AccessToken($apiKey, $apiSecret))
                ->init($tokenOptions)
                ->setGrant($videoGrant)
                ->toJwt();

            return response()->json([
                'serverUrl' => $serverUrl,
                'token' => $token,
                'roomName' => $roomName,
                'identity' => $identity,
            ]);
        } catch (\Exception $e) {
            Log::error('LiveKit Token Generation Failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
            ]);
            return response()->json(['error' => 'Failed to generate token'], 500);
        }
    }

    /**
     * Get all active sessions for monitoring. (Admin Only)
     */
    public function getMonitoringSessions($timetableId)
    {
        // Ensure only admins/supervisors can access
        if (!Auth::user() || !Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sessions = ExamLiveSession::with(['user', 'userTimetable'])
            ->where('timetable_id', $timetableId)
            ->where('is_active', true)
            ->get()
            ->map(function($session) {
                return [
                    'id' => $session->id,
                    'user_id' => $session->user_id,
                    'name' => $session->user->name ?? 'Unknown',
                    'connection_status' => $session->connection_status,
                    'camera_status' => $session->camera_status,
                    'alert_count' => $session->alert_count,
                    'warning_count' => $session->warning_count,
                    'answered_questions' => $session->answered_questions,
                    'total_questions' => $session->total_questions,
                    'current_question' => $session->current_question_number,
                    'last_activity' => $session->last_activity ? $session->last_activity->toIso8601String() : null,
                    'identity' => 'student_' . $session->user_id,
                ];
            });

        return response()->json([
            'success' => true,
            'sessions' => $sessions
        ]);
    }

    /**
     * Get a LiveKit token for the admin to monitor all streams. (Admin Only)
     */
    public function getMonitoringToken($timetableId)
    {
        // Ensure only admins/supervisors can access
        if (!Auth::user() || !Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $apiKey = config('services.livekit.api_key');
        $apiSecret = config('services.livekit.api_secret');
        $serverUrl = config('services.livekit.host');

        if (!$apiKey || !$apiSecret || !$serverUrl) {
            return response()->json(['error' => 'LiveKit credentials not configured'], 500);
        }

        $roomName = 'exam_' . $timetableId;
        $identity = 'admin_' . Auth::id();

        $tokenOptions = (new AccessTokenOptions())
            ->setIdentity($identity);

        // Admin can join and subscribe, but doesn't need to publish
        $videoGrant = (new VideoGrant())
            ->setRoomJoin()
            ->setRoomName($roomName)
            ->setCanPublish(false)
            ->setCanSubscribe(true);

        try {
            $token = (new AccessToken($apiKey, $apiSecret))
                ->init($tokenOptions)
                ->setGrant($videoGrant)
                ->toJwt();

            return response()->json([
                'serverUrl' => $serverUrl,
                'token' => $token,
                'roomName' => $roomName,
            ]);
        } catch (\Exception $e) {
            Log::error('Admin LiveKit Token Generation Failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to generate admin token'], 500);
        }
    }
}
