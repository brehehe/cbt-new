<?php

namespace App\Livewire\Admin\Report\Timetable;

use App\Models\Master\Timetable\Timetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportTimetableIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search;

    public function render()
    {
        $timetables = Timetable::search($this->search)
            ->select('id', 'name', 'module_id', 'start_time', 'end_time', 'description', 'code')
            ->latest()->paginate($this->perPage);

        return view('livewire.admin.report.timetable.admin-report-timetable-index', [
            'timetables' => $timetables,
        ])->extends('layout.app')->section('content');
    }

    public function printOfficialReport($timetableId)
    {
        $timetable = Timetable::with(['module', 'userTimetables'])->find($timetableId);
        if (! $timetable) {
            return;
        }

        $company = Auth::user()->company()->with('companyDetail')->first();

        $totalStudents = $timetable->userTimetables->count();
        $presentStudents = $timetable->userTimetables->whereNotNull('start_exam')->count();
        $absentStudents = $totalStudents - $presentStudents;

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-official-pdf', [
            'timetable' => $timetable,
            'company' => $company,
            'stats' => [
                'total' => $totalStudents,
                'present' => $presentStudents,
                'absent' => $absentStudents,
            ],
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'berita-acara-'.$timetable->name.'.pdf'
        );
    }

    public function printAttendanceList($timetableId)
    {
        $timetable = Timetable::with(['module', 'userTimetables.user'])->find($timetableId);
        if (! $timetable) {
            return;
        }

        $company = Auth::user()->company()->with('companyDetail')->first();

        // Sort by name
        $timetable->userTimetables = $timetable->userTimetables->sortBy(function ($ut) {
            return $ut->user->name;
        });

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-attendance-pdf', [
            'timetable' => $timetable,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'daftar-hadir-'.$timetable->name.'.pdf'
        );
    }

    public function printParticipantCards($timetableId)
    {
        $timetable = Timetable::with(['module', 'userTimetables.user'])->find($timetableId);
        if (! $timetable) {
            return;
        }

        $company = Auth::user()->company()->with('companyDetail')->first();

        // Sort by name
        $timetable->userTimetables = $timetable->userTimetables->sortBy(function ($ut) {
            return $ut->user->name;
        });

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-card-pdf', [
            'timetable' => $timetable,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'kartu-peserta-'.$timetable->name.'.pdf'
        );
    }
}
