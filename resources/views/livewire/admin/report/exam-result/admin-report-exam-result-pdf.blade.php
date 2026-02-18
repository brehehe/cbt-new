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
                <th class="col-no">No</th>
                <th class="text-left">Nama Siswa</th>
                <th>NIM/Username</th>
                <th>Modul</th>
                <th>Jadwal Ujian</th>
                <th>Waktu Ujian</th>
                <th>Nilai</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($examResults as $index => $result)
                @php
                    $gradeDetail = $gradeDetails[$result->id] ?? null;
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
                    <td colspan="8" class="no-data">Tidak ada data yang sesuai dengan kriteria filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span class="page-number"></span> | Dicetak pada: {{ now()->format('d/m/Y H:i') }} | Sistem CBT
    </div>
</body>
</html>
