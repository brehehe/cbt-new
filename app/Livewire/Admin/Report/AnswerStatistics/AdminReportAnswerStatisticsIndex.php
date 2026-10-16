<?php

namespace App\Livewire\Admin\Report\AnswerStatistics;

use App\Models\Company\Company;
use App\Models\Master\Timetable\Timetable;
use App\Services\Report\ItemAnalysisService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class AdminReportAnswerStatisticsIndex extends Component
{
    public $timetable_id = '';

    public $answerStats = [];

    public $timetable = null;

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

    private function hasEssayAnswerColumn(): bool
    {
        static $hasColumn;

        if ($hasColumn === null) {
            $hasColumn = Schema::hasColumn('user_module_questions', 'essay_answer');
        }

        return $hasColumn;
    }

    public function render()
    {
        $companyId = Auth::user()?->company_id;
        $timetables = Timetable::query()
            ->with('module:id,name')
            ->when($companyId, fn ($q) => $q->where('company_id', $companyId))
            ->orderBy('start_time', 'desc')
            ->get(['id', 'name', 'module_id', 'start_time']);

        return view('livewire.admin.report.answer-statistics.admin-report-answer-statistics-index', [
            'timetables' => $timetables,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        if (! $this->timetable_id || empty($this->answerStats)) {
            return;
        }

        $timetable = $this->timetable ?: Timetable::withoutGlobalScopes()->with('module')->find($this->timetable_id);
        if (! $timetable) {
            return;
        }
        $company = Auth::user()?->company ? Auth::user()->company()->with('companyDetail')->first() : Company::with('companyDetail')->first();

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
