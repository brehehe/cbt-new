<?php

namespace App\Livewire\Pengawas\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class PengawasDashboardIndex extends Component
{
    public $activeStudents = 45;
    public $totalStudents = 47;
    public $flaggedStudents = 2;
    public $averageProgress = 78;
    public $activeExams = 3;
    public $violationCount = 1;
    public $suspiciousActivities = [];
    public $studentsActivity = [];
    public $examRooms = [];
    public $systemAlerts = [];
    public $monitoringData = [];

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Sample data for demonstration
        $this->suspiciousActivities = [
            [
                'id' => 1,
                'student_name' => 'Agus Prasetyo',
                'activity' => 'Multiple tab switching',
                'severity' => 'medium',
                'time' => '14:25',
                'exam' => 'UAS Database'
            ],
            [
                'id' => 2,
                'student_name' => 'Siti Nurhaliza',
                'activity' => 'Face not detected',
                'severity' => 'high',
                'time' => '14:20',
                'exam' => 'UTS Algoritma'
            ]
        ];

        $this->studentsActivity = [
            [
                'id' => 1,
                'name' => 'Ahmad Fadli',
                'exam' => 'UAS Database',
                'progress' => 85,
                'time_remaining' => '00:45:30',
                'status' => 'active',
                'camera_status' => 'active'
            ],
            [
                'id' => 2,
                'name' => 'Rina Sari',
                'exam' => 'UTS Algoritma',
                'progress' => 92,
                'time_remaining' => '00:32:15',
                'status' => 'active',
                'camera_status' => 'active'
            ],
            [
                'id' => 3,
                'name' => 'Budi Santoso',
                'exam' => 'Quiz Pemrograman',
                'progress' => 65,
                'time_remaining' => '01:15:45',
                'status' => 'active',
                'camera_status' => 'warning'
            ]
        ];

        $this->examRooms = [
            [
                'room' => 'Lab Komputer 1',
                'exam' => 'UAS Database Systems',
                'students' => 25,
                'active' => 23,
                'flagged' => 1
            ],
            [
                'room' => 'Lab Komputer 2',
                'exam' => 'UTS Algoritma',
                'students' => 22,
                'active' => 22,
                'flagged' => 1
            ]
        ];

        $this->systemAlerts = [
            [
                'type' => 'warning',
                'message' => 'High CPU usage detected on server 2',
                'time' => '14:30'
            ],
            [
                'type' => 'info',
                'message' => 'Automatic backup completed successfully',
                'time' => '14:15'
            ]
        ];

        // Generate sample monitoring data
        $this->monitoringData = [];
        for ($i = 59; $i >= 0; $i--) {
            $this->monitoringData[] = [
                'time' => $i . 'm ago',
                'active_students' => rand(40, 47),
                'violations' => rand(0, 3)
            ];
        }
    }

    public function flagStudent($studentId)
    {
        // In real implementation, this would flag the student
        $this->flaggedStudents++;
        $this->dispatch('studentFlagged', $studentId);
    }

    public function resolveViolation($violationId)
    {
        // In real implementation, this would resolve the violation
        $this->dispatch('violationResolved', $violationId);
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('dataRefreshed');
    }

    public function render()
    {
        return View::make('livewire.pengawas.dashboard.pengawas-dashboard-index');
    }
}
