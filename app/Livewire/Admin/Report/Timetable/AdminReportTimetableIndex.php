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
        $companyId = Auth::user()?->company_id;

        $timetables = Timetable::search($this->search)
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->with(['module'])
            ->select('id', 'name', 'module_id', 'start_time', 'end_time', 'description', 'code')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.report.timetable.admin-report-timetable-index', [
            'timetables' => $timetables,
        ])->extends('layout.app')->section('content');
    }

    public function printOfficialReport($timetableId)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $timetable = Timetable::with(['module', 'userTimetables'])->find($timetableId);
        if (! $timetable) {
            return;
        }

        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

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
            'berita-acara-'.\Str::slug($timetable->name).'.pdf'
        );
    }

    public function printAttendanceList($timetableId)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $timetable = Timetable::with(['module', 'userTimetables.user'])->find($timetableId);
        if (! $timetable) {
            return;
        }

        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

        // Sort by name safely
        $timetable->userTimetables = $timetable->userTimetables->sortBy(function ($ut) {
            return $ut->user?->name ?? '';
        });

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-attendance-pdf', [
            'timetable' => $timetable,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'daftar-hadir-'.\Str::slug($timetable->name).'.pdf'
        );
    }

    public function printParticipantCards($timetableId)
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $timetable = Timetable::with(['module', 'userTimetables.user'])->find($timetableId);
        if (! $timetable) {
            return;
        }

        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : \App\Models\Company\Company::with('companyDetail')->first();

        // Sort by name safely
        $timetable->userTimetables = $timetable->userTimetables->sortBy(function ($ut) {
            return $ut->user?->name ?? '';
        });

        $pdf = Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-card-pdf', [
            'timetable' => $timetable,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'kartu-peserta-'.\Str::slug($timetable->name).'.pdf'
        );
    }
}
