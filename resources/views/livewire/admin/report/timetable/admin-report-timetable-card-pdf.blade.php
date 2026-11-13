<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta Ujian - {{ $timetable->name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: sans-serif;
            font-size: 8pt;
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
        }
        .page-break {
            page-break-after: always;
        }
        .card-container {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .card-row {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .card-cell {
            width: 50%;
            padding: 4px 5px;
            vertical-align: top;
        }
        .card {
            border: 1.5px solid #000;
            padding: 8px;
            min-height: 220px;
            height: auto;
            position: relative;
            background: #fff;
        }
        .card-header {
            border-bottom: 1.5px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
            text-align: center;
        }
        .company-name {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            letter-spacing: 0.3px;
        }
        .card-title {
            font-size: 7.5pt;
            font-weight: bold;
            margin-top: 2px;
            display: inline-block;
            color: #222;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-cell {
            vertical-align: top;
            padding: 0 4px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            line-height: 1.25;
        }
        .info-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 56px;
            white-space: nowrap;
            color: #000;
        }
        .colon {
            width: 8px;
            text-align: center;
            color: #000;
        }
        .value {
            color: #000;
            word-wrap: break-word;
            word-break: break-word;
        }
        .photo-cell {
            width: 58px;
            vertical-align: top;
            text-align: center;
        }
        .photo-box {
            width: 54px;
            height: 72px;
            border: 1px solid #999;
            background-color: #fafafa;
            text-align: center;
            overflow: hidden;
            margin: 0 auto;
        }
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-placeholder {
            padding-top: 26px;
            font-size: 7pt;
            color: #aaa;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .footer-note {
            margin-top: 6px;
            padding-top: 3px;
            border-top: 0.5px dashed #ccc;
            font-size: 6.5pt;
            text-align: center;
            font-style: italic;
            color: #555;
        }
    </style>
</head>
<body>
    @php
        $students = $timetable->userTimetables ?? collect();
        if ($students->isEmpty() && $timetable->classmate?->classmateStudents) {
            $students = $timetable->classmate->classmateStudents;
        }
        $chunkedStudents = $students->chunk(2);
    @endphp

    <table class="card-container">
        @foreach($chunkedStudents as $row)
            <tr class="card-row">
                @foreach($row as $ut)
                    @php
                        $user = $ut->user;
                        $photo = $user?->profile ?? $user?->photo;
                        $hasPhoto = $photo && file_exists(public_path('storage/' . $photo));
                    @endphp
                    <td class="card-cell">
                        <div class="card">
                            <div class="card-header">
                                <div class="company-name">{{ $company->name ?? 'INSTITUSI' }}</div>
                                <div class="card-title">KARTU PESERTA UJIAN &bull; {{ $timetable->name }}</div>
                            </div>
                            
                            <table class="content-table">
                                <tr>
                                    <td class="photo-cell">
                                        <div class="photo-box">
                                            @if($hasPhoto)
                                                <img src="{{ public_path('storage/' . $photo) }}" class="photo-img">
                                            @else
                                                <div class="photo-placeholder">FOTO</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="info-cell">
                                        <table class="info-table">
                                            <tr>
                                                <td class="label">Nama</td>
                                                <td class="colon">:</td>
                                                <td class="value"><strong>{{ $user?->name ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="label">NIM / ID</td>
                                                <td class="colon">:</td>
                                                <td class="value">{{ $user?->nim ?? ($user?->username ?? '-') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Username</td>
                                                <td class="colon">:</td>
                                                <td class="value"><strong>{{ $user?->username ?? '-' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="label">Password</td>
                                                <td class="colon">:</td>
                                                <td class="value"><strong>{{ $user?->password_str ?? '******' }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td class="label">Waktu</td>
                                                <td class="colon">:</td>
                                                <td class="value">{{ $timetable->start_time ? \Carbon\Carbon::parse($timetable->start_time)->format('d/m/Y H:i') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Sesi / Ruang</td>
                                                <td class="colon">:</td>
                                                <td class="value">{{ $timetable->examSession->name ?? '-' }} / {{ $timetable->examRoom->name ?? ($timetable->description ?? '-') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div class="footer-note">
                                Harap membawa kartu ini saat ujian berlangsung &bull; Dicetak: {{ now()->format('d-m-Y H:i') }}
                            </div>
                        </div>
                    </td>
                @endforeach
                @if($row->count() == 1)
                    <td class="card-cell"></td>
                @endif
            </tr>
            @if(($loop->index + 1) % 3 == 0 && !$loop->last)
                </table><div class="page-break"></div><table class="card-container">
            @endif
        @endforeach
    </table>
</body>
</html>