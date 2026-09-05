<!-- KaTeX CSS and JS -->
<link rel="stylesheet" href="{{ asset('vendor/katex/katex.min.css') }}">
<script src="{{ asset('vendor/katex/katex.min.js') }}"></script>
<script src="{{ asset('vendor/katex/contrib/auto-render.min.js') }}"></script>

<script>
    /**
     * Shared KaTeX Configuration Library
     * Using default output (htmlAndMathml) for better compatibility.
     */
    window.katexOptions = {
        delimiters: [
            { left: '$$', right: '$$', display: true },
            { left: '\\[', right: '\\]', display: true },
            { left: '\\(', right: '\\)', display: false },
            { left: '$', right: '$', display: false }
        ],
        throwOnError: false,
        errorColor: '#ef4444',
        trust: true,
        strict: false
    };

    /**
     * Universal manual render function.
     */
    window.renderLatex = function (element) {
        if (!element || typeof window.renderMathInElement !== 'function') {
            return;
        }
        try {
            // Use auto-render extension to handle delimiters
            window.renderMathInElement(element, window.katexOptions);
        } catch (err) {
            console.error('KaTeX Manual Render Error:', err);
        }
    };
</script>
