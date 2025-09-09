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
        'saveVideoChunk',
        'logAlert',
        'startNewRecording',
        'pageReloaded',
        'updateLiveSessionData',
        'saveScreenshot',
        'initializePeerJS',
        'updatePeerJSId'
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
            'chunk_number' => 1,
            'status' => 'recording'
        ]);
    }

    public function saveVideoChunk($videoBlob = '', $chunkNumber = null)
    {
        // Add extensive logging
        \Log::info('saveVideoChunk called', [
            'user_id' => Auth::id(),
            'chunk_number' => $chunkNumber,
            'data_length' => strlen($videoBlob),
            'server_time' => now(),
            'memory_usage' => memory_get_usage(true),
            'server_info' => [
                'php_version' => PHP_VERSION,
                'upload_max' => ini_get('upload_max_filesize'),
                'post_max' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit')
            ]
        ]);

        try {
            // Validate input
            if (empty($videoBlob)) {
                throw new \Exception('Video blob is empty');
            }

            if (!$this->currentRecording) {
                throw new \Exception('No current recording session');
            }

            // Check if we can decode the data
            $headerCheck = substr($videoBlob, 0, 50);
            \Log::info('Video blob header', ['header' => $headerCheck]);

            if (!preg_match('/^data:video\/\w+;base64,/', $videoBlob)) {
                throw new \Exception('Invalid video blob format');
            }

            // Decode base64 video data
            $videoData = base64_decode(preg_replace('#^data:video/\w+;base64,#i', '', $videoBlob));

            if ($videoData === false) {
                throw new \Exception('Failed to decode base64 data');
            }

            $dataSize = strlen($videoData);
            \Log::info('Video data decoded', ['size' => $dataSize]);

            if ($dataSize === 0) {
                throw new \Exception('Decoded video data is empty');
            }

            // Create filename
            $filename = 'exam_recordings/' . $this->userTimetableId . '_chunk_' .
                ($chunkNumber ?? $this->currentRecording->chunk_number) . '_' .
                time() . '.webm';

            \Log::info('Attempting to save file', ['filename' => $filename]);

            // Check storage disk configuration
            $disk = Storage::disk('public');
            $diskConfig = config('filesystems.disks.public');
            \Log::info('Storage disk config', $diskConfig);

            // Ensure directory exists
            $directory = dirname($filename);
            if (!$disk->exists($directory)) {
                $disk->makeDirectory($directory);
                \Log::info('Created directory', ['directory' => $directory]);
            }

            // Attempt to save file
            $saveResult = $disk->put($filename, $videoData);

            if (!$saveResult) {
                throw new \Exception('Storage put operation returned false');
            }

            // Verify file was actually saved
            if (!$disk->exists($filename)) {
                throw new \Exception('File does not exist after save operation');
            }

            $fileSize = $disk->size($filename);
            \Log::info('File saved successfully', [
                'filename' => $filename,
                'file_size' => $fileSize,
                'full_path' => $disk->path($filename)
            ]);

            // Update database
            if ($chunkNumber && $chunkNumber > $this->currentRecording->chunk_number) {
                $this->currentRecording = ExamRecording::create([
                    'timetable_id' => $this->userTimetable->timetable_id,
                    'user_timetable_id' => $this->userTimetableId,
                    'video_path' => $filename,
                    'chunk_number' => $chunkNumber,
                    'file_size' => $fileSize,
                    'start_time' => now(),
                    'status' => 'recording'
                ]);
                \Log::info('Created new recording entry', ['id' => $this->currentRecording->id]);
            } else {
                $this->currentRecording->update([
                    'video_path' => $filename,
                    'file_size' => $fileSize,
                    'end_time' => now(),
                    'status' => 'completed'
                ]);
                \Log::info('Updated recording entry', ['id' => $this->currentRecording->id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Video chunk saved successfully',
                'debug' => [
                    'filename' => $filename,
                    'file_size' => $fileSize,
                    'chunk_number' => $chunkNumber ?? $this->currentRecording->chunk_number
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving video chunk', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'memory_usage' => memory_get_usage(true),
                'server_info' => [
                    'disk_free_space' => disk_free_space(storage_path()),
                    'disk_total_space' => disk_total_space(storage_path())
                ]
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save video chunk',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function startNewRecording()
    {
        // Tutup recording sebelumnya jika ada
        if ($this->currentRecording && $this->currentRecording->status === 'recording') {
            $this->currentRecording->update([
                'end_time' => now(),
                'status' => 'completed'
            ]);
        }

        // Buat recording baru
        $this->currentRecording = ExamRecording::create([
            'timetable_id' => $this->userTimetable->timetable_id,
            'user_timetable_id' => $this->userTimetableId,
            'start_time' => now(),
            'chunk_number' => $this->currentRecording ? $this->currentRecording->chunk_number + 1 : 1,
            'status' => 'recording'
        ]);
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

            AlertHelper::warning('warning', 'Terlalu banyak pelanggaran terdeteksi. Ujian akan dihentikan.');

            // $this->finishExam();
        } elseif ($this->alertCount >= 3) {
            // Tampilkan peringatan jika sudah mencapai 3 alert
            AlertHelper::warning('warning', 'Anda telah melakukan beberapa pelanggaran. Hati-hati!');
        }
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

        $startTime = Carbon::parse($this->userTimetable->start_exam);
        $duration = $timetable->timetable->module->duration;
        $endTime = $startTime->addMinutes($duration);

        $this->remainingTime = max(0, $endTime->timestamp - now()->timestamp);
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

        // Tutup recording yang sedang berjalan
        if ($this->currentRecording && $this->currentRecording->status === 'recording') {
            $this->currentRecording->update([
                'end_time' => now(),
                'status' => 'completed'
            ]);
        }

        // Tutup live session
        if ($this->liveSession) {
            $this->liveSession->update([
                'is_active' => false,
                'connection_status' => 'disconnected'
            ]);
        }

        $userTimetable = UserTimetable::whereIn('status', ['exam', 'warning'])
            ->where('user_id', Auth::id())
            ->first();

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

        $userTimetable->update([
            'status' => 'done',
            'end_exam' => now(),
            'mark' => $mark,
        ]);

        session()->flash('saved', [
            'title' => 'Ujian Telah Selesai!',
            // 'text' => "Terima kasih telah mengerjakan ujian. Nilai Anda: {$mark}/100",
            'text' => "Terima kasih telah mengerjakan ujian",
        ]);

        return redirect()->route('admin.exam.timetable');
    }

    public function render()
    {
        return view('livewire.admin.exam.detail.admin-exam-detail-index')
            ->extends('layout.detail.app')
            ->section('content');
    }
}
