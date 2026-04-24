<?php

namespace App\Livewire\Admin\Exam\Monitor;

use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use Livewire\Component;
use Livewire\WithPagination;

class AdminExamMonitorDetailIndex extends Component
{
    use WithPagination;

    public $sessionId;

    public $session;

    public $refreshInterval = 3; // seconds

    public $autoRefresh = true;

    protected $listeners = [
        'refreshSessionData',
        'toggleAutoRefresh',
    ];

    public function mount($session)
    {
        $this->sessionId = $session;
        $this->loadSessionData();
    }

    public function refreshSessionData()
    {
        $this->loadSessionData();
        $this->dispatch('sessionDataRefreshed');
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = ! $this->autoRefresh;
        $this->dispatch('autoRefreshToggled', $this->autoRefresh);
    }

    public function terminateSession()
    {
        if ($this->session) {
            $this->session->update([
                'is_active' => false,
                'connection_status' => 'disconnected',
            ]);

            session()->flash('success', 'Sesi ujian telah dihentikan.');

            return redirect()->route('admin.exam.monitor');
        }
    }

    public function forceReconnect()
    {
        if ($this->session) {
            $this->session->update([
                'connection_status' => 'connected',
            ]);

            session()->flash('success', 'Koneksi student telah dipaksa untuk tersambung kembali.');
            $this->loadSessionData();
        }
    }

    private function loadSessionData()
    {
        $this->session = ExamLiveSession::with([
            'user',
            'timetable.module',
            'userTimetable',
            'examRecordings' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(10);
            },
        ])->find($this->sessionId);

        if (! $this->session) {
            session()->flash('error', 'Sesi tidak ditemukan.');

            return redirect()->route('admin.exam.monitor');
        }
    }

    public function getRecentAlertsProperty()
    {
        return ExamAlert::where('user_timetable_id', $this->session->user_timetable_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function getRecordingsProperty()
    {
        return ExamRecording::where('user_timetable_id', $this->session->user_timetable_id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        if (! $this->session) {
            return view('livewire.admin.exam.monitor.admin-exam-monitor-detail-index')
                ->with('error', 'Session not found')
                ->extends('layout.app')
                ->section('content');
        }

        return view('livewire.admin.exam.monitor.admin-exam-monitor-detail-index', [
            'recentAlerts' => $this->recentAlerts,
            'recordings' => $this->recordings,
        ])->extends('layout.app')->section('content');
    }
}
