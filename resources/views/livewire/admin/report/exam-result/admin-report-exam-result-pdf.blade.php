<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Ujian</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 10px; color: #1f2937; margin: 0; padding: 20px; }
        
        /* Header Styling */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #374151;
            padding-bottom: 15px;
        }
        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        .header-logo img {
            max-width: 80px;
            max-height: 80px;
        }
        .header-content {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .company-address {
            font-size: 10px;
            color: #4b5563;
            line-height: 1.4;
        }
        .report-title {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            text-decoration: underline;
        }

        /* Statistics Section */
        .stats-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 4px;
        }
        .stats-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #d1d5db;
        }
        .stats-box:last-child {
            border-right: none;
        }
        .stats-label {
            font-size: 9px;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .stats-value {
            font-size: 14px;
            font-weight: bold;
            color: #111827;
        }
        .grade-dist {
            text-align: left;
            font-size: 9px;
        }
        .grade-item {
            display: inline-block;
            margin-right: 8px;
            background-color: #e5e7eb;
            padding: 2px 5px;
            border-radius: 3px;
            margin-bottom: 2px;
        }

        /* Filter Summary */
        .filter-section {
            margin-bottom: 15px;
            border-left: 3px solid #3b82f6;
            padding: 8px 12px;
            background-color: #eff6ff;
            font-size: 9px;
        }
        .filter-row {
            margin-bottom: 3px;
        }
        .filter-label {
            font-weight: bold;
            color: #1e40af;
            width: 80px;
            display: inline-block;
        }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: center; vertical-align: middle; }
        th { 
            background-color: #1f2937; 
            color: #ffffff; 
            font-weight: bold; 
            font-size: 9px; 
            text-transform: uppercase;
        }
        tr:nth-child(even) { background-color: #f9fafb; }
        td { font-size: 9px; color: #374151; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .col-no { width: 30px; }

        .no-data {
            padding: 30px;
            color: #9ca3af;
            font-style: italic;
            background-color: #f9fafb;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
            font-size: 8px;
            color: #9ca3af;
            text-align: right;
        }
        .page-number:before {
            content: "Halaman " counter(page);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-container">
        @if($company && $company->logo)
            <div class="header-logo">
                 {{-- Assuming logo is stored as path, might need asset() or public_path() depending on DOMPDF setup. 
                      Using a placeholder if typical storage link doesn't work out of the box in this env, 
                      but attempting standard img tag first. --}}
                <img src="{{ public_path('storage/' . $company->logo) }}" alt="Logo" onerror="this.style.display='none'">
            </div>
        @endif
        <div class="header-content">
            <div class="company-name">{{ $company->name ?? 'NAMA INSTITUSI' }}</div>
            <div class="company-address">
                {{ $company->companyDetail->address ?? '' }}<br>
                {{ $company->companyDetail->city ?? '' }}, {{ $company->companyDetail->province ?? '' }} {{ $company->companyDetail->postal_code ?? '' }}<br>
                Email: {{ $company->email ?? '-' }} | Telp: {{ $company->phone ?? '-' }}
            </div>
            <div class="report-title">LAPORAN HASIL UJIAN</div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="stats-container">
        <div class="stats-box">
            <div class="stats-label">Total Peserta</div>
            <div class="stats-value">{{ $stats['total_students'] }}</div>
        </div>
        <div class="stats-box">
            <div class="stats-label">Rata-rata Nilai</div>
            <div class="stats-value">{{ number_format($stats['average_score'], 2) }}</div>
        </div>
        <div class="stats-box">
            <div class="stats-label">Nilai Tertinggi / Terendah</div>
            <div class="stats-value">
                <span style="color: #059669">{{ number_format($stats['highest_score'], 2) }}</span> / 
                <span style="color: #dc2626">{{ number_format($stats['lowest_score'], 2) }}</span>
            </div>
        </div>
        <div class="stats-box grade-dist">
            <div class="stats-label" style="text-align: center; margin-bottom: 5px;">Distribusi Grade</div>
            <div style="text-align: center;">
                @forelse($gradeDistribution as $grade => $count)
                    <span class="grade-item"><strong>{{ $grade }}:</strong> {{ $count }}</span>
                @empty
                    <span>-</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filter Information -->
    @if (!empty($filterSummary))
        <div class="filter-section">
            <div style="font-weight: bold; margin-bottom: 5px; text-decoration: underline;">Filter Data:</div>
            @if (isset($filterSummary['user']))
                <div class="filter-row"><span class="filter-label">Peserta</span>: {{ $filterSummary['user'] }}</div>
            @endif
            @if (isset($filterSummary['module']))
                <div class="filter-row"><span class="filter-label">Modul</span>: {{ $filterSummary['module'] }}</div>
            @endif
            @if (isset($filterSummary['timetable']))
                <div class="filter-row"><span class="filter-label">Jadwal</span>: {{ $filterSummary['timetable'] }}</div>
            @endif
            @if (isset($filterSummary['search']))
                <div class="filter-row"><span class="filter-label">Pencarian</span>: {{ $filterSummary['search'] }}</div>
            @endif
        </div>
    @endif

    <!-- Main Table -->
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="col-no">No</th>
                <th rowspan="2" class="text-left">Nama Mahasiswa</th>
                <th rowspan="2">NIM/Username</th>
                <th rowspan="2">Modul</th>
                <th rowspan="2">Jadwal Ujian</th>
                <th rowspan="2">Waktu Ujian</th>
                <th rowspan="2">Soal</th>
                <th rowspan="2">Benar</th>
                <th rowspan="2">Salah</th>
                <th rowspan="2">TJ</th>
                @foreach ($allTopics as $topicId => $topicName)
                    <th colspan="4" style="background-color: #374151; font-size: 8px;">{{ $topicName }}</th>
                @endforeach
                <th rowspan="2">Nilai</th>
                <th rowspan="2">Grade</th>
            </tr>
            <tr>
                @foreach ($allTopics as $topicId => $topicName)
                    <th style="font-size: 7px; background-color: #4b5563;">Soal</th>
                    <th style="font-size: 7px; background-color: #4b5563; color: #10b981;">B</th>
                    <th style="font-size: 7px; background-color: #4b5563; color: #f59e0b;">S</th>
                    <th style="font-size: 7px; background-color: #4b5563; color: #ef4444;">TJ</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($examResults as $index => $result)
                @php
                    $gradeDetail = $gradeDetails[$result->id] ?? null;
                    $totalQ = $result->userModuleQuestions->count();
                    $correct = $result->userModuleQuestions->where('status', 'correct')->count();
                    $wrong = $result->userModuleQuestions->where('status', 'wrong')->count();
                    $unanswered = $result->userModuleQuestions->whereNull('timetable_answer_id')->count();

                    // Calculate topic stats for this student
                    $topicStats = [];
                    foreach ($result->userModuleQuestions as $umq) {
                        $tq = $umq->timetableQuestion;
                        if ($tq && $tq->topic_id) {
                            $topicId = $tq->topic_id;
                            if (!isset($topicStats[$topicId])) {
                                $topicStats[$topicId] = ['total' => 0, 'correct' => 0, 'wrong' => 0, 'unanswered' => 0];
                            }
                            $topicStats[$topicId]['total']++;
                            if (empty($umq->timetable_answer_id)) {
                                $topicStats[$topicId]['unanswered']++;
                            } elseif ($umq->status === 'correct') {
                                $topicStats[$topicId]['correct']++;
                            } elseif ($umq->status === 'wrong') {
                                $topicStats[$topicId]['wrong']++;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left fw-bold">{{ $result->user?->name ?? '-' }}</td>
                    <td>{{ $result->user?->nim ?? ($result->user?->username ?? '-') }}</td>
                    <td>{{ $result->timetable?->module?->name ?? '-' }}</td>
                    <td>{{ $result->timetable?->name ?? '-' }}</td>
                    <td>
                        {{ $result->timetable?->start_time?->format('d/m/y H:i') ?? '-' }} <br> 
                        <span style="color: #6b7280; font-size: 8px;">s.d.</span> <br>
                        {{ $result->timetable?->end_time?->format('d/m/y H:i') ?? '-' }}
                    </td>
                    <td>{{ $totalQ }}</td>
                    <td style="color: #059669; font-weight: bold;">{{ $correct }}</td>
                    <td style="color: #dc2626; font-weight: bold;">{{ $wrong }}</td>
                    <td style="color: #d97706; font-weight: bold;">{{ $unanswered }}</td>

                    @foreach ($allTopics as $topicId => $topicName)
                        @php
                            $tStat = $topicStats[$topicId] ?? ['total' => 0, 'correct' => 0, 'wrong' => 0, 'unanswered' => 0];
                        @endphp
                        <td>{{ $tStat['total'] }}</td>
                        <td style="color: #059669;">{{ $tStat['correct'] }}</td>
                        <td style="color: #dc2626;">{{ $tStat['wrong'] }}</td>
                        <td style="color: #d97706;">{{ $tStat['unanswered'] }}</td>
                    @endforeach

                    <td class="fw-bold" style="font-size: 11px;">{{ number_format($result->mark, 2) }}</td>
                    <td>
                        @if ($gradeDetail)
                            <div class="fw-bold" style="background-color: #eff6ff; color: #1e40af; border-radius: 4px; padding: 2px; display: inline-block; min-width: 25px;">
                                {{ $gradeDetail->grade_letter }}
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ 12 + (count($allTopics) * 4) }}" class="no-data">Tidak ada data yang sesuai dengan kriteria filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span class="page-number"></span> | Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Sistem CBT
    </div>
</body>
</html>
