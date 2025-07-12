<?php

namespace App\Livewire\Admin\Exam\Detail;

use App\Helpers\AlertHelper;
use App\Helpers\AuthHelper;
use App\Models\Exam\ExamAlert;
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

    protected $listeners = [
        'timeExpired',
        'saveVideoChunk',
        'logAlert',
        'startNewRecording',
        'pageReloaded'
    ];

    public function mount()
    {
        $this->initializeExam();
        $this->setupFirstQuestion();
        $this->handleSessionMessages();
        $this->initializeRecording();
    }

    private function initializeRecording()
    {
        // Buat recording entry baru
        $this->currentRecording = ExamRecording::create([
            'timetable_id' => $this->userTimetable->timetable_id,
            'user_timetable_id' => $this->userTimetableId,
            'start_time' => now(),
            'chunk_number' => 1,
            'status' => 'recording'
        ]);
    }

    public function saveVideoChunk($videoBlob, $chunkNumber = null)
    {
        try {
            // Decode base64 video data
            $videoData = base64_decode(preg_replace('#^data:video/\w+;base64,#i', '', $videoBlob));

            $filename = 'exam_recordings/' . $this->userTimetableId . '_chunk_' .
                ($chunkNumber ?? $this->currentRecording->chunk_number) . '_' .
                time() . '.webm';

            // Simpan file ke storage
            Storage::disk('public')->put($filename, $videoData);

            // Update atau buat recording entry baru
            if ($chunkNumber && $chunkNumber > $this->currentRecording->chunk_number) {
                // Buat entry baru untuk chunk berikutnya
                $this->currentRecording = ExamRecording::create([
                    'timetable_id' => $this->userTimetable->timetable_id,
                    'user_timetable_id' => $this->userTimetableId,
                    'video_path' => $filename,
                    'chunk_number' => $chunkNumber,
                    'file_size' => strlen($videoData),
                    'start_time' => now(),
                    'status' => 'recording'
                ]);
            } else {
                // Update recording yang sedang berjalan
                $this->currentRecording->update([
                    'video_path' => $filename,
                    'file_size' => strlen($videoData),
                    'end_time' => now(),
                    'status' => 'completed'
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Video chunk saved successfully']);
        } catch (\Exception $e) {
            \Log::error('Error saving video chunk: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save video chunk']);
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

    public function logAlert($alertType, $description, $metadata = [])
    {
        ExamAlert::create([
            'timetable_id' => $this->userTimetable->timetable_id,
            'user_timetable_id' => $this->userTimetableId,
            'alert_type' => $alertType,
            'description' => $description,
            'metadata' => $metadata
        ]);

        $this->alertCount++;

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
        $this->calculateRemainingTime();

        // Hitung jumlah alert yang sudah ada
        $this->alertCount = ExamAlert::where('user_timetable_id', $this->userTimetableId)->count();
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
            $this->question = $firstQuestion->first()->timetableQuestion->question;
            $this->description = $firstQuestion->first()->timetableQuestion->description;
            $this->images = $firstQuestion->first()->timetableQuestion->images;
            $this->number = 1;
            $this->timetable_answer_id = $firstQuestion->timetable_answer_id;

            $answers = $firstQuestion->first()
                ->timetableQuestion
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
            ->with('timetableModule', 'timetableQuestion', 'timetableAnswer')
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
            'end_exam' => Carbon::now(),
            'mark' => $mark,
        ]);

        session()->flash('saved', [
            'title' => 'Ujian Telah Selesai!',
            'text' => "Terima kasih telah mengerjakan ujian. Nilai Anda: {$mark}/100",
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
