<?php

namespace App\Livewire\Admin\Report\AnswerStatistics;

use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminReportAnswerStatisticsIndex extends Component
{
    public $timetable_id = '';
    public $answerStats = [];
    public $timetables = [];
    public $timetable = null;

    public function mount()
    {
        $companyId = Auth::user()?->company_id;

        $this->timetables = Timetable::query()
            ->with('module:id,name')
            ->where('company_id', $companyId)
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time']);
    }

    public function updatedTimetableId($value)
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        if (!$this->timetable_id) {
            $this->answerStats = [];
            $this->timetable = null;
            return;
        }

        $this->timetable = Timetable::with('module')->find($this->timetable_id);

        // Fetch all questions for this timetable via TimetableModule
        $questions = TimetableQuestion::with('answers')
            ->whereHas('timetableModule', function ($q) {
                $q->where('timetable_id', $this->timetable_id);
            })
            ->where('is_check', true)
            ->orderBy('order', 'asc') // Ensure consistent ordering
            ->get();

        // Fetch all user answers for this timetable
        // Improve performance by selecting only necessary columns
        $userAnswers = UserModuleQuestion::whereHas('userTimetable', function ($q) {
                $q->where('timetable_id', $this->timetable_id)
                  ->where('status', 'done'); // Only count finished exams? Or all? Let's say DONE for now to be safe.
            })
            ->select('timetable_question_id', 'timetable_answer_id', 'status')
            ->get()
            ->groupBy('timetable_question_id');

        $stats = [];

        foreach ($questions as $question) {
            $answersForQuestion = $userAnswers->get($question->id, collect());
            
            $totalAnswered = $answersForQuestion->whereNotNull('timetable_answer_id')->count();
            $totalCorrect = $answersForQuestion->where('status', 'correct')->count();
            $totalWrong = $answersForQuestion->where('status', 'wrong')->count();
            
            // Calculate distribution per option
            $distribution = [];
            foreach ($question->answers as $option) {
                $count = $answersForQuestion->where('timetable_answer_id', $option->id)->count();
                $distribution[] = [
                    'option_text' => $option->context, // Or label A, B, C if stored
                    'is_correct' => $option->is_correct,
                    'count' => $count,
                    'percentage' => $totalAnswered > 0 ? round(($count / $totalAnswered) * 100, 1) : 0,
                ];
            }

            $stats[] = [
                'question_text' => strip_tags($question->question),
                'total_answered' => $totalAnswered,
                'total_correct' => $totalCorrect,
                'total_wrong' => $totalWrong,
                'distribution' => $distribution,
            ];
        }

        $this->answerStats = $stats;
    }

    public function render()
    {
        return view('livewire.admin.report.answer-statistics.admin-report-answer-statistics-index')
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        if (!$this->timetable_id || empty($this->answerStats)) {
            return;
        }

        $company = Auth::user()->company()->with('companyDetail')->first();

        $pdf = Pdf::loadView('livewire.admin.report.answer-statistics.admin-report-answer-statistics-pdf', [
            'timetable' => $this->timetable,
            'answerStats' => $this->answerStats,
            'company' => $company,
        ])
            ->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'laporan-statistik-jawaban-' . date('Y-m-d-H-i-s') . '.pdf'
        );
    }
}
