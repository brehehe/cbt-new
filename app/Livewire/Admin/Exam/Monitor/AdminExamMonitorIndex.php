<?php

namespace App\Livewire\Admin\Exam\Monitor;

use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamAlert;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserTimetable;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class AdminExamMonitorIndex extends Component
{
    use WithPagination;

    public $selectedTimetable = '';
    public $search = '';
    public $statusFilter = '';
    public $riskFilter = '';
    public $refreshInterval = 5; // seconds
    public $autoRefresh = true;

    // Statistics
    public $totalActiveSessions = 0;
    public $totalOnlineStudents = 0;
    public $highRiskStudents = 0;
    public $totalAlerts = 0;

    protected $listeners = [
        'refreshData',
        'toggleAutoRefresh',
        'updateSessionData',
        'studentDisconnected',
        'alertReceived'
    ];

    public function mount()
    {
        $this->updateStatistics();
    }

    public function updatedSelectedTimetable()
    {
        $this->resetPage();
        $this->updateStatistics();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedRiskFilter()
    {
        $this->resetPage();
    }

    public function refreshData()
    {
        $this->updateStatistics();
        $this->dispatch('dataRefreshed');
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
        $this->dispatch('autoRefreshToggled', $this->autoRefresh);
    }

    public function updateSessionData($sessionId, $data)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            $session->update($data);
            $this->updateStatistics();
        }
    }

    public function studentDisconnected($sessionId)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            $session->markOffline();
            $this->updateStatistics();
        }
    }

    public function alertReceived($sessionId, $alertData)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            $session->incrementAlert();
            $this->updateStatistics();

            // Create alert record
            ExamAlert::create([
                'timetable_id' => $session->timetable_id,
                'user_timetable_id' => $session->user_timetable_id,
                'alert_type' => $alertData['type'] ?? 'general',
                'description' => $alertData['description'] ?? 'Alert detected',
                'metadata' => $alertData['metadata'] ?? []
            ]);
        }
    }

    public function viewSessionDetail($sessionId)
    {
        return redirect()->route('admin.exam.monitor.detail', ['session' => $sessionId]);
    }

    public function terminateSession($sessionId)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            // Mark session as inactive
            $session->update([
                'is_active' => false,
                'connection_status' => 'disconnected'
            ]);

            // Update user timetable status if needed
            $userTimetable = $session->userTimetable;
            if ($userTimetable && in_array($userTimetable->status, ['exam', 'warning'])) {
                $userTimetable->update(['status' => 'warning']);
            }

            session()->flash('success', 'Sesi ujian telah dihentikan.');
            $this->updateStatistics();
        }
    }

    public function forceDisconnect($sessionId)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            $session->markOffline();
            session()->flash('success', 'Student telah diputuskan koneksinya.');
            $this->updateStatistics();
        }
    }

    private function updateStatistics()
    {
        $query = ExamLiveSession::query();

        if ($this->selectedTimetable) {
            $query->where('timetable_id', $this->selectedTimetable);
        }

        $this->totalActiveSessions = $query->active()->count();
        $this->totalOnlineStudents = $query->active()->where('connection_status', 'connected')->count();
        $this->highRiskStudents = $query->active()->where(function ($q) {
            $q->where('alert_count', '>=', 3)->orWhere('warning_count', '>=', 5);
        })->count();

        $this->totalAlerts = ExamAlert::when($this->selectedTimetable, function ($q) {
            $q->where('timetable_id', $this->selectedTimetable);
        })->whereDate('created_at', today())->count();
    }

    public function getActiveTimtablesProperty()
    {
        return Timetable::whereHas('userTimetables', function ($query) {
            $query->whereIn('status', ['exam', 'warning']);
        })->with('module')->get();
    }

    public function getActiveSessionsProperty()
    {
        $query = ExamLiveSession::with(['user', 'timetable.module', 'userTimetable'])
            ->active()
            ->orderBy('last_activity', 'desc');

        if ($this->selectedTimetable) {
            $query->where('timetable_id', $this->selectedTimetable);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('nim', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('connection_status', $this->statusFilter);
        }

        if ($this->riskFilter) {
            switch ($this->riskFilter) {
                case 'high':
                    $query->where(function ($q) {
                        $q->where('alert_count', '>=', 5)->orWhere('warning_count', '>=', 10);
                    });
                    break;
                case 'medium':
                    $query->where(function ($q) {
                        $q->whereBetween('alert_count', [3, 4])
                            ->orWhereBetween('warning_count', [5, 9]);
                    });
                    break;
                case 'low':
                    $query->where(function ($q) {
                        $q->whereBetween('alert_count', [1, 2])
                            ->orWhereBetween('warning_count', [1, 4]);
                    });
                    break;
                case 'none':
                    $query->where('alert_count', 0)->where('warning_count', 0);
                    break;
            }
        }

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.exam.monitor.admin-exam-monitor-index', [
            'activeTimetables' => $this->activeTimtables,
            'activeSessions' => $this->activeSessions
        ])->extends('layout.app')->section('content');
    }
}
