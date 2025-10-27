<?php

namespace App\Livewire\Dosen\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DosenDashboardIndex extends Component
{
    public $totalMyExams = 15;
    public $totalStudents = 120;
    public $totalQuestions = 85;
    public $averageScore = 78.5;
    public $activeExams = 2;
    public $activeStudents = 35;
    public $questionTypes = 8;
    public $currentExams = [];
    public $upcomingExams = [];
    public $recentActivities = [];
    public $weeklyPerformance = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Sample data for demonstration
        $this->currentExams = [
            [
                'id' => 1,
                'name' => 'Ujian Tengah Semester - Algoritma',
                'participants' => 25,
                'remaining_time' => '01:45:30'
            ],
            [
                'id' => 2,
                'name' => 'Quiz Mingguan - Database',
                'participants' => 18,
                'remaining_time' => '00:30:15'
            ]
        ];

        $this->upcomingExams = [
            [
                'id' => 3,
                'name' => 'Ujian Akhir Semester - Pemrograman Web',
                'participants' => 45,
                'start_time' => '15 Sep, 09:00'
            ],
            [
                'id' => 4,
                'name' => 'Quiz - Struktur Data',
                'participants' => 32,
                'start_time' => '16 Sep, 14:00'
            ]
        ];

        $this->recentActivities = [
            [
                'student_name' => 'Ahmad Ridwan',
                'activity' => 'Menyelesaikan ujian',
                'status' => 'completed',
                'time' => '5 menit yang lalu'
            ],
            [
                'student_name' => 'Siti Nurhayati',
                'activity' => 'Sedang mengerjakan ujian',
                'status' => 'ongoing',
                'time' => '10 menit yang lalu'
            ],
            [
                'student_name' => 'Budi Santoso',
                'activity' => 'Menyelesaikan ujian',
                'status' => 'completed',
                'time' => '15 menit yang lalu'
            ]
        ];

        $this->weeklyPerformance = [
            ['date' => 'Sep 9', 'average' => 75],
            ['date' => 'Sep 10', 'average' => 78],
            ['date' => 'Sep 11', 'average' => 82],
            ['date' => 'Sep 12', 'average' => 79],
            ['date' => 'Sep 13', 'average' => 85],
            ['date' => 'Sep 14', 'average' => 88],
            ['date' => 'Sep 15', 'average' => 90]
        ];
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dataRefreshed');
    }

    public function render()
    {
        return View::make('livewire.dosen.dashboard.dosen-dashboard-index')
            ->extends('layout.app')
            ->section('content');
    }
}
