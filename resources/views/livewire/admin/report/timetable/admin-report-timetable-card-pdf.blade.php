    <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta Ujian</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background-color: #fff; }
        
        .page-container {
            padding: 10px;
            width: 100%;
        }

        .card {
            width: 48%; /* 2 cards per row roughly */
            float: left;
            border: 1px solid #000;
            margin: 1%;
            padding: 10px;
            height: 220px; /* Fixed height for uniformity */
            page-break-inside: avoid;
        }

        .card-header {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-align: center;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .card-title {
            font-size: 11px;
            font-weight: bold;
            margin-top: 2px;
            background-color: #000;
            color: #fff;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 2px;
        }

        .card-body {
            font-size: 10px;
        }

        .card-row {
            margin-bottom: 4px;
            clear: both;
        }
        .card-label {
            float: left;
            width: 70px;
            font-weight: bold;
        }
        .card-value {
            float: left;
            width: calc(100% - 70px);
        }

        .photo-box {
            position: absolute;
            top: 50px;
            right: 15px;
            width: 60px;
            height: 70px;
            border: 1px solid #ccc;
            text-align: center;
            line-height: 70px;
            font-size: 8px;
            color: #ccc;
        }
        
        .footer-note {
            margin-top: 10px;
            font-size: 8px;
            font-style: italic;
            text-align: center;
            border-top: 1px dashed #ccc;
            padding-top: 3px;
        }

        /* Clearfix for float layout */
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="page-container clearfix">
        @foreach($timetable->userTimetables as $ut)
            <div class="card">
                <div class="card-header">
                    <div class="company-name">{{ $company->name ?? 'INSTITUSI' }}</div>
                    <div class="card-title">KARTU PESERTA UJIAN</div>
                </div>
                
                <div class="card-body">
                    <div class="card-row clearfix">
                        <div class="card-label">Nama</div>
                        <div class="card-value">: {{ $ut->user->name ?? '-' }}</div>
                    </div>
                    <div class="card-row clearfix">
                        <div class="card-label">NIM / ID</div>
                        <div class="card-value">: {{ $ut->user->nim ?? $ut->user->username }}</div>
                    </div>
                    <div class="card-row clearfix">
                        <div class="card-label">Username</div>
                        <div class="card-value">: <strong>{{ $ut->user->username }}</strong></div>
                    </div>
                    <div class="card-row clearfix">
                        <div class="card-label">Password</div>
                        <div class="card-value">: <strong>{{ $ut->user->password_str ?? '******' }}</strong></div>
                    </div>
                    <div class="card-row clearfix">
                        <div class="card-label">Jadwal</div>
                        <div class="card-value">: {{ $timetable->name }}</div>
                    </div>
                    <div class="card-row clearfix">
                        <div class="card-label">Waktu</div>
                        <div class="card-value">: {{ $timetable->start_time->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="card-row clearfix">
                        <div class="card-label">Ruang</div>
                        <div class="card-value">: {{ $timetable->description ?? '-' }}</div>
                    </div>
                </div>

                <div class="footer-note">
                    Harap membawa kartu ini saat ujian berlangsung.
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>
