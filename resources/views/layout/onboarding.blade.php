@php
    use App\Models\Company\Company;

    $company = Company::getCached(); 

    $primary = $company->color_primary;
    $secondary = $company->color_secondary;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Onboarding - PRO-CBT</title>

    <link href="{{ asset('fonts/plus-jakarta-sans.css') }}" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{asset('storage/' . $company->logo_potrait)}}" />
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/all.min.css') }}">
    
    <style>
        :root {
            --primary: {{ $primary }};
            --secondary: {{ $secondary }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        input, textarea {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-gray-50 h-screen w-screen overflow-hidden">
    <div class="h-full w-full flex flex-col overflow-hidden">
        <!-- Main Content -->
        <main class="flex-grow h-full overflow-hidden">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    @stack('scripts')
</body>

</html>
