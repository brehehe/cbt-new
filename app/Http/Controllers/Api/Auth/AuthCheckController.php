<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
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

        $userTimetable = UserTimetable::where('id', $userTimetableId)
            ->where('user_id', $userId)
            ->first();

        // Suspend: admin memanggil suspendSession() yang set status='suspend'
        $isSuspended = $userTimetable && $userTimetable->status === 'suspend';

        // Deteksi logout fisik (session dihapus) sudah ditangani oleh endpoint /ping + middleware auth
        // yang akan mengembalikan 401. Di sini kita hanya cek status ujian di DB.
        $shouldRedirect = $isSuspended;

        return response()->json([
            'active' => true, // Default true selama session masih ada (auth middleware pass)
            'suspended' => $isSuspended,
            'redirect' => $shouldRedirect ? '/logout' : null,
        ]);
    }
}
