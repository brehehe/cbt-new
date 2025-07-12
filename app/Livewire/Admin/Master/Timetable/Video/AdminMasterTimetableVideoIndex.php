<?php

namespace App\Livewire\Admin\Master\Timetable\Video;

use App\Models\Exam\ExamRecording;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Question\Module;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class AdminMasterTimetableVideoIndex extends Component
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
        $this->supervisors = json_decode($timetable['supervisors']) ?? [];
        $this->module_id = $timetable['module_id'];
        $this->start_time = Carbon::parse($timetable->start_time)->format('d/m/Y H:i');
        $this->end_time = Carbon::parse($timetable->end_time)->format('d/m/Y H:i');
    }

    public function render()
    {
        $examRecordings = ExamRecording::where('timetable_id', $this->timetable_id)
            ->search($this->search)
            ->whereNotNull('video_path')
            ->with(['userTimetable', 'userTimetable.user'])
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.video.admin-master-timetable-video-index', [
            'examRecordings' => $examRecordings,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
