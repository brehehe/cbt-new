@php
    use App\Models\Company\Company;

    $company = Company::getCached(); // ambil 1 data pertama

    $primary = $company->color_primary;
    $secondary = $company->color_secondary;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PRO-CBT</title>

    <link href="{{ asset('fonts/plus-jakarta-sans.css') }}" rel="stylesheet">


    <link rel="icon" type="image/x-icon" href="{{ (!empty($company?->logo_potrait) && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_potrait)) ? asset('storage/' . $company->logo_potrait) : asset('favicon.ico') }}" />
    <!-- Add Selectize CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/selectize/css/selectize.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">
    <!-- Summernote Lite CSS -->
    <link href="{{ asset('vendor/summernote/summernote-lite.min.css') }}" rel="stylesheet">

    {{-- file pond --}}
    <link href="{{ asset('vendor/filepond/filepond.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}"
        rel="stylesheet" />
    <style>
        .shadow-top {
            box-shadow: 0 -1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        :root {
            --primary:
                {{ $primary }}
            ;
            --secondary:
                {{ $secondary }}
            ;
        }

        /* Watermark Logo Styles */
        .watermark-logo {
            position: fixed;
            top: 50%;
            left: 35%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 1;
            pointer-events: none;
            width: 500px;
            height: auto;
        }

        /* User Info Watermark */
        /* User Info Watermark */
        .watermark-user {
            position: fixed;
            bottom: 6px;
            right: 16px;
            background: rgba(255, 255, 255, 0.92);
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            color: #666;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            z-index: 30;
            backdrop-filter: blur(10px);
            pointer-events: none;
        }

        @media (max-width: 640px) {
            .watermark-user {
                font-size: 10px;
                padding: 4px 8px;
                bottom: 4px;
                right: 8px;
                opacity: 0.75;
            }
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
        /* body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        } */

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
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script>
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        try { localStorage.setItem('flux.appearance', 'light'); } catch(e){}
    </script>
</head>

<body class="bg-white">

    {{-- <div id="blackout-overlay">
        <h2 class="text-3xl font-bold mb-4">⚠️ Ujian Sedang Berlangsung</h2>
        <p class="text-xl text-gray-300">Dilarang meninggalkan halaman ujian atau mengambil screenshot!</p>
        <p class="mt-4 text-sm text-gray-500">Kembali ke halaman untuk melanjutkan.</p>
        <button class="unlock-button mt-8" onclick="this.parentElement.style.display='none'">Klik untuk Kembali</button>
    </div> --}}

    {{-- <div class="watermark-logo">
        <img src="{{ asset('asset/img/logo-procbt.png') }}" alt="Watermark Logo" style="width: 750px; height: 150px" />
        {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
    </div> --}}

    @auth   
        <div class="watermark-user">
            <div class="flex items-center space-x-2">
                <i class="fas fa-user text-blue-500"></i>
                <span>{{ Auth::user()->name }}</span>
                <span class="text-gray-400">|</span>
                <span class="text-xs">{{ now()->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    @endauth

    @include('layout.navbar')
    @include('layout.sidebar')

    <main id="main-content" class="ml-64 h-screen flex flex-col">
        <!-- Wrapper konten utama dan footer -->
        <div class="flex flex-col flex-grow mt-16 ">

            <!-- Konten Utama -->
            <div class="h-full flex-grow p-5">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="p-4 bg-white border-t text-sm text-gray-500 text-center">
                © 2025 Your PRO CBT. All rights reserved.
            </footer>

        </div>
    </main>

    @filepondScripts
    @livewireScripts
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Add jQuery -->
    <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>

    <!-- Add Selectize JS -->
    <script src="{{ asset('vendor/selectize/js/selectize.min.js') }}"></script>
    <script src="{{ asset('vendor/flatpickr/flatpickr.min.js') }}"></script>
    <!-- Summernote Lite JS -->
    <script src="{{ asset('vendor/summernote/summernote-lite.min.js') }}"></script>

    <!-- HTML5-QRCode Scanner -->
    <script src="{{ asset('vendor/html5-qrcode/html5-qrcode.min.js') }}"></script>

    <!-- file pond -->
    <script src="{{ asset('vendor/filepond-plugin-image-preview/filepond-plugin-image-preview.js') }}"></script>
    <script src="{{ asset('vendor/filepond/filepond.js') }}"></script>
    <script>
        // Register the plugin
        FilePond.registerPlugin(FilePondPluginImagePreview);

        // ... FilePond initialisation code here
    </script>

    <script>
        // Security Logging System
        async function logSecurityEvent(eventType, description, metadata = {}) {
            try {
                const response = await fetch('{{ route("admin.security.log") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        event_type: eventType,
                        description: description,
                        metadata: metadata
                    })
                });
                const data = await response.json();
                console.log('Security log status:', data.status);
            } catch (error) {
                console.error('Failed to send security log:', error);
            }
        }

        // Rate limiting for logs
        const logThrottle = {};
        function throttledLog(type, desc, meta = {}) {
            const now = Date.now();
            if (!logThrottle[type] || now - logThrottle[type] > 5000) { // Limit to 1 log per 5s per type
                logThrottle[type] = now;
                logSecurityEvent(type, desc, meta);
            }
        }

        // document.addEventListener('contextmenu', event => {
        //     event.preventDefault();
        //     throttledLog('security.right_click', 'Percobaan klik kanan diblokir');
        // });

        // document.addEventListener('copy', event => {
        //     event.preventDefault();
        //     throttledLog('security.copy', 'Percobaan menyalin konten (copy) diblokir');
        // });

        // document.addEventListener('cut', event => {
        //     event.preventDefault();
        //     throttledLog('security.cut', 'Percobaan memotong konten (cut) diblokir');
        // });

        // Blackout on blur
        let blurStartTime = null;
        window.addEventListener('blur', () => {
            const overlay = document.getElementById('blackout-overlay');
            if (overlay) overlay.style.display = 'flex';
            blurStartTime = Date.now();
            throttledLog('security.blur', 'Browser kehilangan fokus (Window Blur)');
        });

        window.addEventListener('visibilitychange', () => {
            const overlay = document.getElementById('blackout-overlay');
            if (document.visibilityState === 'hidden') {
                if (overlay) overlay.style.display = 'flex';
                throttledLog('security.visibility_hidden', 'Tab disembunyikan (Visibility Hidden)');
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
            // Ignore if the key being pressed is a modifier (Shift, Ctrl, Meta, Alt) or R (Refresh)
            if ((e.metaKey || e.ctrlKey) && e.shiftKey && ![16, 17, 18, 91, 93, 82].includes(e.keyCode)) {
                const overlay = document.getElementById('blackout-overlay');
                if (overlay) overlay.style.display = 'flex';
                throttledLog('security.screenshot', 'Percobaan screenshot terdeteksi (Shortcut Shift+Cmd/Ctrl)');
                e.preventDefault();
                return false;
            }

            // Disable Ctrl+C, V, U, I, J, F12, PrintScreen
            /* if (
                (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 73 || e.keyCode === 74)) ||
                (e.metaKey && (e.keyCode === 67 || e.keyCode === 86)) ||
                e.keyCode === 123 || e.keyCode === 44 || e.key === 'PrintScreen'
            ) {
                let shortcut = e.key;
                if (e.ctrlKey) shortcut = 'Ctrl+' + shortcut;
                if (e.metaKey) shortcut = 'Cmd+' + shortcut;

                throttledLog('security.inspect', 'Percobaan inspect/shortcut diblokir: ' + shortcut);

                e.preventDefault();
                if (e.key === 'PrintScreen' || e.keyCode === 44) {
                    navigator.clipboard.writeText('');
                }
                return false;
            } */
        }, true);

        // Anti-debugger
        // setInterval(function () {
        //     (function (a) { return (function (a) { return (Function('debugger'))(); }(a)); }(function () { }));
        // }, 1000);

        // Disable autocomplete on all inputs
        document.querySelectorAll('input, form').forEach(el => {
            el.setAttribute('autocomplete', 'off');
        });

        // Force fresh reload if page is restored from browser Back-Forward cache (BFCache)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        // Unregister any rogue Service Worker and purge CacheStorage so fresh CSRF tokens are always served
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.getRegistrations().then(registrations => {
                for (let registration of registrations) {
                    registration.unregister();
                }
            });
            if ('caches' in window) {
                caches.keys().then(names => {
                    for (let name of names) {
                        caches.delete(name);
                    }
                });
            }
        }

        // Handle Livewire 419 (Page Expired / Session Expired after migrate:fresh) automatically without ugly browser popup
        document.addEventListener('livewire:init', () => {
            if (window.Livewire) {
                Livewire.hook('request', ({ fail }) => {
                    fail(({ status, preventDefault }) => {
                        if (status === 419) {
                            preventDefault();
                            window.location.reload();
                        }
                    });
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>