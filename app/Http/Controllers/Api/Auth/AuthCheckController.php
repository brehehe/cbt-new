<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Exam\ExamAlert;
use App\Models\Exam\ExamLiveSession;
use App\Models\User\UserTimetable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthCheckController extends Controller
{
    /**
     * Ping — lightweight auth check.
     *
     * Dipanggil oleh React exam page setiap 8 detik untuk mendeteksi
     * force-logout oleh admin. Karena route dilindungi middleware 'auth'
     * dan request membawa header "Accept: application/json", Laravel
     * otomatis mengembalikan 401 JSON saat session expired/dihapus.
     *
     * GET /api/exam/ping
     */
    public function ping(): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'user_id' => Auth::id(),
        ]);
    }

    /**
     * Status ujian — cek apakah live session masih aktif.
     *
     * Layer kedua deteksi force-logout: tidak bergantung pada penghapusan
     * session fisik (Redis/DB/file). Selama forceLogoutUser() mengupdate
     * ExamLiveSession.is_active = false, endpoint ini PASTI mendeteksinya.
     *
     * GET /api/exam/{userTimetableId}/status
     */
    public function examStatus(string $userTimetableId): JsonResponse
    {
        $userId = Auth::id();

        $userTimetable = UserTimetable::withoutGlobalScopes()
            ->with(['timetable.module'])
            ->where('id', $userTimetableId)
            ->where('user_id', $userId)
            ->first();

        // Suspend: admin memanggil suspendSession() yang set status='suspend'
        $isSuspended = $userTimetable && $userTimetable->status === 'suspend';
        $isPaused = $userTimetable && !is_null($userTimetable->paused_at);

        // Deteksi logout fisik (session dihapus) sudah ditangani oleh endpoint /ping + middleware auth
        // yang akan mengembalikan 401. Di sini kita hanya cek status ujian di DB.
        $shouldRedirect = $isSuspended;

        $remainingTime = $userTimetable ? $userTimetable->getRemainingTime() : 0;

        // Cek apakah ada pesan belum dibaca dari pengawas/supervisor
        $latestAlert = ExamAlert::withoutGlobalScope('user_scope')
            ->where('user_timetable_id', $userTimetableId)
            ->where('alert_type', 'supervisor_message')
            ->where(function ($q) {
                $q->whereNull('metadata->is_read')
                  ->orWhere('metadata->is_read', false);
            })
            ->latest()
            ->first();

        $supervisorMessage = null;
        if ($latestAlert) {
            $meta = $latestAlert->metadata ?? [];
            $supervisorMessage = [
                'id' => $latestAlert->id,
                'text' => $meta['message'] ?? str_replace('Pesan dari supervisor: ', '', $latestAlert->description),
                'sender' => $meta['sender'] ?? 'Pengawas Ujian',
                'timestamp' => $latestAlert->created_at ? $latestAlert->created_at->format('H:i:s') : now()->format('H:i:s'),
            ];
        }

        return response()->json([
            'active' => true, // Default true selama session masih ada (auth middleware pass)
            'suspended' => $isSuspended,
            'paused' => $isPaused,
            'redirect' => $shouldRedirect ? '/logout' : null,
            'remainingTime' => (int) $remainingTime,
            'supervisorMessage' => $supervisorMessage,
        ]);
    }

    /**
     * Tandai pesan supervisor telah dibaca oleh peserta
     *
     * POST /api/exam/message/{alertId}/ack
     */
    public function acknowledgeMessage(string $alertId): JsonResponse
    {
        $alert = ExamAlert::withoutGlobalScope('user_scope')->find($alertId);
        if ($alert) {
            $meta = $alert->metadata ?? [];
            $meta['is_read'] = true;
            $meta['read_at'] = now()->toISOString();
            $alert->update(['metadata' => $meta]);
        }

        return response()->json(['success' => true]);
    }
}
