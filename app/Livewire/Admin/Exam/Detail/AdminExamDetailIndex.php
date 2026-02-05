<?php

namespace App\Livewire\Admin\Exam\Detail;

use App\Helpers\AlertHelper;
use App\Helpers\AuthHelper;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\Master\Question\Answer;
use App\Models\Timetable\TimetableAnswer;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Services\Exam\RecordingFinalizer;

class AdminExamDetailIndex extends Component
{
    public $userTimetableId;
    public $remainingTime;
    public $userTimetable;
    public $questionNavigations = [];
    public $questionNavigationId;
    public $isMark = false;
    public $percentage = 0;
    public $timetable_answer_id;
    public $question;
    public $images = [];
    public $description;
    public $number;
    public $question_answers = [];
    public $currentRecording = null;
    public $alertCount = 0;
    public $liveSession = null;
    public $peerJSId = null;

    protected $listeners = [
        'timeExpired',
        'saveRecordingVideo' => 'saveRecordingVideo',
        'saveRecordingChunk' => 'saveRecordingChunk',
        'logAlert',
        'stopRecording',
        'pageReloaded',
        'updateLiveSessionData',
        'saveScreenshot',
        'initializePeerJS',
        'updatePeerJSId',
        'completeExamFinalization',
        'finalizeRecording' => 'finalizeRecording',
        'completeSuspendFinalization'
    ];

    public function mount()
    {
        $this->initializeExam();
        $this->setupFirstQuestion();
        $this->handleSessionMessages();
        $this->initializeRecording();
        $this->initializeLiveSession();
    }

    private function initializeLiveSession()
    {
        if (!$this->userTimetable || !$this->userTimetable->timetable_id) {
            return redirect()->route('admin.exam.timetable');
        }

        // Enforce single-device login: block new session if another active session exists with different session_id
        $currentSessionId = session()->getId();
        $existingActive = ExamLiveSession::where('user_timetable_id', $this->userTimetableId)
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->orderByDesc('last_activity')
            ->first();

        // Check if existing session is "stale" (inactive for > 2 minutes)
        $isStale = false;
        if ($existingActive) {
            $lastActivity = Carbon::parse($existingActive->last_activity);
            if (now()->diffInMinutes($lastActivity) > 2) {
                $isStale = true;
                // Auto-close stale session
                $existingActive->update(['is_active' => false, 'connection_status' => 'timeout']);
            }
        }

        if ($existingActive && !$isStale && data_get($existingActive->session_metadata, 'session_id') && data_get($existingActive->session_metadata, 'session_id') !== $currentSessionId) {
            ExamAlert::create([
                'timetable_id' => $this->userTimetable->timetable_id,
                'user_timetable_id' => $this->userTimetableId,
                'alert_type' => 'connection_lost',
                'description' => 'Percobaan login dari perangkat lain terdeteksi (Sesi aktif ditemukan)',
                'metadata' => [
                    'previous_session_id' => data_get($existingActive->session_metadata, 'session_id'),
                    'new_session_id' => $currentSessionId,
                    'user_agent' => request()->header('User-Agent'),
                    'ip' => request()->ip(),
                ],
            ]);

            // Optional: Don't immediately suspend/warn heavily if it's just a reconnect
            // if ($this->userTimetable && in_array($this->userTimetable->status, ['exam', 'warning'])) {
            //     $this->userTimetable->update(['status' => 'warning']);
            // }

            AlertHelper::warning('Perhatian', 'Akun Anda tercatat masih aktif di perangkat lain/tab lain. Jika Anda baru saja refresh/reconnect, tunggu 2 menit atau hubungi pengawas.');
            return redirect()->route('admin.exam.monitor');
        }

        // Buat atau update live session
        $this->liveSession = ExamLiveSession::updateOrCreate(
            [
                'user_timetable_id' => $this->userTimetableId,
                'user_id' => Auth::id()
            ],
            [
                'timetable_id' => $this->userTimetable->timetable_id,
                'company_id' => $this->userTimetable->company_id,
                'is_active' => true,
                'connection_status' => 'connected',
                'camera_status' => 'pending',
                'screen_status' => 'pending',
                'last_activity' => Carbon::now(),
                'session_metadata' => [
                    'start_time' => Carbon::now()->toISOString(),
                    'user_agent' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                    'session_id' => $currentSessionId,
                ],
                'browser_info' => [
                    'user_agent' => request()->header('User-Agent'),
                    'platform' => $this->detectPlatform(request()->header('User-Agent'))
                ],
                'peer_id' => $this->peerJSId // Simpan PeerJS ID untuk koneksi langsung
            ]
        );

        $this->updateLiveSessionProgress();
    }

