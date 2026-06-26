<?php

namespace App\Livewire\Admin\Report\AnswerStatistics;

use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class AdminReportAnswerStatisticsIndex extends Component
{
    public $timetable_id = '';

    public $answerStats = [];

    public $timetable = null;

    public function mount()
    {
        //
    }

    public function updatedTimetableId($value)
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        if (! $this->timetable_id) {
            $this->answerStats = [];
            $this->timetable = null;

            return;
        }

        $this->timetable = Timetable::with('module')->find($this->timetable_id);
        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();

        // Fetch all questions for this timetable via TimetableModule
        $questions = TimetableQuestion::with('answers')
            ->whereHas('timetableModule', function ($q) {
                $q->where('timetable_id', $this->timetable_id);
            })
            ->where('is_check', true)
            ->orderBy('order', 'asc') // Ensure consistent ordering
            ->get();

        // Fetch all user answers for this timetable
        // Fetch all user answers for this timetable
        $answerSelects = ['timetable_question_id', 'timetable_answer_id', 'status'];
        if ($hasEssayAnswerColumn) {
            $answerSelects[] = 'essay_answer';
        }

        $userAnswers = UserModuleQuestion::whereHas('userTimetable', function ($q) {
            $q->where('timetable_id', $this->timetable_id)
                ->where('status', 'done');
        })
            ->select($answerSelects)
            ->get()
            ->groupBy('timetable_question_id');

        $stats = [];

        foreach ($questions as $question) {
            $answersForQuestion = $userAnswers->get($question->id, collect());
            $isEssay = $question->type === 'essay';

            if ($isEssay) {
                $totalAnswered = $hasEssayAnswerColumn
                    ? $answersForQuestion->whereNotNull('essay_answer')->count()
                    : $answersForQuestion->whereIn('status', ['correct', 'wrong', 'check'])->count();
                $totalCorrect = $answersForQuestion->where('status', 'correct')->count();
                $totalWrong = $answersForQuestion->where('status', 'wrong')->count();
                $totalPending = $answersForQuestion->where('status', 'check')->count();

                $distribution = [
                    [
                        'option_text' => 'Benar (Koreksi)',
                        'is_correct' => true,
                        'count' => $totalCorrect,
                        'percentage' => $totalAnswered > 0 ? round(($totalCorrect / $totalAnswered) * 100, 1) : 0,
                        'label' => 'CORRECT',
                    ],
                    [
                        'option_text' => 'Salah (Koreksi)',
                        'is_correct' => false,
                        'count' => $totalWrong,
                        'percentage' => $totalAnswered > 0 ? round(($totalWrong / $totalAnswered) * 100, 1) : 0,
                        'label' => 'WRONG',
                    ],
                    [
                        'option_text' => 'Belum Dinilai',
                        'is_correct' => false,
                        'count' => $totalPending,
                        'percentage' => $totalAnswered > 0 ? round(($totalPending / $totalAnswered) * 100, 1) : 0,
                        'label' => 'PENDING',
                        'is_pending' => true,
                    ],
                ];
            } else {
                $totalAnswered = $answersForQuestion->whereNotNull('timetable_answer_id')->count();
                $totalCorrect = $answersForQuestion->where('status', 'correct')->count();
                $totalWrong = $answersForQuestion->where('status', 'wrong')->count();
                $totalPending = 0;

                $distribution = [];
                foreach ($question->answers as $option) {
                    $count = $answersForQuestion->where('timetable_answer_id', $option->id)->count();
                    $distribution[] = [
                        'option_text' => $option->context,
                        'is_correct' => $option->is_correct,
                        'count' => $count,
                        'percentage' => $totalAnswered > 0 ? round(($count / $totalAnswered) * 100, 1) : 0,
                    ];
                }
            }

            $stats[] = [
                'question_text' => $question->question, // Keep tags for limit later
                'question_type' => $question->type,
                'total_answered' => $totalAnswered,
                'total_correct' => $totalCorrect,
                'total_wrong' => $totalWrong,
                'total_pending' => $totalPending,
                'distribution' => $distribution,
            ];
        }

        $this->answerStats = $stats;
    }

    private function hasEssayAnswerColumn(): bool
    {
        static $hasColumn;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('user_module_questions', 'essay_answer');
        }

        return $hasColumn;
    }

    public function render()
    {
        $companyId = Auth::user()?->company_id;
        $timetables = Timetable::query()
            ->with('module:id,name')
            ->where('company_id', $companyId)
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time']);

        return view('livewire.admin.report.answer-statistics.admin-report-answer-statistics-index', [
            'timetables' => $timetables,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        if (! $this->timetable_id || empty($this->answerStats)) {
            return;
        }

        $timetable = Timetable::with('module')->findOrFail($this->timetable_id);
        $company = Auth::user()->company()->with('companyDetail')->first();

        $pdf = Pdf::loadView('livewire.admin.report.answer-statistics.admin-report-answer-statistics-pdf', [
            'timetable' => $timetable,
            'answerStats' => $this->answerStats,
            'company' => $company,
        ])
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'laporan-statistik-jawaban-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }
}
