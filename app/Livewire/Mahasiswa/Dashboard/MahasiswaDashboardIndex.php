<?php

namespace App\Livewire\Mahasiswa\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MahasiswaDashboardIndex extends Component
{
    public $todayExams = 2;
    public $activeExamToday = 1;
    public $totalExams = 25;
    public $completedExams = 18;
    public $averageScore = 82.3;
    public $classRank = 5;
    public $totalStudents = 45;
    public $notifications = 3;
    public $upcomingExams = [];
    public $activeExams = [];
    public $learningProgress = [];
    public $recentResults = [];
    public $monthlyPerformance = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Sample data for demonstration
        $this->upcomingExams = [
            [
                'id' => 1,
                'name' => 'Ujian Akhir Semester',
                'subject' => 'Pemrograman Web',
                'date' => '16 Sep 2025',
                'time' => '09:00'
            ],
            [
                'id' => 2,
                'name' => 'Quiz Mingguan',
                'subject' => 'Database Systems',
                'date' => '17 Sep 2025',
                'time' => '14:00'
            ]
        ];

        $this->activeExams = [
            [
                'id' => 3,
                'name' => 'Ujian Tengah Semester',
                'subject' => 'Algoritma & Struktur Data',
                'remaining_time' => '01:25:30'
            ]
        ];

        $this->learningProgress = [
            'Pemrograman Web' => [
                'percentage' => 85,
                'completed' => 8,
                'total' => 10
            ],
            'Database Systems' => [
                'percentage' => 70,
                'completed' => 7,
                'total' => 10
            ],
            'Algoritma' => [
                'percentage' => 92,
                'completed' => 11,
                'total' => 12
            ],
            'Jaringan Komputer' => [
                'percentage' => 60,
                'completed' => 6,
                'total' => 10
            ]
        ];

        $this->recentResults = [
            [
                'exam_name' => 'Quiz Database',
                'subject' => 'Database Systems',
                'score' => 88,
                'date' => '12 Sep 2025'
            ],
            [
                'exam_name' => 'UTS Algoritma',
                'subject' => 'Algoritma',
                'score' => 92,
                'date' => '10 Sep 2025'
            ],
            [
                'exam_name' => 'Praktikum Web',
                'subject' => 'Pemrograman Web',
                'score' => 85,
                'date' => '8 Sep 2025'
            ]
        ];

        // Generate sample monthly performance data
        $this->monthlyPerformance = [];
        for ($i = 29; $i >= 0; $i--) {
            $this->monthlyPerformance[] = [
                'date' => 'Day ' . (30 - $i),
                'score' => rand(70, 95)
            ];
        }
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dataRefreshed');
    }

    public function render()
    {
        return View::make('livewire.mahasiswa.dashboard.mahasiswa-dashboard-index');
    }
}
