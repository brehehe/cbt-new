<!DOCTYPE html>
<html lang="en" class="h-full">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Peraturan Ujian - CBT System</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    </head>

    <body class="h-full bg-gray-50">

        @yield('content')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Initialize camera
            async function initCamera() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        video: true
                    });
                    const video = document.getElementById('cameraPreview');
                    video.srcObject = stream;
                } catch (err) {
                    console.error('Error accessing camera:', err);
                    alert('Tidak dapat mengakses kamera. Pastikan kamera terhubung dan izin diberikan.');
                }
            }

            // Enable start button when consent is given
            const consentCheckbox = document.getElementById('consent');
            const startButton = document.getElementById('startExam');

            consentCheckbox.addEventListener('change', function() {
                startButton.disabled = !this.checked;
            });

            // Start camera when page loads
            initCamera();
        </script>
    </body>

</html>
