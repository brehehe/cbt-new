<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBT - Computer Based Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Watermark Logo Styles */
        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 40%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 1;
            pointer-events: none;
            width: 500px;
            height: auto;
        }

        /* User Info Watermark */
        .watermark-user {
            position: fixed;
            bottom: 0px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 12px;
            color: #666;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            backdrop-filter: blur(10px);
        }

        /* Alternative: Corner watermark */
        .watermark-corner {
            position: fixed;
            top: 20px;
            right: 20px;
            opacity: 0.3;
            z-index: 1;
            pointer-events: none;
            width: 80px;
            height: auto;
        }

        /* Content wrapper to ensure watermark doesn't interfere */
        .content-wrapper {
            position: relative;
            z-index: 10;
        }

        /* Disable text selection */
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Re-enable selection for inputs and textareas */
        input,
        textarea {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }

        /* Blackout overlay */
        #blackout-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.98);
            z-index: 9999999;
            color: white;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: sans-serif;
            backdrop-filter: blur(20px);
            text-align: center;
        }

        .unlock-button {
            margin-top: 20px;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .unlock-button:hover {
            background: #2563eb;
        }
    </style>
    @livewireStyles
    @stack('styles')
</head>

<body class="min-h-screen bg-gray-50">
    <div id="blackout-overlay">
        <h2 class="text-3xl font-bold mb-4">⚠️ Ujian Sedang Berlangsung</h2>
        <p class="text-xl text-gray-300">Dilarang meninggalkan halaman ujian atau mengambil screenshot!</p>
        <p class="mt-4 text-sm text-gray-500">Kembali ke halaman untuk melanjutkan.</p>
        <button class="unlock-button mt-8" onclick="this.parentElement.style.display='none'">Klik untuk Kembali</button>
    </div>
    <div class="watermark-logo">
        <img src="{{ $companyData?->logo ? asset('storage/' . $companyData->logo) : asset('asset/img/logo-procbt.png') }}"
            alt="Watermark Logo" style="width: 750px; height: 150px" />
        {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
    </div>
    @yield('content')
    @livewireScripts

    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('copy', event => event.preventDefault());
        document.addEventListener('cut', event => event.preventDefault());

        // Blackout on blur
        window.addEventListener('blur', () => {
            document.getElementById('blackout-overlay').style.display = 'flex';
        });
        window.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                document.getElementById('blackout-overlay').style.display = 'flex';
            }
        });

        // HYPER-AGGRESIVE SHORTCUT DETECTION
        window.addEventListener('keydown', function (e) {
            // Pengecualian khusus: Izinkan Refresh
            if (
                ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 82) ||
                ((e.ctrlKey || e.metaKey) && e.keyCode === 82) ||
                e.keyCode === 116
            ) {
                return true;
            }

            // Immediate blackout on Cmd+Shift or Ctrl+Shift (screenshot combination)
            if ((e.metaKey || e.ctrlKey) && e.shiftKey && e.keyCode !== 82) { // 82 is R for refresh
                document.getElementById('blackout-overlay').style.display = 'flex';
                e.preventDefault();
                return false;
            }

            // Disable Ctrl+C, V, U, I, J, F12, PrintScreen
            if (
                (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 73 || e.keyCode === 74)) ||
                (e.metaKey && (e.keyCode === 67 || e.keyCode === 86)) ||
                e.keyCode === 123 || e.keyCode === 44 || e.key === 'PrintScreen'
            ) {
                e.preventDefault();
                if (e.key === 'PrintScreen' || e.keyCode === 44) {
                    navigator.clipboard.writeText('');
                }
                return false;
            }
        }, true);

        // Anti-debugger
        setInterval(function () {
            (function (a) { return (function (a) { return (Function('debugger'))(); }(a)); }(function () { }));
        }, 1000);

        // Disable autocomplete on all inputs
        document.querySelectorAll('input, form').forEach(el => {
            el.setAttribute('autocomplete', 'off');
        });
    </script>

    @stack('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>