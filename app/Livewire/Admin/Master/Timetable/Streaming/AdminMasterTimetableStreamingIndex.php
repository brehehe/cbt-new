<?php

namespace App\Livewire\Admin\Master\Timetable\Streaming;

use App\Models\Exam\ExamLiveSession;
use App\Models\Master\Timetable\Timetable;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterTimetableStreamingIndex extends Component
{
    use WithPagination;

    public $timetableId;
    public $timetable;
    public $selectedSessionId = null;
    public $selectedSession = null;
    public $viewMode = 'grid'; // grid, single, gallery
    public $filterStatus = 'all'; // all, active, warning, error
    public $sortBy = 'last_activity'; // last_activity, name, alerts
    public $sortDirection = 'desc';

    protected $listeners = [
        'refreshStreamData',
        'selectSession',
        'takeSnapshot',
        'sendMessage',
        'terminateSession'
    ];

    public function mount($timetable_id)
    {
        $this->timetableId = $timetable_id;
        $this->loadTimetable();
        $this->loadActiveSessions();
    }

    public function loadTimetable()
    {
        $this->timetable = Timetable::with(['module', 'company'])->findOrFail($this->timetableId);
    }

    public function refreshStreamData()
    {
        $this->loadActiveSessions();
        if ($this->selectedSessionId) {
            $this->loadSelectedSession();
        }
        $this->dispatch('streamDataRefreshed');
    }

    public function selectSession($sessionId)
    {
        $this->selectedSessionId = $sessionId;
        $this->loadSelectedSession();
        $this->dispatch('sessionSelected', $sessionId);
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
        $this->dispatch('viewModeChanged', $mode);
    }

    public function setFilter($status)
    {
        $this->filterStatus = $status;
        $this->resetPage();
    }

    public function setSorting($sortBy, $direction = null)
    {
        $this->sortBy = $sortBy;
        $this->sortDirection = $direction ?? ($this->sortDirection === 'asc' ? 'desc' : 'asc');
        $this->resetPage();
    }

    public function takeSnapshot($sessionId)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            // Implement snapshot logic here
            session()->flash('success', 'Snapshot berhasil diambil untuk ' . $session->user->name);
        }
    }

    public function sendMessage($sessionId, $message)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            // Implement messaging logic here
            session()->flash('success', 'Pesan berhasil dikirim ke ' . $session->user->name);
        }
    }

    public function terminateSession($sessionId)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            $session->update([
                'is_active' => false,
                'connection_status' => 'terminated',
                'last_activity' => now()
            ]);

            session()->flash('success', 'Sesi ujian ' . $session->user->name . ' telah dihentikan');
        }
    }

    private function loadActiveSessions()
    {
        // This will be used in the render method
    }

    private function loadSelectedSession()
    {
        $this->selectedSession = ExamLiveSession::with([
            'user',
            'timetable.module',
            'userTimetable',
            'examRecordings' => function ($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }
        ])->find($this->selectedSessionId);
    }

    public function getActiveSessionsProperty()
    {
        $query = ExamLiveSession::with(['user', 'timetable.module', 'userTimetable'])
            ->where('is_active', true)
            ->where('timetable_id', $this->timetableId); // Filter by specific timetable

        // Apply filters
        switch ($this->filterStatus) {
            case 'active':
                $query->where('connection_status', 'connected');
                break;
            case 'warning':
                $query->where(function ($q) {
                    $q->where('alert_count', '>=', 3)
                        ->orWhere('warning_count', '>=', 5);
                });
                break;
            case 'error':
                $query->where(function ($q) {
                    $q->where('connection_status', 'disconnected')
                        ->orWhere('camera_status', 'error');
                });
                break;
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'name':
                $query->join('users', 'exam_live_sessions.user_id', '=', 'users.id')
                    ->orderBy('users.name', $this->sortDirection);
                break;
            case 'alerts':
                $query->orderBy('alert_count', $this->sortDirection);
                break;
            default:
                $query->orderBy($this->sortBy, $this->sortDirection);
                break;
        }

        return $query->paginate(12);
    }

    public function getSessionStatsProperty()
    {
        $total = ExamLiveSession::where('is_active', true)
            ->where('timetable_id', $this->timetableId)
            ->count();

        $active = ExamLiveSession::where('is_active', true)
            ->where('timetable_id', $this->timetableId)
            ->where('connection_status', 'connected')
            ->count();

        $warning = ExamLiveSession::where('is_active', true)
            ->where('timetable_id', $this->timetableId)
            ->where(function ($q) {
                $q->where('alert_count', '>=', 3)
                    ->orWhere('warning_count', '>=', 5);
            })->count();

        $error = ExamLiveSession::where('is_active', true)
            ->where('timetable_id', $this->timetableId)
            ->where(function ($q) {
                $q->where('connection_status', 'disconnected')
                    ->orWhere('camera_status', 'error');
            })->count();

        return [
            'total' => $total,
            'active' => $active,
            'warning' => $warning,
            'error' => $error
        ];
    }

    public function render()
    {
        return view('livewire.admin.master.timetable.streaming.admin-master-timetable-streaming-index', [
            'sessions' => $this->getActiveSessionsProperty(),
            'stats' => $this->getSessionStatsProperty()
        ])->extends('layout.app')->section('content');
    }
}
