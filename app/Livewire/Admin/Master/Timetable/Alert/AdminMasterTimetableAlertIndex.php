<?php

namespace App\Livewire\Admin\Master\Timetable\Alert;

use App\Models\Exam\ExamAlert;
use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterTimetableAlertIndex extends Component
{
    use WithPagination;

    public $timetable_id;

    public $timetable;

    public $modules = [];

    public $supervisors = [];

    public $module_id;

    public $getSupervisors = [];

    public $search = '';

    public $perPage = 5;

    public $start_time;

    public $end_time;

    public function mount($timetable_id = null)
    {
        $this->timetable_id = $timetable_id;

        if (! $this->timetable_id) {
            return redirect()->route('admin.master.timetable');
        }

        $timetable = Timetable::with('userTimetables')->find($this->timetable_id);
        if (! $timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->modules = Module::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $this->timetable = $timetable->toArray();
        $this->supervisors = json_decode($timetable['supervisors']) ?? [];
        $this->module_id = $timetable['module_id'];
        $this->start_time = Carbon::parse($timetable->start_time)->format('d/m/Y H:i');
        $this->end_time = Carbon::parse($timetable->end_time)->format('d/m/Y H:i');
    }

    public function render()
    {
        $examAlerts = ExamAlert::where('timetable_id', $this->timetable_id)
            ->search($this->search)
            ->with(['userTimetable', 'timetable', 'userTimetable.user']);

        return view('livewire.admin.master.timetable.alert.admin-master-timetable-alert-index', [
            'examAlerts' => $examAlerts->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
