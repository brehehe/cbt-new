<?php

namespace App\Livewire\Admin\Report\Timetable;

use App\Models\Master\Timetable\Timetable;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportTimetableIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public function render()
    {
        $timetables = Timetable::search($this->search)
            ->select('id', 'name', 'module_id', 'start_time', 'end_time', 'description', 'code')
            ->latest()->paginate($this->perPage);
        return view('livewire.admin.report.timetable.admin-report-timetable-index',[
            'timetables' => $timetables
        ])->extends('layout.app')->section('content');
    }
}
