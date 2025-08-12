<?php

namespace App\Livewire\Admin\Dashboard;

use App\Helpers\AlertHelper;
use App\Models\User;
use App\Models\User\UserTimetable;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Exam\ExamType;
use App\Models\Exam\ExamAlert;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class AdminDashboardIndex extends Component
{
    public $totalUsers;
    public $totalExams;
    public $totalExamTypes;
    public $activeExams;
    public $completedExams;
    public $todayExams;
    public $examAlerts;
    public $recentExamResults;
    public $examStatistics;

    public function mount()
    {
        $this->loadDashboardData();

        if (Session::has('saved')) {
            AlertHelper::success(Session::get('saved.title'), Session::get('saved.text'));
            Session::forget('saved');
            return;
        }
    }

    public function loadDashboardData()
    {
         // Total users
            $this->totalUsers = User::count();

            // Total timetables/exams
            $this->totalExams = Timetable::count();

            // Total exam types
            $this->totalExamTypes = ExamType::count();

            // Active exams (status: exam, warning)
            $this->activeExams = UserTimetable::whereIn('status', ['exam', 'warning'])->count();

            // Completed exams
            $this->completedExams = UserTimetable::where('status', 'done')->count();

            // Today's exams
            $this->todayExams = UserTimetable::whereDate('created_at', date('Y-m-d'))->count();

            // Exam alerts
            $this->examAlerts = ExamAlert::count();

            // Recent exam results (last 10)
            $this->recentExamResults = UserTimetable::with(['user:id,name', 'timetable.module:id,name'])
                ->where('status', 'done')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            // Exam statistics by status
            // $this->examStatistics = UserTimetable::select('status', DB::raw('count(*) as total'))
            //     ->groupBy('status')
            //     ->get()
            //     ->pluck('total', 'status')
            //     ->toArray();
    }

    public function refreshData()
    {
        $this->loadDashboardData();

        AlertHelper::success('Data Refreshed', 'Dashboard data has been refreshed successfully.');
    }

    public function render()
    {
        return view('livewire.admin.dashboard.admin-dashboard-index')
            ->extends('layout.app')
            ->section('content');
    }
}