    private function detectPlatform($userAgent)
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Mac')) return 'Mac';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iOS')) return 'iOS';
        return 'Unknown';
    }

    private function updateLiveSessionProgress()
    {
        if (!$this->liveSession) return;

        $questions = $this->getUserQuestions();

        $this->liveSession->update([
            'current_question_number' => $this->number ?? 1,
            'total_questions' => $questions->count(),
            'answered_questions' => $questions->whereNotNull('timetable_answer_id')->count(),
            'marked_questions' => $questions->where('is_mark', true)->count(),
            'warning_count' => $this->alertCount,
            'alert_count' => ExamAlert::where('user_timetable_id', $this->userTimetableId)->count(),
            'last_activity' => Carbon::now()
        ]);
    }

    // Cek auth untuk mendeteksi force logout dari pengawas
    public function checkAuth(): bool
    {
        return Auth::check();
    }

    // Mengambil status UserTimetable terkini untuk polling status
    public function getUserTimetableStatus(): ?string
    {
        // Jika belum set id, cari yang aktif untuk user
        if (!$this->userTimetableId) {
            $active = UserTimetable::where('user_id', Auth::id())
                ->whereIn('status', ['exam', 'warning', 'suspend', 'done'])
                ->orderByDesc('updated_at')
                ->first();
            return $active?->status;
        }

        $current = UserTimetable::find($this->userTimetableId);
        return $current?->status;
    }

    public function initializePeerJS()
    {
        // Method ini akan dipanggil dari JavaScript setelah PeerJS berhasil diinisialisasi
        \Log::info('PeerJS initialization requested for user', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId
        ]);
    }

    public function updatePeerJSId($peerId)
    {
        \Log::info('🔥 updatePeerJSId CALLED via Livewire', [
            'peer_id' => $peerId,
            'user_id' => Auth::id(),
            'live_session_exists' => $this->liveSession ? 'yes' : 'no'
        ]);

        $this->peerJSId = $peerId;

        // Update live session dengan PeerJS ID
        if ($this->liveSession) {
            $this->liveSession->update([
                'peer_id' => $peerId,
                'camera_status' => 'active', // Anggap kamera aktif jika PeerJS berhasil
                'last_activity' => Carbon::now()
            ]);

            \Log::info('✅ PeerJS ID updated for live session in DB', [
                'peer_id' => $peerId,
                'live_session_id' => $this->liveSession->id
            ]);
        } else {
             \Log::warning('⚠️ liveSession is NULL in updatePeerJSId');
        }

        // Emit event ke JavaScript untuk memberitahu bahwa PeerJS ID sudah disimpan
        $this->dispatch('peerJSIdSaved', ['peer_id' => $peerId]);
    }

    private function initializeRecording()
    {
        if (!$this->userTimetable || !$this->userTimetable->timetable_id) {
            return redirect()->route('admin.exam.timetable');
        }

        // Cek apakah fitur recording diaktifkan untuk peserta ini (atau global jika logic diubah nanti)
        // Asumsi field 'is_recording' ada di tabel user_timetables
        if (!$this->userTimetable->is_recording) {
             \Log::info('Recording skipped (is_recording=false)', [
                'user_id' => Auth::id(),
                'user_timetable_id' => $this->userTimetableId
            ]);
            return;
        }

        // Buat recording entry baru
        $this->currentRecording = ExamRecording::create([
            'timetable_id' => $this->userTimetable->timetable_id,
            'user_timetable_id' => $this->userTimetableId,
            'start_time' => now(),
            'status' => 'recording'
        ]);

        \Log::info('Recording initialized', [
            'user_id' => Auth::id(),
            'recording_id' => $this->currentRecording->id
        ]);
    }

    /**
     * Save a single recording chunk sent from the client.
     * Expects base64 data URI in $chunkBlob (data:video/...;base64,...) and a chunkNumber in $data.
     */
    public function saveRecordingChunk($chunkBlob = '', $data = [])
    {
        try {
            if (!$this->currentRecording) {
                \Log::warning('❌ saveRecordingChunk called without currentRecording', [
                    'user_id' => Auth::id(),
                    'user_timetable_id' => $this->userTimetableId,
                ]);
                return false;
            }

            // Normalize input parameters
            $actualChunkBlob = $chunkBlob;
            if (is_array($chunkBlob) && isset($chunkBlob['chunkBlob'])) {
                $actualChunkBlob = $chunkBlob['chunkBlob'];
            } elseif (is_array($data) && isset($data['chunkBlob'])) {
                $actualChunkBlob = $data['chunkBlob'];
            }

            $chunkNumber = null;
            if (is_array($chunkBlob) && isset($chunkBlob['chunkNumber'])) {
                $chunkNumber = (int) $chunkBlob['chunkNumber'];
            } elseif (is_array($data) && isset($data['chunkNumber'])) {
                $chunkNumber = (int) $data['chunkNumber'];
            }

            // Validate format
            if (empty($actualChunkBlob) || !preg_match('/^data:video\/[^;]+;.*base64,/', $actualChunkBlob)) {
                \Log::error('❌ Invalid chunk blob format', [
                    'user_id' => Auth::id(),
                    'blob_preview' => is_string($actualChunkBlob) ? substr($actualChunkBlob, 0, 80) : 'not_string',
                ]);
                return false;
            }

            // Decode
            $videoData = base64_decode(preg_replace('#^data:video/[^;]+;.*base64,#i', '', $actualChunkBlob));
            if ($videoData === false || strlen($videoData) === 0) {
                \Log::error('❌ Failed to decode chunk video data', [
                    'user_id' => Auth::id(),
                ]);
                return false;
            }

            // Determine chunk number
            if (!$chunkNumber || $chunkNumber <= 0) {
                $chunkNumber = ($this->currentRecording->chunk_number ?? 0) + 1;
            }

            // Build path: store under chunks directory per userTimetable
            $baseDir = 'exam_recordings/chunks/' . $this->userTimetableId;
            $filename = $baseDir . '/' . $this->currentRecording->id . '_chunk_' . $chunkNumber . '.webm';

            $disk = Storage::disk('public');
            if (!$disk->exists($baseDir)) {
                $disk->makeDirectory($baseDir);
            }

            $saveResult = $disk->put($filename, $videoData);
            if (!$saveResult) {
                \Log::error('❌ Failed to save chunk file', [
                    'filename' => $filename,
                ]);
                return false;
            }

            $fileSize = $disk->size($filename);

            // Update aggregate info on current recording
            $newFileSizeTotal = ($this->currentRecording->file_size ?? 0) + $fileSize;
            $this->currentRecording->update([
                'chunk_number' => $chunkNumber,
                'file_size' => $newFileSizeTotal,
            ]);

            \Log::info('✅ Chunk saved', [
                'user_id' => Auth::id(),
                'recording_id' => $this->currentRecording->id,
                'chunk_number' => $chunkNumber,
                'chunk_size' => $fileSize,
                'total_size' => $newFileSizeTotal,
                'path' => $filename,
            ]);

            // Inform client that chunk saved
            $this->dispatch('chunkSaved', [
                'chunk_number' => $chunkNumber,
                'size' => $fileSize,
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('💥 Error saving recording chunk', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Finalize a chunked recording: write manifest and mark as completed.
     */
    public function finalizeRecording($data = [])
    {
        try {
            if (!$this->currentRecording) {
                return false;
            }

            $result = RecordingFinalizer::finalizeForUserTimetable($this->userTimetableId);

            $this->currentRecording->update([
                'video_path' => $result['merged_video'] ?: $result['manifest'],
                'file_size' => $result['total_size'],
                'end_time' => now(),
                'status' => 'completed',
            ]);

            \Log::info('🎉 Recording finalized', [
                'recording_id' => $this->currentRecording->id,
                'manifest' => $result['manifest'],
                'merged_video' => $result['merged_video'],
                'total_size' => $result['total_size'],
                'chunk_count' => $result['chunk_count'],
            ]);

            $this->dispatch('recordingFinalized', [
                'manifest' => $result['manifest'],
                'merged_video' => $result['merged_video'],
                'chunk_count' => $result['chunk_count'],
            ]);

            if (method_exists($this, 'completeExamFinalization')) {
                try {
                    $this->completeExamFinalization();
                } catch (\Throwable $t) {
                    \Log::warning('completeExamFinalization call failed or not implemented');
                }
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('💥 Error finalizing recording', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    public function saveRecordingVideo($videoBlob = '', $data = [])
    {
        // Handle different parameter formats from different calling methods
        $isEmergencyRecovery = false;
        if (is_array($videoBlob) && isset($videoBlob['videoBlob'])) {
            $actualVideoBlob = $videoBlob['videoBlob'];
            $isEmergencyRecovery = $videoBlob['isEmergencyRecovery'] ?? false;
        } elseif (is_array($data) && isset($data['videoBlob'])) {
            $actualVideoBlob = $data['videoBlob'];
            $isEmergencyRecovery = $data['isEmergencyRecovery'] ?? false;
        } else {
            $actualVideoBlob = $videoBlob;
        }

        // Extract compression info if available
        $compressionInfo = null;
        if (is_array($videoBlob) && isset($videoBlob['compressionInfo'])) {
            $compressionInfo = $videoBlob['compressionInfo'];
        } elseif (is_array($data) && isset($data['compressionInfo'])) {
            $compressionInfo = $data['compressionInfo'];
        }

        \Log::info('🚀 saveRecordingVideo METHOD CALLED!', [
            'called_at' => now()->toISOString(),
            'user_id' => Auth::id(),
            'is_emergency_recovery' => $isEmergencyRecovery,
            'video_optimized' => $compressionInfo['optimized'] ?? false,
            'compression_savings' => $compressionInfo['compressionSavings'] ?? '0%',
            'original_size_mb' => $compressionInfo['originalSize'] ?? 'unknown',
            'raw_parameters' => func_get_args(),
            'video_blob_type' => gettype($videoBlob),
            'video_blob_received' => !empty($actualVideoBlob),
            'video_blob_length' => is_string($actualVideoBlob) ? strlen($actualVideoBlob) : 'not_string',
            'data_parameter' => $data
        ]);

        // Use the actual video blob for processing
        $videoBlob = $actualVideoBlob;

        // Debug output to browser console
        $this->js('console.log("🚀 PHP METHOD saveRecordingVideo CALLED! Video length: ' . strlen($videoBlob) . '");');

        try {
            \Log::info('🎬 saveRecordingVideo processing', [
                'user_id' => Auth::id(),
                'user_timetable_id' => $this->userTimetableId,
                'has_current_recording' => !is_null($this->currentRecording),
                'video_blob_length' => strlen($videoBlob),
                'video_blob_preview' => substr($videoBlob, 0, 100)
            ]);

            if (empty($videoBlob) || !$this->currentRecording) {
                \Log::warning('❌ No video data or recording session', [
                    'user_id' => Auth::id(),
                    'has_video_blob' => !empty($videoBlob),
                    'has_current_recording' => !is_null($this->currentRecording)
                ]);

                return false;
            }

            // Check video blob format - improved regex to handle codecs parameter
            if (!preg_match('/^data:video\/[^;]+;.*base64,/', $videoBlob)) {
                \Log::error('❌ Invalid video blob format', [
                    'user_id' => Auth::id(),
                    'blob_start' => substr($videoBlob, 0, 100)
                ]);

                return false;
            }
            \Log::info('✅ Video blob format valid, decoding...');

            // Decode base64 video data - improved regex to handle codecs parameter
            $videoData = base64_decode(preg_replace('#^data:video/[^;]+;.*base64,#i', '', $videoBlob));

            if ($videoData === false || strlen($videoData) === 0) {
                \Log::error('❌ Failed to decode video data', [
                    'user_id' => Auth::id(),
                    'decode_result' => $videoData === false ? 'false' : 'empty'
                ]);

                return false;
            }

            \Log::info('✅ Video data decoded successfully', [
                'original_size' => strlen($videoBlob),
                'decoded_size' => strlen($videoData)
            ]);

            // Create final filename
            $recoveryPrefix = $isEmergencyRecovery ? 'RECOVERY_' : '';
            $filename = 'exam_recordings/' . $recoveryPrefix . $this->userTimetableId . '_exam_' .
                now()->format('Y-m-d_H-i-s') . '.webm';

            \Log::info('💾 Saving to file: ' . $filename);

            // Save to storage
            $disk = Storage::disk('public');
            $directory = dirname($filename);

            if (!$disk->exists($directory)) {
                $disk->makeDirectory($directory);
                \Log::info('📁 Created directory: ' . $directory);
            }

            $saveResult = $disk->put($filename, $videoData);

            \Log::info('💾 Save result: ' . ($saveResult ? 'SUCCESS' : 'FAILED'));

            if ($saveResult) {
                $fileSize = $disk->size($filename);
                $fullPath = $disk->path($filename);

                \Log::info('✅ File saved successfully', [
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'full_path' => $fullPath,
                    'file_exists' => file_exists($fullPath)
                ]);

                // Prepare metadata for recording
                $metadata = [
                    'compression_applied' => $compressionInfo['optimized'] ?? false,
                    'compression_savings' => $compressionInfo['compressionSavings'] ?? '0%',
                    'original_size_mb' => $compressionInfo['originalSize'] ?? 'unknown',
                    'final_size_mb' => number_format($fileSize / (1024 * 1024), 2) . 'MB',
                    'optimization_timestamp' => now()->toISOString()
                ];

                // Update recording with final file info and compression metadata
                $updateResult = $this->currentRecording->update([
                    'video_path' => $filename,
                    'file_size' => $fileSize,
                    'end_time' => now(),
                    'status' => 'completed',
                    'metadata' => $metadata  // Store compression info
                ]);

                \Log::info('📝 Recording record updated with compression info', [
                    'update_result' => $updateResult,
                    'recording_id' => $this->currentRecording->id,
                    'compression_applied' => $metadata['compression_applied'],
                    'compression_savings' => $metadata['compression_savings']
                ]);

                \Log::info('🎉 Optimized exam recording saved successfully', [
                    'user_id' => Auth::id(),
                    'filename' => $filename,
                    'original_size_mb' => $metadata['original_size_mb'],
                    'final_size_mb' => $metadata['final_size_mb'],
                    'compression_savings' => $metadata['compression_savings'],
                    'recording_id' => $this->currentRecording->id
                ]);

                // Video saved successfully - no alert needed

                return true;
            } else {
                \Log::error('❌ Failed to save video file', [
                    'filename' => $filename,
                    'directory' => $directory,
                    'disk_root' => $disk->path(''),
                    'save_result' => $saveResult
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('💥 Error saving exam recording', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return false;
    }

    public function stopRecording()
    {
        \Log::info('🛑 stopRecording() method called from PHP', [
            'user_id' => Auth::id(),
            'has_current_recording' => !is_null($this->currentRecording),
            'current_recording_status' => $this->currentRecording->status ?? 'no_recording'
        ]);

        // Trigger JavaScript to stop recording and save video first
        $this->js('
            console.log("🛑 PHP stopRecording() called - triggering JavaScript");
            if (typeof stopRecording === "function") {
                console.log("🎬 Calling JavaScript stopRecording()...");
                stopRecording();
            } else {
                console.log("❌ stopRecording function not found, trying manual save...");
                if (typeof saveFinalVideo === "function") {
                    saveFinalVideo();
                } else if (typeof manualSaveRecording === "function") {
                    manualSaveRecording();
                } else {
                    alert("❌ Tidak ada function untuk menyimpan video!");
                }
            }
        ');

        // Update recording status in database
        if ($this->currentRecording && $this->currentRecording->status === 'recording') {
            // Don't set to completed yet - let JavaScript save the video first
            // We'll update to completed in saveRecordingVideo() method

            \Log::info('🛑 Recording stop triggered, waiting for video save...', [
                'user_id' => Auth::id(),
                'recording_id' => $this->currentRecording->id,
                'note' => 'Status will be updated to completed after video is saved'
            ]);
        }
    }

    public function logAlert($alertType = '', $description = '', $metadata = [])
    {
        ExamAlert::create([
            'timetable_id' => $this->userTimetable->timetable_id,
            'user_timetable_id' => $this->userTimetableId,
            'alert_type' => $alertType,
            'description' => $description,
            'metadata' => $metadata
        ]);

        $this->alertCount++;

        // Update live session alert count
        if ($this->liveSession) {
            $this->liveSession->incrementAlert();
            $this->liveSession->updateActivity();
        }

        // Jika terlalu banyak alert, ubah status menjadi warning
        if ($this->alertCount >= 5) {
            // $this->userTimetable->update([
            //     'status' => 'done'
            // ]);

            // session()->flash('warning', [
            //     'title' => 'Peringatan!',
            //     'text' => 'Terlalu banyak pelanggaran terdeteksi. Ujian akan dihentikan.'
            // ]);

            AlertHelper::warning('warning', 'Terlalu banyak pelanggaran terdeteksi');

            // $this->finishExam();
        } elseif ($this->alertCount >= 3) {
            // Tampilkan peringatan jika sudah mencapai 3 alert
            AlertHelper::warning('warning', 'Anda telah melakukan beberapa pelanggaran. Hati-hati!');
        }

        return;
    }

    public function pageReloaded()
    {
        $this->logAlert('page_reload', 'Halaman ujian di-refresh', [
            'timestamp' => now()->toISOString(),
            'user_agent' => request()->header('User-Agent')
        ]);
    }

    public function updateLiveSessionData($data = [])
    {
        if ($this->liveSession && is_array($data)) {
            $updateData = [
                'last_activity' => now()
            ];

            // Update connection status
            if (isset($data['connection_status'])) {
                $updateData['connection_status'] = $data['connection_status'];
            }

            // Update camera status
            if (isset($data['camera_status'])) {
                $updateData['camera_status'] = $data['camera_status'];
            }

            // Update browser info
            if (isset($data['browser_info'])) {
                $updateData['browser_info'] = $data['browser_info'];
            }

            // Update peer_id if provided (Critical for streaming)
            if (isset($data['peer_id']) && !empty($data['peer_id'])) {
                $updateData['peer_id'] = $data['peer_id'];

                // If we get a peer_id, we can assume camera is potentially active
                if (!isset($data['camera_status'])) {
                     $updateData['camera_status'] = 'active';
                }
            }

            $this->liveSession->update($updateData);
            $this->updateLiveSessionProgress();
        }
    }

    public function saveScreenshot($screenshotData = [])
    {
        try {
            if (!$this->liveSession || empty($screenshotData['screenshot'])) {
                return;
            }

            // Decode base64 screenshot
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $screenshotData['screenshot']));

            if ($imageData === false) {
                throw new \Exception('Failed to decode screenshot data');
            }

            // Create filename
            $filename = 'exam_screenshots/' . $this->userTimetableId . '_' .
                now()->format('Y-m-d_H-i-s') . '_' .
                time() . '.jpg';

            // Save to storage
            $disk = Storage::disk('public');
            $directory = dirname($filename);

            if (!$disk->exists($directory)) {
                $disk->makeDirectory($directory);
            }

            $saved = $disk->put($filename, $imageData);

            if ($saved) {
                // Log screenshot save
                \Log::info('Screenshot saved', [
                    'user_id' => Auth::id(),
                    'filename' => $filename,
                    'size' => strlen($imageData),
                    'timestamp' => $screenshotData['timestamp'] ?? now()
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Error saving screenshot', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function updateMark()
    {
        DB::transaction(function () {
            $this->saveCurrentAnswer();
            $userModuleQuestion = UserModuleQuestion::findOrFail($this->questionNavigationId);

            $userModuleQuestion->update([
                'is_mark' => !$userModuleQuestion->is_mark
            ]);

            $this->isMark = $userModuleQuestion->is_mark;
            $this->refreshQuestionData();
        });
    }

    private function initializeExam()
    {
        $this->userTimetable = UserTimetable::where('user_id', Auth::id())
            ->whereIn('status', ['exam', 'warning'])
            ->first();

        if ($redirect = $this->validateExamStatus()) {
            return $redirect;
        }


        $this->userTimetableId = $this->userTimetable->id;
        // $this->checkQuestion();

        $this->calculateRemainingTime();

        // Hitung jumlah alert yang sudah ada
        $this->alertCount = ExamAlert::where('user_timetable_id', $this->userTimetableId)->count();
    }

    public function checkQuestion()
    {
        // Cek apakah user punya UserTimetable dengan relasi userModuleQuestions dan status exam/warning
        $users = UserTimetable::where('user_id', Auth::id())
            ->whereIn('status', ['exam', 'warning'])
            ->whereHas('userModuleQuestions')
            ->get();

        if ($users->isEmpty()) {
            // Ambil UserTimetable yang aktif (exam/warning)
            $userTimetable = UserTimetable::where('user_id', Auth::id())
                ->whereIn('status', ['exam', 'warning'])
                ->first();

            if ($userTimetable) {
                $userTimetable->update([
                    'status'   => 'done',
                    'end_exam' => now(),
                    'mark'     => 0,
                ]);
            }

            session()->flash('saved', [
                'title' => 'Ujian Telah Selesai!',
                'text'  => "Terima kasih telah mengerjakan ujian. Nilai Anda: 0/100",
            ]);

            return redirect()->route('admin.exam.timetable');
        }
    }

    private function validateExamStatus()
    {
        if (!$this->userTimetable) {
            return redirect()->route('admin.exam.timetable');
        }

        if ($this->userTimetable->status === 'done') {
            return redirect()->route('admin.exam.timetable');
        }

        if ($this->userTimetable->status === 'warning') {
            return redirect()->route('admin.exam.warning');
        }

        if ($this->userTimetable->status === 'suspend') {
            session()->flash('error', 'Sesi ujian Anda telah di-suspend oleh pengawas.');
            return redirect()->route('admin.exam.timetable');
        }

        return null;
    }

    private function setupFirstQuestion()
    {
        $firstQuestion = $this->getUserQuestions()->first();

        if ($firstQuestion) {
            $this->questionNavigationId = $firstQuestion->id;
            $this->isMark = $firstQuestion->is_mark;
            $this->question = $firstQuestion->timetableQuestion->question;
            $this->description = $firstQuestion->timetableQuestion->description;
            $images = $firstQuestion->timetableQuestion->images;
            $this->images = collect(json_decode($images, true));
            $this->number = 1;
            $this->timetable_answer_id = $firstQuestion->timetable_answer_id;

            $answers = $firstQuestion->timetableQuestion
                ->answers()
                ->orderBy('order', 'asc')
                ->get();

            foreach ($answers as $index => $answer) {
                $this->question_answers[] = [
                    'id'       => $answer->id,
                    'alphabet' => chr(64 + $index + 1),
                    'context'  => $answer->context,
                    'images'   => collect(json_decode($images, true)),
                ];
            }

            $this->refreshQuestionData();
        } else {
            // Jika tidak ada soal, set default values dan log error
            \Log::warning('No questions found for user timetable', [
                'user_id' => Auth::id(),
                'user_timetable_id' => $this->userTimetableId
            ]);

            $this->questionNavigationId = null;
            $this->question = 'Tidak ada soal yang tersedia.';
            $this->description = '';
            $this->images = collect();
            $this->number = 0;
            $this->question_answers = [];

            session()->flash('error', 'Tidak ada soal yang ditemukan untuk ujian ini.');
        }
    }

    private function calculateRemainingTime()
    {
        $timetable = $this->userTimetable->load(['timetable.timetableModule:id,duration']);


        // Check if start_exam is set, otherwise set it to now
        if (!$this->userTimetable->start_exam) {
            $this->userTimetable->update(['start_exam' => now()]);
            $this->userTimetable->refresh();
        }

        $startTime = Carbon::parse($this->userTimetable->start_exam);
        $duration = $timetable->timetable->module->duration ?? 60; // Default 60 minutes if not set
        // Perpanjang waktu selesai dengan akumulasi pause_total_seconds
        $pauseSeconds = (int) ($this->userTimetable->pause_total_seconds ?? 0);
        $endTime = $startTime->addMinutes($duration)->copy()->addSeconds($pauseSeconds);

        $this->remainingTime = max(0, $endTime->timestamp - now()->timestamp);
        \Log::info('Countdown calculated', [
            'start_exam' => $this->userTimetable->start_exam,
            'duration' => $duration,
            'pause_total_seconds' => $pauseSeconds,
            'remaining_seconds' => $this->remainingTime
        ]);
    }

    // Resume timer jika sedang paused (paused_at != null): tambahkan durasi pause ke akumulasi
    public function resumeTimerIfPaused(): int
    {
        $this->userTimetable = UserTimetable::find($this->userTimetableId) ?? $this->userTimetable;
        if (!$this->userTimetable) {
            return 0;
        }

        if (!is_null($this->userTimetable->paused_at)) {
            $pausedAt = Carbon::parse($this->userTimetable->paused_at);
            $delta = now()->diffInSeconds($pausedAt);
            $newTotal = (int) ($this->userTimetable->pause_total_seconds ?? 0) + $delta;

            $this->userTimetable->update([
                'pause_total_seconds' => $newTotal,
                'paused_at' => null,
            ]);

            \Log::info('⏯️ Resumed timer, accumulated pause seconds', [
                'user_timetable_id' => $this->userTimetable->id,
                'added_seconds' => $delta,
                'pause_total_seconds' => $newTotal,
            ]);
        }

        // Recalculate and return latest remaining time
        $this->calculateRemainingTime();
        return (int) $this->remainingTime;
    }

    // Public accessor untuk mengambil sisa detik dari server
    public function getRemainingTime(): int
    {
        $this->calculateRemainingTime();
        return (int) $this->remainingTime;
    }

    private function refreshQuestionData()
    {
        $this->questionNavigations = [];

        $questions = $this->getUserQuestions();

        $this->questionNavigations = [
            'numbers' => $this->mapQuestionNumbers($questions),
            'total' => $questions->count(),
            'answered' => $questions->whereNotNull('timetable_answer_id')->count(),
            'marked' => $questions->where('is_mark', true)->count(),
            'unanswered' => $questions->whereNull('timetable_answer_id')->count(),
        ];

        $this->updateCurrentQuestionMark();
        $this->updatePercentage();
    }

    private function getUserQuestions()
    {
        return UserModuleQuestion::select('id', 'is_mark', 'timetable_module_id', 'timetable_answer_id', 'timetable_question_id')
            ->with([
                'timetableModule',
                'timetableQuestion.answers',
                // 'timetableQuestion.images',
                'timetableAnswer'
            ])
            ->where('user_timetable_id', $this->userTimetableId)
            ->orderBy('order')
            ->get();
    }

    private function questionIds()
    {
        return UserModuleQuestion::select('id')
            ->where('user_timetable_id', $this->userTimetableId)
            ->orderBy('order')
            ->get()
            ->pluck('id')
            ->toArray();
    }

    private function mapQuestionNumbers($questions)
    {
        return $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'is_mark' => $question->is_mark,
                'timetable_answer_id' => $question->timetable_answer_id,
            ];
        })->toArray();
    }

    private function updateCurrentQuestionMark()
    {
        $currentQuestion = UserModuleQuestion::with('timetableModule', 'timetableQuestion', 'timetableAnswer')
            ->find($this->questionNavigationId);

        if ($currentQuestion) {
            $this->isMark = $currentQuestion->is_mark;
            $this->timetable_answer_id = $currentQuestion->timetable_answer_id ? (string) $currentQuestion->timetable_answer_id : null;

            $questionModel = $currentQuestion->timetableQuestion;
            $this->question = $questionModel->question;
            $this->description = $questionModel->description;
            $images = $questionModel->images;
            $this->images = collect(json_decode($images, true));

            $index = array_search($currentQuestion->id, $this->questionIds());
            $this->number = $index !== false ? $index + 1 : 1;

            $answers = $questionModel->answers()
                ->orderBy('order')
                ->get();

            $this->question_answers = $answers->map(
                fn($ans, $i) => [
                    'id' => (string) $ans->id,
                    'alphabet' => chr(65 + $i),
                    'context' => $ans->context,
                    'images' => collect(json_decode($ans->images, true)),
                ]
            )->all();
        }
    }

    public function previousQuestion()
    {
        $this->saveCurrentAnswer();
        $this->navigateToQuestion('previous');
    }

    public function nextQuestion()
    {
        $this->saveCurrentAnswer();
        $this->navigateToQuestion('next');
    }

    private function saveCurrentAnswer()
    {
        UserModuleQuestion::where('id', $this->questionNavigationId)
            ->update([
                'timetable_answer_id' => $this->timetable_answer_id,
                'is_mark' => $this->isMark,
            ]);

        $this->reset('timetable_answer_id');
        $this->refreshQuestionData();
    }

    private function navigateToQuestion($direction)
    {
        $query = UserModuleQuestion::where('user_timetable_id', $this->userTimetableId);

        if ($direction === 'previous') {
            $nextQuestion = $query->where('id', '<', $this->questionNavigationId)
                ->orderBy('order', 'desc')
                ->first();
        } else {
            $nextQuestion = $query->where('id', '>', $this->questionNavigationId)
                ->orderBy('order', 'asc')
                ->first();
        }

        if ($nextQuestion) {
            $this->questionNavigationId = $nextQuestion->id;
            $this->refreshQuestionData();
            $this->updateCurrentQuestionMark();
            $this->updatePercentage();
        }

        return;
    }

    private function updatePercentage()
    {
        $answers = $this->getUserQuestions()->whereNotNull('timetable_answer_id')->count();
        $total = $this->getUserQuestions()->count();
        $this->percentage = ($answers / $total) * 100;
    }

    private function handleSessionMessages()
    {
        if (session()->has('saved')) {
            AlertHelper::success(
                session('saved.title'),
                session('saved.text')
            );
            session()->forget('saved');
        }
    }

    public function changeQuestionNavigation($id)
    {
        $this->saveCurrentAnswer();
        $this->questionNavigationId = $id;
        $this->updateCurrentQuestionMark();
        $this->updatePercentage();
        $this->updateLiveSessionProgress(); // Update live session
    }

    public function timeExpired()
    {
        // Double check remaining time on server side
        $this->calculateRemainingTime();

        if ($this->remainingTime > 15) {
            \Log::warning('⚠️ Client triggered timeExpired but server has time remaining', [
                'client_remaining' => 0,
                'server_remaining' => $this->remainingTime,
                'user_id' => Auth::id()
            ]);

            // Sync client timer instead of finishing
            $this->js("
                if(typeof remainingTime !== 'undefined') {
                    remainingTime = {$this->remainingTime};
                    console.log('🔄 Timer synced from server: {$this->remainingTime}s');

                    // Restart interval if stopped
                    if (!window.countdownInterval) {
                        window.countdownInterval = setInterval(updateCountdown, 1000);
                    }

                    // Reset UI
                    document.querySelector('#countdown').style.color = '';
                }
            ");

            return;
        }

        $this->finishExam();
    }

    public function confirmFinishExam()
    {
        return AlertHelper::confirmWarning('finishExam', 'Apakah Anda Yakin Untuk Menyelesaikan Ujian');
    }

    public function finishExam()
    {
        $this->saveCurrentAnswer();

        \Log::info('🏁 finishExam() called', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId,
            'has_current_recording' => !is_null($this->currentRecording)
        ]);

        // Wait for video to be completely saved before finishing exam
        try {
            \Log::info('📡 Triggering video save and waiting for completion...');

            $this->js('
                console.log("🏁 finishExam() - Starting video save process with callback...");

                // Set flag to prevent multiple calls
                if (window.isFinishingExam) {
                    console.log("❌ finishExam already in progress, skipping...");
                    return;
                }
                window.isFinishingExam = true;

                // Function to complete exam after video is saved
                function completeExamAfterVideoSave() {
                    console.log("✅ Video save completed, finishing exam...");

                    // Call PHP to complete the exam process
                    Livewire.dispatch("completeExamFinalization");
                }

                // Function to handle video save timeout
                function handleVideoSaveTimeout() {
                    console.warn("⚠️ Video save timeout (5s), finishing exam anyway...");
                    // Non-blocking notification; avoid alert to prevent UI freeze
                    completeExamAfterVideoSave();
                }

                // Set timeout as fallback (reduced to 5 seconds)
                const videoSaveTimeout = setTimeout(handleVideoSaveTimeout, 5000);

                // Enhanced stopRecording with completion callback
                if (typeof stopRecording === "function" && !window.isRecordingStopping) {
                    console.log("🎬 Stopping recording with completion callback...");

                    // Override the completion callback in saveFinalVideo
                    window.examFinishCallback = function(success) {
                        clearTimeout(videoSaveTimeout);

                        if (success) {
                            console.log("✅ Video successfully saved, completing exam...");
                            completeExamAfterVideoSave();
                        } else {
                            console.error("❌ Video save failed, but completing exam anyway...");
                            // Non-blocking notification; avoid alert to prevent UI freeze
                            completeExamAfterVideoSave();
                        }
                    };

                    // Call stop recording
                    stopRecording();

                } else {
                    console.log("❌ stopRecording not available, finishing exam immediately...");
                    clearTimeout(videoSaveTimeout);
                    completeExamAfterVideoSave();
                }
            ');
            \Log::info('✅ Video save process initiated with callback');
        } catch (\Exception $e) {
            \Log::error('❌ Error initiating video save: ' . $e->getMessage());
        }

        // Initial logging - exam finish process started
        \Log::info('📡 Exam finish process initiated, waiting for video save...', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId,
            'recording_status' => $this->currentRecording ? $this->currentRecording->status : 'no_recording'
        ]);

        // Note: Actual exam completion will happen in completeExamFinalization()
        // after video is successfully saved
    }

    /**
     * Suspend exam: segera menyelesaikan ujian dengan status 'suspend'.
     * Mirip finishExam, namun finalisasi diarahkan ke suspend.
     */
    public function suspendExam()
    {
        $this->saveCurrentAnswer();

        \Log::info('⏸ suspendExam() called', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId,
            'has_current_recording' => !is_null($this->currentRecording)
        ]);

        try {
            \Log::info('📡 Triggering video save and proceeding to suspend finalization...');

            $this->js('
                console.log("⏸ suspendExam() - Starting video save process with callback...");

                if (window.isSuspendingExam) {
                    console.log("❌ suspendExam already in progress, skipping...");
                    return;
                }
                window.isSuspendingExam = true;

                function completeSuspendAfterVideoSave() {
                    console.log("✅ Video save completed, finalizing suspend...");
                    Livewire.dispatch("completeSuspendFinalization");
                }

                function handleVideoSaveTimeout() {
                    console.warn("⚠️ Video save timeout (3s), suspending anyway...");
                    completeSuspendAfterVideoSave();
                }

                const videoSaveTimeout = setTimeout(handleVideoSaveTimeout, 3000);

                if (typeof stopRecording === "function" && !window.isRecordingStopping) {
                    console.log("🎬 Stopping recording before suspend...");

                    window.examFinishCallback = function(success) {
                        clearTimeout(videoSaveTimeout);
                        completeSuspendAfterVideoSave();
                    };

                    stopRecording();
                } else {
                    console.log("❌ stopRecording not available, finalizing suspend immediately...");
                    clearTimeout(videoSaveTimeout);
                    completeSuspendAfterVideoSave();
                }
            ');
            \Log::info('✅ Suspend video save process initiated');
        } catch (\Exception $e) {
            \Log::error('❌ Error initiating suspend video save: ' . $e->getMessage());
        }
    }

    /**
     * Complete exam finalization after video has been saved
     * This method is called from JavaScript after video save is confirmed
     */
    public function completeExamFinalization()
    {
        \Log::info('🎯 completeExamFinalization() called after video save', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId,
            'recording_status' => $this->currentRecording ? $this->currentRecording->status : 'no_recording'
        ]);

        // Tutup live session
        if ($this->liveSession) {
            $this->liveSession->update([
                'is_active' => false,
                'connection_status' => 'disconnected',
                'end_time' => now()
            ]);
        }

        // Process all exam calculations and database updates
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$userTimetable) {
            \Log::error('❌ No active user timetable found for exam completion');
            return redirect()->route('admin.exam.timetable');
        }

        $userTimetableQuestions = UserModuleQuestion::where('user_timetable_id', $userTimetable->id)
            ->get();

        $totalQuestions = $userTimetableQuestions->count();
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $unansweredQuestions = 0;

        foreach ($userTimetableQuestions as $question) {
            if ($question->timetable_answer_id) {
                $answer = TimetableAnswer::find($question->timetable_answer_id);
                if ($answer && $answer->is_correct) {
                    $question->update([
                        'status' => 'correct',
                    ]);
                    $correctAnswers++;
                } else {
                    $question->update([
                        'status' => 'wrong',
                    ]);
                    $wrongAnswers++;
                }
            } else {
                $question->update([
                    'status' => 'unanswered',
                ]);
                $unansweredQuestions++;
            }
        }

        $mark = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $mark = min($mark, 100);

        // Final update to complete the exam
        $userTimetable->update([
            'status' => 'done',
            'end_exam' => now(),
            'mark' => $mark,
        ]);

        \Log::info('✅ Exam completed successfully after video save', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'final_mark' => $mark,
            'recording_completed' => $this->currentRecording ? $this->currentRecording->status === 'completed' : false
        ]);

        // Redirect after successful completion
        $this->js('
            console.log("✅ Exam finalization completed successfully!");
            console.log("📊 Final Score: ' . $mark . '/' . $totalQuestions . ' (' . $mark . '%)");

            // alert("✅ Ujian berhasil diselesaikan!\\n📊 Nilai: ' . $mark . '/100\\n🎬 Video recording tersimpan");

            // Immediate redirect since everything is now complete
            window.location.href = "/admin/exam/timetable";
        ');

        session()->flash('saved', [
            'title' => 'Ujian Telah Selesai!',
            // 'text' => "Terima kasih telah mengerjakan ujian. Nilai Anda: {$mark}/100",
            'text' => "Terima kasih telah mengerjakan ujian",
        ]);

        // Redirect after all processes are complete
        return redirect()->route('admin.exam.timetable');
    }

    /**
     * Finalisasi ujian sebagai suspend setelah video tersimpan.
     */
    public function completeSuspendFinalization()
    {
        \Log::info('🎯 completeSuspendFinalization() called after video save', [
            'user_id' => Auth::id(),
            'user_timetable_id' => $this->userTimetableId,
            'recording_status' => $this->currentRecording ? $this->currentRecording->status : 'no_recording'
        ]);

        // Tutup live session
        if ($this->liveSession) {
            $this->liveSession->update([
                'is_active' => false,
                'connection_status' => 'disconnected',
                'end_time' => now()
            ]);
        }

        // Ambil user timetable aktif (exam/warning)
        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$userTimetable) {
            \Log::error('❌ No active user timetable found for suspend completion');
            return redirect()->route('admin.exam.timetable');
        }

        // Finalisasi rekaman: gabungkan chunk menjadi satu file dan simpan ke ExamRecording
        try {
            $final = RecordingFinalizer::finalizeForUserTimetable($userTimetable->id);

            // Update ExamRecording terbaru dengan hasil finalisasi
            $latestRecording = ExamRecording::where('user_timetable_id', $userTimetable->id)
                ->orderBy('created_at', 'desc')
                ->first();
            if ($latestRecording) {
                $latestRecording->update([
                    'video_path' => $final['merged_video'] ?: $final['manifest'] ?? $latestRecording->video_path,
                    'file_size' => $final['total_size'] ?? $latestRecording->file_size,
                    'end_time' => now(),
                    'status' => 'completed',
                ]);
            }
        } catch (\Throwable $e) {
            \Log::warning('⚠️ Gagal finalisasi rekaman pada suspend: ' . $e->getMessage());
        }

        // Hitung nilai dari jawaban yang sudah ada sebelum suspend
        $userTimetableQuestions = UserModuleQuestion::where('user_timetable_id', $userTimetable->id)->get();
        $totalQuestions = $userTimetableQuestions->count();
        $correctAnswers = 0;

        foreach ($userTimetableQuestions as $question) {
            if ($question->timetable_answer_id) {
                $answer = TimetableAnswer::find($question->timetable_answer_id);
                if ($answer && $answer->is_correct) {
                    $question->update(['status' => 'correct']);
                    $correctAnswers++;
                } else {
                    $question->update(['status' => 'wrong']);
                }
            } else {
                $question->update(['status' => 'unanswered']);
            }
        }

        $mark = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $mark = min($mark, 100);

        // Tandai sebagai suspend dan simpan nilai
        $userTimetable->update([
            'status' => 'suspend',
            'end_exam' => now(),
            'mark' => $mark,
        ]);

        // Redirect keluar dari halaman ujian
        $this->js('
            console.log("⏸ Exam suspended by supervisor. Redirecting...");
            window.location.href = "/admin/exam/timetable";
        ');

        session()->flash('error', 'Sesi ujian Anda telah di-suspend oleh pengawas.');
        return redirect()->route('admin.exam.timetable');
    }

    public function render()
    {
        return view('livewire.admin.exam.detail.admin-exam-detail-index')
            ->extends('layout.detail.app')
            ->section('content');
    }
}
