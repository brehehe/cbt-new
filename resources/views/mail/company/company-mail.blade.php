<!DOCTYPE html>
<html>

<head>
    <title>Konfirmasi Kode Perusahaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: normal;
        }

        .content {
            padding: 30px 20px;
            line-height: 1.6;
        }

        .company-code-box {
            background-color: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 25px 0;
        }

        .company-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            letter-spacing: 3px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }

        .code-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }

        .important-note h3 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .important-note p {
            color: #856404;
            margin: 0;
            font-size: 14px;
        }

        .footer {
            background-color: #e9ecef;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }

        .contact-info {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .contact-info h4 {
            color: #007bff;
            margin: 0 0 10px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .container {
                margin: 0;
                box-shadow: none;
            }

            .company-code {
                font-size: 24px;
            }

            .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Konfirmasi Kode Perusahaan</h1>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $company->name }}</strong>,</p>

            <p>Terima kasih telah mendaftarkan perusahaan Anda di platform kami. Berikut adalah konfirmasi kode perusahaan yang telah dibuat untuk:</p>

            <div class="company-code-box">
                <div class="code-label">Kode Perusahaan Anda</div>
                <div class="company-code">{{ $company->code }}</div>
                <p style="margin: 10px 0 0 0; color: #666; font-size: 14px;">
                    Simpan kode ini dengan aman
                </p>
            </div>

            <div class="important-note">
                <h3>⚠️ Penting untuk Diingat:</h3>
                <p>• Kode perusahaan ini bersifat unik dan rahasia<br>
                    • Gunakan kode ini untuk mengakses fitur-fitur khusus perusahaan<br>
                    • Jangan bagikan kode ini kepada pihak yang tidak berwenang<br>
                    • Hubungi support jika Anda lupa atau kehilangan kode ini</p>
            </div>

            <p>Dengan kode perusahaan ini, Anda dapat:</p>
            <ul>
                <li>Mengakses dashboard admin perusahaan</li>
                <li>Mengelola data karyawan dan departemen</li>
                <li>Mengundang anggota tim untuk bergabung</li>
                <li>Mengakses laporan dan analitik perusahaan</li>
            </ul>

            <div class="contact-info">
                <h4>Butuh Bantuan?</h4>
                <p>Jika Anda memiliki pertanyaan atau mengalami kesulitan, jangan ragu untuk menghubungi tim support kami:</p>
                <p>📧 Email: support@burningroom.co.id<br>
                    📞 Telepon: +62-XXX-XXXX-XXXX<br>
                    🕒 Jam Operasional: Senin - Jumat, 08:00 - 17:00 WIB</p>
            </div>

            <p>Terima kasih telah mempercayai {{ config('app.name') }} sebagai partner teknologi perusahaan Anda.</p>

            <br>
            <p>Salam hangat,<br>
                <strong>Tim {{ config('app.name') }}</strong>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>Tanggal: {{ date('d F Y, H:i') }} WIB</p>
        </div>
    </div>
</body>

</html>
