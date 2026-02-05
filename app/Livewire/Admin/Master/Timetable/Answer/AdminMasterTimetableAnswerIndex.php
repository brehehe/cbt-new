<?php

namespace App\Livewire\Admin\Master\Timetable\Answer;

use App\Models\Master\Question\Module;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class AdminMasterTimetableAnswerIndex extends Component
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

        return view('livewire.admin.master.timetable.answer.admin-master-timetable-answer-index', [
            'userModuleQuestions' => $userModuleQuestions
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        $userModuleQuestions = $this->user_timetable->userModuleQuestions()
            ->search($this->search)
            ->with(['timetableQuestion', 'timetableModule', 'timetableAnswer', 'timetableQuestion.answers'])
            ->get();

        $pdf = Pdf::loadView('livewire.admin.master.timetable.answer.admin-master-timetable-answer-pdf', [
            'timetable' => $this->timetable,
            'user_timetable' => $this->user_timetable,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'userModuleQuestions' => $userModuleQuestions,
        ])->setPaper('a4', 'portrait');

        $fileName = 'nilai-ujian-detail-' . ($this->user_timetable_id ?? 'peserta') . '.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
}
