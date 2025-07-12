<?php

namespace App\Livewire\Admin\Report\Timetable;

use App\Models\User\UserTimetable;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportTimetableDetail extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10, $search;

    public $user_timetables = [];

    public function render()
    {

        return view('livewire.admin.report.timetable.admin-report-timetable-detail', [

        ])->extends('layout.app')->section('content');
    }

    public function mount($id)
    {
        $this->user_timetables = UserTimetable::where('timetable_id', $id)->get();
    }
}
