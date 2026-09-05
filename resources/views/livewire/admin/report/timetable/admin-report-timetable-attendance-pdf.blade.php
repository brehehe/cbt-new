<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Peserta</title>
    <style>
         * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #000; margin: 0; padding: 20px; }
        
        .header-container {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header-logo img { max-height: 60px; }
        .company-name { font-size: 16px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .company-address { font-size: 10px; }
        .report-title { font-size: 14px; font-weight: bold; margin-top: 10px; text-transform: uppercase; text-decoration: underline; }

        .info-table { width: 100%; margin-bottom: 15px; font-size: 11px; }
        .info-table td { padding: 2px; }

        table.main-table { width: 100%; border-collapse: collapse; }
        table.main-table th, table.main-table td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        table.main-table th { background-color: #f0f0f0; font-weight: bold; text-align: center; }
        
        .text-center { text-align: center; }
        .col-no { width: 30px; text-align: center; }
        .col-nim { width: 100px; text-align: center; }
        .col-ttd { width: 150px; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 9px; text-align: right; border-top: 1px solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="company-name">{{ $company->name ?? 'INSTITUSI' }}</div>
        <div class="report-title">DAFTAR HADIR PESERTA UJIAN</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="150"><strong>Modul / Mata Ujian</strong></td>
            <td>: {{ $timetable->module->name ?? '-' }}</td>
            <td width="100"><strong>Waktu</strong></td>
            <td>: {{ $timetable->start_time->format('H:i') }} - {{ $timetable->end_time->format('H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Jadwal</strong></td>
            <td>: {{ $timetable->name ?? '-' }}</td>
            <td><strong>Tanggal</strong></td>
            <td>: {{ $timetable->start_time->format('d/m/Y') }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th class="col-nim">NIM / ID</th>
                <th>Nama Peserta</th>
                <th class="col-ttd">Tanda Tangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($timetable->userTimetables as $index => $ut)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td class="col-nim">{{ $ut->user->nim ?? $ut->user->username  }}</td>
                    <td>{{ $ut->user->name ?? '-' }}</td>
                    <td class="col-ttd" style="font-size: 9px; color: #888;">
                        @if($index % 2 == 0)
                            {{ $index + 1 }} . . . . . . . .
                        @else
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $index + 1 }} . . . . . . . .
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada peserta pada jadwal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; float: right; width: 250px; text-align: center;">
        {{ $company->companyDetail->city ?? 'Tempat' }}, {{ now()->translatedFormat('d F Y') }}<br>
        Pengawas Ujian,<br><br><br><br>
        ( ........................................... )
    </div>

    <div class="footer">
        Dicetak oleh Sistem CBT pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
