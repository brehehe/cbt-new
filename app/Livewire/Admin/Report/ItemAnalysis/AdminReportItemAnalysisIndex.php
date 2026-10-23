<?php

namespace App\Livewire\Admin\Report\ItemAnalysis;

use App\Models\Master\Timetable\Timetable;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportItemAnalysisIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $search;

    public $sortBy = 'start_time';

    public $sortDirection = 'desc';

    public function setSorting(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function toggleSortDirection(): void
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->resetPage();
    }

    public function render()
    {
        $dir = strtolower($this->sortDirection) === 'asc' ? 'asc' : 'desc';

        $query = Timetable::search($this->search)
            ->with(['module'])
            ->select('id', 'name', 'module_id', 'start_time', 'end_time', 'description', 'code');

        if ($this->sortBy === 'name') {
            $query->orderByRaw('LOWER(TRIM(name)) '.$dir);
        } elseif ($this->sortBy === 'end_time') {
            $query->orderBy('end_time', $dir);
        } else {
            $query->orderBy('start_time', $dir);
        }

        $timetables = $query->paginate($this->perPage);

        return view('livewire.admin.report.item-analysis.admin-report-item-analysis-index', [
            'timetables' => $timetables,
        ])->extends('layout.app')->section('content');
    }
}
