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
        $chunkSize = 35; // Number of questions per table section
        $chunks = $timetable_questions->chunk($chunkSize);
    @endphp

    @php
        $count_question = $timetable_questions->count();
        $chunkSize = 25; // Smaller chunks for PDF readability
        $chunks = $timetable_questions->chunk($chunkSize);
    @endphp

    @foreach ($chunks as $chunkIndex => $questionChunk)
        <div style="margin-bottom: 25px; page-break-inside: avoid;">
            <div style="background-color: #f9fafb; padding: 10px; border-radius: 8px; border-left: 4px solid #3b82f6; margin-bottom: 15px;">
                <h3 style="font-size: 14px; margin: 0; color: #1e40af;">
                    BAGIAN {{ $chunkIndex + 1 }}: Soal {{ ($chunkIndex * $chunkSize) + 1 }} - {{ min(($chunkIndex + 1) * $chunkSize, $count_question) }}
                </h3>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 10px;">
                <thead>
                    <tr>
                        <th style="width: 30px;">No</th>
                        <th class="text-left" style="width: 150px;">Nama Mahasiswa</th>
                        @foreach ($questionChunk as $index => $q)
                            <th style="font-size: 8px;">{{ ($chunkIndex * $chunkSize) + $loop->iteration }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        <th colspan="2" style="background-color: #f3f4f6; font-size: 8px; text-align: right;">Kunci:</th>
                        @foreach ($questionChunk as $q)
                            <th style="font-size: 8px; background-color: #f3f4f6; font-weight: bold;">{{ $answerMap[$q->id] ?? '-' }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user_timetables as $uIndex => $user_timetable)
                        <tr>
                            <td style="font-size: 8px;">{{ $uIndex + 1 }}</td>
                            <td class="text-left" style="font-size: 8px; font-weight: bold; overflow: hidden;">
                                {{ \Illuminate\Support\Str::limit($user_timetable->user?->name ?? '-', 25) }}
                            </td>
                            @foreach ($questionChunk as $question)
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
                                <td class="{{ $class }}" style="font-size: 8px;">{{ $content }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div style="margin-top: 20px; page-break-inside: avoid;">
        <h3 style="font-size: 14px; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px;">Ringkasan Nilai Akhir</h3>
        <table style="width: 50%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th class="text-left">Nama Mahasiswa</th>
                    <th style="width: 50px;">JB</th>
                    <th style="width: 50px;">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user_timetables as $uIndex => $user_timetable)
                    <tr>
                        <td>{{ $uIndex + 1 }}</td>
                        <td class="text-left" style="font-weight: bold;">{{ $user_timetable->user?->name ?? '-' }}</td>
                        <td style="font-weight: bold;">{{ $correctCounts[$user_timetable->id] ?? 0 }}</td>
                        <td style="font-weight: bold; color: #2563eb;">{{ $user_timetable->mark ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

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
