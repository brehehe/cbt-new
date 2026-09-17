<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'Laravel' }}</title>

<link href="{{ asset('fonts/instrument-sans.css') }}" rel="stylesheet" />

@viteReactRefresh
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script>
    // Pre-emptively lock appearance to light mode before any library scripts evaluate
    try {
        localStorage.setItem('flux.appearance', 'light');
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
    } catch (e) {}
</script>
@fluxAppearance
<script>
    // Enforce 100% Light Mode across all pages and components
    try {
        localStorage.setItem('flux.appearance', 'light');
        if (window.Flux) {
            window.Flux.applyAppearance = function() {
                try { localStorage.setItem('flux.appearance', 'light'); } catch(e){}
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
            };
            window.Flux.applyAppearance('light');
        }
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
    } catch (e) {}

    // Ensure dark class is immediately stripped if dynamically attached
    const enforceLight = () => {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
        }
        document.documentElement.classList.add('light');
    };
    enforceLight();
    document.addEventListener('DOMContentLoaded', enforceLight);

    // MutationObserver to permanently block 'dark' class on <html>
    try {
        new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class' && document.documentElement.classList.contains('dark')) {
                    enforceLight();
                }
            });
        }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    } catch (e) {}
</script>
