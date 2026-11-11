<?php

namespace App\Livewire\Admin\Report\AnswerStatistics;

use App\Models\Master\Timetable\Timetable;
use App\Services\Report\ItemAnalysisService;
use App\Traits\HasQuestionReportFilters;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportAnswerStatisticsIndex extends Component
{
    use HasQuestionReportFilters, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $timetable_id = '';

    public $search = '';

    public $sortBy = 'order';

    public $sortDirection = 'asc';

    protected $answerStats = [];

    protected $timetable = null;

    public function mount()
    {
        //
    }

    public function updatedTimetableId($value)
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedFilterTopicId()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedFilterMaterialCategoryId()
    {
        $this->filterMaterialId = '';
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedFilterMaterialId()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedFilterQuestionTypeId()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedFilterCategoryQuestionId()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function updatedSortDirection()
    {
        $this->resetPage();
        $this->loadStatistics();
    }

    public function setSorting(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
        $this->loadStatistics();
    }

    public function toggleSortDirection(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
        $this->loadStatistics();
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
        $this->resetPage();
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
        $report->mount($this->timetable_id, [
            'filterTopicId' => $this->filterTopicId,
            'filterMaterialCategoryId' => $this->filterMaterialCategoryId,
            'filterMaterialId' => $this->filterMaterialId,
            'filterQuestionTypeId' => $this->filterQuestionTypeId,
            'filterCategoryQuestionId' => $this->filterCategoryQuestionId,
            'search' => $this->search,
            'sortBy' => $this->sortBy,
            'sortDirection' => $this->sortDirection,
        ]);
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

        $statsToRender = $this->answerStats;
        if ($this->perPage !== 'all' && is_numeric($this->perPage) && count($this->answerStats) > 0) {
            $page = $this->getPage();
            $perPage = (int) $this->perPage;
            $items = array_slice($this->answerStats, ($page - 1) * $perPage, $perPage, true);
            $statsToRender = new LengthAwarePaginator(
                $items,
                count($this->answerStats),
                $perPage,
                $page,
                ['path' => request()->url(), 'pageName' => 'page']
            );
        }

        return view('livewire.admin.report.answer-statistics.admin-report-answer-statistics-index', [
            'timetables' => $timetables,
            'answerStats' => $statsToRender,
        ])
            ->extends('layout.app')
            ->section('content');
    }

    public function exportPdf()
    {
        @ini_set('memory_limit', '1024M');
        @set_time_limit(600);

        if (! $this->timetable_id) {
            return;
        }

        try {
            $this->loadStatistics();
            $this->skipRender();
            $timetable = $this->timetable;

            if (empty($this->answerStats)) {
                session()->flash('error', 'Tidak ada butir soal untuk diekspor ke PDF.');

                return;
            }

            $pdf = Pdf::loadView('livewire.admin.report.shared.overview-pdf', [
                'title' => 'Statistik Jawaban — '.($timetable?->name ?? 'Jadwal Ujian'),
                'analyses' => $this->answerStats,
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', false)
                ->setOption('isFontSubsettingEnabled', true)
                ->setOption('defaultFont', 'sans-serif')
                ->setOption('chroot', public_path());

            return response()->streamDownload(
                fn () => print ($pdf->output()),
                'laporan-statistik-jawaban-'.date('Y-m-d-H-i-s').'.pdf'
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Answer Statistics PDF Export Error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'Gagal membuat file PDF: '.$e->getMessage());
        }
    }
}
