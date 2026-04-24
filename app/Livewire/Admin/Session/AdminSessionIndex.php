<?php

namespace App\Livewire\Admin\Session;

use App\Helpers\AlertHelper;
use App\Models\Exam\ExamLiveSession;
use App\Models\User;
use App\Models\User\UserTimetable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class AdminSessionIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DB::table(config('session.table', 'sessions'))
            ->whereNotNull('user_id');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('ip_address', 'like', '%' . $this->search . '%')
                  ->orWhere('user_agent', 'like', '%' . $this->search . '%');
            });
        }

        $sessions = $query->orderBy('last_activity', 'desc')->paginate($this->perPage);

        $userIds = collect($sessions->items())->pluck('user_id')->filter()->unique();
        $users   = User::whereIn('id', $userIds)->get()->keyBy('id');

        return view('livewire.admin.session.admin-session-index', [
            'sessions'       => $sessions,
            'users'          => $users,
            'session_driver' => config('session.driver'),
        ])->extends('layout.app')->section('content');
    }

    public function confirmForceLogout($userId)
    {
        return AlertHelper::confirmDelete('forceLogoutUser', 'Anda yakin ingin memaksa logout pengguna ini?', $userId);
    }

    public function confirmForceLogoutAll()
    {
        return AlertHelper::confirmDelete('forceLogoutAll', 'Anda yakin ingin memaksa logout SEMUA pengguna (kecuali Anda)?', 'all');
    }

    public function forceLogoutUser($userId)
    {
        try {
            // Layer 1: Hapus session dari database
            $deletedCount = DB::table(config('session.table', 'sessions'))
                ->where('user_id', $userId)
                ->delete();

            // Putuskan semua live session user di monitoring
            ExamLiveSession::query()
                ->where('user_id', $userId)
                ->update([
                    'is_active'         => false,
                    'connection_status' => 'disconnected',
                    'last_activity'     => Carbon::now(),
                ]);

            // Pause timer pada UserTimetable yang aktif
            UserTimetable::query()
                ->where('user_id', $userId)
                ->whereIn('status', ['exam', 'warning'])
                ->whereNull('paused_at')
                ->update(['paused_at' => Carbon::now()]);

            AlertHelper::success('Berhasil', "Akun di-logout ({$deletedCount} sesi dihapus) dan sesi ujian diputus.");
        } catch (\Throwable $e) {
            AlertHelper::warning('Perhatian', 'Gagal logout akun: ' . $e->getMessage());
        }
    }

    public function forceLogoutAll()
    {
        try {
            $currentUserId = auth()->id();

            // Dapatkan semua user_id yang punya sesi aktif kecuali diri sendiri
            $userIds = DB::table(config('session.table', 'sessions'))
                ->whereNotNull('user_id')
                ->where('user_id', '!=', $currentUserId)
                ->pluck('user_id')
                ->unique();

            if ($userIds->isNotEmpty()) {
                // Delete session database
                DB::table(config('session.table', 'sessions'))
                    ->whereIn('user_id', $userIds)
                    ->delete();

                // Putuskan live session monitoring
                ExamLiveSession::query()
                    ->whereIn('user_id', $userIds)
                    ->update([
                        'is_active'         => false,
                        'connection_status' => 'disconnected',
                        'last_activity'     => Carbon::now(),
                    ]);

                // Pause ujian
                UserTimetable::query()
                    ->whereIn('user_id', $userIds)
                    ->whereIn('status', ['exam', 'warning'])
                    ->whereNull('paused_at')
                    ->update(['paused_at' => Carbon::now()]);
            }

            AlertHelper::success('Berhasil', 'Semua akun berhasil di force logout.');
        } catch (\Throwable $e) {
            AlertHelper::warning('Perhatian', 'Gagal memproses force logout: ' . $e->getMessage());
        }
    }
}
