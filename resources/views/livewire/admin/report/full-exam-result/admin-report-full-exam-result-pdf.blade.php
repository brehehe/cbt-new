<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Ujian Lengkap</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10pt;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            padding: 0;
            font-size: 16pt;
        }
        .header p {
            margin: 5px 0;
        }
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-info td {
            padding: 2px;
            vertical-align: top;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }
        table.data th, table.data td {
            border: 1px solid #000;
            padding: 5px;
        }
        table.data th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $company->name ?? 'Laporan Hasil Ujian' }}</h1>
        <p>Laporan Hasil Ujian Lengkap</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Dicetak Pada</strong></td>
            <td width="35%">: {{ now()->format('d F Y H:i') }}</td>
            <td width="15%"><strong>Filter</strong></td>
            <td width="35%">
                : 
                @if(isset($filterSummary['search'])) Pencarian "{{ $filterSummary['search'] }}", @endif
                @if(isset($filterSummary['user'])) User: {{ $filterSummary['user'] }}, @endif
                @if(isset($filterSummary['module'])) Modul: {{ $filterSummary['module'] }}, @endif
                @if(isset($filterSummary['timetable'])) Jadwal: {{ $filterSummary['timetable'] }} @endif
                @if(empty($filterSummary)) Semua Data @endif
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Peserta</th>
                <th width="15%">Jadwal/Modul</th>
                <th width="20%">Waktu</th>
                <th width="10%">Durasi</th>
                <th width="20%">Statistik (B/S/K)</th>
                <th width="10%">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($examResults as $index => $result)
                @php
                    $stats = $resultStats[$result->id] ?? ['correct' => 0, 'wrong' => 0, 'unanswered' => 0, 'check' => 0];
                    $start = $result->start_exam ? \Carbon\Carbon::parse($result->start_exam) : null;
                    $end = $result->end_exam ? \Carbon\Carbon::parse($result->end_exam) : null;
                    $duration = ($start && $end) ? $start->diffInMinutes($end) . ' m' : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $result->user->name ?? '-' }}<br>
                        <small>{{ $result->user->nim ?? ($result->user->username ?? '-') }}</small>
                    </td>
                    <td>
                        {{ $result->timetable->name ?? '-' }}<br>
                        <small>{{ $result->timetable->module->name ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                        <small>
                        Start: {{ $start ? $start->format('d/m H:i') : '-' }}<br>
                        End: {{ $end ? $end->format('d/m H:i') : '-' }}
                        </small>
                    </td>
                    <td class="text-center">{{ $duration }}</td>
                    <td class="text-center">
                        <div>Jwb: {{ $stats['answered'] }}</div>
                        B: {{ $stats['correct'] }} | S: {{ $stats['wrong'] }}
                        @if($stats['check'] > 0 || $stats['unanswered'] > 0)
                            <br>
                            <small>Cek: {{ $stats['check'] }} | Ksg: {{ $stats['unanswered'] }}</small>
                        @endif
                    </td>
                    <td class="text-center">
                        <strong>{{ $result->mark ?? '-' }}</strong><br>
                        <small>{{ $gradeDetails[$result->id]->grade_letter ?? '' }}</small>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh: {{ Auth::user()->name }}</p>
    </div>
</body>
</html>
