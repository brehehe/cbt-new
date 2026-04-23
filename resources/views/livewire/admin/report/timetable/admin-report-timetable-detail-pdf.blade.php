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
        $mcqQuestions = $timetable_questions->filter(fn($q) => $q->type !== 'essay');
        $essayQuestions = $timetable_questions->filter(fn($q) => $q->type === 'essay');
        
        $chunkSize = 25; 
    @endphp

    @if($mcqQuestions->isNotEmpty())
        <div style="margin-bottom: 20px;">
            <h2 style="font-size: 16px; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px;">A. Pilihan Ganda</h2>
            @foreach ($mcqQuestions->chunk($chunkSize) as $chunkIndex => $questionChunk)
                <div style="margin-bottom: 15px; page-break-inside: avoid;">
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 5px;">
                        <thead>
                            <tr>
                                <th style="width: 30px;">No</th>
                                <th class="text-left" style="width: 150px;">Nama Mahasiswa</th>
                                @foreach ($questionChunk as $q)
                                    <th style="font-size: 8px;">{{ $timetable_questions->search($q) + 1 }}</th>
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
                                            $userAnswer = $userQuestionStatuses[$user_timetable->id][$question->id] ?? null;
                                            $status = $userAnswer?->status;
                                            $content = '-';
                                            $style = '';
                                            
                                            if ($status === 'correct') {
                                                $style = 'background-color: #d1fae5; color: #065f46;';
                                                $content = '1';
                                            } elseif ($status === 'wrong') {
                                                $style = 'background-color: #fee2e2; color: #991b1b;';
                                                $content = '0';
                                            }
                                        @endphp
                                        <td style="font-size: 8px; {{ $style }}">{{ $content }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    @if($essayQuestions->isNotEmpty())
        <div style="margin-bottom: 20px; page-break-before: auto;">
            <h2 style="font-size: 16px; color: #374151; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px;">B. Essay</h2>
            @foreach ($essayQuestions->chunk($chunkSize) as $chunkIndex => $questionChunk)
                <div style="margin-bottom: 15px; page-break-inside: avoid;">
                    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 5px;">
                        <thead>
                            <tr>
                                <th style="width: 30px;">No</th>
                                <th class="text-left" style="width: 150px;">Nama Mahasiswa</th>
                                @foreach ($questionChunk as $q)
                                    <th style="font-size: 8px;">{{ $timetable_questions->search($q) + 1 }}</th>
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
                                            $userAnswer = $userQuestionStatuses[$user_timetable->id][$question->id] ?? null;
                                            $status = $userAnswer?->status;
                                            $content = '-';
                                            $style = '';
                                            
                                            if ($status === 'correct') {
                                                $style = 'background-color: #d1fae5; color: #065f46; font-weight: bold;';
                                                $content = '1';
                                            } elseif ($status === 'wrong') {
                                                $style = 'background-color: #fee2e2; color: #991b1b; font-weight: bold;';
                                                $content = '0';
                                            } elseif ($status === 'check') {
                                                $style = 'background-color: #ffedd5; color: #9a3412; font-weight: bold;';
                                                $content = '?';
                                            }
                                        @endphp
                                        <td style="font-size: 8px; {{ $style }}">{{ $content }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif

    <div class="page-break"></div>

    <div style="margin-top: 20px;">
        <h2 style="font-size: 16px; color: #374151; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px; margin-bottom: 15px;">Detail Jawaban Essay</h2>
        @foreach ($user_timetables as $uIndex => $user_timetable)
            @php
                $userAnswersForUser = $userQuestionStatuses[$user_timetable->id] ?? collect();
                $essayQuestionIds = $essayQuestions->pluck('id');
                $hasEssayAnswers = $essayQuestionIds->contains(fn($id) => isset($userAnswersForUser[$id]));
            @endphp
            @if($hasEssayAnswers)
                <div style="margin-bottom: 20px; page-break-inside: avoid; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                    <div style="background-color: #f3f4f6; padding: 8px 12px; font-weight: bold; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: justify-between;">
                        <span>{{ $uIndex + 1 }}. {{ $user_timetable->user?->name ?? '-' }}</span>
                        <span style="color: #6b7280; font-size: 8px; float: right;">Nilai: {{ $user_timetable->mark ?? '0' }}</span>
                    </div>
                    <div style="padding: 10px;">
                        @foreach ($essayQuestions as $question)
                            @php
                                $ans = $userAnswersForUser[$question->id] ?? null;
                                $status = $ans?->status;
                            @endphp
                            <div style="margin-bottom: 10px; border-bottom: 1px dashed #f3f4f6; padding-bottom: 5px;">
                                <div style="font-size: 9px; margin-bottom: 4px;">
                                    <span style="font-weight: bold; background: #e5e7eb; padding: 1px 4px; border-radius: 4px;">Soal {{ $timetable_questions->search($question) + 1 }}</span>
                                    @if($status === 'correct')
                                        <span style="color: #059669; font-weight: bold; margin-left: 10px;">[BENAR]</span>
                                    @elseif($status === 'wrong')
                                        <span style="color: #dc2626; font-weight: bold; margin-left: 10px;">[SALAH]</span>
                                    @else
                                        <span style="color: #d97706; font-weight: bold; margin-left: 10px;">[PENDING]</span>
                                    @endif
                                </div>
                                <div style="font-size: 9px; color: #4b5563; font-style: italic; padding-left: 10px;">
                                    "{{ $ans?->essay_answer ?: '(Belum dijawab)' }}"
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <div style="margin-top: 20px; page-break-inside: avoid;">
        <h3 style="font-size: 14px; border-bottom: 2px solid #e5e7eb; padding-bottom: 5px;">Ringkasan Nilai Akhir</h3>
        <table style="width: 50%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th class="text-left">Nama Mahasiswa</th>
                    <th style="width: 50px;">Benar (PG)</th>
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
                <th>Jenis</th>
                <th class="text-left">Isi Soal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($timetable_questions as $index => $question)
                <tr>
                    <td style="font-weight: bold;">{{ $index + 1 }}</td>
                    <td><span style="font-size: 8px; text-transform: uppercase; font-weight: bold; color: #6b7280;">{{ $question->type }}</span></td>
                    <td class="text-left" style="padding: 8px;">
                        <div>{!! $question->question?->question ?? $question->question ?? '-' !!}</div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
