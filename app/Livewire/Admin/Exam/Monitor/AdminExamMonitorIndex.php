<?php

namespace App\Livewire\Admin\Exam\Monitor;

use App\Exports\ExamMonitorExport;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserTimetable;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdminExamMonitorIndex extends Component
{
    use WithPagination;

    public $selectedTimetable = '';

    public $search = '';

    public $statusFilter = '';

    public $riskFilter = '';

    public $sessionType = 'all';

    public $utStatus = '';

    protected $queryString = [
        'selectedTimetable' => ['except' => ''],
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'riskFilter' => ['except' => ''],
        'sessionType' => ['except' => 'all'],
        'utStatus' => ['except' => ''],
    ];

    public $refreshInterval = 5; // seconds

    public $autoRefresh = true;

    // Statistics
    public $totalActiveSessions = 0;

    public $totalOnlineStudents = 0;

    public $highRiskStudents = 0;

    public $totalAlerts = 0;

    // Force Finish Modal
    public $confirmFinishModal = false;

    public $finishTargetSession = null; // ExamLiveSession ID

    public $finishTargetInfo = []; // Display info for modal

    protected $listeners = [
        'refreshData',
        'toggleAutoRefresh',
        'updateSessionData',
        'studentDisconnected',
        'alertReceived',
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

    public function updatedSessionType()
    {
        $this->resetPage();
    }

    public function updatedUtStatus()
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
        $this->autoRefresh = ! $this->autoRefresh;
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
                'metadata' => $alertData['metadata'] ?? [],
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
                'connection_status' => 'disconnected',
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

    // ── Force Finish ────────────────────────────────────────────────────────────

    public function openFinishModal($sessionId)
    {
        $session = ExamLiveSession::with(['user', 'timetable', 'userTimetable'])->find($sessionId);
        if (! $session) {
            return;
        }

        $this->finishTargetSession = $sessionId;
        $this->finishTargetInfo = [
            'student_name' => $session->user?->name ?? 'Unknown',
            'nim' => $session->user?->nim ?? ($session->user?->username ?? '-'),
            'timetable_name' => $session->timetable?->name ?? '-',
            'module_name' => $session->timetable?->module?->name ?? '-',
            'ut_status' => $session->userTimetable?->status ?? '-',
        ];
        $this->confirmFinishModal = true;
    }

    public function closeFinishModal()
    {
        $this->confirmFinishModal = false;
        $this->finishTargetSession = null;
        $this->finishTargetInfo = [];
    }

    public function confirmForceFinish()
    {
        $session = ExamLiveSession::with(['userTimetable.userModuleQuestions.timetableAnswer', 'userTimetable.userModuleQuestions.timetableQuestion'])
            ->find($this->finishTargetSession);

        if (! $session) {
            $this->closeFinishModal();
            session()->flash('error', 'Sesi tidak ditemukan.');

            return;
        }

        $userTimetable = $session->userTimetable;
        if (! $userTimetable) {
            $this->closeFinishModal();
            session()->flash('error', 'Data ujian peserta tidak ditemukan.');

            return;
        }

        // ── Grade each question (same logic as ExamApiController::finishExam) ──
        $questions = $userTimetable->userModuleQuestions;
        $total = $questions->count();
        $correct = 0;

        foreach ($questions as $q) {
            $type = $q->timetableQuestion?->type ?? 'single';

            if ($type === 'essay') {
                $q->update(['status' => $q->essay_answer ? 'check' : 'unanswered']);

                continue;
            }

            if ($q->timetable_answer_id) {
                if ($q->timetableAnswer && $q->timetableAnswer->is_correct) {
                    $q->update(['status' => 'correct']);
                    $correct++;
                } else {
                    $q->update(['status' => 'wrong']);
                }
            } else {
                $q->update(['status' => 'unanswered']);
            }
        }

        $mark = $total > 0 ? round(($correct / $total) * 100, 2) : 0;

        // ── Mark UserTimetable as done ──────────────────────────────────────────
        $userTimetable->update([
            'status' => 'done',
            'end_exam' => Carbon::now(),
            'mark' => $mark,
        ]);

        // ── Close the live session ──────────────────────────────────────────────
        $session->update([
            'is_active' => false,
            'connection_status' => 'disconnected',
            'end_time' => Carbon::now(),
        ]);

        // Also close any other active sessions for the same user+timetable
        ExamLiveSession::where('user_timetable_id', $userTimetable->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'connection_status' => 'disconnected',
                'end_time' => Carbon::now(),
            ]);

        Log::info('ForceFinishExam: UserTimetable #'.$userTimetable->id.' marked done. Score: '.$mark);

        $this->closeFinishModal();
        $this->updateStatistics();
        session()->flash('success', 'Ujian peserta berhasil diselesaikan. Nilai: '.$mark);
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
        return Timetable::whereHas('userTimetables')->with('module')->get();
    }

    public function getActiveSessionsProperty()
    {
        ExamLiveSession::cleanupStaleSessions();

        $query = ExamLiveSession::with(['user', 'timetable.module', 'userTimetable.userModuleQuestions'])
            ->orderBy('last_activity', 'desc');

        if ($this->sessionType === 'active') {
            $query->active();
        } elseif ($this->sessionType === 'history') {
            $query->where('is_active', false);
        }

        if ($this->selectedTimetable) {
            $query->where('timetable_id', $this->selectedTimetable);
        }

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('nim', 'ilike', '%'.$this->search.'%')
                    ->orWhere('username', 'ilike', '%'.$this->search.'%');
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

        if ($this->utStatus) {
            $query->whereHas('userTimetable', function ($q) {
                $q->where('status', $this->utStatus);
            });
        }

        return $query->paginate(10);
    }

    public function exportExcel()
    {
        try {
            $fileName = 'monitoring-ujian-'.date('YmdHis').'.xlsx';

            return Excel::download(
                new ExamMonitorExport(
                    $this->selectedTimetable,
                    $this->search,
                    $this->statusFilter,
                    $this->riskFilter,
                    $this->sessionType,
                    $this->utStatus
                ),
                $fileName
            );
        } catch (\Exception $e) {
            Log::error('Exam Monitor Export Excel Error: '.$e->getMessage());
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengekspor data ke Excel.',
            ]);
        }
    }

    public function exportPdf()
    {
        try {
            $query = ExamLiveSession::with(['user', 'timetable.module', 'userTimetable.userModuleQuestions'])
                ->orderBy('last_activity', 'desc');

            if ($this->sessionType === 'active') {
                $query->active();
            } elseif ($this->sessionType === 'history') {
                $query->where('is_active', false);
            }

            if ($this->selectedTimetable) {
                $query->where('timetable_id', $this->selectedTimetable);
            }

            if ($this->search) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'ilike', '%'.$this->search.'%')
                        ->orWhere('nim', 'ilike', '%'.$this->search.'%')
                        ->orWhere('username', 'ilike', '%'.$this->search.'%');
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

            if ($this->utStatus) {
                $query->whereHas('userTimetable', function ($q) {
                    $q->where('status', $this->utStatus);
                });
            }

            $sessions = $query->get();

            $pdf = Pdf::loadView('livewire.admin.exam.monitor.admin-exam-monitor-pdf', [
                'sessions' => $sessions,
            ])->setPaper('a4', 'landscape');

            $fileName = 'monitoring-ujian-'.date('YmdHis').'.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName);
        } catch (\Exception $e) {
            Log::error('Exam Monitor Export PDF Error: '.$e->getMessage());
            $this->dispatch('swal:alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal mengekspor data ke PDF.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.exam.monitor.admin-exam-monitor-index', [
            'activeTimetables' => $this->activeTimtables,
            'activeSessions' => $this->activeSessions,
        ])->extends('layout.app')->section('content');
    }
}
