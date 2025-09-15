<?php

namespace App\Livewire\Admin\Exam\HistoryTimetable\Detail;

use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserModuleQuestion;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\WithPagination;

class AdminExamHistoryTimetableDetailIndex extends Component
{
    use WithPagination;
    public $user_timetable_id, $timetable_id, $timetable, $user_timetable;
    public $search = '', $perPage = 5;
    public $modules = [], $supervisors = [], $getSupervisors = [], $module_id;
    public $start_time, $end_time;

    public function hydrate()
    {
        $this->resetPage();
    }

    public function mount($timetable_id, $user_timetable_id)
    {
        $this->timetable_id = $timetable_id;
        $this->user_timetable_id = $user_timetable_id;

        $timetable = Timetable::with('userTimetables')->find($this->timetable_id);
        if (!$timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->timetable = $timetable->toArray();

        $this->user_timetable = $timetable->userTimetables()->find($this->user_timetable_id);
        if (!$this->user_timetable) {
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
            'userModuleQuestions' => $userModuleQuestions
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
