<?php

namespace App\Livewire\Admin\Report\AnswerStatistics;

use App\Models\Master\Timetable\Timetable;
use App\Services\Report\ItemAnalysisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminReportAnswerStatisticsIndex extends Component
{
    public $timetable_id = '';

    protected $answerStats = [];

    protected $timetable = null;

    public function mount()
    {
        //
    }

    public function updatedTimetableId($value)
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        if (! $this->timetable_id) {
            $this->answerStats = [];
            $this->timetable = null;

            return;
        }

        $report = new ItemAnalysisService;
        $report->mount($this->timetable_id);
        $this->timetable = $report->timetable;
        $this->answerStats = $report->itemAnalysisData;
    }

    public function render()
    {
        if ($this->timetable_id && $this->timetable === null) {
            $this->loadStatistics();
        }
        $companyId = Auth::user()?->company_id;
        $timetables = Timetable::query()
            ->with('module:id,name')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time']);

        return view('livewire.admin.report.answer-statistics.admin-report-answer-statistics-index', [
            'timetables' => $timetables,
            'answerStats' => $this->answerStats,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        if (! $this->timetable_id) {
            return;
        }

        // Rebuild on the server instead of hydrating one Eloquent model per question/option.
        $this->loadStatistics();
        $this->skipRender();
        $timetable = $this->timetable;

        $pdf = Pdf::loadView('livewire.admin.report.shared.overview-pdf', [
            'title' => 'Statistik Jawaban — '.$timetable->name,
            'analyses' => $this->answerStats,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            'laporan-statistik-jawaban-'.date('Y-m-d-H-i-s').'.pdf'
        );
    }
}
