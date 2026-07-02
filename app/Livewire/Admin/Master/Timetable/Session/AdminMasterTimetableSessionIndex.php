<?php

namespace App\Livewire\Admin\Master\Timetable\Session;

use App\Exports\TimetableSessionExport;
use App\Helpers\AlertHelper;
use App\Models\Exam\ExamLiveSession;
use App\Models\Exam\ExamRecording;
use App\Models\Master\Timetable\Timetable;
use App\Models\User\UserTimetable;
use App\Services\Exam\RecordingFinalizer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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
        'unsuspendSession',
        'forceLogoutUser',
    ];

    public function mount($timetable_id = null)
    {
        $this->timetable_id = $timetable_id;

        if (! $this->timetable_id) {
            return redirect()->route('admin.master.timetable');
        }

        $timetable = Timetable::with(['module', 'company'])->find($this->timetable_id);
        if (! $timetable) {
            return redirect()->route('admin.master.timetable');
        }

        $this->timetable = $timetable;
    }

    public function suspendSession($userId)
    {
        $session = ExamLiveSession::where('user_id', $userId)->where('timetable_id', $this->timetable_id)->first();
        if ($session) {
            $session->update([
                'is_active' => false,
                'connection_status' => 'disconnected',
                'last_activity' => Carbon::now(),
                'end_time' => Carbon::now(),
            ]);
        }

        $userTimetable = UserTimetable::where('user_id', $userId)->where('timetable_id', $this->timetable_id)->first();
        if ($userTimetable) {
            $userTimetable->update([
                'status' => 'suspend',
                'end_exam' => Carbon::now(),
                'paused_at' => Carbon::now(),
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
                AlertHelper::warning('Perhatian', 'Gagal finalisasi rekaman: '.$e->getMessage());
            }
        }

        AlertHelper::success('Berhasil', 'Sesi disuspend dan user di-logout.');
    }

    public function unsuspendSession($userId)
    {
        $userTimetable = UserTimetable::where('user_id', $userId)->where('timetable_id', $this->timetable_id)->first();
        if ($userTimetable) {
            $userTimetable->update([
                'status' => 'exam',
                'end_exam' => null,
            ]);
        }

        AlertHelper::success('Berhasil', 'Status suspend telah dicabut, peserta dapat melanjutkan ujian.');
    }

    public function terminateSession($sessionId)
    {
        $session = ExamLiveSession::with(['user', 'timetable'])->find($sessionId);
        if (! $session) {
            AlertHelper::warning('Perhatian', 'Sesi tidak ditemukan.');

            return;
        }

        $session->update([
            'is_active' => false,
            'connection_status' => 'disconnected',
            'last_activity' => Carbon::now(),
            'end_time' => Carbon::now(),
        ]);

        AlertHelper::success('Berhasil', 'Sesi diputus.');
    }

    /**
     * Force logout account — SESSION_DRIVER=database.
     *
     * Layer 1: Hapus baris session dari tabel 'sessions'.
     * Layer 2: Update ExamLiveSession.is_active = false →
     *          React polling /api/exam/{id}/status mendeteksi & redirect ke /logout.
     */
    public function forceLogoutUser($userId)
    {
        try {
            // Layer 1: Hapus session dari database
            $deletedCount = \DB::table(config('session.table', 'sessions'))
                ->where('user_id', $userId)
                ->delete();

            // Layer 1.5: Hapus session dari Redis/Cache
            $lastSessionId = \Illuminate\Support\Facades\Cache::get("user_session_{$userId}");
            if ($lastSessionId) {
                try {
                    \Illuminate\Support\Facades\Session::getHandler()->destroy($lastSessionId);
                } catch (\Throwable $e) {
                    // Ignore
                }
                \Illuminate\Support\Facades\Cache::forget("user_session_{$userId}");
            }

            \Log::info("ForceLogout: Deleted {$deletedCount} DB session(s) and cleared Redis session for user #{$userId}");

            // Layer 2: Putuskan ExamLiveSession → React polling mendeteksi dalam ≤8 detik
            ExamLiveSession::query()
                ->where('user_id', $userId)
                ->where('timetable_id', $this->timetable_id)
                ->update([
                    'is_active' => false,
                    'connection_status' => 'disconnected',
                    'last_activity' => Carbon::now(),
                    'end_time' => Carbon::now(),
                ]);

            // Pause timer pada UserTimetable yang aktif
            UserTimetable::query()
                ->where('user_id', $userId)
                ->where('timetable_id', $this->timetable_id)
                ->whereIn('status', ['exam', 'warning'])
                ->whereNull('paused_at')
                ->update(['paused_at' => Carbon::now()]);

            AlertHelper::success('Berhasil', "Akun di-logout ({$deletedCount} sesi dihapus) dan ujian diputus.");
        } catch (\Throwable $e) {
            \Log::error("ForceLogout error: " . $e->getMessage());
            AlertHelper::warning('Perhatian', 'Gagal logout akun: ' . $e->getMessage());
        }
    }


    public function exportExcel()
    {
        try {
            $timetableName = $this->timetable->name ?? 'sesi';
            $fileName = 'sesi-ujian-' . str()->slug($timetableName) . '-' . date('YmdHis') . '.xlsx';

            return Excel::download(
                new TimetableSessionExport($this->timetable_id, $this->search),
                $fileName
            );
        } catch (\Exception $e) {
            Log::error('Timetable Session Export Excel Error: ' . $e->getMessage());
            AlertHelper::warning('Perhatian', 'Gagal mengekspor data ke Excel.');
        }
    }

    public function exportPdf()
    {
        try {
            $classmateId = $this->timetable->classmate_id;

            $sessions = \App\Models\Classmate\ClassmateStudent::query()
                ->where('classmate_id', $classmateId)
                ->when($this->search, function ($q) {
                    $q->whereHas('user', function ($uq) {
                        $uq->where('name', 'ilike', '%' . $this->search . '%')
                           ->orWhere('nim', 'ilike', '%' . $this->search . '%')
                           ->orWhere('username', 'ilike', '%' . $this->search . '%');
                    });
                })
                ->with([
                    'user.usrSecKey',
                    'user.examLiveSessions' => function ($q) {
                        $q->where('timetable_id', $this->timetable_id);
                    },
                    'user.userTimetables' => function ($q) {
                        $q->where('timetable_id', $this->timetable_id)->with('userModuleQuestions');
                    },
                ])
                ->get();

            $pdf = Pdf::loadView('livewire.admin.master.timetable.session.admin-master-timetable-session-pdf', [
                'sessions'      => $sessions,
                'timetableName' => $this->timetable->name ?? 'Sesi Ujian',
            ])->setPaper('a4', 'landscape');

            $timetableName = $this->timetable->name ?? 'sesi';
            $fileName = 'sesi-ujian-' . str()->slug($timetableName) . '-' . date('YmdHis') . '.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $fileName);
        } catch (\Exception $e) {
            Log::error('Timetable Session Export PDF Error: ' . $e->getMessage());
            AlertHelper::warning('Perhatian', 'Gagal mengekspor data ke PDF.');
        }
    }

    public function render()
    {
        $classmateId = $this->timetable->classmate_id;

        $sessions = \App\Models\Classmate\ClassmateStudent::query()
            ->where('classmate_id', $classmateId)
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'ilike', '%'.$this->search.'%')
                       ->orWhere('nim', 'ilike', '%'.$this->search.'%')
                       ->orWhere('username', 'ilike', '%'.$this->search.'%');
                });
            })
            ->when($this->filterStatus !== 'all', function ($q) {
                if ($this->filterStatus === 'active') {
                    $q->whereHas('user.examLiveSessions', function ($sq) {
                        $sq->where('timetable_id', $this->timetable_id)->where('is_active', true);
                    });
                } elseif ($this->filterStatus === 'disconnected') {
                    $q->whereHas('user.examLiveSessions', function ($sq) {
                        $sq->where('timetable_id', $this->timetable_id)->where('is_active', false)->where('connection_status', 'disconnected');
                    });
                }
            })
            ->with([
                'user.usrSecKey',
                'user.examLiveSessions' => function($q) {
                    $q->where('timetable_id', $this->timetable_id);
                },
                'user.userTimetables' => function($q) {
                    $q->where('timetable_id', $this->timetable_id)->with('userModuleQuestions');
                }
            ])
            ->paginate($this->perPage);

        return view('livewire.admin.master.timetable.session.admin-master-timetable-session-index', [
            'sessions' => $sessions,
        ])->extends('layout.app')->section('content');
    }
}
