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

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet" />
    <tallstackui:script />
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>{{ $setting?->website_name ?? 'Burningroom Technology' }}</title>
</head>

<body class="bg-gray-100 font-inter">
    {{ $slot }}

    @livewireScripts
    @stack('scripts')
    @filepondScripts

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>

    {{-- <x-livewire-alert::scripts />
    <x-livewire-alert::flash /> --}}
</body>

</html>
