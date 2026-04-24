<?php

namespace App\Http\Controllers\Api\Exam;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;
use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ExamApiController extends Controller
{
    protected $userTimetableId;
    protected $userTimetable;
    protected $remainingTime = 0;

    /**
     * Get the initial state of the exam for the student.
     */
    public function getInitialState($userTimetableId)
    {
        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();

        $userTimetable = UserTimetable::withoutGlobalScopes()
            ->select('id', 'user_id', 'status', 'start_exam', 'pause_total_seconds', 'is_recording', 'is_streaming', 'company_id', 'timetable_id')
            ->with([
                'user:id,name,nim,username',
                'timetable' => function ($q) {
                    $q->withoutGlobalScopes()->select('id', 'module_id', 'company_id', 'is_simulation');
                },
                'timetable.module' => function ($q) {
                    $q->withoutGlobalScopes()->select('id', 'name', 'duration');
                },
            ])
            ->where('id', $userTimetableId)
            ->firstOrFail();

        $this->userTimetableId = $userTimetableId;
        $this->userTimetable = $userTimetable;

        // 1. Resume timer jika sebelumnya di-pause (oleh admin atau force logout)
        // Ini akan menghitung selisih waktu dari 'paused_at' sampai 'now' 
        // dan menambahkannya ke 'pause_total_seconds'
        $this->remainingTime = $this->resumeTimerIfPaused();


        // 2. Fetch Questions & Navigation
        $questionSelects = ['id', 'is_mark', 'timetable_answer_id', 'timetable_question_id', 'order'];
        if ($hasEssayAnswerColumn) {
            $questionSelects[] = 'essay_answer';
        }

        $questions = UserModuleQuestion::withoutGlobalScopes()
            ->select($questionSelects)
            ->with([
                'timetableQuestion' => function ($q) {
                    $q->withoutGlobalScopes()->select('id', 'type', 'question', 'description', 'latex', 'latex_preview_png', 'images');
                },
                'timetableQuestion.answers' => function ($q) {
                    $q->withoutGlobalScopes()->select('id', 'timetable_question_id', 'context', 'images', 'latex', 'latex_preview_png', 'order');
                },
            ])
            ->where('user_timetable_id', $userTimetableId)
            ->orderBy('order')
            ->get()
            ->values();

        $navigation = $questions->map(function ($q) use ($hasEssayAnswerColumn) {
            $essayAnswer = $hasEssayAnswerColumn ? ($q->essay_answer ?? null) : null;

            return [
                'id' => $q->id,
                'isMarked' => (bool) $q->is_mark,
                'isAnswered' => ! is_null($q->timetable_answer_id) || ! empty($essayAnswer),
                'order' => $q->order,
            ];
        })->values();

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
            'remainingTime' => (int) $this->remainingTime,
            'questions' => $questions,
            'navigation' => $navigation,
            'alertCount' => $alertCount,
            'liveSession' => $liveSession,
            'isRecordingEnabled' => (bool) $userTimetable->is_recording,
            'isStreamingEnabled' => (bool) $userTimetable->is_streaming,
        ]);
    }

    /**
     * Save answer for a specific question.
     */
    public function saveAnswer(Request $request)
    {
        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();

        $validated = $request->validate([
            'question_navigation_id' => 'required|exists:user_module_questions,id',
            'timetable_answer_id' => 'nullable',
            'essay_answer' => 'nullable|string',
            'is_mark' => 'boolean',
        ]);

        $userModuleQuestion = UserModuleQuestion::findOrFail($validated['question_navigation_id']);

        // Ensure student owns this question
        $userTimetable = UserTimetable::findOrFail($userModuleQuestion->user_timetable_id);
        if ($userTimetable->user_id !== Auth::id() && ! Auth::user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $payload = [
            'timetable_answer_id' => $validated['timetable_answer_id'],
            'is_mark' => $validated['is_mark'] ?? $userModuleQuestion->is_mark,
        ];

        if ($hasEssayAnswerColumn) {
            $payload['essay_answer'] = $validated['essay_answer'] ?? null;
        }

        $userModuleQuestion->update($payload);

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
            'is_mark' => ! $userModuleQuestion->is_mark,
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
            'metadata' => $validated['metadata'] ?? [],
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

        if (! $currentRecording) {
            $userTimetable = UserTimetable::find($userTimetableId);
            if (! $userTimetable) {
                return response()->json(['error' => 'Timetable not found'], 404);
            }
            $currentRecording = ExamRecording::create([
                'timetable_id' => $userTimetable->timetable_id,
                'user_timetable_id' => $userTimetableId,
                'user_id' => $userTimetable->user_id,
                'start_time' => now(),
                'status' => 'recording',
            ]);
        }

        // Decode base64 OR handle file upload
        if ($request->hasFile('chunkBlob')) {
            $videoData = file_get_contents($request->file('chunkBlob')->getRealPath());
        } else {
            if (! preg_match('/^data:video\/[^;]+/', $chunkBlob)) {
                return response()->json(['error' => 'Invalid chunk format'], 400);
            }
            $videoData = base64_decode(preg_replace('#^data:video/[^;]+;.*base64,#i', '', $chunkBlob));
        }

        if ($videoData === false || empty($videoData)) {
            return response()->json(['error' => 'No video data received'], 400);
        }

        // Save file
        $baseDir = 'exam_recordings/chunks/'.$userTimetableId;
        $filename = $baseDir.'/'.$currentRecording->id.'_chunk_'.$chunkNumber.'.webm';
        Storage::disk('public')->put($filename, $videoData);

        $currentRecording->update([
            'chunk_number' => $chunkNumber,
            'file_size' => ($currentRecording->file_size ?? 0) + strlen($videoData),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Upload the full recording at the end of the exam.
     */
    public function uploadFullRecording(Request $request)
    {
        $request->validate([
            'user_timetable_id' => 'required',
            'videoBlob' => 'required', // Handles both File and Base64
        ]);

        $userTimetableId = $request->user_timetable_id;
        $videoBlob = $request->videoBlob;

        $userTimetable = UserTimetable::find($userTimetableId);
        if (! $userTimetable) {
            return response()->json(['error' => 'Timetable not found'], 404);
        }

        $currentRecording = ExamRecording::where('user_timetable_id', $userTimetableId)
            ->where('status', 'recording')
            ->first();

        if (! $currentRecording) {
            $currentRecording = ExamRecording::create([
                'timetable_id' => $userTimetable->timetable_id,
                'user_timetable_id' => $userTimetableId,
                'start_time' => now(),
                'status' => 'recording',
            ]);
        }

        // Decode base64 OR handle file upload
        if ($request->hasFile('videoBlob')) {
            $videoData = file_get_contents($request->file('videoBlob')->getRealPath());
        } else {
            if (! preg_match('/^data:video\/[^;]+/', $videoBlob)) {
                return response()->json(['error' => 'Invalid video format'], 400);
            }
            $videoData = base64_decode(preg_replace('#^data:video/[^;]+;.*base64,#i', '', $videoBlob));
        }

        if ($videoData === false || empty($videoData)) {
            return response()->json(['error' => 'No video data received'], 400);
        }

        // Save file
        $baseDir = 'exam_recordings/'.$userTimetableId;
        $filename = $baseDir.'/'.$currentRecording->id.'_full.webm';
        Storage::disk('public')->put($filename, $videoData);

        // Save to database directly
        $currentRecording->update([
            'video_path' => $filename,
            'file_size' => strlen($videoData),
            'end_time' => now(),
            'status' => 'completed', // Mark as completed since no chunk merging is needed
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Heartbeat to update live session activity.
     */
    public function updateLiveSession($userTimetableId, Request $request)
    {
        $isAdmin = Auth::user() && Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin']);

        $liveSession = ExamLiveSession::where('user_timetable_id', $userTimetableId)
            ->when(! $isAdmin, function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->first();

        if ($liveSession) {
            $connStatus = strtolower($request->connection_status ?? 'connected');
            if ($connStatus === 'connection error') {
                $connStatus = 'error';
            }

            $liveSession->update([
                'last_activity' => now(),
                'connection_status' => $connStatus,
                'camera_status' => $request->camera_status ?? $liveSession->camera_status,
                'peer_id' => $request->peer_id ?? $liveSession->peer_id,
                'answered_questions' => $request->answered_count ?? $liveSession->answered_questions,
                'current_question_number' => $request->current_number ?? $liveSession->current_question_number,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Merge all uploaded chunks into a single video using FFmpeg.
     * Called after the last chunk has been uploaded (when the exam ends).
     */
    public function mergeRecordingChunks(Request $request)
    {
        $request->validate([
            'user_timetable_id' => 'required',
        ]);

        $userTimetableId = $request->user_timetable_id;

        $currentRecording = ExamRecording::where('user_timetable_id', $userTimetableId)
            ->where('status', 'recording')
            ->first();

        if (! $currentRecording) {
            return response()->json(['error' => 'No active recording found'], 404);
        }

        $chunksDir = storage_path('app/public/exam_recordings/chunks/'.$userTimetableId);
        $outputDir = storage_path('app/public/exam_recordings/'.$userTimetableId);
        $outputFile = $outputDir.'/'.$currentRecording->id.'_merged.webm';
        $concatFile = $chunksDir.'/concat_list.txt';

        // Ensure output dir exists
        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Find all chunk files, sorted numerically
        $chunkPattern = $chunksDir.'/'.$currentRecording->id.'_chunk_*.webm';
        $chunkFiles = glob($chunkPattern);

        if (empty($chunkFiles)) {
            // No chunks found — mark as failed
            $currentRecording->update(['status' => 'failed', 'end_time' => now()]);

            return response()->json(['error' => 'No chunks found to merge'], 422);
        }

        // Sort by chunk number
        usort($chunkFiles, function ($a, $b) {
            preg_match('/_chunk_(\d+)\.webm$/', $a, $mA);
            preg_match('/_chunk_(\d+)\.webm$/', $b, $mB);

            return (int) ($mA[1] ?? 0) <=> (int) ($mB[1] ?? 0);
        });

        // Write FFmpeg concat list
        $lines = array_map(fn ($f) => "file '".str_replace("'", "'\\''", $f)."'", $chunkFiles);
        file_put_contents($concatFile, implode("\n", $lines));

        // Run FFmpeg in the background (non-blocking)
        $ffmpegBin = '/usr/bin/ffmpeg';
        $cmd = escapeshellcmd(
            "$ffmpegBin -y -f concat -safe 0 -i ".escapeshellarg($concatFile).
            ' -c copy '.escapeshellarg($outputFile)
        ).' > /dev/null 2>&1 &';

        exec($cmd);

        // Mark as completed and store final path
        $relPath = 'exam_recordings/'.$userTimetableId.'/'.$currentRecording->id.'_merged.webm';
        $currentRecording->update([
            'status' => 'merging',
            'video_path' => $relPath,
            'end_time' => now(),
        ]);

        Log::info('[Recording] FFmpeg merge triggered for recording #'.$currentRecording->id, [
            'chunks' => count($chunkFiles),
            'output' => $outputFile,
        ]);

        return response()->json([
            'success' => true,
            'chunk_count' => count($chunkFiles),
            'output_path' => $relPath,
        ]);
    }

    /**
     * Finish the exam.
     */
    public function finishExam($userTimetableId)
    {
        $userTimetable = UserTimetable::where('id', $userTimetableId)
            ->whereIn('status', ['exam', 'warning'])
            ->firstOrFail();

        if ($userTimetable->user_id !== Auth::id() && ! Auth::user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Calculate score
        $questions = UserModuleQuestion::where('user_timetable_id', $userTimetableId)
            ->with(['timetableAnswer', 'timetableQuestion'])
            ->get();

        $total = $questions->count();
        $correct = 0;

        foreach ($questions as $q) {
            $type = $q->timetableQuestion->type ?? 'single';

            if ($type === 'essay') {
                if ($q->essay_answer) {
                    $q->update(['status' => 'check']);
                } else {
                    $q->update(['status' => 'unanswered']);
                }

                continue;
            }

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
            'mark' => $mark,
        ]);

        // Close live session
        ExamLiveSession::where('user_timetable_id', $userTimetableId)->update([
            'is_active' => false,
            'end_time' => now(),
        ]);

        // Finalization background job removed because recording is now saved fully without chunking.

        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.exam.timetable'),
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

        if (! $apiKey || ! $apiSecret || ! $serverUrl) {
            return response()->json(['error' => 'LiveKit credentials not configured'], 500);
        }

        $roomName = 'exam_'.$userTimetable->timetable_id;
        $identity = 'student_'.$user->id;

        // 1. Define participant and room details
        $tokenOptions = (new AccessTokenOptions)
            ->setIdentity($identity)
            ->setMetadata(json_encode([
                'name' => $user->name,
                'user_timetable_id' => $userTimetableId,
            ]));

        // 2. Define the video grants
        $videoGrant = (new VideoGrant)
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
                'user_id' => $user->id,
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
        if (! Auth::user() || ! Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $sessions = ExamLiveSession::with(['user', 'userTimetable'])
            ->where('timetable_id', $timetableId)
            ->where('is_active', true)
            ->get()
            ->map(function ($session) {
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
                    'identity' => 'student_'.$session->user_id,
                ];
            });

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
        ]);
    }

    /**
     * Get a LiveKit token for the admin to monitor all streams. (Admin Only)
     */
    public function getMonitoringToken($timetableId)
    {
        // Ensure only admins/supervisors can access
        if (! Auth::user() || ! Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $apiKey = config('services.livekit.api_key');
        $apiSecret = config('services.livekit.api_secret');
        $serverUrl = config('services.livekit.host');

        if (! $apiKey || ! $apiSecret || ! $serverUrl) {
            return response()->json(['error' => 'LiveKit credentials not configured'], 500);
        }

        $roomName = 'exam_'.$timetableId;
        $identity = 'admin_'.Auth::id();

        $tokenOptions = (new AccessTokenOptions)
            ->setIdentity($identity);

        // Admin can join and subscribe, but doesn't need to publish
        $videoGrant = (new VideoGrant)
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

    /**
     * essay_answer is optional in some deployments.
     */
    private function hasEssayAnswerColumn(): bool
    {
        static $hasColumn;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('user_module_questions', 'essay_answer');
        }

        return $hasColumn;
    }

    /**
     * Resume timer jika status sedang di-pause.
     * Menghitung akumulasi waktu jeda dan mengupdate database.
     */
    public function resumeTimerIfPaused(): int
    {
        // Refresh data terbaru dari database
        $this->userTimetable = UserTimetable::withoutGlobalScopes()
            ->select('id', 'paused_at', 'pause_total_seconds', 'start_exam', 'timetable_id')
            ->with(['timetable.module:id,duration'])
            ->find($this->userTimetableId) ?? $this->userTimetable;

        if (!$this->userTimetable) {
            return 0;
        }

        if (!is_null($this->userTimetable->paused_at)) {
            $pausedAt = Carbon::parse($this->userTimetable->paused_at);
            // Hitung berapa detik ujian terhenti (dari paused_at sampai sekarang)
            $delta = (int) abs(now()->diffInSeconds($pausedAt));
            $newTotal = (int) ($this->userTimetable->pause_total_seconds ?? 0) + $delta;

            $this->userTimetable->update([
                'pause_total_seconds' => $newTotal,
                'paused_at' => null,
            ]);

            Log::info('⏯️ Resumed timer, accumulated pause seconds', [
                'user_timetable_id' => $this->userTimetable->id,
                'added_seconds' => $delta,
                'pause_total_seconds' => $newTotal,
            ]);
        }

        // Hitung ulang sisa waktu setelah update
        $this->calculateRemainingTime();
        return (int) $this->remainingTime;
    }

    /**
     * Kalkulasi sisa waktu berdasarkan start_exam, duration, dan pause_total_seconds.
     */
    private function calculateRemainingTime()
    {
        if (!$this->userTimetable || !$this->userTimetable->start_exam) {
            $this->remainingTime = 0;
            return;
        }

        $startTime = Carbon::parse($this->userTimetable->start_exam);
        // Pastikan relasi module sudah ada
        $duration = $this->userTimetable->timetable->module->duration ?? 60;
        $pauseSeconds = (int) ($this->userTimetable->pause_total_seconds ?? 0);
        
        $endTime = $startTime->addMinutes($duration)->addSeconds($pauseSeconds);
        $this->remainingTime = max(0, $endTime->timestamp - now()->timestamp);
    }
}

