<!DOCTYPE html>
<html>
<head>
    <title>Kartu Peserta Ujian</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .page-break { page-break-after: always; }
        .card-container {
            width: 100%;
            border-collapse: collapse;
        }
        .card-row {
            page-break-inside: avoid;
        }
        .card-cell {
            width: 50%;
            padding: 10px;
            vertical-align: top;
        }
        .card {
            border: 1px solid #000;
            padding: 10px;
            height: 220px; /* Fixed height for consistency */
            position: relative;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-align: center;
        }
        .header h3 { margin: 0; font-size: 12pt; text-transform: uppercase; }
        .header p { margin: 0; font-size: 8pt; }
        .content {
            display: table;
            width: 100%;
        }
        .photo-cell {
            display: table-cell;
            width: 80px;
            vertical-align: top;
            text-align: center;
        }
        .info-cell {
            display: table-cell;
            vertical-align: top;
            padding-left: 10px;
        }
        .photo-box {
            width: 70px;
            height: 90px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-table {
            width: 100%;
            font-size: 9pt;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .label { font-weight: bold; width: 70px; }
        .footer {
            margin-top: 10px;
            font-size: 8pt;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    @php
        $students = $timetable->classmate->classmateStudents;
        $chunkedStudents = $students->chunk(2); // 2 cards per row
    @endphp

    <table class="card-container">
        @foreach($chunkedStudents as $row)
            <tr class="card-row">
                @foreach($row as $student)
                    <td class="card-cell">
                        <div class="card">
                            <div class="header">
                                <h3>{{ $company->name ?? 'KARTU PESERTA' }}</h3>
                                <p>{{ $timetable->name }} | {{ $timetable->timetableModule?->name ?? $timetable->module?->name ?? '-' }}</p>
                            </div>
                            
                            <div class="content">
                                <div class="photo-cell">
                                    <div class="photo-box">
                                        @if($student->user->userDetail && $student->user->profile)
                                            <img src="{{ public_path('storage/' . $student->user->profile) }}" class="photo-img">
                                        @else
                                            <div style="padding-top: 35px; font-size: 8pt; color: #ccc;">FOTO</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-cell">
                                    <table class="info-table">
                                        <tr>
                                            <td class="label">Nama</td>
                                            <td>: {{ $student->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Username</td>
                                            <td>: {{ $student->user->username }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Sesi</td>
                                            <td>: {{ $timetable->examSession->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="label">Ruang</td>
                                            <td>: {{ $timetable->examRoom->name ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

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
