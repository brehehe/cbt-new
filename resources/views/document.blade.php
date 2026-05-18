<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dokumentasi Instalasi PROCBT</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @layer base {
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            html,
            body {
                width: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background: #ffffff;
                color: #1e293b;
            }
        }

        /* ---- COVER ---- */
        .cover {
            background: #0f172a;
            position: relative;
            overflow: hidden;
            padding: 72px 64px 60px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .cover-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(99, 114, 243, .12) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 114, 243, .12) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .cover-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(74, 81, 232, .35) 0%, transparent 70%);
            top: -180px;
            right: -120px;
            pointer-events: none;
        }

        .cover-glow2 {
            position: absolute;
            width: 400px;
            height: 300px;
            background: radial-gradient(circle, rgba(14, 165, 233, .2) 0%, transparent 70%);
            bottom: -80px;
            left: 60px;
            pointer-events: none;
        }

        .cover-accent-line {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: linear-gradient(180deg, #6172f3, #0ea5e9, #6172f3);
        }

        .cover-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(99, 114, 243, .18);
            border: 1px solid rgba(99, 114, 243, .35);
            border-radius: 99px;
            padding: 5px 14px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #a5bcfb;
        }

        .cover-title {
            font-size: 52px;
            font-weight: 800;
            color: #f8fafc;
            line-height: 1.1;
            letter-spacing: -.02em;
        }

        .cover-title span {
            background: linear-gradient(135deg, #6172f3, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .cover-desc {
            font-size: 15px;
            color: #94a3b8;
            font-weight: 400;
            line-height: 1.75;
            max-width: 800px;
        }

        .cover-meta-bar {
            display: flex;
            gap: 0;
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .09);
            border-radius: 12px;
            overflow: hidden;
        }

        .cover-meta-item {
            flex: 1;
            padding: 16px 20px;
            border-right: 1px solid rgba(255, 255, 255, .08);
        }

        .cover-meta-item:last-child {
            border-right: none;
        }

        .cover-meta-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 5px;
        }

        .cover-meta-value {
            font-size: 13px;
            font-weight: 600;
            color: #e2e8f0;
        }

        .cover-steps-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(99, 114, 243, .15);
            border: 1px solid rgba(99, 114, 243, .3);
            border-radius: 10px;
            padding: 10px 18px;
            width: fit-content;
        }

        /* ---- BODY ---- */
        .wrapper {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 48px 5%;
        }

        /* 2-column grid for steps */
        .step-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            align-items: start;
        }

        /* Short steps: 1 column in the grid */
        .step-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        /* Long steps: span full 2 columns */
        .step-card.wide {
            grid-column: span 2;
        }

        /* ---- UTILITY ---- */
        /* Manual page-break helpers — tambahkan class ini ke step-card */
        /* Contoh: <div class="step-card page-break-before"> */
        @media print {
            .page-break-before {
                margin-top: 40px;
                break-before: page !important;
                page-break-before: always !important;
            }

            .page-break-after {
                break-after: page !important;
                page-break-after: always !important;
            }

            .no-page-break {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
            }
        }

        .section-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #6172f3;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, #e2e8f0 0%, #e2e8f0 70%, transparent);
            margin: 36px 0;
        }

        .step-num {
            width: 38px;
            height: 38px;
            background: #4a51e8;
            color: #fff;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .step-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
        }

        .step-sub {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .code-block {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12.5px;
            line-height: 1.75;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 20px 24px;

            overflow-x: hidden;
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;

            color: #334155;
            position: relative;
        }

        .env-block {
            font-size: 10px;
            line-height: 1.3;
        }

        @media print {
            .env-block {
                font-size: 7px !important;
                /* Font lebih kecil saat print agar pasti muat 1 halaman */
                column-count: 1;
            }

            .configuration-block {
                font-size: 8px !important;
                /* Font lebih kecil saat print agar pasti muat 1 halaman */
                column-count: 1;
            }

            .no-page-break {
                break-inside: avoid-page !important;
                page-break-inside: avoid !important;
            }
        }

        .code-block .cm {
            color: #94a3b8;
        }

        .code-block .ck {
            color: #7c3aed;
            font-weight: 500;
        }

        .code-block .cv {
            color: #059669;
        }

        .copy-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #e0eaff;
            color: #3b40cf;
            border: none;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 11px;
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            cursor: pointer;
            transition: background .15s;
        }

        .copy-btn:hover {
            background: #c7d7fd;
        }

        .copy-btn.copied {
            background: #d1fae5;
            color: #065f46;
        }

        .sub-label {
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .info-box {
            background: #f0f9ff;
            border-left: 3px solid #0ea5e9;
            border-radius: 0 8px 8px 0;
            padding: 13px 18px;
        }

        .warn-box {
            background: #fffbeb;
            border-left: 3px solid #f59e0b;
            border-radius: 0 8px 8px 0;
            padding: 13px 18px;
        }

        .ok-box {
            background: #f0fdf4;
            border-left: 3px solid #22c55e;
            border-radius: 0 8px 8px 0;
            padding: 13px 18px;
        }

        .toc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 4px;
            break-inside: avoid;
        }

        .toc-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 7px 10px;
            border-radius: 7px;
        }

        .toc-n {
            width: 24px;
            height: 24px;
            background: #f0f4ff;
            color: #4a51e8;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .toc-t {
            font-size: 12.5px;
            color: #475569;
            font-weight: 500;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
        }

        .card-title {
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #4a51e8;
            margin-bottom: 12px;
        }

        .dot {
            width: 4px;
            height: 4px;
            background: #4a51e8;
            border-radius: 50%;
            flex-shrink: 0;
            margin-top: 5px;
        }

        .login-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .login-key {
            font-size: 12px;
            color: #15803d;
            font-weight: 600;
            width: 80px;
        }

        .login-val {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            background: #dcfce7;
            padding: 2px 10px;
            border-radius: 5px;
            color: #166534;
        }

        @media print {

            .no-print,
            .copy-btn {
                display: none !important;
            }


            body {
                background: white;
            }

            .wrapper {
                padding: 30px;
            }

            .divider {
                margin: 20px 0;
            }

            .step-num {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: A4 landscape;
                margin: 0;
            }

            /* Definisi Halaman Portrait Khusus */
            @page portrait-page {
                size: A4 portrait;
                margin: 0;
            }

            .force-portrait {
                page: portrait-page;
                width: 210mm !important;
                /* Lebar A4 Portrait */
                margin-left: auto !important;
                margin-right: auto !important;
                break-before: page;
                break-after: page;
            }

            /* Cover = exactly one full A4 landscape page */
            .cover {
                margin: 0 !important;
                padding: 20mm 8% !important;
                width: 100% !important;
                height: 210mm !important;
                min-height: unset !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: center !important;
                page-break-after: always !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .code-block {
                white-space: pre-wrap !important;
                overflow: visible !important;
                word-break: break-word !important;
                overflow-wrap: anywhere !important;
            }

            /* Step grid stays 2-col in print */
            .step-grid {
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .step-card {
                break-inside: avoid;
                page-break-inside: avoid;
                border: 1px solid #e2e8f0;
            }

            .step-card.wide {
                grid-column: span 2;
                break-inside: auto;
                /* allow long steps to break between pages */
                page-break-inside: auto;
            }

            .code-block {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .wrapper {
                padding: 10mm 5% !important;
                width: 100% !important;
                max-width: none !important;
                margin: 0 !important;
            }

            .no-print,
            .copy-btn {
                display: none !important;
            }

            .cover {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <!-- =========================================================
     COVER
========================================================== -->
    <div class="cover">
        <div class="cover-grid"></div>
        <div class="cover-glow"></div>
        <div class="cover-glow2"></div>
        <div class="cover-accent-line"></div>

        <div style="position:relative;z-index:1;">

            <!-- top row -->
            <div style="display:flex;align-items:center;gap:40px;margin-bottom:40px;">
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <span class="cover-badge">Server Setup</span>
                    <span class="cover-badge">Ubuntu 24.04 LTS</span>
                    <span class="cover-badge">Laravel 12+</span>
                </div>
                <div style="font-family:'JetBrains Mono',monospace;font-size:11px;color:#475569;">v1.0.0 &nbsp;/&nbsp;
                    2025</div>
                <button onclick="window.print()" class="no-print"
                    style="margin-left:auto;background:linear-gradient(135deg,#6172f3,#0ea5e9);color:white;border:none;padding:8px 20px;border-radius:99px;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:8px;box-shadow:0 10px 15px -3px rgba(97,114,243,0.3);">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <rect x="6" y="14" width="12" height="8"></rect>
                    </svg>
                    Cetak / Simpan PDF
                </button>
            </div>

            <!-- app icon + name -->
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;">
                <div
                    style="width:54px;height:54px;background:linear-gradient(135deg,#4a51e8,#0ea5e9);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" />
                        <path d="M8 21h8M12 17v4" />
                        <path d="M7 8h2M7 11h6M7 14h10" />
                    </svg>
                </div>
                <div>
                    <div
                        style="font-size:12px;font-weight:700;color:#6172f3;letter-spacing:.15em;text-transform:uppercase;">
                        PROCBT</div>
                    <div style="font-size:12px;color:#475569;margin-top:1px;">Computer Based Test System</div>
                </div>
            </div>

            <h1 class="cover-title" style="margin-bottom:18px;">
                Dokumentasi<br><span>Instalasi Server</span>
            </h1>
            <p class="cover-desc" style="margin-bottom:36px;">
                Panduan instalasi lengkap sistem CBT berbasis Laravel pada Ubuntu Server menggunakan Nginx,
                PHP&nbsp;8.3, PostgreSQL, Supervisor, dan LiveKit Cloud untuk fitur video proctoring.
            </p>

            <!-- step count pill -->
            <div class="cover-steps-pill" style="margin-bottom:32px;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#6172f3" stroke-width="2.2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 11 12 14 22 4" />
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                </svg>
                <span style="font-size:13px;font-weight:700;color:#a5bcfb;">26 Langkah Instalasi</span>
                <span style="font-size:12px;color:#475569;">— dari update sistem hingga optimasi produksi</span>
            </div>

            <!-- meta bar -->
            <div class="cover-meta-bar">
                <div class="cover-meta-item">
                    <div class="cover-meta-label">Sistem Operasi</div>
                    <div class="cover-meta-value">Ubuntu 22.04 / 24.04 LTS</div>
                </div>
                <div class="cover-meta-item">
                    <div class="cover-meta-label">Web Server</div>
                    <div class="cover-meta-value">Nginx + PHP 8.3-FPM</div>
                </div>
                <div class="cover-meta-item">
                    <div class="cover-meta-label">Database</div>
                    <div class="cover-meta-value">PostgreSQL</div>
                </div>
                <div class="cover-meta-item">
                    <div class="cover-meta-label">Video Proctoring</div>
                    <div class="cover-meta-value">LiveKit Cloud</div>
                </div>
                <div class="cover-meta-item">
                    <div class="cover-meta-label">Runtime</div>
                    <div class="cover-meta-value">Node.js + FFmpeg</div>
                </div>
            </div>

        </div>
    </div>


    <!-- =========================================================
     BODY CONTENT
========================================================== -->
    <div class="wrapper">

        <!-- DAFTAR ISI -->
        <div style="margin-bottom:52px;">
            <div class="section-label" style="margin-bottom:16px;">Daftar Isi</div>
            <div class="toc-grid" id="toc"></div>
        </div>

        <div class="page-break-before"></div>

        <!-- SEPARATOR -->
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:32px;">
            <div style="flex:1;height:1px;background:#e2e8f0;"></div>
            <span class="section-label">Langkah Instalasi</span>
            <div style="flex:1;height:1px;background:#e2e8f0;"></div>
        </div>

        <div class="step-grid">
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">1</div>
                    <div>
                        <div class="step-title">Update Ubuntu Server</div>
                        <div class="step-sub">Perbarui seluruh paket sistem dan atur zona waktu</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c1"><span class="cm"># Perbarui paket sistem</span>
                        sudo apt update && sudo apt upgrade -y
                        sudo timedatectl set-timezone Asia/Jakarta</div><button class="copy-btn"
                        onclick="cp('c1',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">2</div>
                    <div>
                        <div class="step-title">Install Nginx</div>
                        <div class="step-sub">Web server untuk melayani aplikasi Laravel</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c2">sudo apt install nginx -y

                        sudo systemctl enable nginx
                        sudo systemctl start nginx
                        sudo systemctl status nginx</div><button class="copy-btn" onclick="cp('c2',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">3</div>
                    <div>
                        <div class="step-title">Install PHP 8.3</div>
                        <div class="step-sub">Runtime PHP beserta seluruh ekstensi yang dibutuhkan Laravel</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c3"><span class="cm"># Tambahkan repositori PPA ondrej/php</span>
                        sudo add-apt-repository ppa:ondrej/php -y
                        sudo apt update

                        <span class="cm"># Install PHP 8.3 dan ekstensi</span>
                        sudo apt install -y \
                        php8.3 \
                        php8.3-fpm \
                        php8.3-cli \
                        php8.3-pgsql \
                        php8.3-mbstring \
                        php8.3-xml \
                        php8.3-curl \
                        php8.3-zip \
                        php8.3-bcmath \
                        php8.3-gd \
                        php8.3-intl \
                        php8.3-soap \
                        php8.3-imagick \
                        unzip \
                        curl \
                        git
                    </div><button class="copy-btn" onclick="cp('c3',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">4</div>
                    <div>
                        <div class="step-title">Konfigurasi Upload</div>
                        <div class="step-sub">Izinkan unggahan file besar untuk rekaman ujian</div>
                    </div>
                </div>
                <div class="info-box" style="margin-bottom:12px;">
                    <p style="font-size:12px;color:#0c4a6e;margin:0;">Edit file <code
                            style="background:#e0f2fe;padding:1px 6px;border-radius:4px;font-family:'JetBrains Mono',monospace;font-size:11px;">/etc/php/8.3/fpm/php.ini</code>
                        lalu cari dan ubah nilai-nilai berikut.</p>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Buka File Konfigurasi</div>
                <div style="position:relative">
                    <div class="code-block" id="c4a" style="margin-bottom:12px;">sudo nano /etc/php/8.3/fpm/php.ini
                    </div><button class="copy-btn" onclick="cp('c4a',this)">Salin</button>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Cari Lalu Ubah</div>
                <div style="position:relative">
                    <div class="code-block" id="c4b" style="margin-bottom:12px;">upload_max_filesize = 1024M
                        post_max_size = 1024M
                        memory_limit = 2048M
                        max_execution_time = 3600
                        max_input_time = 3600
                        max_file_uploads = 100</div><button class="copy-btn" onclick="cp('c4b',this)">Salin</button>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Restart PHP-FPM</div>
                <div style="position:relative">
                    <div class="code-block" id="c4c">sudo systemctl restart php8.3-fpm</div><button class="copy-btn"
                        onclick="cp('c4c',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">5</div>
                    <div>
                        <div class="step-title">Install Composer</div>
                        <div class="step-sub">Package manager dependensi PHP untuk Laravel</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c5">curl -sS https://getcomposer.org/installer | php
                        sudo mv composer.phar /usr/local/bin/composer
                        composer --version</div><button class="copy-btn" onclick="cp('c5',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">6</div>
                    <div>
                        <div class="step-title">Install PostgreSQL</div>
                        <div class="step-sub">Database relasional yang digunakan PROCBT</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c6">sudo apt install postgresql postgresql-contrib -y

                        sudo systemctl enable postgresql
                        sudo systemctl start postgresql</div><button class="copy-btn"
                        onclick="cp('c6',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">7</div>
                    <div>
                        <div class="step-title">Membuat Database PostgreSQL</div>
                        <div class="step-sub">Buat database dan pengguna khusus untuk aplikasi</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c7"><span class="cm"># Masuk ke konsol PostgreSQL sebagai
                            superuser</span>
                        sudo -u postgres psql

                        <span class="cm"># Buat database, user, dan berikan hak akses</span>
                        CREATE DATABASE procbt;
                        CREATE USER procbt_user WITH PASSWORD 'passwordku';
                        GRANT ALL PRIVILEGES ON DATABASE procbt TO procbt_user;

                        <span class="cm"># Keluar dari konsol</span>
                        \q
                    </div><button class="copy-btn" onclick="cp('c7',this)">Salin</button>
                </div>
                <div class="warn-box" style="margin-top:12px;">
                    <p style="font-size:12px;color:#92400e;margin:0;"><strong>Perhatian:</strong> Ganti <code
                            style="background:#fef3c7;padding:1px 6px;border-radius:4px;font-family:'JetBrains Mono',monospace;font-size:11px;">'passwordku'</code>
                        dengan kata sandi yang kuat.</p>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">8</div>
                    <div>
                        <div class="step-title">Install Node.js</div>
                        <div class="step-sub">Runtime JavaScript untuk build asset frontend</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c8"><span class="cm"># Tambahkan repositori Node.js 22.x</span>
                        curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
                        sudo apt install nodejs -y
                        node -v
                        npm -v</div><button class="copy-btn" onclick="cp('c8',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">9</div>
                    <div>
                        <div class="step-title">Install FFmpeg</div>
                        <div class="step-sub">Library pemrosesan dan kompresi video rekaman ujian</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c9">sudo apt install ffmpeg -y
                        ffmpeg -version</div><button class="copy-btn" onclick="cp('c9',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">10</div>
                    <div>
                        <div class="step-title">Install Supervisor</div>
                        <div class="step-sub">Process manager untuk menjaga worker queue tetap berjalan</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c10">sudo apt install supervisor -y

                        sudo systemctl enable supervisor
                        sudo systemctl start supervisor</div><button class="copy-btn"
                        onclick="cp('c10',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">11</div>
                    <div>
                        <div class="step-title">Clone Repository PROCBT</div>
                        <div class="step-sub">Unduh source code aplikasi dari GitHub</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c11">cd /var/www/html
                        git clone https://github.com/brehehe/cbt-new.git procbt
                        cd procbt</div><button class="copy-btn" onclick="cp('c11',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">12</div>
                    <div>
                        <div class="step-title">Install Dependency Laravel</div>
                        <div class="step-sub">Install paket PHP via Composer dan build asset frontend</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c12">composer install
                        npm install
                        npm run build</div><button class="copy-btn" onclick="cp('c12',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">13</div>
                    <div>
                        <div class="step-title">Copy File Environment</div>
                        <div class="step-sub">Salin template konfigurasi lingkungan aplikasi</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c13">cp .env.example .env</div><button class="copy-btn"
                        onclick="cp('c13',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <!-- JANGAN HAPUS CLASS no-page-break DAN force-portrait AGAR TIDAK TERPOTONG SAAT CETAK -->
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">14</div>
                    <div>
                        <div class="step-title">Konfigurasi File <code
                                style="font-family:'JetBrains Mono',monospace;font-size:14px;color:#4a51e8;">.env</code>
                        </div>
                        <div class="step-sub">Sesuaikan variabel lingkungan dengan konfigurasi server Anda</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block env-block" id="c14">
                        <span class="ck">APP_NAME</span>=<span class="cv">"CBT Procbt"</span>
                        <span class="ck">APP_ENV</span>=<span class="cv">production</span>
                        <span class="ck">APP_KEY</span>=<span class="cv">base64:YOUR_APP_KEY</span>
                        <span class="ck">APP_DEBUG</span>=<span class="cv">false</span>
                        <span class="ck">APP_URL</span>=<span class="cv">https://domainanda.com</span>

                        <span class="ck">APP_LOCALE</span>=<span class="cv">id</span>
                        <span class="ck">APP_FALLBACK_LOCALE</span>=<span class="cv">id</span>
                        <span class="ck">APP_FAKER_LOCALE</span>=<span class="cv">id_ID</span>

                        <span class="ck">LOG_CHANNEL</span>=<span class="cv">stack</span>
                        <span class="ck">LOG_STACK</span>=<span class="cv">single</span>
                        <span class="ck">LOG_LEVEL</span>=<span class="cv">info</span>

                        <span class="ck">DB_CONNECTION</span>=<span class="cv">pgsql</span>
                        <span class="ck">DB_HOST</span>=<span class="cv">127.0.0.1</span>
                        <span class="ck">DB_PORT</span>=<span class="cv">5432</span>
                        <span class="ck">DB_DATABASE</span>=<span class="cv">nama_database</span>
                        <span class="ck">DB_USERNAME</span>=<span class="cv">username_database</span>
                        <span class="ck">DB_PASSWORD</span>=<span class="cv">password_database</span>

                        <span class="ck">SESSION_DRIVER</span>=<span class="cv">database</span>
                        <span class="ck">SESSION_LIFETIME</span>=<span class="cv">120</span>
                        <span class="ck">SESSION_ENCRYPT</span>=<span class="cv">false</span>
                        <span class="ck">SESSION_PATH</span>=<span class="cv">/</span>
                        <span class="ck">SESSION_DOMAIN</span>=<span class="cv">null</span>

                        <span class="ck">BROADCAST_CONNECTION</span>=<span class="cv">log</span>
                        <span class="ck">FILESYSTEM_DISK</span>=<span class="cv">public</span>

                        <span class="ck">QUEUE_CONNECTION</span>=<span class="cv">database</span>
                        <span class="ck">CACHE_STORE</span>=<span class="cv">database</span>

                        <span class="ck">MAIL_MAILER</span>=<span class="cv">log</span>
                        <span class="ck">MAIL_FROM_ADDRESS</span>=<span class="cv">"noreply@domainanda.com"</span>
                        <span class="ck">MAIL_FROM_NAME</span>=<span class="cv">"${APP_NAME}"</span>

                        <span class="ck">VITE_APP_NAME</span>=<span class="cv">"${APP_NAME}"</span>

                        <span class="ck">TALLSTACKUI_PREFIX</span>=<span class="cv">"ts-"</span>
                        <span class="ck">APP_SLUG_NAME</span>=<span class="cv">"login_universitas"</span>

                        <span class="ck">IMPORT_QUESTION</span>=<span class="cv">true</span>
                        <span class="ck">QUESTION_DUMMY</span>=<span class="cv">false</span>
                        <span class="ck">QUESTION_PACKAGE</span>=<span class="cv">Lite</span>

                        <span class="ck">SEB_ENABLED</span>=<span class="cv">true</span>
                        <span class="ck">SEB_REQUIRE_ALL</span>=<span class="cv">false</span>
                        <span class="ck">SEB_BROWSER_EXAM_KEY</span>=<span class="cv">YOUR_SEB_BROWSER_EXAM_KEY</span>
                        <span class="ck">SEB_REQUIRE_BEK</span>=<span class="cv">false</span>
                        <span class="ck">SEB_CONFIG_KEY</span>=<span class="cv">YOUR_SEB_CONFIG_KEY</span>
                        <span class="ck">SEB_QUIT_PASSWORD</span>=<span class="cv">YOUR_SEB_PASSWORD</span>

                        <span class="ck">CLEAR_SESSION_PASSWORD</span>=<span class="cv">procbt</span>
                        <span class="ck">OCTANE_SERVER</span>=<span class="cv">frankenphp</span>

                        <span class="ck">LIMIT_QUESTION_VIEW</span>=<span class="cv">false</span>
                        <span class="ck">LIMIT_QUESTION_COUNT</span>=<span class="cv">5</span>

                        <span class="ck">LIVEKIT_URL</span>=<span
                            class="cv">wss://your-livekit-domain.livekit.cloud</span>
                        <span class="ck">LIVEKIT_API_KEY</span>=<span class="cv">YOUR_LIVEKIT_API_KEY</span>
                        <span class="ck">LIVEKIT_API_SECRET</span>=<span class="cv">YOUR_LIVEKIT_API_SECRET</span>
                        <span class="ck">LOGIN_IS_CREDENTIALS</span>=<span class="cv">true</span>
                        <span class="ck">NEW_TEMPLATE</span>=<span class="cv">false</span>
                    </div><button class="copy-btn" onclick="cp('c14',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">15</div>
                    <div>
                        <div class="step-title">Generate APP Key</div>
                        <div class="step-sub">Buat kunci enkripsi unik untuk aplikasi</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c15">php artisan key:generate</div><button class="copy-btn"
                        onclick="cp('c15',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">16</div>
                    <div>
                        <div class="step-title">Migrasi Database</div>
                        <div class="step-sub">Buat seluruh tabel dan isi data awal melalui seeder</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c16">php artisan migrate:fresh --seed</div><button class="copy-btn"
                        onclick="cp('c16',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">17</div>
                    <div>
                        <div class="step-title">Login Default PROCBT</div>
                        <div class="step-sub">Kredensial bawaan setelah proses seeding selesai</div>
                    </div>
                </div>
                <div class="ok-box">
                    <p style="font-size:12.5px;font-weight:700;color:#15803d;margin-bottom:10px;">Akun Administrator
                        Default</p>
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <div class="login-row"><span class="login-key">Username</span><span
                                class="login-val">procbt</span></div>
                        <div class="login-row"><span class="login-key">Password</span><span
                                class="login-val">12345678</span></div>
                    </div>
                    <p style="font-size:11.5px;color:#166534;margin-top:10px;margin-bottom:0;">Segera ubah password
                        setelah berhasil login pertama kali.</p>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">18</div>
                    <div>
                        <div class="step-title">Permission Laravel</div>
                        <div class="step-sub">Atur hak akses direktori penyimpanan dan cache bootstrap</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c18">sudo chown -R www-data:www-data /var/www/html/procbt
                        sudo chmod -R 775 storage
                        sudo chmod -R 775 bootstrap/cache</div><button class="copy-btn"
                        onclick="cp('c18',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide no-page-break">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">19</div>
                    <div>
                        <div class="step-title">Konfigurasi Supervisor Queue</div>
                        <div class="step-sub">Worker queue berjalan otomatis di background secara persisten</div>
                    </div>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Buat File Konfigurasi</div>
                <div style="position:relative">
                    <div class="code-block" id="c19a" style="margin-bottom:12px;">sudo nano
                        /etc/supervisor/conf.d/procbt-worker.conf</div><button class="copy-btn"
                        onclick="cp('c19a',this)">Salin</button>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Isi Konfigurasi</div>
                <div style="position:relative">
                    <div class="code-block" id="c19b" style="margin-bottom:12px;">[program:procbt-worker]
                        process_name=%(program_name)s_%(process_num)02d
                        command=php /var/www/html/procbt/artisan queue:work database --sleep=3 --tries=3 --timeout=3600
                        autostart=true
                        autorestart=true
                        user=www-data
                        numprocs=2
                        redirect_stderr=true
                        stdout_logfile=/var/www/html/procbt/storage/logs/worker.log
                        stopwaitsecs=3600</div><button class="copy-btn" onclick="cp('c19b',this)">Salin</button>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Reload Supervisor</div>
                <div style="position:relative">
                    <div class="code-block" id="c19c">sudo supervisorctl reread
                        sudo supervisorctl update
                        sudo supervisorctl start procbt-worker:*</div><button class="copy-btn"
                        onclick="cp('c19c',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide" style="margin-top:50px">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">20</div>
                    <div>
                        <div class="step-title">Konfigurasi Nginx</div>
                        <div class="step-sub">Arahkan domain ke direktori publik Laravel</div>
                    </div>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Buat File Konfigurasi</div>
                <div style="position:relative">
                    <div class="code-block" id="c20a" style="margin-bottom:12px;">sudo nano
                        /etc/nginx/sites-available/procbt</div><button class="copy-btn"
                        onclick="cp('c20a',this)">Salin</button>
                </div>
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Isi Konfigurasi</div>
                <div style="position:relative">
                    <div class="code-block configuration-block" id="c20b" style="margin-bottom:12px;">server {
                        listen 80;
                        server_name domainanda.com www.domainanda.com;
                        root /var/www/html/procbt/public;
                        index index.php;

                        client_max_body_size 1024M;
                        proxy_read_timeout 3600;
                        proxy_connect_timeout 3600;
                        proxy_send_timeout 3600;
                        fastcgi_read_timeout 3600;

                        location / {
                        try_files $uri $uri/ /index.php?$query_string;
                        }

                        location ~ \.php$ {
                        include snippets/fastcgi-php.conf;
                        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
                        }

                        location ~ /\.(?!well-known).* {
                        deny all;
                        }
                        }</div><button class="copy-btn" onclick="cp('c20b',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide" style="margin-top:50px">
                <div class="sub-label" style="margin-top:12px;margin-bottom:6px;">Aktifkan Site & Restart Nginx</div>
                <div style="position:relative">
                    <div class="code-block" id="c20c">sudo ln -s /etc/nginx/sites-available/procbt
                        /etc/nginx/sites-enabled/
                        sudo nginx -t
                        sudo systemctl restart nginx</div><button class="copy-btn"
                        onclick="cp('c20c',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">21</div>
                    <div>
                        <div class="step-title">Install SSL HTTPS</div>
                        <div class="step-sub">Aktifkan enkripsi HTTPS menggunakan Certbot (Let's Encrypt)</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c21">sudo apt install certbot python3-certbot-nginx -y
                        sudo certbot --nginx -d domainanda.com -d www.domainanda.com</div><button class="copy-btn"
                        onclick="cp('c21',this)">Salin</button>
                </div>
                <div class="info-box" style="margin-bottom:12px;">
                    <p style="font-size:12px;color:#0c4a6e;margin:0;">Ganti <code
                            style="background:#e0f2fe;padding:1px 6px;border-radius:4px;font-family:'JetBrains Mono'">domainanda.com</code>
                        dengan domain asli.</p>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide no-page-break">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">22</div>
                    <div>
                        <div class="step-title">Setup LiveKit Cloud</div>
                        <div class="step-sub">Platform video real-time untuk fitur proctoring ujian</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Langkah Pendaftaran</div>
                    <div style="display:flex;flex-direction:column;gap:9px;">
                        <div style="display:flex;align-items:flex-start;gap:11px;">
                            <div
                                style="width:22px;height:22px;background:#e0eaff;color:#4a51e8;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">
                                1</div><span style="font-size:13px;color:#475569;">Buka website <strong
                                    style="color:#1e293b;">livekit.io</strong>, kemudian Register atau Login akun</span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:11px;">
                            <div
                                style="width:22px;height:22px;background:#e0eaff;color:#4a51e8;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">
                                2</div><span style="font-size:13px;color:#475569;">Klik <strong
                                    style="color:#1e293b;">Create Project</strong>, lalu pilih <strong
                                    style="color:#1e293b;">Cloud Project</strong></span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:11px;">
                            <div
                                style="width:22px;height:22px;background:#e0eaff;color:#4a51e8;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">
                                3</div><span style="font-size:13px;color:#475569;">Masuk ke menu <strong
                                    style="color:#1e293b;">Settings</strong> pada proyek yang baru dibuat</span>
                        </div>
                        <div style="display:flex;align-items:flex-start;gap:11px;">
                            <div
                                style="width:22px;height:22px;background:#e0eaff;color:#4a51e8;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0;">
                                4</div><span style="font-size:13px;color:#475569;">Salin nilai: <strong
                                    style="color:#1e293b;">WebSocket URL</strong>, <strong style="color:#1e293b;">API
                                    Key</strong>, dan <strong style="color:#1e293b;">API Secret</strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">23</div>
                    <div>
                        <div class="step-title">Contoh Konfigurasi LiveKit</div>
                        <div class="step-sub">Format nilai LiveKit yang dimasukkan ke file .env</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c23"><span class="ck">LIVEKIT_URL</span>=<span
                            class="cv">wss://namaproject.livekit.cloud</span>
                        <span class="ck">LIVEKIT_API_KEY</span>=<span class="cv">YOUR_API_KEY</span>
                        <span class="ck">LIVEKIT_API_SECRET</span>=<span class="cv">YOUR_API_SECRET</span>
                    </div><button class="copy-btn" onclick="cp('c23',this)">Salin</button>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">24</div>
                    <div>
                        <div class="step-title">Testing Queue Database</div>
                        <div class="step-sub">Pastikan antrian pekerjaan berjalan dengan benar</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c24"><span class="cm"># Buat tabel queue (jika belum ada)</span>
                        php artisan queue:table
                        php artisan migrate

                        <span class="cm"># Uji coba worker queue secara manual</span>
                        php artisan queue:work
                    </div><button class="copy-btn" onclick="cp('c24',this)">Salin</button>
                </div>
            </div>
            <div class="step-card wide no-page-break">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">25</div>
                    <div>
                        <div class="step-title">Optimasi Recording Video</div>
                        <div class="step-sub">Rekomendasi konfigurasi server untuk performa rekaman</div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                    <div class="card">
                        <div class="card-title">Spesifikasi Minimum Server</div>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;"><span
                                    style="font-size:12px;color:#64748b;">Penyimpanan</span><span
                                    style="font-size:12px;font-weight:600;color:#1e293b;">SSD atau NVMe</span></div>
                            <div style="height:1px;background:#e2e8f0;"></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;"><span
                                    style="font-size:12px;color:#64748b;">RAM Minimum</span><span
                                    style="font-size:12px;font-weight:600;color:#1e293b;">8 GB</span></div>
                            <div style="height:1px;background:#e2e8f0;"></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;"><span
                                    style="font-size:12px;color:#64748b;">CPU Minimum</span><span
                                    style="font-size:12px;font-weight:600;color:#1e293b;">4 Core</span></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-title">Tips Rekaman Stabil</div>
                        <div style="display:flex;flex-direction:column;gap:8px;font-size:12px;color:#475569;">
                            <div style="display:flex;gap:8px;align-items:flex-start;">
                                <div class="dot"></div><span>Gunakan FFmpeg untuk kompresi video</span>
                            </div>
                            <div style="display:flex;gap:8px;align-items:flex-start;">
                                <div class="dot"></div><span>Pastikan kapasitas storage mencukupi</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="page-break-before"></div>
            <div class="step-card wide">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                    <div class="step-num">26</div>
                    <div>
                        <div class="step-title">Optimasi Laravel Production</div>
                        <div class="step-sub">Cache konfigurasi, route, dan view untuk performa optimal</div>
                    </div>
                </div>
                <div style="position:relative">
                    <div class="code-block" id="c26"><span class="cm"># Bersihkan semua cache lama</span>
                        php artisan optimize:clear

                        <span class="cm"># Cache konfigurasi, route, dan view</span>
                        php artisan config:cache
                        php artisan route:cache
                        php artisan view:cache
                    </div><button class="copy-btn" onclick="cp('c26',this)">Salin</button>
                </div>
            </div>
        </div><!-- /step-grid -->

        <!-- ======= FOOTER ======= -->
        <div
            style="border-top:1px solid #e2e8f0;padding-top:28px;display:flex;flex-direction:column;gap:16px;align-items:flex-start;">
            <div>
                <div style="font-size:15px;font-weight:700;color:#0f172a;">
                    PROCBT
                </div>
                <div style="font-size:11.5px;color:#94a3b8;margin-top:2px;">
                    Dokumentasi
                    Instalasi
                    Server
                </div>
            </div>
            <div style="text-align:left;">
                <div style="font-size:11px;color:#94a3b8;">
                    Laravel
                    &middot;
                    Nginx
                    &middot;
                    PHP
                    8.3
                    &middot;
                    PostgreSQL
                    &middot;
                    LiveKit
                </div>
                <div style="font-size:11px;color:#cbd5e1;margin-top:2px;">
                    Ubuntu
                    Server
                    22.04
                    /
                    24.04
                    LTS
                </div>
            </div>
        </div>

    </div>
    <!-- /wrapper -->

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="no-print"
        style="position:fixed;bottom:30px;right:30px;width:56px;height:56px;background:linear-gradient(135deg,#6172f3,#0ea5e9);color:white;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 10px 25px -5px rgba(97,114,243,0.4);z-index:100;transition:transform 0.2s;"
        onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 6 2 18 2 18 9">
            </polyline>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2">
            </path>
            <rect x="6" y="14" width="12" height="8">
            </rect>
        </svg>
    </button>

    <script>
        // Build TOC
        const tocData = [
            [1, 'Update Ubuntu Server'], [2, 'Install Nginx'], [3, 'Install PHP 8.3'],
            [4, 'Konfigurasi Upload'], [5, 'Install Composer'], [6, 'Install PostgreSQL'],
            [7, 'Membuat Database PostgreSQL'], [8, 'Install Node.js'], [9, 'Install FFmpeg'],
            [10, 'Install Supervisor'], [11, 'Clone Repository PROCBT'], [12, 'Install Dependency Laravel'],
            [13, 'Copy File Environment'], [14, 'Konfigurasi File .env'], [15, 'Generate APP Key'],
            [16, 'Migrasi Database'], [17, 'Login Default PROCBT'], [18, 'Permission Laravel'],
            [19, 'Konfigurasi Supervisor Queue'], [20, 'Konfigurasi Nginx'], [21, 'Install SSL HTTPS'],
            [22, 'Setup LiveKit Cloud'], [23, 'Contoh Konfigurasi LiveKit'], [24, 'Testing Queue Database'],
            [25, 'Optimasi Recording Video'], [26, 'Optimasi Laravel Production'],
        ];
        const toc = document.getElementById('toc');
        tocData.forEach(([n, t]) => {
            toc.innerHTML += `<div class="toc-row"><div class="toc-n">${n}</div><div class="toc-t">${t}</div></div>`;
        });

        // Copy code
        function cp(id, btn) {
            const el = document.getElementById(id);
            const tmp = document.createElement('div');
            tmp.innerHTML = el.innerHTML;
            const b = tmp.querySelector('button');
            if (b) b.remove();
            const text = tmp.innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Disalin';
                btn.classList.add('copied');
                setTimeout(() => { btn.textContent = 'Salin'; btn.classList.remove('copied'); }, 2000);
            });
        }

        // ─── CODE BLOCK DATA ─────────────────────────────────────────────────
        // All code content stored here so the editor cannot break it.
        const CODE = {
            c1: `<span class="cm"># Perbarui paket sistem</span>
sudo apt update && sudo apt upgrade -y
sudo timedatectl set-timezone Asia/Jakarta`,
            c2: `sudo apt install nginx -y

                        sudo systemctl enable nginx
                        sudo systemctl start nginx
                        sudo systemctl status nginx`,
            c3: `<span class="cm"># Tambahkan repositori PPA ondrej/php</span>
                        sudo add-apt-repository ppa:ondrej/php -y
                        sudo apt update

                        <span class="cm"># Install PHP 8.3 dan ekstensi</span>
                        sudo apt install -y \\
                        php8.3 \\
                        php8.3-fpm \\
                        php8.3-cli \\
                        php8.3-pgsql \\
                        php8.3-mbstring \\
                        php8.3-xml \\
                        php8.3-curl \\
                        php8.3-zip \\
                        php8.3-bcmath \\
                        php8.3-gd \\
                        php8.3-intl \\
                        php8.3-soap \\
                        php8.3-imagick \\
                        unzip \\
                        curl \\
                        git`,
            c4a: `sudo nano /etc/php/8.3/fpm/php.ini`,
            c4b: `upload_max_filesize = 1024M
                        post_max_size = 1024M
                        memory_limit = 2048M
                        max_execution_time = 3600
                        max_input_time = 3600
                        max_file_uploads = 100`,
            c4c: `sudo systemctl restart php8.3-fpm`,
            c5: `curl -sS https://getcomposer.org/installer | php
                        sudo mv composer.phar /usr/local/bin/composer
                        composer --version`,
            c6: `sudo apt install postgresql postgresql-contrib -y

                        sudo systemctl enable postgresql
                        sudo systemctl start postgresql`,
            c7: `<span class="cm"># Masuk ke konsol PostgreSQL sebagai
                            superuser</span>
                        sudo -u postgres psql

                        <span class="cm"># Buat database, user, dan berikan hak akses</span>
                        CREATE DATABASE procbt;
                        CREATE USER procbt_user WITH PASSWORD 'passwordku';
                        GRANT ALL PRIVILEGES ON DATABASE procbt TO procbt_user;

                        <span class="cm"># Keluar dari konsol</span>
                        \\q`,
            c8: `<span class="cm"># Tambahkan repositori Node.js 22.x</span>
                        curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
                        sudo apt install nodejs -y
                        node -v
                        npm -v`,
            c9: `sudo apt install ffmpeg -y
                        ffmpeg -version`,
            c10: `sudo apt install supervisor -y

                        sudo systemctl enable supervisor
                        sudo systemctl start supervisor`,
            c11: `cd /var/www/html
                        git clone https://github.com/brehehe/cbt-new.git procbt
                        cd procbt`,
            c12: `composer install
                        npm install
                        npm run build`,
            c13: `cp .env.example .env`,
            c14: `<span class="ck">APP_NAME</span>=<span class="cv">"CBT Procbt"</span>
<span class="ck">APP_ENV</span>=<span class="cv">production</span>
<span class="ck">APP_KEY</span>=<span class="cv">base64:YOUR_APP_KEY</span>
<span class="ck">APP_DEBUG</span>=<span class="cv">false</span>
<span class="ck">APP_URL</span>=<span class="cv">https://domainanda.com</span>

<span class="ck">APP_LOCALE</span>=<span class="cv">id</span>
<span class="ck">APP_FALLBACK_LOCALE</span>=<span class="cv">id</span>
<span class="ck">APP_FAKER_LOCALE</span>=<span class="cv">id_ID</span>

<span class="ck">LOG_CHANNEL</span>=<span class="cv">stack</span>
<span class="ck">LOG_STACK</span>=<span class="cv">single</span>
<span class="ck">LOG_LEVEL</span>=<span class="cv">info</span>

<span class="ck">DB_CONNECTION</span>=<span class="cv">pgsql</span>
<span class="ck">DB_HOST</span>=<span class="cv">127.0.0.1</span>
<span class="ck">DB_PORT</span>=<span class="cv">5432</span>
<span class="ck">DB_DATABASE</span>=<span class="cv">nama_database</span>
<span class="ck">DB_USERNAME</span>=<span class="cv">username_database</span>
<span class="ck">DB_PASSWORD</span>=<span class="cv">password_database</span>

<span class="ck">SESSION_DRIVER</span>=<span class="cv">database</span>
<span class="ck">SESSION_LIFETIME</span>=<span class="cv">120</span>
<span class="ck">SESSION_ENCRYPT</span>=<span class="cv">false</span>
<span class="ck">SESSION_PATH</span>=<span class="cv">/</span>
<span class="ck">SESSION_DOMAIN</span>=<span class="cv">null</span>

<span class="ck">BROADCAST_CONNECTION</span>=<span class="cv">log</span>
<span class="ck">FILESYSTEM_DISK</span>=<span class="cv">public</span>

<span class="ck">QUEUE_CONNECTION</span>=<span class="cv">database</span>
<span class="ck">CACHE_STORE</span>=<span class="cv">database</span>

<span class="ck">MAIL_MAILER</span>=<span class="cv">log</span>
<span class="ck">MAIL_FROM_ADDRESS</span>=<span class="cv">"noreply@domainanda.com"</span>
<span class="ck">MAIL_FROM_NAME</span>=<span class="cv">"\${APP_NAME}"</span>

<span class="ck">VITE_APP_NAME</span>=<span class="cv">"\${APP_NAME}"</span>

<span class="ck">TALLSTACKUI_PREFIX</span>=<span class="cv">"ts-"</span>
<span class="ck">APP_SLUG_NAME</span>=<span class="cv">"login_universitas"</span>

<span class="ck">IMPORT_QUESTION</span>=<span class="cv">true</span>
<span class="ck">QUESTION_DUMMY</span>=<span class="cv">false</span>
<span class="ck">QUESTION_PACKAGE</span>=<span class="cv">Lite</span>

<span class="ck">SEB_ENABLED</span>=<span class="cv">true</span>
<span class="ck">SEB_REQUIRE_ALL</span>=<span class="cv">false</span>
<span class="ck">SEB_BROWSER_EXAM_KEY</span>=<span class="cv">YOUR_SEB_BROWSER_EXAM_KEY</span>
<span class="ck">SEB_REQUIRE_BEK</span>=<span class="cv">false</span>
<span class="ck">SEB_CONFIG_KEY</span>=<span class="cv">YOUR_SEB_CONFIG_KEY</span>
<span class="ck">SEB_QUIT_PASSWORD</span>=<span class="cv">YOUR_SEB_PASSWORD</span>

<span class="ck">CLEAR_SESSION_PASSWORD</span>=<span class="cv">procbt</span>
<span class="ck">OCTANE_SERVER</span>=<span class="cv">frankenphp</span>

<span class="ck">LIMIT_QUESTION_VIEW</span>=<span class="cv">false</span>
<span class="ck">LIMIT_QUESTION_COUNT</span>=<span class="cv">5</span>

<span class="ck">LIVEKIT_URL</span>=<span
    class="cv">wss://your-livekit-domain.livekit.cloud</span>
<span class="ck">LIVEKIT_API_KEY</span>=<span class="cv">YOUR_LIVEKIT_API_KEY</span>
<span class="ck">LIVEKIT_API_SECRET</span>=<span class="cv">YOUR_LIVEKIT_API_SECRET</span>
<span class="ck">LOGIN_IS_CREDENTIALS</span>=<span class="cv">true</span>
<span class="ck">NEW_TEMPLATE</span>=<span class="cv">false</span>`,
            c15: `php artisan key:generate`,
            c16: `php artisan migrate:fresh --seed`,
            c18: `sudo chown -R www-data:www-data /var/www/html/procbt

                        sudo chmod -R 775 storage

                        sudo chmod -R 775 bootstrap/cache`,
            c19a: `sudo nano
                        /etc/supervisor/conf.d/procbt-worker.conf`,
            c19b: `[program:procbt-worker]
                        process_name=%(program_name)s_%(process_num)02d
                        command=php /var/www/html/procbt/artisan queue:work database --sleep=3 --tries=3 --timeout=3600
                        autostart=true
                        autorestart=true
                        user=www-data
                        numprocs=2
                        redirect_stderr=true
                        stdout_logfile=/var/www/html/procbt/storage/logs/worker.log
                        stopwaitsecs=3600`,
            c19c: `sudo supervisorctl reread
                        sudo supervisorctl update
                        sudo supervisorctl start procbt-worker:*`,
            c20a: `sudo nano
                        /etc/nginx/sites-available/procbt`,
            c20b: `server {
                        listen 80;
                        server_name domainanda.com www.domainanda.com;
                        root /var/www/html/procbt/public;
                        index index.php;

                        client_max_body_size 1024M;
                        proxy_read_timeout 3600;
                        proxy_connect_timeout 3600;
                        proxy_send_timeout 3600;
                        fastcgi_read_timeout 3600;

                        location / {
                        try_files $uri $uri/ /index.php?$query_string;
                        }

                        location ~ \\.php$ {
                        include snippets/fastcgi-php.conf;
                        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
                        }

                        location ~ /\\.(?!well-known).* {
                        deny all;
                        }
                        }`,
            c20c: `sudo ln -s /etc/nginx/sites-available/procbt
                        /etc/nginx/sites-enabled/
                        sudo nginx -t
                        sudo systemctl restart nginx`,
            c21: `sudo apt install certbot python3-certbot-nginx -y
                        sudo certbot --nginx -d domainanda.com -d www.domainanda.com`,
            c23: `<span class="ck">LIVEKIT_URL</span>=<span
                            class="cv">wss://namaproject.livekit.cloud</span>
                        <span class="ck">LIVEKIT_API_KEY</span>=<span class="cv">YOUR_API_KEY</span>
                        <span class="ck">LIVEKIT_API_SECRET</span>=<span class="cv">YOUR_API_SECRET</span>`,
            c24: `<span class="cm"># Buat tabel queue (jika belum ada)</span>
                        php artisan queue:table
                        php artisan migrate

                        <span class="cm"># Uji coba worker queue secara manual</span>
                        php artisan queue:work`,
            c26: `<span class="cm"># Bersihkan semua cache lama</span>
                        php artisan optimize:clear

                        <span class="cm"># Cache konfigurasi, route, dan view</span>
                        php artisan config:cache
                        php artisan route:cache
                        php artisan view:cache`,
        };

        // Fill code blocks from data
        Object.entries(CODE).forEach(([id, html]) => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = html;
        });

        // ─── AUTO-DEDENT (editor-proof) ─────────────────────────────────────────
        // Runs on every page load. Fixes two problems caused by code editors:
        //   1. HTML indentation leaking into <pre>-like code blocks
        //   2. <span> tags split across lines by the editor's word-wrap
        (function dedentCodeBlocks() {
            document.querySelectorAll('.code-block').forEach(el => {
                let html = el.innerHTML;

                // Step 1 – rejoin lines where a <span> tag was split by the editor.
                // Pattern: a line ends mid-tag (no closing ">") and the next line
                // has only indentation + the rest of the tag attributes.
                // e.g.  <span class="cv">"CBT
                //            Procbt"</span>
                // → join them with a space so the value stays on one visual line.
                html = html.replace(/(<span[^>]*)(\n\s+)([^<\n]*<\/span>)/g, (_, open, _ws, rest) => {
                    // re-join the two pieces, removing the newline+indent between them
                    return open + ' ' + rest.trim();
                });

                // Also fix split span opening tags: <span\n    class="...">
                html = html.replace(/<span\n\s+(class="[^"]*">)/g, '<span $1');

                // Step 2 – strip common HTML indentation from subsequent lines.
                const lines = html.split('\n');
                if (lines.length <= 1) { el.innerHTML = html.trim(); return; }

                const rest = lines.slice(1);
                const nonEmpty = rest.filter(l => l.trim() !== '');
                if (!nonEmpty.length) { el.innerHTML = html.trim(); return; }

                const minIndent = Math.min(...nonEmpty.map(l => (l.match(/^(\s*)/) || ['', ''])[1].length));

                const fixed = [lines[0], ...rest.map(l => minIndent > 0 ? l.slice(minIndent) : l)];
                el.innerHTML = fixed.join('\n').replace(/^\n+/, '').replace(/\n+$/, '');
            });
        })();
    </script>
</body>

</html>