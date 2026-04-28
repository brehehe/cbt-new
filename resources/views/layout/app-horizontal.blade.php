<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
    @stack('styles')
</head>

<body class="bg-[#F4F7F6] antialiased text-slate-800">
    <div class="min-h-screen flex flex-col">
        <!-- Top Horizontal Navbar -->
        @include('layout.navbar-horizontal')

        <!-- Main Content (with top padding for the fixed navbar) -->
        <main class="flex-grow w-full mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-8">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>

    @stack('scripts')
</body>

</html>