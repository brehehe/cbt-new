<?php

namespace App\Livewire\Admin\Report\Attendance;

use App\Models\Company\Company;
use App\Models\Master\Timetable\Timetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportAttendanceIndex extends Component
{
    use WithPagination;

    public $search;

    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $user = Auth::user();
        $companyData = Company::first();

        $query = Timetable::with(['module'])
            ->where('company_id', $user->company_id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhereHas('module', function ($q) {
                        $q->where('name', 'ilike', '%'.$this->search.'%');
                    });
            });
        }

        $timetables = $query->latest()->paginate($this->perPage);

        return view('livewire.admin.report.attendance.admin-report-attendance-index', [
            'timetables' => $timetables,
            'companyData' => $companyData,
        ])->extends('layout.app')->section('content');
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
}
