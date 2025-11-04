<!DOCTYPE html>
<html lang="en" class="h-full">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Peraturan Ujian - CBT System</title>
        <script src="https://cdn.tailwindcss.com"></script>
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
        </style>
    </head>

    <body class="h-full bg-gray-50">

        {{-- <div class="watermark-logo">
            <img src="https://ikmb.ac.id/wp-content/uploads/2020/04/Screen-Shot-2020-04-14-at-13.16.27.png" alt="Watermark Logo" style="width: 750px; height: 150px" />
            {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
        </div> --}}
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
</script>
    </body>

</html>
