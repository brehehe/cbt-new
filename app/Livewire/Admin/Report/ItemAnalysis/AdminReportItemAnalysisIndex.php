<?php

namespace App\Livewire\Admin\Report\ItemAnalysis;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Master\Timetable\Timetable;

class AdminReportItemAnalysisIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public function render()
    {
        $timetables = Timetable::search($this->search)
            ->select('id', 'name', 'module_id', 'start_time', 'end_time', 'description', 'code')
            ->latest()->paginate($this->perPage);
        return view('livewire.admin.report.item-analysis.admin-report-item-analysis-index', [
            'timetables' => $timetables
        ])->extends('layout.app')->section('content');
    }
}
