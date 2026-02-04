<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safe Exam Browser - Invalid Configuration</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            text-align: center;
        }

        .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon svg {
            width: 40px;
            height: 40px;
            stroke: #dc2626;
            stroke-width: 2;
            fill: none;
        }

        h1 {
            font-size: 24px;
            color: #1f2937;
            margin-bottom: 12px;
        }

        p {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .error-code {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 8px;
            margin: 20px 0;
            font-family: monospace;
            color: #374151;
            font-size: 14px;
        }

        .steps {
            background: #f9fafb;
            padding: 24px;
            border-radius: 12px;
            margin: 24px 0;
            text-align: left;
        }

        .steps h3 {
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .steps ol {
            padding-left: 20px;
        }

        .steps li {
            color: #4b5563;
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <svg viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>

        <h1>⚠️ Konfigurasi Safe Exam Browser Tidak Valid</h1>

        <p>Konfigurasi Safe Exam Browser yang Anda gunakan tidak valid atau sudah kadaluarsa.</p>

        @if(isset($message))
            <div class="error-code">
                {{ $message }}
            </div>
        @endif

        <div class="steps">
            <h3>Langkah-langkah untuk memperbaiki:</h3>
            <ol>
                <li>Tutup Safe Exam Browser saat ini</li>
                <li>Hubungi administrator untuk mendapatkan file konfigurasi (.seb) yang baru</li>
                <li>Download file konfigurasi terbaru</li>
                <li>Buka file konfigurasi tersebut untuk memulai ujian</li>
            </ol>
        </div>

        <p style="font-size: 14px; color: #9ca3af;">
            Jika masalah berlanjut, silakan hubungi administrator sistem.
        </p>

        <a href="{{ url('/') }}" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
