<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Nilai Ujian Detail</title>
    <style>
        * {
            font-family: DejaVu Sans, Arial, sans-serif;
        }

        body {
            font-size: 11px;
            color: #111827;
        }

        h1 {
            font-size: 16px;
            margin: 0 0 4px;
        }

        .subtitle {
            color: #6b7280;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: 600;
        }

        .center {
            text-align: center;
        }

        .badge-ok {
            background: #d1fae5;
        }

        .badge-no {
            background: #fecaca;
        }

        .badge-neutral {
            background: #f3f4f6;
        }

        .badge-check {
            background: #dbeafe;
        }
    </style>
</head>

<body>
    <h1>Nilai Ujian Detail</h1>
    <div class="subtitle">{{ $timetable['name'] ?? 'Jadwal' }} - {{ $user_timetable->user->name ?? '-' }}</div>

    <table>
        <thead>
            <tr>
                <th class="center">No</th>
                <th>Soal</th>
                <th>Jawaban Benar</th>
                <th>Jawaban Terpilih</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($userModuleQuestions as $index => $userModuleQuestion)
                @php
                    $answers = $userModuleQuestion->timetableQuestion?->answers ?? collect();
                    $correctAnswer = $answers->firstWhere('is_correct', true);
                    $chosenAnswer = $userModuleQuestion->timetableAnswer;
                    $posCorrect = $answers->search(fn($a) => $a->is_correct);
                    $labelCorrect = $posCorrect !== false ? $posCorrect + 1 : null;
                    $posChosen = $answers->search(fn($a) => $a->id === optional($chosenAnswer)->id);
                    $labelChosen = $posChosen !== false ? $posChosen + 1 : null;
                    $letter = fn($n) => $n ? chr(64 + $n) : '-';
                @endphp
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>
                        {!! optional($userModuleQuestion->timetableQuestion)->question ?? '-' !!}
                        @if($userModuleQuestion->timetableQuestion?->type === 'essay')
                            <div style="font-size: 8px; color: #2563eb; font-weight: bold; margin-top: 2px;">ESSAY</div>
                        @endif
                    </td>
                    <td>
                        @if($userModuleQuestion->timetableQuestion?->type === 'essay')
                            -
                        @else
                            {!! $letter($labelCorrect) !!}. {!! optional($correctAnswer)->context ?? '-' !!}
                        @endif
                    </td>
                    <td>
                        @if($userModuleQuestion->timetableQuestion?->type === 'essay')
                            <div style="font-style: italic;">{!! $userModuleQuestion->essay_answer ?: 'Tidak ada jawaban' !!}</div>
                        @else
                            {!! $letter($labelChosen) !!}. {!! optional($chosenAnswer)->context ?? '-' !!}
                        @endif
                    </td>
                    <td
                        class="center {{ $userModuleQuestion->status === 'correct' ? 'badge-ok' : ($userModuleQuestion->status === 'wrong' ? 'badge-no' : ($userModuleQuestion->status === 'check' ? 'badge-check' : 'badge-neutral')) }}">
                        {{ $userModuleQuestion->status === 'correct' ? 'Benar' : ($userModuleQuestion->status === 'wrong' ? 'Salah' : ($userModuleQuestion->status === 'check' ? 'Menunggu Koreksi' : 'Tidak Terjawab')) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>