<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nilai Ujian</title>
    <style>
        * { font-family: DejaVu Sans, Arial, sans-serif; }
        body { font-size: 11px; color: #111827; }
        h1 { font-size: 16px; margin: 0 0 4px; }
        .subtitle { color: #6b7280; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: center; }
        th { background: #f3f4f6; font-weight: 600; }
        td.left, th.left { text-align: left; }
        .badge-ok { background: #d1fae5; }
        .badge-no { background: #fecaca; }
        .badge-info { background: #dbeafe; }
    </style>
</head>
<body>
    <h1>Nilai Ujian</h1>
    <div class="subtitle">{{ $timetable['name'] ?? 'Jadwal' }}</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th class="left">NIM</th>
                <th class="left">Nama</th>
                <th>Terjawab</th>
                <th>Tidak Terjawab</th>
                <th>Benar</th>
                <th>Salah</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($userTimetables as $index => $userTimetable)
                @php
                    $counts = $countMap[$userTimetable->id] ?? ['answered' => 0, 'unanswered' => 0, 'correct' => 0, 'wrong' => 0];
                    $grade = $gradeMap[$userTimetable->id] ?? '-';
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left">{{ $userTimetable->user->nim ?? ($userTimetable->user->username ?? '-') }}</td>
                    <td class="left">{{ $userTimetable->user->name ?? '-' }}</td>
                    <td>{{ $counts['answered'] }}</td>
                    <td>{{ $counts['unanswered'] }}</td>
                    <td class="badge-ok">{{ $counts['correct'] }}</td>
                    <td class="badge-no">{{ $counts['wrong'] }}</td>
                    <td class="badge-info">{{ $userTimetable->mark ?? '-' }} ({{ $grade }})</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
