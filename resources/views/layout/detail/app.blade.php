<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CBT - Computer Based Test</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '#1e40af', // blue-700
                            secondary: '#3b82f6', // blue-500
                            accent: '#60a5fa', // blue-400
                        }
                    }
                }
            }
        </script>
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
        </style>
        @livewireStyles
        @stack('styles')
    </head>

    <body class="min-h-screen bg-gray-50">
        <div class="watermark-logo">
            <img src="https://ikmb.ac.id/wp-content/uploads/2020/04/Screen-Shot-2020-04-14-at-13.16.27.png" alt="Watermark Logo" style="width: 750px; height: 150px" />
            {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
        </div>
        @yield('content')
        @livewireScripts
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

</html>
