<?php

namespace App\Livewire\Admin\Report\Question;

use App\Models\Master\Question\Question;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserModuleQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportQuestionIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search;

    public $timetables = [];

    protected $correctCounts = [];

    public function render()
    {
        $companyId = Auth::user()?->company_id;

        $questions = Question::search($this->search)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->with(['timetableQuestion:id,question_id'])
            ->select(['id', 'question', 'description', 'order'])
            ->paginate($this->perPage);

        $tqIds = $questions->pluck('timetableQuestion.id')->filter()->all();
        $this->correctCounts = [];

        if (! empty($tqIds)) {
            $this->correctCounts = DB::table('user_module_questions')
                ->whereIn('timetable_question_id', $tqIds)
                ->where('status', 'correct')
                ->whereNull('deleted_at')
                ->groupBy('timetable_question_id')
                ->select('timetable_question_id', DB::raw('COUNT(*) as total'))
                ->pluck('total', 'timetable_question_id')
                ->toArray();
        }

        return view('livewire.admin.report.question.admin-report-question-index', [
            'questions' => $questions,
            'correctCounts' => $this->correctCounts,
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        $this->timetables = [];
    }

    public function getQuestionCorrect($question, $timetable = null)
    {
        $tqId = $question?->timetableQuestion?->id;
        if (! $timetable && $tqId && isset($this->correctCounts[$tqId])) {
            return $this->correctCounts[$tqId];
        }

        if (! $tqId) {
            return 0;
        }

        return UserModuleQuestion::when($timetable, function ($query) use ($timetable) {
            $query->where('timetable_module_id', $timetable?->timetableModule?->id);
        })->where('timetable_question_id', $tqId)->where('status', 'correct')->count();
    }
}
