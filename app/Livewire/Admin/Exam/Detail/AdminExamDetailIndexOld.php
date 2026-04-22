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

class AdminExamDetailIndexOld extends Component
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
        'logAlert',
        'stopRecording',
        'pageReloaded',
        'updateLiveSessionData',
        'saveScreenshot',
        'initializePeerJS',
        'updatePeerJSId',
        'completeExamFinalization'
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
                    'ip_address' => request()->ip()
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
        $this->peerJSId = $peerId;

        // Update live session dengan PeerJS ID
        if ($this->liveSession) {
            $this->liveSession->update([
                'peer_id' => $peerId,
                'camera_status' => 'active', // Anggap kamera aktif jika PeerJS berhasil
                'last_activity' => Carbon::now()
            ]);

            \Log::info('PeerJS ID updated for live session', [
                'peer_id' => $peerId,
                'live_session_id' => $this->liveSession->id,
                'user_id' => Auth::id()
            ]);
        }

        // Emit event ke JavaScript untuk memberitahu bahwa PeerJS ID sudah disimpan
        $this->dispatch('peerJSIdSaved', ['peer_id' => $peerId]);
    }

    private function initializeRecording()
    {
        if (!$this->userTimetable || !$this->userTimetable->timetable_id) {
            return redirect()->route('admin.exam.timetable');
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
                $connStatus = strtolower($data['connection_status']);
                if ($connStatus === 'connection error') {
                    $connStatus = 'error';
                }
                $updateData['connection_status'] = $connStatus;
            }

            // Update camera status
            if (isset($data['camera_status'])) {
                $updateData['camera_status'] = $data['camera_status'];
            }

            // Update browser info
            if (isset($data['browser_info'])) {
                $updateData['browser_info'] = $data['browser_info'];
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
            $this->images = $firstQuestion->timetableQuestion->images;
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
                    'images'   => $answer->images,
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
        $endTime = $startTime->addMinutes($duration);

        $this->remainingTime = max(0, $endTime->timestamp - now()->timestamp);
        \Log::info('Countdown calculated', [
            'start_exam' => $this->userTimetable->start_exam,
            'duration' => $duration,
            'remaining_seconds' => $this->remainingTime
        ]);
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
            $this->images = $questionModel->images;

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
                    'images' => $ans->images,
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

    public function render()
    {
        return view('livewire.admin.exam.detail.admin-exam-detail-index')
            ->extends('layout.detail.app')
            ->section('content');
    }
}
