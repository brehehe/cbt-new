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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportTimetableDetail extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $timetable_module;

    public function render()
    {
        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();

        $timetable_questions = $this->timetable_module->questions()
            ->with(['question'])
            ->where('is_check', true)
            ->orderBy('order')
            ->get();

        $questionSelects = ['id', 'user_timetable_id', 'timetable_question_id', 'status'];
        if ($hasEssayAnswerColumn) {
            $questionSelects[] = 'essay_answer';
        }

        $user_timetables = UserTimetable::where('timetable_id', $this->timetable_module?->timetable_id)
            ->search($this->search)
            ->with([
                'user',
                'userModuleQuestions' => function ($q) use ($questionSelects) {
                    $q->select($questionSelects);
                }
            ])
            ->paginate($this->perPage);

        return view('livewire.admin.report.timetable.admin-report-timetable-detail', [
            'user_timetables' => $user_timetables,
            'timetable_questions' => $timetable_questions,
        ])->extends('layout.app')->section('content');
    }

    public function exportPdf()
    {
        $hasEssayAnswerColumn = $this->hasEssayAnswerColumn();

        $questions = $this->timetable_module->questions()
            ->with(['question'])
            ->where('is_check', true)
            ->orderBy('order')
            ->get();

        $questionIds = $questions->pluck('id')->filter()->values();

        $user_timetables = UserTimetable::where('timetable_id', $this->timetable_module?->timetable_id)
            ->with(['user'])
            ->get();

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

        $statusSelects = ['user_timetable_id', 'timetable_question_id', 'status'];
        if ($hasEssayAnswerColumn) {
            $statusSelects[] = 'essay_answer';
        }

        $userQuestionStatuses = UserModuleQuestion::whereIn('user_timetable_id', $userTimetableIds)
            ->whereIn('timetable_question_id', $questionIds)
            ->get($statusSelects)
            ->groupBy('user_timetable_id');

        $correctCounts = [];
        foreach ($user_timetables as $userTimetable) {
            $userAnswers = $userQuestionStatuses->get($userTimetable->id) ?? collect();
            $count = $userAnswers->where('status', 'correct')->count();
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
        $this->timetable_module = TimetableModule::where('timetable_id', $id)->firstOrFail();
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

    private function hasEssayAnswerColumn(): bool
    {
        static $hasColumn;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('user_module_questions', 'essay_answer');
        }

        return $hasColumn;
    }


}
