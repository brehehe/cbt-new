<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Ujian</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #000; margin: 0; padding: 20px; }
        
        .header-container {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .header-logo img { max-height: 80px; }
        .company-name { font-size: 16px; font-weight: bold; text-transform: uppercase; margin-bottom: 5px; }
        .company-address { font-size: 10px; }
        .report-title { font-size: 14px; font-weight: bold; margin-top: 15px; text-transform: uppercase; text-decoration: underline; }

        .content { line-height: 1.6; text-align: justify; }
        .data-table { width: 100%; margin: 10px 0; }
        .data-table td { vertical-align: top; padding: 3px; }
        .label-col { width: 150px; }
        
        .box { border: 1px solid #000; padding: 10px; margin: 15px 0; }
        
        .signature-section { margin-top: 50px; width: 100%; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; padding-top: 60px; }
        
        .footer { position: fixed; bottom: 0; left: 0; right: 0; font-size: 9px; text-align: right; border-top: 1px solid #ccc; padding-top: 5px; }
    </style>
</head>
<body>
    <div class="header-container">
        @if($company && $company->logo)
            <div class="header-logo">
                 <img src="{{ public_path('storage/' . $company->logo) }}" alt="Logo" onerror="this.style.display='none'">
            </div>
        @endif
        <div class="company-name">{{ $company->name ?? 'INSTITUSI' }}</div>
        <div class="company-address">
            {{ $company->companyDetail->address ?? '' }}<br>
            {{ $company->companyDetail->city ?? '' }}, {{ $company->companyDetail->province ?? '' }}
        </div>
        <div class="report-title">BERITA ACARA PELAKSANAAN UJIAN</div>
    </div>

    <div class="content">
        <p>Pada hari ini <strong>{{ $timetable->start_time->translatedFormat('l') }}</strong> tanggal <strong>{{ $timetable->start_time->translatedFormat('d F Y') }}</strong>, telah diselenggarakan ujian dengan rincian sebagai berikut:</p>

        <table class="data-table">
            <tr>
                <td class="label-col">Mata Ujian / Modul</td>
                <td>: {{ $timetable->module->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jadwal Ujian</td>
                <td>: {{ $timetable->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Waktu Pelaksanaan</td>
                <td>: {{ $timetable->start_time->format('H:i') }} s.d. {{ $timetable->end_time->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Lokasi / Ruang</td>
                <td>: {{ $timetable->description ?? 'Online / Daring' }}</td>
            </tr>
        </table>

        <div class="box">
            <strong>Rekapitulasi Peserta:</strong>
            <table class="data-table">
                <tr>
                    <td class="label-col">Total Peserta Terdaftar</td>
                    <td>: {{ $stats['total'] }} Orang</td>
                </tr>
                <tr>
                    <td>Hadir (Login)</td>
                    <td>: {{ $stats['present'] }} Orang</td>
                </tr>
                <tr>
                    <td>Tidak Hadir</td>
                    <td>: {{ $stats['absent'] }} Orang</td>
                </tr>
            </table>
        </div>

        <p>Demikian berita acara ini dibuat dengan sesungguhnya untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td width="50%">
                    Pengawas Ujian<br><br><br><br><br>
                    ( ........................................... )
                </td>
                <td width="50%">
                    {{ $company->companyDetail->city ?? 'Tempat' }}, {{ now()->translatedFormat('d F Y') }}<br>
                    Koordinator / Penanggung Jawab<br><br><br><br><br>
                    ( ........................................... )
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak oleh Sistem CBT pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
