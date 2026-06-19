<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ $setting?->favicon_apple_touch_icon && Storage::disk('public')->exists($setting?->favicon_apple_touch_icon) ? asset('storage/' . $setting?->favicon_apple_touch_icon) : asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ $setting?->favicon_32 && Storage::disk('public')->exists($setting?->favicon_32) ? asset('storage/' . $setting?->favicon_32) : asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ $setting?->favicon_16 && Storage::disk('public')->exists($setting?->favicon_16) ? asset('storage/' . $setting?->favicon_16) : asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest"
        href="{{ $setting?->favicon_site_webmanifest && Storage::disk('public')->exists($setting?->favicon_site_webmanifest) ? asset('storage/' . $setting?->favicon_site_webmanifest) : asset('assets/favicon/site.webmanifest') }}">

    <link href="{{ asset('fonts/inter.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/tabler-icons/tabler-icons-v2.min.css') }}">

    <link href="{{ asset('vendor/remixicon/remixicon.css') }}" rel="stylesheet" />
    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $setting?->website_name ?? 'Burningroom Technology' }}</title>
    <style>
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

    </style>
</head>

<body class="bg-gray-100 font-inter">
    {{ $slot }}

    @livewireScripts
    
    <script>
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('copy', event => event.preventDefault());
        document.addEventListener('cut', event => event.preventDefault());


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
                e.keyCode === 123 ||
                e.keyCode === 44 // PrintScreen
            ) {
                
                if (e.ctrlKey || e.metaKey || e.keyCode === 123 || e.keyCode === 44) {
                    e.preventDefault();
                }
                return false;
            }
        }, true);

        });

        // Disable autocomplete on all inputs
        document.querySelectorAll('input, form').forEach(el => {
            el.setAttribute('autocomplete', 'off');
        });
    </script>

    @stack('scripts')
    @filepondScripts

    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>

    {{-- <x-livewire-alert::scripts />
    <x-livewire-alert::flash /> --}}
</body>

</html>
