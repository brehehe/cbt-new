<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Analisis Butir Soal</title>
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
            text-align: center;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: 600;
        }

        td.left,
        th.left {
            text-align: left;
        }

        .badge-easy {
            background: #d1fae5;
        }

        .badge-mid {
            background: #fef3c7;
        }

        .badge-hard {
            background: #fecaca;
        }

        .badge-good {
            background: #d1fae5;
        }

        .badge-ok {
            background: #fef3c7;
        }

        .badge-bad {
            background: #fecaca;
        }
    </style>
</head>

<body>
    <h1>Analisis Butir Soal</h1>
    <div class="subtitle">
        Ujian: {{ $timetable->name ?? 'Tidak diketahui' }} | Modul: {{ $timetableModule->name ?? 'Tidak diketahui' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th class="left">Soal</th>
                <th>Peserta</th>
                <th>Benar</th>
                <th>P</th>
                <th>D</th>
                <th>Tingkat</th>
                <th>Daya Pembeda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($itemAnalysisData as $analysis)
                @php
                    $question = $analysis['question'] ?? null;
                    $questionType = $question?->type ?? 'single';
                    $questionText = strip_tags($question?->question ?? '');
                    $difficultyClass =
                        $analysis['difficulty_level'] === 'Mudah'
                            ? 'badge-easy'
                            : ($analysis['difficulty_level'] === 'Sedang'
                                ? 'badge-mid'
                                : 'badge-hard');
                    $discClass = in_array($analysis['discrimination_level'], ['Sangat Baik', 'Baik'])
                        ? 'badge-good'
                        : (in_array($analysis['discrimination_level'], ['Cukup'])
                            ? 'badge-ok'
                            : 'badge-bad');
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="left">
                        [{{ $questionType === 'essay' ? 'Essay' : 'PG' }}]
                        {!! \Str::limit($questionText, 80) !!}
                    </td>
                    <td>{{ $analysis['total_participants'] }}</td>
                    <td>{{ $analysis['correct_answers'] }}</td>
                    <td>{{ $analysis['difficulty_index'] }}</td>
                    <td>{{ $analysis['discrimination_index'] }}</td>
                    <td class="{{ $difficultyClass }}">{{ $analysis['difficulty_level'] }}</td>
                    <td class="{{ $discClass }}">{{ $analysis['discrimination_level'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
