<!DOCTYPE html>
<html>

<head>
    <title>Laporan Hasil Ujian Mahasiswa</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .company-logo {
            height: 60px;
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
        }

        .company-address {
            font-size: 12px;
            color: #555;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-decoration: underline;
        }

        .student-info {
            margin-bottom: 20px;
            width: 100%;
        }

        .student-info td {
            padding: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        table.results {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        table.results th,
        table.results td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table.results th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }

        .signature {
            margin-top: 50px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        @if($company->logo)
            <img src="{{ public_path('storage/' . $company->logo) }}" class="company-logo">
        @endif
        <div class="company-name">{{ $company->name }}</div>
        <div class="company-address">{{ $company->companyDetail->address ?? '' }}</div>

        <div class="report-title">LAPORAN HASIL UJIAN MAHASISWA</div>
    </div>

    <table class="student-info">
        <tr>
            <td class="info-label">Nama Mahasiswa</td>
            <td>: {{ $user->name }}</td>
            <td class="info-label">Dicetak Tanggal</td>
            <td>: {{ date('d M Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">NIM / ID</td>
            <td>: {{ $user->nim ?? $user->username }}</td>
            <td class="info-label">Total Ujian</td>
            <td>: {{ $stats['total_exams'] }}</td>
        </tr>
        <tr>
            <td class="info-label">Rata-rata Nilai</td>
            <td>: {{ number_format($stats['average_score'], 2) }}</td>
        </tr>
    </table>

    <table class="results">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Ujian / Modul</th>
                <th width="20%">Waktu</th>
                <th width="15%" class="text-center">Nilai</th>
                <th width="10%" class="text-center">Grade</th>
                <th width="15%" class="text-center">Ket.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($examResults as $index => $result)
                @php
                    $grade = $gradeDetails[$result->id] ?? null;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $result->timetable->name ?? '-' }}</strong><br>
                        <small>{{ $result->timetable->module->name ?? '-' }}</small>
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($result->created_at)->format('d/m/Y') }}<br>
                        {{ \Carbon\Carbon::parse($result->created_at)->format('H:i') }}
                    </td>
                    <td class="text-center font-bold">{{ $result->mark ?? 0 }}</td>
                    <td class="text-center">{{ $grade->grade_letter ?? '-' }}</td>
                    <td class="text-center">{{ $grade->description ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data hasil ujian.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <div class="signature">
            (_________________________)
        </div>
    </div>
</body>

</html>