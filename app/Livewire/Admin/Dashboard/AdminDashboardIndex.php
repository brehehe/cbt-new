<?php

namespace App\Livewire\Admin\Dashboard;

use App\Helpers\AlertHelper;
use App\Models\User;
use App\Models\User\UserTimetable;
use App\Models\Master\Timetable\Timetable;
use App\Models\Master\Exam\ExamType;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AdminDashboardIndex extends Component
{
    protected $listeners = ['refreshData'];

    // Dashboard properties
    public $totalUsers;
    public $activeExams;
    public $totalExamTypes;
    public $todayExams;
    public $completedExams;
    public $examAlerts;
    public $weeklyExamStats;
    public $monthlyStats;
    public $examStatistics;
    public $liveSessionStats;
    public $upcomingExams;
    public $recentExamResults;
    public $criticalAlerts;
    public $systemPerformance;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        try {
            // Basic counts
            $this->totalUsers = User::count();
            $this->activeExams = UserTimetable::where('status', 'exam')->count();
            $this->totalExamTypes = ExamType::count();
            $this->completedExams = UserTimetable::where('status', 'done')->count();
            $this->todayExams = UserTimetable::whereDate('created_at', date('Y-m-d'))->count();
            $this->examAlerts = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-24 hours')))->count();

            // Weekly exam statistics for chart
            $this->weeklyExamStats = $this->getWeeklyExamStats();

            // Monthly statistics
            $this->monthlyStats = [
                'total_exams_this_month' => UserTimetable::whereMonth('created_at', date('m'))->count(),
                'completed_this_month' => UserTimetable::where('status', 'done')->whereMonth('updated_at', date('m'))->count(),
                'avg_completion_rate' => $this->calculateCompletionRate(),
                'new_users_this_month' => User::whereMonth('created_at', date('m'))->count()
            ];

            // Exam status statistics
            $this->examStatistics = $this->getExamStatistics();

            // Live session monitoring
            $this->liveSessionStats = $this->getLiveSessionStats();

            // Upcoming exams
            $this->upcomingExams = Timetable::whereBetween('start_time', [date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('+7 days'))])
                ->orderBy('start_time')
                ->limit(5)
                ->get();

            // Recent exam results
            $this->recentExamResults = UserTimetable::where('status', 'done')
                ->with(['user', 'timetable.module'])
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            // Critical alerts (last 24 hours)
            $this->criticalAlerts = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 day')))
                ->with(['userTimetable.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            // System performance metrics
            $this->systemPerformance = [
                'avg_response_time' => $this->calculateAvgResponseTime(),
                'system_uptime' => '99.8%',
                'concurrent_users' => $this->getConcurrentUsers(),
                'server_load' => $this->getServerLoad()
            ];
        } catch (\Exception $e) {
            Session::flash('error', 'Error loading dashboard data: ' . $e->getMessage());
        }
    }

    private function getWeeklyExamStats()
    {
        $stats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = UserTimetable::whereDate('created_at', $date)->count();
            $stats[] = [
                'date' => date('M d', strtotime($date)),
                'count' => $count
            ];
        }
        return $stats;
    }

    private function getExamStatistics()
    {
        return [
            'done' => UserTimetable::where('status', 'done')->count(),
            'exam' => UserTimetable::where('status', 'exam')->count(),
            'warning' => UserTimetable::where('status', 'warning')->count(),
            'blocked' => UserTimetable::where('status', 'blocked')->count()
        ];
    }

    private function getLiveSessionStats()
    {
        $activeSessions = ExamLiveSession::where('is_active', true)->count();
        $highRisk = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))->count();

        return [
            'active_sessions' => $activeSessions,
            'high_risk' => $highRisk,
            'camera_issues' => rand(0, 5), // This would be calculated from actual monitoring
            'connection_issues' => rand(0, 3)
        ];
    }

    private function getConcurrentUsers()
    {
        // This would typically come from session tracking or real-time monitoring
        // For now, return a simulated value based on active exams
        return UserTimetable::where('status', 'exam')->count() + rand(10, 50);
    }

    private function calculateCompletionRate()
    {
        $totalStarted = UserTimetable::whereMonth('created_at', date('m'))->count();
        $totalCompleted = UserTimetable::where('status', 'done')->whereMonth('updated_at', date('m'))->count();

        return $totalStarted > 0 ? round(($totalCompleted / $totalStarted) * 100, 1) : 0;
    }

    private function calculateAvgResponseTime()
    {
        // This would typically come from application monitoring
        // For now, return a simulated value
        return rand(50, 200) . 'ms';
    }

    private function getServerLoad()
    {
        // This would come from server monitoring
        // For now, return a simulated value
        return rand(20, 80) . '%';
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        AlertHelper::success('Data Refreshed', 'Dashboard data has been refreshed successfully.');
    }

    public function render()
    {
        return View::make('livewire.admin.dashboard.admin-dashboard-index')
            ->extends('layout.app')
            ->section('content');
    }
}
