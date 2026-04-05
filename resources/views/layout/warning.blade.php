<!DOCTYPE html>
<html lang="en" class="h-full">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Peraturan Ujian - CBT System</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <style>
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
    </head>

    <body class="h-full bg-gray-50">

        <div class="watermark-logo">
            <img src="{{ !empty($companyData?->logo) ? asset('storage/'.$companyData->logo) : asset('asset/img/logo-procbt.png') }}" alt="Watermark Logo" style="width: 750px; height: 150px" />
            {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
        </div>
        @yield('content')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
    const consentCheckbox = document.getElementById('consent');
    const startButton = document.getElementById('startExam');
    const cameraStatus = document.getElementById('cameraStatus');
    let cameraActive = false;

    // Fungsi inisialisasi kamera
    async function initCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: true });
            const video = document.getElementById('cameraPreview');
            video.srcObject = stream;
            cameraActive = true;
            cameraStatus.textContent = "✅ Kamera aktif dan berfungsi.";
            cameraStatus.classList.remove("text-gray-500");
            cameraStatus.classList.add("text-green-600");
            updateButtonState();
        } catch (err) {
            console.error('Error accessing camera:', err);
            cameraActive = false;
            cameraStatus.textContent = "❌ Kamera tidak dapat diakses. Harap izinkan akses kamera sebelum melanjutkan.";
            cameraStatus.classList.remove("text-gray-500");
            cameraStatus.classList.add("text-red-600");
            updateButtonState();
        }
    }

    // Fungsi cek kondisi tombol
    function updateButtonState() {
        startButton.disabled = !(consentCheckbox.checked && cameraActive);
    }

    // Event listener untuk checkbox
    consentCheckbox.addEventListener('change', updateButtonState);

    // Jalankan kamera saat halaman dimuat
    window.addEventListener('DOMContentLoaded', initCamera);


        // Disable copy/selection
        @if(!Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin']))
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('copy', event => event.preventDefault());
        document.addEventListener('cut', event => event.preventDefault());
        @endif


        // HYPER-AGGRESIVE SHORTCUT DETECTION (Capture Phase)
        window.addEventListener('keydown', function(e) {
            @if(Auth::user()->hasRole(['Admin', 'Super Admin', 'Pengawas', 'admin']))
                return true;
            @endif

            // Pengecualian khusus: Izinkan Refresh Halaman (Cmd+Shift+R atau Ctrl+Shift+R atau F5 atau Cmd+R)
            if (
                ((e.ctrlKey || e.metaKey) && e.shiftKey && e.keyCode === 82) || // Cmd/Ctrl+Shift+R
                ((e.ctrlKey || e.metaKey) && e.keyCode === 82) || // Cmd/Ctrl+R
                e.keyCode === 116 // F5
            ) {
                return true; 
            }


            // Disable Ctrl+C, Ctrl+V, Ctrl+U, F12, PrintScreen
            if (
                (e.ctrlKey && (e.keyCode === 67 || e.keyCode === 86 || e.keyCode === 85 || e.keyCode === 73 || e.keyCode === 74)) ||
                (e.metaKey && (e.keyCode === 67 || e.keyCode === 86)) ||
                e.keyCode === 123 || e.keyCode === 44
            ) {
                e.preventDefault();
                return false;
            }
        }, true);

        // Anti-debugger
        setInterval(function(){
            (function(a){return (function(a){return (Function('debugger'))();}(a));}(function(){}));
        }, 1000);

        // Disable autocomplete on all inputs
        document.querySelectorAll('input, form').forEach(el => {
            el.setAttribute('autocomplete', 'off');
        });
    </script>
    </body>

</html>
