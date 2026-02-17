<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Riwayat Jadwal Ujian</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 10px; color: #1f2937; margin: 0; padding: 20px; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; }
        h1 { font-size: 18px; margin: 0 0 5px; color: #111827; }
        .subtitle { font-size: 12px; color: #6b7280; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #d1d5db; padding: 4px; text-align: center; vertical-align: middle; }
        th { background-color: #f3f4f6; font-weight: bold; font-size: 9px; color: #374151; }
        td { font-size: 9px; }
        .text-left { text-align: left; }
        .badge-ok { background-color: #d1fae5; color: #065f46; font-weight: bold; }
        .badge-no { background-color: #fee2e2; color: #991b1b; }
        .col-no { width: 30px; }
        .col-name { width: 150px; }
        .col-score { width: 40px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Riwayat Ujian</h1>
        <div class="subtitle">Modul: {{ $timetable_module->module->name ?? '-' }}</div>
    </div>

    @php
        $count_question = $timetable_questions->count();
    @endphp

    <table>
        <thead>
            <tr>
                <th rowspan="3" class="col-no">No</th>
                <th rowspan="3" class="col-name text-left">Nama Mahasiswa</th>
                <th colspan="{{ $count_question }}">Daftar Soal ({{ $count_question }})</th>
                <th rowspan="3" class="col-score">JB</th>
                <th rowspan="3" class="col-score">Nilai</th>
            </tr>
            <tr>
                @foreach ($timetable_questions as $index => $q)
                    <th>{{ $index + 1 }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($timetable_questions as $q)
                    <!-- Displaying Correct Answer Key if available, else dash -->
                    <th style="font-size: 8px;">{{ $answerMap[$q->id] ?? '-' }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($user_timetables as $index => $user_timetable)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <div style="font-weight: bold;">{{ $user_timetable->user?->name ?? '-' }}</div>
                        <div style="font-size: 8px; color: #6b7280;">{{ $user_timetable->user?->username ?? '' }}</div>
                    </td>
                    @foreach ($timetable_questions as $question)
                        @php
                            $status = $userQuestionStatuses[$user_timetable->id][$question->id] ?? null;
                            $isCorrect = $status === 'correct';
                            $hasAnswered = !is_null($status);
                            
                            $class = '';
                            $content = '-';
                            
                            if ($hasAnswered) {
                                if ($isCorrect) {
                                    $class = 'badge-ok';
                                    $content = '1';
                                } else {
                                    $class = 'badge-no';
                                    $content = '0';
                                }
                            }
                        @endphp
                        <td class="{{ $class }}">{{ $content }}</td>
                    @endforeach
                    <td style="font-weight: bold;">{{ $correctCounts[$user_timetable->id] ?? 0 }}</td>
                    <td style="font-weight: bold; color: #2563eb;">{{ $user_timetable->mark ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $count_question + 4 }}" style="padding: 20px; color: #6b7280;">Tidak ada data ujian found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="header">
        <h1>Daftar Referensi Soal</h1>
        <div class="subtitle">Modul: {{ $timetable_module->module->name ?? '-' }}</div>
    </div>

    <table style="table-layout: auto;">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Isi Soal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timetable_questions as $index => $question)
                <tr>
                    <td style="font-weight: bold;">{{ $index + 1 }}</td>
                    <td class="text-left" style="padding: 8px;">
                        <div>{!! $question->question?->question ?? $question->question ?? '-' !!}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
