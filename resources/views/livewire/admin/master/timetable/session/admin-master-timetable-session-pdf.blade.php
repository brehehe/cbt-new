<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sesi Ujian</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; }
        body { font-size: 10px; color: #111827; margin: 12px; }
        h1 { font-size: 15px; margin: 0 0 2px; }
        .subtitle { color: #6b7280; margin-bottom: 4px; font-size: 10px; }
        .meta { color: #6b7280; margin-bottom: 10px; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 5px 7px; text-align: center; vertical-align: middle; }
        th { background: #f3f4f6; font-weight: 700; font-size: 9px; }
        td.left, th.left { text-align: left; }
        .hadir     { background: #d1fae5; color: #065f46; }
        .tdk-hadir { background: #f3f4f6; color: #6b7280; }
        .aktif     { background: #d1fae5; color: #065f46; }
        .login     { background: #dbeafe; color: #1e40af; }
        .blm-login { background: #f3f4f6; color: #6b7280; }
        .offline   { background: #fecaca; color: #991b1b; }
        .footer { margin-top: 12px; font-size: 8px; color: #9ca3af; text-align: right; }
    </style>
</head>
<body>
    <h1>Sesi Ujian</h1>
    <div class="subtitle">{{ $timetableName ?? 'Jadwal Ujian' }}</div>
    <div class="meta">Diekspor: {{ now()->format('d/m/Y H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th class="left">No Peserta (NIM)</th>
                <th class="left">Password</th>
                <th class="left">Nama Peserta</th>
                <th>Kehadiran</th>
                <th>Status Login</th>
                <th>Jml Soal</th>
                <th>Terjawab</th>
                <th class="left">Aktivitas Terakhir</th>
                <th>Kamera</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sessions as $index => $classmateStudent)
                @php
                    $user = $classmateStudent->user;
                    $liveSession = $user?->examLiveSessions->first();
                    $userTimetable = $user?->userTimetables->first();

                    $kehadiranText  = $userTimetable ? 'Hadir' : 'Tidak Hadir';
                    $kehadiranClass = $userTimetable ? 'hadir' : 'tdk-hadir';

                    $statusText  = 'Belum Login';
                    $statusClass = 'blm-login';
                    if ($liveSession) {
                        if ($liveSession->is_active) {
                            $statusText  = 'Aktif (Online)';
                            $statusClass = 'aktif';
                        } else {
                            $statusText  = $liveSession->connection_status === 'disconnected' ? 'Offline' : 'Login';
                            $statusClass = $liveSession->connection_status === 'disconnected' ? 'offline' : 'login';
                        }
                    }

                    $totalSoal = $userTimetable ? $userTimetable->userModuleQuestions->count() : 0;
                    $terjawab  = $userTimetable
                        ? $userTimetable->userModuleQuestions->filter(fn($q) => $q->timetable_answer_id || $q->essay_answer)->count()
                        : 0;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left">{{ $user->nim ?? ($user->username ?? '-') }}</td>
                    <td class="left">{{ $user->decrypted_password ?? '-' }}</td>
                    <td class="left">{{ $user->name ?? '-' }}</td>
                    <td class="{{ $kehadiranClass }}">{{ $kehadiranText }}</td>
                    <td class="{{ $statusClass }}">{{ $statusText }}</td>
                    <td>{{ $totalSoal }}</td>
                    <td>{{ $terjawab }}</td>
                    <td class="left">{{ $liveSession?->last_activity ?? '-' }}</td>
                    <td>{{ $liveSession?->camera_status ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">Tidak ada data peserta</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Total: {{ $sessions->count() }} peserta &nbsp;|&nbsp; ProCBT &copy; {{ date('Y') }}</div>
</body>
</html>
