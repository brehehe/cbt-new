<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Ujian</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; }
        body { font-size: 9px; color: #111827; margin: 12px; }
        h1 { font-size: 15px; margin: 0 0 2px; }
        .subtitle { color: #6b7280; margin-bottom: 4px; font-size: 9px; }
        .meta { color: #6b7280; margin-bottom: 10px; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 4px 6px; text-align: center; vertical-align: middle; }
        th { background: #f3f4f6; font-weight: 700; font-size: 8px; }
        td.left, th.left { text-align: left; }
        .risk-high   { background: #fecaca; color: #991b1b; }
        .risk-medium { background: #fef3c7; color: #92400e; }
        .risk-low    { background: #dbeafe; color: #1e40af; }
        .risk-none   { background: #d1fae5; color: #065f46; }
        .active-yes  { background: #d1fae5; }
        .active-no   { background: #f3f4f6; color: #6b7280; }
        .footer { margin-top: 12px; font-size: 8px; color: #9ca3af; text-align: right; }
    </style>
</head>
<body>
    <h1>Monitoring Ujian</h1>
    <div class="subtitle">Laporan Data Sesi Ujian</div>
    <div class="meta">Diekspor: {{ now()->format('d/m/Y H:i:s') }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th class="left">Nama Mahasiswa</th>
                <th class="left">NIM / Username</th>
                <th class="left">Jadwal</th>
                <th class="left">Modul</th>
                <th>Total</th>
                <th>Jwb</th>
                <th>Benar</th>
                <th>Salah</th>
                <th>Blm</th>
                <th>%</th>
                <th>Alert</th>
                <th>Risk</th>
                <th>Status</th>
                <th>Aktif</th>
                <th class="left">Mulai</th>
                <th class="left">IP Address</th>
                <th class="left">Last Activity</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sessions as $index => $session)
                @php
                    $stats = $session->db_question_stats;
                    $riskClass = match($session->risk_level) {
                        'high'   => 'risk-high',
                        'medium' => 'risk-medium',
                        'low'    => 'risk-low',
                        'none'   => 'risk-none',
                        default  => '',
                    };
                    $riskLabels = ['high'=>'Tinggi','medium'=>'Sedang','low'=>'Rendah','none'=>'Aman'];
                    $statusLabels = ['connected'=>'Terhubung','disconnected'=>'Terputus','unstable'=>'Tdk Stabil'];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left">{{ $session->user->name ?? 'Unknown' }}</td>
                    <td class="left">{{ $session->user->nim ?? ($session->user->username ?? 'N/A') }}</td>
                    <td class="left">{{ $session->timetable->name ?? 'N/A' }}</td>
                    <td class="left">{{ $session->timetable->module->name ?? 'N/A' }}</td>
                    <td>{{ $stats['total'] }}</td>
                    <td>{{ $stats['answered'] }}</td>
                    <td>{{ $stats['correct'] }}</td>
                    <td>{{ $stats['wrong'] }}</td>
                    <td>{{ $stats['unanswered'] }}</td>
                    <td>{{ $stats['percentage'] }}%</td>
                    <td>{{ $session->alert_count }}</td>
                    <td class="{{ $riskClass }}">{{ $riskLabels[$session->risk_level] ?? $session->risk_level }}</td>
                    <td>{{ $statusLabels[$session->connection_status] ?? $session->connection_status }}</td>
                    <td class="{{ $session->is_active ? 'active-yes' : 'active-no' }}">{{ $session->is_active ? 'Ya' : 'Tidak' }}</td>
                    <td class="left">{{ isset($session->session_metadata['start_time']) ? \Carbon\Carbon::parse($session->session_metadata['start_time'])->format('d/m/Y H:i') : 'N/A' }}</td>
                    <td class="left">{{ $session->session_metadata['ip_address'] ?? 'N/A' }}</td>
                    <td class="left">{{ $session->last_activity ? $session->last_activity->format('d/m/Y H:i') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="18">Tidak ada data sesi ujian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Total: {{ $sessions->count() }} sesi &nbsp;|&nbsp; ProCBT &copy; {{ date('Y') }}</div>
</body>
</html>
