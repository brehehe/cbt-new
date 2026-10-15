<!DOCTYPE html>
<html>
<head>
    <title>Laporan Statistik Jawaban</title>
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
            font-size: 16pt;
        }
        .meta {
            margin-bottom: 20px;
            width: 100%;
        }
        .question-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .question-text {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stats-table {
            width: 100%;
            margin-top: 5px;
            font-size: 9pt;
        }
        .stats-table td {
            vertical-align: top;
        }
        .bar-container {
            width: 100px;
            background-color: #f0f0f0;
            height: 10px;
            display: inline-block;
        }
        .bar {
            height: 100%;
            background-color: #007bff;
        }
        .bar.correct {
            background-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $company->name ?? 'Laporan Statistik Jawaban' }}</h1>
        <p>Analisis Sebaran Jawaban</p>
    </div>

    <table class="meta">
        <tr>
            <td width="15%"><strong>Ujian</strong></td>
            <td>: {{ $timetable->name }}</td>
            <td width="15%"><strong>Modul</strong></td>
            <td>: {{ $timetable->module?->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><strong>Waktu</strong></td>
            <td>: {{ \Carbon\Carbon::parse($timetable->start_time)->format('d M Y H:i') }}</td>
            <td><strong>Dicetak</strong></td>
            <td>: {{ now()->format('d M Y H:i') }}</td>
        </tr>
    </table>

    <hr>

    @foreach($answerStats as $index => $stat)
        <div class="question-block">
            <div class="question-text">
                <span style="background: #e5e7eb; padding: 2px 5px; border-radius: 3px; font-size: 8pt; margin-right: 5px;">
                    {{ $stat['question_type'] === 'essay' ? 'ESSAY' : 'PG' }}
                </span>
                {{ $index + 1 }}. {!! strip_tags($stat['question_text']) !!}
            </div>
            
            <table class="stats-table">
                <tr>
                    <td width="30%">
                        <strong>Statistik:</strong><br>
                        Total Dijawab: {{ $stat['total_answered'] }}<br>
                        Benar: <span style="color: green;">{{ $stat['total_correct'] }}</span><br>
                        Salah: <span style="color: red;">{{ $stat['total_wrong'] }}</span>
                        @if($stat['question_type'] === 'essay')
                            <br>Pending: <span style="color: #d97706;">{{ $stat['total_pending'] }}</span>
                        @endif
                    </td>
                    <td width="70%">
                        <strong>{{ $stat['question_type'] === 'essay' ? 'Status Penilaian:' : 'Sebaran Jawaban:' }}</strong>
                        <table width="100%" style="font-size: 8pt; margin-top: 5px;">
                            @foreach($stat['distribution'] as $optIndex => $opt)
                                <tr>
                                    <td width="20" style="vertical-align: top; font-weight: bold;">
                                        @if($stat['question_type'] === 'essay')
                                            -
                                        @else
                                            {{ chr(65 + $optIndex) }}
                                        @endif
                                    </td>
                                    <td>
                                        <div style="margin-bottom: 2px;">
                                            {!! strip_tags($opt['option_text']) !!}
                                            @if(!($opt['is_pending'] ?? false) && $opt['is_correct'] && $stat['question_type'] !== 'essay') 
                                                <strong>(Kunci)</strong> 
                                            @endif
                                        </div>
                                        <div style="margin-top: 2px;">
                                            @php
                                                $barColor = $opt['is_correct'] ? '#28a745' : '#007bff';
                                                if ($opt['is_pending'] ?? false) $barColor = '#f59e0b';
                                                elseif ($stat['question_type'] === 'essay' && !$opt['is_correct']) $barColor = '#dc3545';
                                            @endphp
                                            <div class="bar-container" style="margin-right: 5px; width: 120px; height: 8px;">
                                                <div class="bar" style="width: {{ $opt['percentage'] }}%; background-color: {{ $barColor }};"></div>
                                            </div>
                                            <span style="font-size: 7pt;">{{ $opt['count'] }} ({{ $opt['percentage'] }}%)</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

</body>
</html>
