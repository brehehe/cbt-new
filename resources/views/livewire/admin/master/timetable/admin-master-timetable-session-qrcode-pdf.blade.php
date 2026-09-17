<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lembar QRCODE Presensi Kehadiran</title>
    <style>
        @page {
            margin: 20mm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            line-height: 1.5;
        }
        .container {
            border: 3px double #0f172a;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
        }
        .header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 4px 0;
            color: #0f172a;
        }
        .doc-title {
            font-size: 13pt;
            font-weight: 800;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 0;
        }
        .doc-subtitle {
            font-size: 9pt;
            color: #64748b;
            margin-top: 2px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 20px 0;
            text-align: left;
            font-size: 10pt;
        }
        .info-table td {
            padding: 5px 8px;
            border-bottom: 1px dashed #cbd5e1;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            color: #475569;
            width: 25%;
        }
        .info-table td.colon {
            width: 2%;
            text-align: center;
        }
        .info-table td.val {
            font-weight: 600;
            color: #0f172a;
        }
        .qr-wrapper {
            margin: 20px auto;
            padding: 16px;
            display: inline-block;
            border: 2px solid #0f172a;
            border-radius: 12px;
            background: #ffffff;
        }
        .qr-img {
            width: 200px;
            height: 200px;
            display: block;
            margin: 0 auto;
        }
        .code-badge {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #0f172a;
            margin-top: 8px;
            background-color: #f1f5f9;
            padding: 4px 12px;
            display: inline-block;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }
        .instruction-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 16px;
            margin-top: 15px;
            text-align: left;
            font-size: 9pt;
            color: #1e3a8a;
        }
        .instruction-box h4 {
            margin: 0 0 4px 0;
            font-size: 9.5pt;
            font-weight: bold;
        }
        .instruction-box ol {
            margin: 0;
            padding-left: 18px;
        }
        .instruction-box li {
            margin-bottom: 2px;
        }
        .footer {
            margin-top: 20px;
            font-size: 8pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="company-name">{{ $company->name ?? 'CBT EXAMINATION SYSTEM' }}</h1>
            <h2 class="doc-title">LEMBAR QRCODE PRESENSI KEHADIRAN</h2>
            <div class="doc-subtitle">Tempel atau tampilkan lembar ini di ruang pelaksanaan untuk di-scan oleh {{ student_label() }}</div>
        </div>

        <!-- Info Jadwal & Sesi -->
        <table class="info-table">
            <tr>
                <td class="label">Nama Jadwal</td>
                <td class="colon">:</td>
                <td class="val">{{ $timetable->name }}</td>
            </tr>
            <tr>
                <td class="label">Peserta / Kelas</td>
                <td class="colon">:</td>
                <td class="val">{{ $timetable->classmate?->name ?? 'Semua Kelas' }}</td>
            </tr>
            <tr>
                <td class="label">Ruang & Sesi</td>
                <td class="colon">:</td>
                <td class="val">
                    {{ $detail->examRoom?->name ?? 'Ruang -' }} | {{ $detail->examSession?->name ?? 'Sesi -' }}
                </td>
            </tr>
            <tr>
                <td class="label">Tipe Kegiatan</td>
                <td class="colon">:</td>
                <td class="val">
                    @if($detail->isMaterial())
                        [MATERI] {{ $detail->digitalBook?->title ?? 'Buku Digital' }}
                    @else
                        [UJIAN CBT] {{ $detail->module?->name ?? 'Modul Ujian' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Tanggal Pelaksanaan</td>
                <td class="colon">:</td>
                <td class="val">
                    {{ $detail->exam_date ? \Carbon\Carbon::parse($detail->exam_date)->isoFormat('dddd, DD MMMM Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td class="label">Waktu Pelaksanaan</td>
                <td class="colon">:</td>
                <td class="val">
                    {{ $detail->start_time ? \Carbon\Carbon::parse($detail->start_time)->format('H:i') : '-' }} - 
                    {{ $detail->end_time ? \Carbon\Carbon::parse($detail->end_time)->format('H:i') : '-' }} WIB
                </td>
            </tr>
            <tr>
                <td class="label">Pengawas Bertugas</td>
                <td class="colon">:</td>
                <td class="val">{{ $detail->supervisor_names ?: 'Pengawas' }}</td>
            </tr>
        </table>

        <!-- QRCODE Block -->
        <div>
            <div class="qr-wrapper">
                <img src="data:image/png;base64,{{ $base64Qr }}" class="qr-img" alt="QRCODE Presensi">
            </div>
            <br>
            <div class="code-badge">ID: {{ $detail->code }}</div>
        </div>

        <!-- Instructions for students -->
        <div class="instruction-box">
            <h4>PANDUAN PRESENSI BAGI {{ strtoupper(student_label()) }}:</h4>
            <ol>
                <li>Buka menu <strong>Jadwal</strong> / <strong>Daftar Ujian</strong> pada akun CBT Anda.</li>
                <li>Tekan tombol <strong>"Scan QRCODE Presensi (Buka Kamera)"</strong>.</li>
                <li>Arahkan kamera smartphone atau perangkat Anda ke kode QR di atas hingga terdengar bunyi notifikasi berhasil.</li>
                <li>Setelah berhasil, tombol Masuk Ujian / Buka Materi pada jadwal Anda akan otomatis aktif.</li>
            </ol>
        </div>

        <!-- Footer -->
        <div class="footer">
            <em>Dicetak otomatis oleh Sistem CBT pada {{ now()->isoFormat('DD MMMM Y, HH:mm:ss') }} WIB | ID Detail: {{ $detail->id }}</em>
        </div>
    </div>
</body>
</html>
