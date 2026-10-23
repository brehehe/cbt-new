<?php

namespace App\Livewire\Admin\Report\ItemAnalysis\Detail;

use App\Services\Report\ItemAnalysisService;
use App\Traits\HasQuestionReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class AdminReportItemAnalysisDetailIndex extends Component
{
    use HasQuestionReportFilters;

    public $timetableId;

    public $search = '';

    public $sortBy = 'order';

    public $sortDirection = 'asc';

    public function setSorting(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleSortDirection(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function resetQuestionFilters(): void
    {
        $this->filterTopicId = '';
        $this->filterMaterialCategoryId = '';
        $this->filterMaterialId = '';
        $this->filterQuestionTypeId = '';
        $this->filterCategoryQuestionId = '';
        $this->search = '';
        $this->sortBy = 'order';
        $this->sortDirection = 'asc';
    }

    public function mount($id)
    {
        $this->timetableId = $id;
    }

    protected function report()
    {
        $report = new ItemAnalysisService;
        $report->mount($this->timetableId, [
            'filterTopicId' => $this->filterTopicId,
            'filterMaterialCategoryId' => $this->filterMaterialCategoryId,
            'filterMaterialId' => $this->filterMaterialId,
            'filterQuestionTypeId' => $this->filterQuestionTypeId,
            'filterCategoryQuestionId' => $this->filterCategoryQuestionId,
            'search' => $this->search,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
        ]);

        return $report;
    }

    public function render()
    {
        $report = $this->report();

        return view('livewire.admin.report.item-analysis.detail.admin-report-item-analysis-detail-index', [
            'timetable' => $report->timetable,
            'itemAnalysisData' => $report->itemAnalysisData,
        ])->extends('layout.app')->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);
        $this->skipRender();
        $report = $this->report();
        $pdf = Pdf::loadView('livewire.admin.report.shared.overview-pdf', [
            'title' => 'Analisis Butir Soal — '.$report->timetable->name,
            'analyses' => $report->itemAnalysisData,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(fn () => print ($pdf->output()), 'analisis-butir-soal.pdf');
    }
}
