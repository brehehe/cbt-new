<?php

namespace App\Livewire\Admin\Exam\LiveStream;

use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminExamLiveStreamIndex extends Component
{
    use WithPagination;

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
        'terminateSession',
    ];

    public function mount()
    {
        $this->loadActiveSessions();
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

    public function setSorting($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function takeSnapshot($sessionId = null)
    {
        $targetSessionId = $sessionId ?? $this->selectedSessionId;

        if (! $targetSessionId) {
            return;
        }

        $session = ExamLiveSession::find($targetSessionId);
        if ($session) {
            // Trigger snapshot capture
            $this->dispatch('captureSnapshot', $targetSessionId);

            session()->flash('success', 'Snapshot berhasil diambil untuk '.$session->user->name);
        }
    }

    public function sendMessage($sessionId, $message)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            // Send message to student (this would be implemented via broadcasting)
            $this->dispatch('messageSent', $sessionId, $message);

            // Log the message as an alert
            ExamAlert::create([
                'timetable_id' => $session->timetable_id,
                'user_timetable_id' => $session->user_timetable_id,
                'alert_type' => 'supervisor_message',
                'description' => 'Pesan dari supervisor: '.$message,
                'metadata' => [
                    'sender' => Auth::user()->name,
                    'timestamp' => now()->toISOString(),
                ],
            ]);

            session()->flash('success', 'Pesan berhasil dikirim ke '.$session->user->name);
        }
    }

    public function terminateSession($sessionId)
    {
        $session = ExamLiveSession::find($sessionId);
        if ($session) {
            $session->update([
                'is_active' => false,
                'connection_status' => 'terminated',
            ]);

            // Log termination
            ExamAlert::create([
                'timetable_id' => $session->timetable_id,
                'user_timetable_id' => $session->user_timetable_id,
                'alert_type' => 'session_terminated',
                'description' => 'Sesi dihentikan oleh supervisor',
                'metadata' => [
                    'terminated_by' => Auth::user()->name,
                    'timestamp' => now()->toISOString(),
                ],
            ]);

            session()->flash('success', 'Sesi ujian '.$session->user->name.' telah dihentikan');
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
            },
        ])->find($this->selectedSessionId);
    }

    public function getActiveSessionsProperty()
    {
        $query = ExamLiveSession::with(['user', 'timetable.module', 'userTimetable'])
            ->where('is_active', true);

        // Apply filters
        switch ($this->filterStatus) {
            case 'active':
                $query->where('connection_status', 'connected');
                // ->where('camera_status', 'active')
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
        $total = ExamLiveSession::where('is_active', true)->count();
        $active = ExamLiveSession::where('is_active', true)
            ->where('connection_status', 'connected')
            // ->where('camera_status', 'active')
            ->count();
        $warning = ExamLiveSession::where('is_active', true)
            ->where(function ($q) {
                $q->where('alert_count', '>=', 3)
                    ->orWhere('warning_count', '>=', 5);
            })->count();
        $error = ExamLiveSession::where('is_active', true)
            ->where(function ($q) {
                $q->where('connection_status', 'disconnected')
                    ->orWhere('camera_status', 'error');
            })->count();

        return [
            'total' => $total,
            'active' => $active,
            'warning' => $warning,
            'error' => $error,
        ];
    }

    public function render()
    {
        return view('livewire.admin.exam.live-stream.admin-exam-live-stream-index', [
            'sessions' => $this->getActiveSessionsProperty(),
            'stats' => $this->getSessionStatsProperty(),
        ])->extends('layout.app')->section('content');
    }
}
