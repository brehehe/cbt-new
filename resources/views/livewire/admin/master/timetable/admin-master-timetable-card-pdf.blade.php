<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
        .header {
            border-bottom: 1.5px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
            text-align: center;
        }
        .header h3 {
            margin: 0;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            letter-spacing: 0.3px;
        }
        .header p {
            margin: 2px 0 0 0;
            font-size: 7.5pt;
            color: #222;
            line-height: 1.2;
            word-wrap: break-word;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
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
        .info-cell {
            vertical-align: top;
            padding: 0 6px;
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
            width: 48px;
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
        .qr-cell {
            width: 62px;
            vertical-align: top;
            text-align: center;
        }
        .qr-img {
            width: 56px;
            height: 56px;
            display: block;
            margin: 0 auto;
        }
        .qr-label {
            font-size: 6pt;
            text-align: center;
            color: #333;
            margin-top: 2px;
            font-weight: bold;
            letter-spacing: 0.3px;
        }
        .footer {
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
        $students = $timetable->classmate?->classmateStudents;
        if (!$students || $students->isEmpty()) {
            $students = $timetable->userTimetables ?? collect();
        }
        $chunkedStudents = $students->chunk(2); // 2 cards per row
        $barcodeObj = new \Milon\Barcode\DNS2D();
    @endphp

    <table class="card-container">
        @foreach($chunkedStudents as $row)
            <tr class="card-row">
                @foreach($row as $student)
                    @php
                        $user = $student->user;
                        $photo = $user?->profile ?? $user?->photo;
                        $hasPhoto = $photo && file_exists(public_path('storage/' . $photo));
                        
                        $qrPayload = json_encode([
                            'user_id' => (string) ($user?->id ?? ''),
                            'username' => (string) ($user?->username ?? ''),
                            'timetable_id' => (string) $timetable->id,
                        ]);
                        $base64Qr = $barcodeObj->getBarcodePNG($qrPayload, 'QRCODE', 3, 3);
                    @endphp
                    <td class="card-cell">
                        <div class="card">
                            <div class="header">
                                <h3>{{ $company->name ?? 'KARTU PESERTA' }}</h3>
                                <p>{{ $timetable->name }} | {{ $timetable->timetableModule?->name ?? $timetable->module?->name ?? '-' }}</p>
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
                                                <td class="label">Username</td>
                                                <td class="colon">:</td>
                                                <td class="value">{{ $user?->username ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Sesi</td>
                                                <td class="colon">:</td>
                                                <td class="value">{{ $timetable->examSession->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="label">Ruang</td>
                                                <td class="colon">:</td>
                                                <td class="value">{{ $timetable->examRoom->name ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td class="qr-cell">
                                        <img src="data:image/png;base64,{{ $base64Qr }}" class="qr-img">
                                        <div class="qr-label">QRCODE ABSEN</div>
                                    </td>
                                </tr>
                            </table>

                            <div class="footer">
                                <em>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</em>
                            </div>
                        </div>
                    </td>
                @endforeach
                {{-- Fill empty cell if row has only 1 student --}}
                @if($row->count() == 1)
                    <td class="card-cell"></td>
                @endif
            </tr>
            {{-- Break page every 3 rows (6 cards) to fit A4 page properly --}}
            @if(($loop->index + 1) % 3 == 0 && !$loop->last)
                </table><div class="page-break"></div><table class="card-container">
            @endif
        @endforeach
    </table>
</body>
</html>