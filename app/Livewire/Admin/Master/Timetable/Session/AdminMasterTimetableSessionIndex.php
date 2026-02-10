<?php

namespace App\Livewire\Admin\Master\Timetable\Session;

use App\Helpers\AlertHelper;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserTimetable;
use App\Services\Exam\RecordingFinalizer;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class AdminMasterTimetableSessionIndex extends Component
{
    use WithPagination;

    public $timetable_id;
    public $timetable;
    public $perPage = 12;
    public $search = '';
    public $filterStatus = 'all'; // all, active, disconnected

    protected $listeners = [
        'terminateSession',
        'suspendSession',
        'forceLogoutUser',
    ];

    public function mount($timetable_id = null)
    {
        $this->timetable_id = $timetable_id;

        if (!$this->timetable_id) {
            return redirect()->route('admin.master.timetable');
        }

        $timetable = Timetable::with(['module', 'company'])->find($this->timetable_id);
        if (!$timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->timetable = $timetable;
    }

    public function suspendSession($sessionId)
    {
        $session = ExamLiveSession::with(['user', 'timetable'])->find($sessionId);
        if (!$session) {
            AlertHelper::warning('Perhatian', 'Sesi tidak ditemukan.');
            return;
        }

        $session->update([
            'is_active' => false,
            'connection_status' => 'disconnected',
            'last_activity' => Carbon::now(),
        ]);

        $userTimetable = UserTimetable::find($session->user_timetable_id);
        if ($userTimetable) {
            $userTimetable->update([
                'status' => 'suspend',
                'end_exam' => Carbon::now(),
            ]);

            try {
                // Finalisasi chunk rekaman dan simpan hasil ke ExamRecording terbaru
                $final = RecordingFinalizer::finalizeForUserTimetable($userTimetable->id);

                $latestRecording = ExamRecording::where('user_timetable_id', $userTimetable->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($latestRecording) {
                    $latestRecording->update([
                        'video_path' => $final['merged_video'] ?: ($final['manifest'] ?? $latestRecording->video_path),
                        'file_size' => $final['total_size'] ?? $latestRecording->file_size,
                        'end_time' => Carbon::now(),
                        'status' => 'completed',
                    ]);
                }
            } catch (\Throwable $e) {
                AlertHelper::warning('Perhatian', 'Gagal finalisasi rekaman: ' . $e->getMessage());
            }
        }

        AlertHelper::success('Berhasil', 'Sesi disuspend dan user di-logout.');
    }

    public function terminateSession($sessionId)
    {
        $session = ExamLiveSession::with(['user', 'timetable'])->find($sessionId);
        if (!$session) {
            AlertHelper::warning('Perhatian', 'Sesi tidak ditemukan.');
            return;
        }

        $session->update([
            'is_active' => false,
            'connection_status' => 'disconnected',
            'last_activity' => Carbon::now(),
        ]);

        AlertHelper::success('Berhasil', 'Sesi diputus.');
    }

    /**
     * Force logout account by deleting all web sessions for the user.
     */
    public function forceLogoutUser($userId)
    {
        try {
            \DB::table(config('session.table', 'sessions'))
                ->where('user_id', $userId)
                ->delete();

            // Putuskan semua live session user pada jadwal ini agar ujian langsung berhenti di sisi user
            $liveSessions = ExamLiveSession::query()
                ->where('user_id', $userId)
                ->where('timetable_id', $this->timetable_id)
                ->get();

            foreach ($liveSessions as $session) {
                $session->update([
                    'is_active' => false,
                    'connection_status' => 'disconnected',
                    'last_activity' => Carbon::now(),
                    'end_time' => Carbon::now(),
                ]);
            }

            // Pause timer pada UserTimetable yang aktif untuk user ini pada timetable terkait
            $userTimetable = UserTimetable::query()
                ->where('user_id', $userId)
                ->where('timetable_id', $this->timetable_id)
                ->whereIn('status', ['exam', 'warning'])
                ->first();

            if ($userTimetable) {
                // Set paused_at jika belum diset (hindari overwrite bila sudah paused)
                if (is_null($userTimetable->paused_at)) {
                    $userTimetable->update([
                        'paused_at' => Carbon::now(),
                    ]);
                }
            }

            AlertHelper::success('Berhasil', 'Akun di-logout dari semua perangkat dan sesi ujian diputus.');
        } catch (\Throwable $e) {
            AlertHelper::warning('Perhatian', 'Gagal logout akun: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $sessions = ExamLiveSession::query()
            ->where('timetable_id', $this->timetable_id)
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus !== 'all', function ($q) {
                if ($this->filterStatus === 'active') {
                    $q->where('is_active', true);
                } else {
                    $q->where('is_active', false)->where('connection_status', $this->filterStatus);
                }
            })
            ->with(['user', 'timetable'])
            ->orderBy('last_activity', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.session.admin-master-timetable-session-index', [
            'sessions' => $sessions,
        ])->extends('layout.app')->section('content');
    }
}