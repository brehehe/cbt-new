<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Riwayat Jadwal Ujian</title>
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
    </style>
</head>
<body>
    <h1>Detail Riwayat Ujian</h1>
    <div class="subtitle">Rekap Nilai dari "{{$timetable_module->module->name}}"</div>

    @php
        $count_question = empty($timetable_questions) ? 1 : count($timetable_questions);
    @endphp

    <table>
        <thead>
            <tr>
                <th rowspan="3">No</th>
                <th rowspan="3" class="left">Nama Mahasiswa</th>
                <th colspan="{{ $count_question }}">Daftar Soal</th>
                <th rowspan="3">JB</th>
                <th rowspan="3">Nilai</th>
            </tr>
            <tr>
                @foreach ($timetable_questions as $key => $timetable_question)
                    <th>{{ $key + 1 }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach ($timetable_questions as $timetable_question)
                    <th>{{ $answerMap[$timetable_question->id] ?? '-' }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($user_timetables as $index => $user_timetable)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left">{{ $user_timetable->user?->name ?? '-' }}</td>
                    @foreach ($timetable_questions as $timetable_question)
                        @php
                            $status = $userQuestionStatuses[$user_timetable->id][$timetable_question->id] ?? null;
                            $isCorrect = $status === 'correct';
                        @endphp
                        <td class="{{ $isCorrect ? 'badge-ok' : 'badge-no' }}">{{ $isCorrect ? 1 : 0 }}</td>
                    @endforeach
                    <td>{{ $correctCounts[$user_timetable->id] ?? 0 }}</td>
                    <td>{{ $user_timetable?->mark ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $count_question + 4 }}">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
