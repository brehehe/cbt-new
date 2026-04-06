@php
    use App\Models\Company\Company;

    $company = Company::first(); 
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PRO-CBT</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">


    <link rel="icon" type="image/png" href="{{asset('storage/' . $company->logo_potrait)}}" />
    <!-- Add Selectize CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.bootstrap5.min.css"
        integrity="sha512-Ars0BmSwpsUJnWMw+KoUKGKunT7+T8NGK0ORRKj+HT8naZzLSIQoOSIIM3oyaJljgLxFi0xImI5oZkAWEFARSA=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
    {{--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- Summernote Lite CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">

    {{-- file pond --}}
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet" />
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet" />
    <style>
        .shadow-top {
            box-shadow: 0 -1px 3px 0 rgba(0, 0, 0, 0.1);
        }

        :root {
            --primary-50:
                {{ '#f58634' }}
            ;
            /* 10% opacity fallback */
            --primary-600:
                {{ '#f58634' }}
            ;
            --color-primary:
                {{ '#f58634' }}
            ;
            --color-secondary:
                {{ '#4a5568' }}
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
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-white">

    <div class="watermark-logo">
        <img src="{{ asset('asset/img/logo-procbt.png') }}" alt="Watermark Logo" style="width: 750px; height: 150px" />
        {{ Auth::user()->name . ' - ' . (Auth::user()->nim ?? (Auth::user()->username ?? '-')) }}
    </div>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Add Selectize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Summernote Lite JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>

    <!-- file pond -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
        // Register the plugin
        FilePond.registerPlugin(FilePondPluginImagePreview);

        // ... FilePond initialisation code here
    </script>

    <script>
        @if(!Auth::user()->hasRole(['Admin']))
            document.addEventListener('contextmenu', event => event.preventDefault());
            document.addEventListener('copy', event => event.preventDefault());
            document.addEventListener('cut', event => event.preventDefault());
        @endif

        // Disable autocomplete on all inputs
        document.querySelectorAll('input, form').forEach(el => {
            el.setAttribute('autocomplete', 'off');
        });
    </script>

    @stack('scripts')
</body>

</html>