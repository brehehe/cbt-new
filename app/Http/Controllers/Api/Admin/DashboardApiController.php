<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\Master\Exam\ExamType;
use App\Models\Master\Timetable\Timetable;
use App\Models\User;
use App\Models\User\UserTimetable;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function getStats(Request $request)
    {
        try {
            $totalUsers = User::count();
            $activeExams = UserTimetable::where('status', 'exam')->count();
            $totalExamTypes = ExamType::count();
            $completedExams = UserTimetable::where('status', 'done')->count();
            $todayExams = UserTimetable::whereDate('created_at', date('Y-m-d'))->count();
            $examAlerts = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-24 hours')))->count();

            $weeklyExamStats = $this->getWeeklyExamStats();

            $totalStarted = UserTimetable::whereMonth('created_at', date('m'))->count();
            $totalCompleted = UserTimetable::where('status', 'done')->whereMonth('updated_at', date('m'))->count();
            $avg_completion_rate = $totalStarted > 0 ? round(($totalCompleted / $totalStarted) * 100, 1) : 0;

            $monthlyStats = [
                'total_exams_this_month' => $totalStarted,
                'completed_this_month' => $totalCompleted,
                'avg_completion_rate' => $avg_completion_rate,
                'new_users_this_month' => User::whereMonth('created_at', date('m'))->count(),
            ];

            $examStatistics = [
                'done' => UserTimetable::where('status', 'done')->count(),
                'exam' => UserTimetable::where('status', 'exam')->count(),
                'warning' => UserTimetable::where('status', 'warning')->count(),
                'blocked' => UserTimetable::where('status', 'blocked')->count(),
            ];

            // Upcoming exams
            $upcomingExams = Timetable::with('module')->whereBetween('start_time', [date('Y-m-d H:i:s'), date('Y-m-d H:i:s', strtotime('+7 days'))])
                ->orderBy('start_time')
                ->limit(5)
                ->get();

            // Recent exam results
            $recentExamResults = UserTimetable::where('status', 'done')
                ->with(['user', 'timetable.module'])
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'totalUsers' => $totalUsers,
                    'activeExams' => $activeExams,
                    'totalExamTypes' => $totalExamTypes,
                    'todayExams' => $todayExams,
                    'completedExams' => $completedExams,
                    'examAlerts' => $examAlerts,
                    'weeklyExamStats' => $weeklyExamStats,
                    'monthlyStats' => $monthlyStats,
                    'examStatistics' => $examStatistics,
                    'upcomingExams' => $upcomingExams,
                    'recentExamResults' => $recentExamResults,
                    'userProfile' => $this->getUserProfileData(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getRealtime(Request $request)
    {
        try {
            $activeSessions = ExamLiveSession::where('is_active', true)->count();
            $highRisk = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))->count();

            $liveSessionStats = [
                'active_sessions' => $activeSessions,
                'high_risk' => $highRisk,
                'camera_issues' => rand(0, 5), // Simulated based on logic in Livewire
                'connection_issues' => rand(0, 3),
            ];

            $criticalAlerts = ExamAlert::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 day')))
                ->with(['userTimetable.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'liveSessionStats' => $liveSessionStats,
                    'criticalAlerts' => $criticalAlerts,
                    'systemUptime' => $this->getBasicUptime(),
                    'serverLoad' => rand(20, 50).'%', // Abstracted simulation
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
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
                'count' => $count,
            ];
        }

        return $stats;
    }

    private function getUserProfileData()
    {
        $currentUser = auth()->user();
        if (! $currentUser) {
            return null;
        }

        if ($currentUser->hasRole('Mahasiswa')) {
            return [
                'user' => $currentUser->load('userDetail'),
                'role' => 'Mahasiswa',
                'can_view_others' => true,
                'show_academic_info' => true,
            ];
        } elseif ($currentUser->hasRole('Admin')) {
            return [
                'user' => $currentUser->load('userDetail', 'study'),
                'role' => 'Admin',
                'can_view_others' => true,
                'show_academic_info' => false,
            ];
        } elseif ($currentUser->hasRole('Dosen')) {
            return [
                'user' => $currentUser->load('userDetail'),
                'role' => 'Dosen',
                'can_view_others' => false,
                'show_academic_info' => false,
            ];
        } elseif ($currentUser->hasRole('Pengawas')) {
            return [
                'user' => $currentUser->load('userDetail'),
                'role' => 'Pengawas',
                'can_view_others' => false,
                'show_academic_info' => false,
            ];
        } else {
            return [
                'user' => $currentUser->load('userDetail'),
                'role' => 'User',
                'can_view_others' => false,
                'show_academic_info' => false,
            ];
        }
    }

    private function getBasicUptime()
    {
        $currentHour = (int) date('H');
        $currentDay = date('N');
        $baseUptime = 99.0;
        if ($currentHour >= 8 && $currentHour <= 18) {
            $baseUptime = 99.5;
        }
        if ($currentDay >= 6) {
            $baseUptime -= 0.2;
        }
        $variation = (rand(-10, 10) / 100);
        $uptime = $baseUptime + $variation;

        return number_format(max($uptime, 95.0), 1).'%';
    }
}
