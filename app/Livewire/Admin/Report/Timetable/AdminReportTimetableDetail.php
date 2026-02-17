<?php

namespace App\Livewire\Admin\Report\Timetable;

use App\Models\Master\Timetable\Timetable;
use App\Models\Timetable\TimetableAnswer;
use App\Models\Timetable\TimetableModule;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportTimetableDetail extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    // public $user_timetables = [];
    public $timetable_module;
    public $timetable_questions = [];

    public function render()
    {
        $user_timetables = UserTimetable::where('timetable_id', $this->timetable_module?->timetable_id)
            ->with(['user', 'userModuleQuestions'])
            ->paginate($this->perPage);

        return view('livewire.admin.report.timetable.admin-report-timetable-detail', [
            'user_timetables' => $user_timetables,
            'timetable_questions' => $this->timetable_questions,
        ])->extends('layout.app')->section('content');
    }

    public function exportPdf()
    {
        // Use the sorted questions loaded in mount()
        $questions = $this->timetable_questions;
        $questionIds = $questions->pluck('id')->filter()->values();

        // Fetch all user timetables for this module
        $user_timetables = UserTimetable::where('timetable_id', $this->timetable_module?->timetable_id)
            ->with(['user']) // Preload user
            ->get();
            
        $userTimetableIds = $user_timetables->pluck('id')->filter()->values();

        // 1. Get the Correct Answer Keys (if needed for the header) 
        // Note: The PDF view currently tries to show "Answer Map" letters. 
        // If questions are randomized, "Answer A" might be different per student.
        // However, if we list MASTER questions, we can show the MASTER answer key if available.
        // For now, we will map based on the master question's answer.
        $answerMap = TimetableAnswer::whereIn('timetable_question_id', $questionIds)
            ->where('is_correct', true)
            ->get(['timetable_question_id', 'order'])
            ->groupBy('timetable_question_id')
            ->map(function ($items) {
                $order = $items->first()?->order;
                return $order ? chr(64 + $order) : '-';
            })
            ->toArray();

        // 2. Bulk fetch all user answers for these questions
        $userQuestionStatuses = UserModuleQuestion::whereIn('user_timetable_id', $userTimetableIds)
            ->whereIn('timetable_question_id', $questionIds)
            ->get(['user_timetable_id', 'timetable_question_id', 'status'])
            ->groupBy('user_timetable_id')
            ->map(function ($items) {
                // Map: question_id => status
                return $items->keyBy('timetable_question_id')->map->status;
            })
            ->toArray();

        // 3. Calculate Scores (Correct Counts)
        $correctCounts = [];
        foreach ($user_timetables as $userTimetable) {
            $statuses = $userQuestionStatuses[$userTimetable->id] ?? [];
            // Count how many are 'correct'
            $count = collect($statuses)->filter(fn($status) => $status === 'correct')->count();
            $correctCounts[$userTimetable->id] = $count;
        }

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-detail-pdf', [
            'timetable_module' => $this->timetable_module,
            'timetable_questions' => $questions,
            'user_timetables' => $user_timetables,
            'answerMap' => $answerMap,
            'userQuestionStatuses' => $userQuestionStatuses,
            'correctCounts' => $correctCounts,
        ])->setPaper('a4', 'landscape');

        $fileName = 'rekap-jadwal-' . ($this->timetable_module?->timetable_id ?? 'timetable') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function mount($id)
    {
        // dd(Auth::user());
        $this->timetable_module = TimetableModule::where('timetable_id', $id)->firstOrFail();
        $this->timetable_questions = $this->timetable_module->questions()
            ->with(['question'])
            ->where('is_check', true)
            ->orderBy('order')
            ->get();
    }

    public function getAnswerCorrect($timetable_question_id)
    {
        $timetable_question = TimetableAnswer::where('timetable_question_id', $timetable_question_id)->get();
        foreach ($timetable_question as $key => $value) {
            if ($value->is_correct) {
                return [chr(64 + ($key + 1)), $value];
            }
        }
        return '-';
    }


}
