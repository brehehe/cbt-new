<?php

namespace App\Livewire\Admin\Master\Timetable\Detail;

use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use Auth;
use Livewire\Component;
use Session;
use Carbon\Carbon;
use Livewire\WithPagination;

class AdminMasterTimetableDetailIndex extends Component
{
    use WithPagination;
    public $timetable_id, $timetable, $modules = [], $supervisors = [], $module_id, $getSupervisors = [];
    public $search = '', $perPage = 5, $start_time, $end_time;

    public function mount($timetable_id = null)
    {
        $this->timetable_id = $timetable_id;

        if (!$this->timetable_id) {
            return redirect()->route('admin.master.timetable');
        }

        $timetable = Timetable::with('userTimetables')->find($this->timetable_id);
        if (!$timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->modules = Module::select('id', 'name')->get()->pluck('name', 'id')->toArray();
        $this->getSupervisors = User::companyRole('Pengawas', Auth::user()->company_id)->select('name', 'id')->get()->pluck('name', 'id')->toArray();
        $this->timetable = $timetable->toArray();
        $this->start_time = Carbon::parse($timetable->start_time)->format('d/m/Y H:i');
        $this->end_time = Carbon::parse($timetable->end_time)->format('d/m/Y H:i');
        $this->supervisors = json_decode($timetable['supervisors']) ?? [];
        $this->module_id = $timetable['module_id'];
    }

    public function confirmDetail($id)
    {
        return redirect()->route('admin.master.timetable.answer', ['timetable_id' => $this->timetable_id, 'user_timetable_id' => $id]);
    }

    public function render()
    {
        $userTimetables = UserTimetable::search($this->search)
            ->where('timetable_id', $this->timetable_id)
            ->with(['user', 'timetable']);

        return view('livewire.admin.master.timetable.detail.admin-master-timetable-detail-index', [
            'userTimetables' => $userTimetables->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
