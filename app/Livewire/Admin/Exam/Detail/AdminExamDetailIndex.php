<?php

namespace App\Livewire\Admin\Exam\Detail;

use App\Helpers\AlertHelper;
use App\Helpers\AuthHelper;
use App\Models\Master\Question\Answer;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use DB;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class AdminExamDetailIndex extends Component
{
    public $userTimetableId;
    public $remainingTime;
    public $userTimetable;
    public $questionNavigations = [];
    public $questionNavigationId;
    public $isMark = false;
    public $percentage = 0;
    public $answer_id;
    public $question;
    public $images = [];
    public $description;
    public $number;
    public $question_answers = [];

    public function mount()
    {
        $this->initializeExam();
        $this->setupFirstQuestion();
        $this->handleSessionMessages();
    }

    public function updateMark()
    {
        DB::transaction(function () {
            $userModuleQuestion = UserModuleQuestion::findOrFail($this->questionNavigationId);

            $userModuleQuestion->update([
                'is_mark' => !$userModuleQuestion->is_mark
            ]);

            $this->isMark = $userModuleQuestion->is_mark;
            $this->saveCurrentAnswer();
            $this->refreshQuestionData();
        });
    }

    private function initializeExam()
    {
        $this->userTimetable = UserTimetable::where('user_id', Auth::id())
            ->whereIn('status', ['exam', 'warning'])
            ->first();

        // Teruskan redirect, bila ada
        if ($redirect = $this->validateExamStatus()) {
            return $redirect;          // ⬅️ penting!
        }

        $this->userTimetableId = $this->userTimetable->id;
        $this->calculateRemainingTime();
    }

    private function validateExamStatus()
    {
        // 1) tidak ada timetable
        if (!$this->userTimetable) {
            return redirect()->route('admin.exam.timetable');
        }

        // 2) sudah selesai
        if ($this->userTimetable->status === 'done') {
            return redirect()->route('admin.exam.timetable');
        }

        // 3) status warning
        if ($this->userTimetable->status === 'warning') {
            return redirect()->route('admin.exam.warning');
        }

        // tidak perlu redirect
        return null;
    }

    private function setupFirstQuestion()
    {
        $firstQuestion = $this->getUserQuestions()->first();

        if ($firstQuestion) {
            $this->questionNavigationId = $firstQuestion->id;
            $this->isMark = $firstQuestion->is_mark;
            $this->question = $firstQuestion->first()->moduleQuestion->question->question;
            $this->description = $firstQuestion->first()->moduleQuestion->question->description;
            $this->images = $firstQuestion->first()->moduleQuestion->question->images;
            $this->number = 1;
            $this->answer_id = $firstQuestion->answer_id;

            $answers = $firstQuestion->first()          // ambil record pertama
                ->moduleQuestion->question      // telusuri relasi
                ->answers()                     // ← perhatikan tanda kurung!
                ->orderBy('order', 'asc')       // urutkan di query
                ->get();                        // eksekusi query

            foreach ($answers as $index => $answer) {
                $this->question_answers[] = [
                    'id'       => $answer->id,
                    'alphabet' => chr(64 + $index + 1), // A, B, C, …
                    'context'  => $answer->context,
                    'images'   => $answer->images,
                ];
            }

            $this->refreshQuestionData();
        }
    }

    private function calculateRemainingTime()
    {
        $timetable = $this->userTimetable->load(['timetable.module:id,duration']);

        $startTime = Carbon::parse($this->userTimetable->start_exam);
        $duration = $timetable->timetable->module->duration;
        // $duration = 999999999;
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
            'answered' => $questions->whereNotNull('answer_id')->count(),
            'marked' => $questions->where('is_mark', true)->count(),
            'unanswered' => $questions->whereNull('answer_id')->count(),
        ];

        $this->updateCurrentQuestionMark();
        $this->updatePercentage();
    }

    private function getUserQuestions()
    {
        return UserModuleQuestion::select('id', 'is_mark', 'answer_id', 'module_question_id')
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
                'answer_id' => $question->answer_id,
            ];
        })->toArray();
    }

    private function updateCurrentQuestionMark()
    {
        $currentQuestion = UserModuleQuestion::with('moduleQuestion.question')
            ->find($this->questionNavigationId);

        if ($currentQuestion) {
            // Status mark dan jawaban dari database
            $this->isMark = $currentQuestion->is_mark;
            $this->answer_id = $currentQuestion->answer_id ? (string) $currentQuestion->answer_id : null;

            // Detail soal
            $questionModel = $currentQuestion->moduleQuestion->question;
            $this->question = $questionModel->question;
            $this->description = $questionModel->description;
            $this->images = $questionModel->images;

            // Nomor soal
            $index = array_search($currentQuestion->id, $this->questionIds());
            $this->number = $index !== false ? $index + 1 : 1;

            // Jawaban
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
                'answer_id' => $this->answer_id,
                'is_mark' => $this->isMark,
            ]);
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
        $answers =  $this->getUserQuestions()->whereNotNull('answer_id')->count();
        $total =  $this->getUserQuestions()->count();
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
        $this->questionNavigationId = $id;
        $this->saveCurrentAnswer();
        $this->updateCurrentQuestionMark();
        $this->updatePercentage();
    }

    protected $listeners = ['timeExpired'];

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
            if ($question->answer_id) {
                $answer = Answer::find($question->answer_id);
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

        // Hitung nilai mark dengan maksimal 100
        $mark = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;

        // Pastikan nilai tidak lebih dari 100
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
