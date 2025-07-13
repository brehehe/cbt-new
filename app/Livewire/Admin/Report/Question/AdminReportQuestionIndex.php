<?php

namespace App\Livewire\Admin\Report\Question;

use App\Models\Master\Question\Question;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportQuestionIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $timetables = [];

    public function render()
    {
        $questions = Question::search($this->search)->paginate($this->perPage);
        return view('livewire.admin.report.question.admin-report-question-index', [
            'questions' => $questions
        ])->extends('layout.app')->section('content');
    }

    public function mount()
    {
        $this->timetables = Timetable::get();
    }

    public function getQuestionCorrect($question, $timetable = null)
    {
        // dd($question?->timetableQuestion, $timetable?->timetableModule);
        return UserModuleQuestion::when($timetable, function ($query) use ($timetable){
            $query->where('timetable_module_id', $timetable?->timetableModule?->id);
        })->where('timetable_question_id', $question?->timetableQuestion?->id)->where('status', 'correct')->count();
    }
}
