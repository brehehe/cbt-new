<?php

namespace App\Livewire\Admin\Exam\HistoryTimetable\Detail;

use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminExamHistoryTimetableDetailIndex extends Component
{
    use WithPagination;

    public $user_timetable_id;

    public $timetable_id;

    public $timetable;

    public $user_timetable;

    public $search = '';

    public $perPage = 5;

    public $modules = [];

    public $supervisors = [];

    public $getSupervisors = [];

    public $module_id;

    public $start_time;

    public $end_time;

    // public function hydrate()
    // {
    //     $this->resetPage();
    // }

    public function mount($timetable_id, $user_timetable_id)
    {
        $this->timetable_id = $timetable_id;
        $this->user_timetable_id = $user_timetable_id;

        $timetable = Timetable::with('userTimetables')->find($this->timetable_id);
        if (! $timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->timetable = $timetable->toArray();

        $this->user_timetable = $timetable->userTimetables()->find($this->user_timetable_id);
        if (! $this->user_timetable) {
            return redirect()->route('admin.master.timetable.detail', ['timetable_id' => $this->timetable_id]);
        }

        $this->start_time = Carbon::parse($timetable->start_time)->format('d/m/Y H:i');
        $this->end_time = Carbon::parse($timetable->end_time)->format('d/m/Y H:i');
        $this->modules = Module::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();

        $this->supervisors = json_decode($timetable['supervisors']) ?? [];
        $this->module_id = $timetable['module_id'];
    }

    public function render()
    {
        $userModuleQuestions = $this->user_timetable->userModuleQuestions()
            ->search($this->search)
            ->with(['timetableQuestion', 'timetableModule', 'timetableAnswer'])
            ->paginate($this->perPage);

        return view('livewire.admin.exam.history-timetable.detail.admin-exam-history-timetable-detail-index', [
            'userModuleQuestions' => $userModuleQuestions,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function getGradeDetail($mark)
    {
        if ($mark === null) {
            return null;
        }

        return RatingScale::where('min_score', '<=', $mark)
            ->where('max_score', '>=', $mark)
            ->orderBy('order')
            ->first();
    }
}
