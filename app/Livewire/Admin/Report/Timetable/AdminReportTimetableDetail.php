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
        $user_timetables = UserTimetable::where('timetable_id', $this->timetable_module?->timetable_id)->paginate($this->perPage);
        return view('livewire.admin.report.timetable.admin-report-timetable-detail', [
            'user_timetables' => $user_timetables
        ])->extends('layout.app')->section('content');
    }

    public function exportPdf()
    {
        $questions = $this->timetable_questions ?? collect();
        $questionIds = $questions->pluck('id')->filter()->values();

        $user_timetables = UserTimetable::where('timetable_id', $this->timetable_module?->timetable_id)->get();
        $userTimetableIds = $user_timetables->pluck('id')->filter()->values();

        $answerMap = TimetableAnswer::whereIn('timetable_question_id', $questionIds)
            ->where('is_correct', true)
            ->get(['timetable_question_id', 'order'])
            ->groupBy('timetable_question_id')
            ->map(function ($items) {
                $order = $items->first()?->order;
                return $order ? chr(64 + $order) : '-';
            })
            ->toArray();

        $userQuestionStatuses = UserModuleQuestion::whereIn('user_timetable_id', $userTimetableIds)
            ->whereIn('timetable_question_id', $questionIds)
            ->get(['user_timetable_id', 'timetable_question_id', 'status'])
            ->groupBy('user_timetable_id')
            ->map(function ($items) {
                return $items->keyBy('timetable_question_id')->map->status;
            })
            ->toArray();

        $correctCounts = [];
        foreach ($user_timetables as $userTimetable) {
            $statuses = $userQuestionStatuses[$userTimetable->id] ?? [];
            $correctCounts[$userTimetable->id] = collect($statuses)->filter(fn($status) => $status === 'correct')->count();
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
        $this->timetable_questions = $this->timetable_module->questions()->get();
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

    public function getUserModuleQuestion ($timetable_question_id, $user_timetable_id)
    {
        return UserModuleQuestion::where('timetable_question_id', $timetable_question_id)->where('user_timetable_id', $user_timetable_id)->first();
    }
}
