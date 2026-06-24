<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin Login</title>

    <!-- Fonts & Icons -->
    <link href="{{ asset('fonts/inter.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/tabler-icons/tabler-icons-v3.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">


    <!-- App -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <tallstackui:script />
    @livewireStyles
    @stack('styles')
</head>

<body class="min-h-screen">
    @yield('content')

    @stack('scripts')
    @livewireScripts
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
</body>

</html>
