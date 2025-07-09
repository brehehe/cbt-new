<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="bg-[#FDFDFC] antialiased">
    <div class="min-h-screen">
        @include('layout.navbar')
        @include('layout.sidebar')
        
        <!-- Main Content -->
        <main class="md:ml-64 min-h-screen p-4 lg:p-8">
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
</body>
</html>