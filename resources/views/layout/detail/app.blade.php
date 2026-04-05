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
            input, textarea {
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
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.95);
                z-index: 99999;
                color: white;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                font-family: sans-serif;
                backdrop-filter: blur(10px);
            }
        </style>
        @livewireStyles
        @stack('styles')
    </head>

    <body class="min-h-screen bg-gray-50">
        <div class="watermark-logo">
            <img src="{{ $companyData?->logo ? asset('storage/'.$companyData->logo) : asset('asset/img/logo-procbt.png') }}" alt="Watermark Logo" style="width: 750px; height: 150px" />
            {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
        </div>
        @yield('content')
        @livewireScripts
        
        <script>
            document.addEventListener('contextmenu', event => event.preventDefault());
            document.addEventListener('copy', event => event.preventDefault());
            document.addEventListener('cut', event => event.preventDefault());

            // Blackout on blur

            // HYPER-AGGRESIVE SHORTCUT DETECTION (Capture Phase)
            window.addEventListener('keydown', function(e) {
                // Pengecualian khusus: Izinkan Refresh Halaman (Cmd+Shift+R atau Ctrl+Shift+R atau F5 atau Cmd+R)
                if (
                    ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 82) || // Cmd/Ctrl+Shift+R
                    ((e.ctrlKey || e.metaKey) && e.keyCode === 82) || // Cmd/Ctrl+R
                    e.keyCode === 116 // F5
                ) {
                    return true; 
                }


                // Disable Ctrl+C, Ctrl+V, Ctrl+U, F12
                if (
                    (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 73 || e.keyCode === 74)) ||
                    e.keyCode === 123
                ) {
                    
                    if (e.ctrlKey || e.metaKey || e.keyCode === 123 || e.keyCode === 44) {
                        e.preventDefault();
                    }
                    return false;
                }
            }, true);

            // Disable autocomplete on all inputs
            document.querySelectorAll('input, form').forEach(el => {
                el.setAttribute('autocomplete', 'off');
            });
        </script>

        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

</html>
