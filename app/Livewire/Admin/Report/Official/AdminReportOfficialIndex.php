<?php

namespace App\Livewire\Admin\Report\Official;

use App\Models\Company\Company;
use App\Models\Master\Timetable\Timetable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportOfficialIndex extends Component
{
    use WithPagination;

    public $search;
    public $perPage = 10;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $user = Auth::user();
        $companyData = Company::first();

        // Base query
        $query = Timetable::with(['module', 'userTimetables'])
            ->where('company_id', $user->company_id);

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('module', function ($q) {
                      $q->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $timetables = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.report.official.admin-report-official-index', [
            'timetables' => $timetables,
            'companyData' => $companyData
        ])->extends('layout.app')->section('content');
    }

    public function printOfficialReport($timetableId)
    {
        $timetable = Timetable::with(['module', 'userTimetables'])->find($timetableId);
        if (!$timetable) return;

        $company = \Illuminate\Support\Facades\Auth::user()->company()->with('companyDetail')->first();
        
        $totalStudents = $timetable->userTimetables->count();
        $presentStudents = $timetable->userTimetables->whereNotNull('start_exam')->count();
        $absentStudents = $totalStudents - $presentStudents;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.admin.report.timetable.admin-report-timetable-official-pdf', [
            'timetable' => $timetable,
            'company' => $company,
            'stats' => [
                'total' => $totalStudents,
                'present' => $presentStudents,
                'absent' => $absentStudents
            ]
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'berita-acara-' . $timetable->name . '.pdf'
        );
    }
}
