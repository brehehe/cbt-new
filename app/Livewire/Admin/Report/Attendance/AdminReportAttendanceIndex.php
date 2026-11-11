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

        $query = Timetable::with(['module:id,name'])
            ->select(['id', 'name', 'module_id', 'start_time', 'end_time'])
            ->when($user?->company_id, fn ($q, $cid) => $q->where('company_id', $cid));

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
        @ini_set('memory_limit', '1024M');
        @set_time_limit(600);
        $this->skipRender();

        try {
            $timetable = Timetable::with([
                'module:id,name',
                'userTimetables:id,timetable_id,user_id,start_exam',
                'userTimetables.user:id,name,nim,username',
            ])->find($timetableId);

            if (! $timetable) {
                session()->flash('error', 'Jadwal ujian tidak ditemukan.');

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
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', false)
                ->setOption('isFontSubsettingEnabled', true)
                ->setOption('defaultFont', 'sans-serif')
                ->setOption('chroot', public_path());

            return response()->streamDownload(
                fn () => print ($pdf->output()),
                'daftar-hadir-'.\Str::slug($timetable->name).'.pdf'
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Print Attendance List Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal membuat file PDF: '.$e->getMessage());
        }
    }
}
