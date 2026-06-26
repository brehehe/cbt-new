<?php

namespace App\Livewire\Admin\Master\Timetable\Detail;

use App\Exports\TimetableDetailExport;
use App\Models\Master\Question\Module;
use App\Models\Master\RatingScale\RatingScale;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminMasterTimetableDetailIndex extends Component
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
        $this->start_time = Carbon::parse($timetable->start_time)->format('d/m/Y H:i');
        $this->end_time = Carbon::parse($timetable->end_time)->format('d/m/Y H:i');
        $this->supervisors = json_decode($timetable['supervisors']) ?? [];
        $this->module_id = $timetable['module_id'];
    }

    public function confirmDetail($id)
    {
        return redirect()->route('admin.master.timetable.answer', ['timetable_id' => $this->timetable_id, 'user_timetable_id' => $id]);
    }

    public function getGrade($mark)
    {
        if ($mark === null) {
            return '-';
        }

        return RatingScale::where('min_score', '<=', $mark)
            ->where('max_score', '>=', $mark)
            ->orderBy('order')
            ->first()
            ?->grade_letter ?? '-';
    }

    public function render()
    {
        $userTimetables = UserTimetable::search($this->search)
            ->where('timetable_id', $this->timetable_id)
            ->with(['user', 'timetable', 'userModuleQuestions']);

        return view('livewire.admin.master.timetable.detail.admin-master-timetable-detail-index', [
            'userTimetables' => $userTimetables->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        $userTimetables = UserTimetable::search($this->search)
            ->where('timetable_id', $this->timetable_id)
            ->with(['user', 'timetable', 'userModuleQuestions'])
            ->get();

        $ratingScales = RatingScale::orderBy('order')->get();
        $gradeMap = [];
        $countMap = [];

        foreach ($userTimetables as $userTimetable) {
            $mark = $userTimetable->mark;
            $grade = '-';
            if ($mark !== null) {
                $scale = $ratingScales->first(function ($item) use ($mark) {
                    return $item->min_score <= $mark && $item->max_score >= $mark;
                });
                $grade = $scale?->grade_letter ?? '-';
            }

            $countMap[$userTimetable->id] = [
                'answered' => $userTimetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count(),
                'unanswered' => $userTimetable->userModuleQuestions->whereNull('timetable_answer_id')->count(),
                'correct' => $userTimetable->userModuleQuestions->where('status', 'correct')->count(),
                'wrong' => $userTimetable->userModuleQuestions->where('status', 'wrong')->count(),
            ];

            $gradeMap[$userTimetable->id] = $grade;
        }

        $pdf = Pdf::loadView('livewire.admin.master.timetable.detail.admin-master-timetable-detail-pdf', [
            'timetable' => $this->timetable,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'userTimetables' => $userTimetables,
            'countMap' => $countMap,
            'gradeMap' => $gradeMap,
        ])->setPaper('a4', 'landscape');

        $fileName = 'nilai-ujian-'.($this->timetable_id ?? 'timetable').'.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    public function exportExcel()
    {
        try {
            $fileName = 'nilai-ujian-'.($this->timetable['name'] ?? 'detail').'-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new TimetableDetailExport($this->timetable_id, $this->search),
                $fileName
            );
        } catch (\Exception $e) {
            Log::error('Timetable Detail Export Error: '.$e->getMessage());
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengekspor data ke Excel.',
            ]);
        }
    }
}
