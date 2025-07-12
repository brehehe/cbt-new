<?php

namespace App\Livewire\Admin\Master\Timetable\Answer;

use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use Auth;
use Livewire\Component;

class AdminMasterTimetableAnswerIndex extends Component
{
    use \Livewire\WithPagination;
    public $user_timetable_id, $timetable_id, $timetable, $user_timetable;
    public $search = '', $perPage = 5;
    public $modules = [], $supervisors = [], $getSupervisors = [], $module_id;

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

        return view('livewire.admin.master.timetable.answer.admin-master-timetable-answer-index', [
            'userModuleQuestions' => $userModuleQuestions
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
