<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran Presensi</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; box-sizing: border-box; }
        body { font-size: 11px; color: #1e293b; line-height: 1.4; margin: 15px; }
        .header { margin-bottom: 16px; border-bottom: 2px solid #cbd5e1; padding-bottom: 10px; }
        h1 { font-size: 16px; margin: 0 0 4px; color: #0f172a; text-transform: uppercase; }
        .subtitle { font-size: 11px; color: #475569; margin-bottom: 2px; }
        .stats { margin-bottom: 14px; }
        .stat-box { display: inline-block; padding: 6px 12px; margin-right: 8px; border-radius: 6px; font-size: 11px; font-weight: bold; border: 1px solid #cbd5e1; }
        .stat-total { background: #f8fafc; color: #334155; }
        .stat-hadir { background: #dcfce7; color: #166534; border-color: #86efac; }
        .stat-absen { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #cbd5e1; padding: 7px 10px; text-align: left; }
        th { background: #f1f5f9; font-weight: bold; color: #334155; font-size: 10px; text-transform: uppercase; }
        td.center, th.center { text-align: center; }
        .badge-hadir { background: #dcfce7; color: #166534; font-weight: bold; padding: 2px 6px; border-radius: 4px; display: inline-block; font-size: 10px; }
        .badge-absen { background: #fee2e2; color: #991b1b; font-weight: bold; padding: 2px 6px; border-radius: 4px; display: inline-block; font-size: 10px; }
        .footer-sign { margin-top: 35px; width: 100%; }
        .sign-box { float: right; width: 220px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekap Kehadiran Presensi {{ student_label() }}</h1>
        <div class="subtitle"><strong>Jadwal:</strong> {{ $timetable['name'] ?? '-' }}</div>
        <div class="subtitle"><strong>Kelas:</strong> {{ $classmateName ?? 'Semua Kelas' }}</div>
        @if($detailInfo)
            <div class="subtitle"><strong>Sesi Kegiatan:</strong> {{ $detailInfo }}</div>
        @endif
        <div class="subtitle"><strong>Dicetak Pada:</strong> {{ \Carbon\Carbon::now()->isoFormat('dddd, DD MMMM Y HH:mm') }}</div>
    </div>

    <div class="stats">
        <div class="stat-box stat-total">Total: {{ $stats['total'] }} Orang</div>
        <div class="stat-box stat-hadir">Hadir: {{ $stats['present'] }} Orang ({{ $stats['percent'] }}%)</div>
        <div class="stat-box stat-absen">Belum Hadir: {{ $stats['absent'] }} Orang</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="center" style="width: 35px;">No</th>
                <th style="width: 140px;">{{ student_label() === 'Kenshi' ? 'Nomer Kenshi / Username' : 'NIM / Username' }}</th>
                <th>Nama Lengkap</th>
                <th class="center" style="width: 95px;">Status</th>
                <th class="center" style="width: 125px;">Waktu Presensi</th>
                <th class="center" style="width: 85px;">Metode</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $index => $st)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $st['username'] }}</td>
                    <td><strong>{{ $st['name'] }}</strong></td>
                    <td class="center">
                        @if($st['has_attended'])
                            <span class="badge-hadir">HADIR</span>
                        @else
                            <span class="badge-absen">BELUM HADIR</span>
                        @endif
                    </td>
                    <td class="center">{{ $st['attended_at'] }}</td>
                    <td class="center">{{ $st['method'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center" style="padding: 20px; color: #94a3b8;">Tidak ada data peserta ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-sign">
        <div class="sign-box">
            <p style="margin-bottom: 60px;">Pengawas / Admin,</p>
            <p><strong>_________________________</strong></p>
        </div>
    </div>
</body>
</html>
