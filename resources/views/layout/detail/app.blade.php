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
        @livewireStyles
        @stack('styles')
    </head>

    <body class="min-h-screen bg-gray-50">

        @yield('content')
        @livewireScripts
        @stack('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // Timer countdown
            function updateTimer() {
                const timer = document.getElementById('timer');
                let time = timer.textContent.split(':');
                let hours = parseInt(time[0]);
                let minutes = parseInt(time[1]);
                let seconds = parseInt(time[2]);

                if (seconds > 0) {
                    seconds--;
                } else if (minutes > 0) {
                    minutes--;
                    seconds = 59;
                } else if (hours > 0) {
                    hours--;
                    minutes = 59;
                    seconds = 59;
                }

                timer.textContent =
                    hours.toString().padStart(2, '0') + ':' +
                    minutes.toString().padStart(2, '0') + ':' +
                    seconds.toString().padStart(2, '0');
            }

            setInterval(updateTimer, 1000);

            // Navigation untuk nomor soal
            document.querySelectorAll('button[class*="w-8 h-8"], button[class*="w-10 h-10"]').forEach(button => {
                button.addEventListener('click', function() {
                    // Reset semua button
                    document.querySelectorAll('button[class*="w-8 h-8"], button[class*="w-10 h-10"]').forEach(
                        btn => {
                            btn.classList.remove('ring-2', 'ring-blue-300');
                        });
                    // Tambahkan ring pada button yang diklik
                    this.classList.add('ring-2', 'ring-blue-300');
                });
            });

            // Mobile sidebar functionality
            const toggleLeftSidebar = document.getElementById('toggleLeftSidebar');
            const toggleRightSidebar = document.getElementById('toggleRightSidebar');
            const leftSidebar = document.getElementById('leftSidebar');
            const rightSidebar = document.getElementById('rightSidebar');
            const overlay = document.getElementById('overlay');
            const closeLeftSidebar = document.getElementById('closeLeftSidebar');
            const closeRightSidebar = document.getElementById('closeRightSidebar');

            function showOverlay() {
                overlay.classList.remove('hidden');
            }

            function hideOverlay() {
                overlay.classList.add('hidden');
            }

            function closeSidebars() {
                leftSidebar.classList.add('-translate-x-full');
                rightSidebar.classList.add('translate-x-full');
                hideOverlay();
            }

            toggleLeftSidebar.addEventListener('click', function() {
                rightSidebar.classList.add('translate-x-full');
                leftSidebar.classList.toggle('-translate-x-full');
                if (!leftSidebar.classList.contains('-translate-x-full')) {
                    showOverlay();
                } else {
                    hideOverlay();
                }
            });

            toggleRightSidebar.addEventListener('click', function() {
                leftSidebar.classList.add('-translate-x-full');
                rightSidebar.classList.toggle('translate-x-full');
                if (!rightSidebar.classList.contains('translate-x-full')) {
                    showOverlay();
                } else {
                    hideOverlay();
                }
            });

            closeLeftSidebar.addEventListener('click', function() {
                leftSidebar.classList.add('-translate-x-full');
                hideOverlay();
            });

            closeRightSidebar.addEventListener('click', function() {
                rightSidebar.classList.add('translate-x-full');
                hideOverlay();
            });

            overlay.addEventListener('click', closeSidebars);

            // Auto-close sidebars on window resize to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) { // lg breakpoint
                    closeSidebars();
                }
            });
        </script>
    </body>

</html>
