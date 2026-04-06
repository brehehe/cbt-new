<!-- KaTeX Stylesheet -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css">
<style>
    /* Custom LaTeX helper styles */
    .latex-inline-math {
        display: inline-block;
        vertical-align: middle;
    }
    /* Simple spin animation for loading */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    /*
     * FIX: Only hide the MathML accessibility tree, NOT the visual HTML output.
     * KaTeX renders two things:
     *   1. .katex-mathml  → MathML for accessibility (should be hidden visually)
     *   2. .katex-html    → Actual visual math (must NEVER be hidden)
     */
    .katex-mathml {
        position: absolute !important;
        width: 1px !important;
        height: 1px !important;
        padding: 0 !important;
        margin: -1px !important;
        overflow: hidden !important;
        clip: rect(0, 0, 0, 0) !important;
        white-space: nowrap !important;
        border: 0 !important;
    }

    /* Ensure .prose elements handle KaTeX overflow gracefully */
    .prose .katex-display {
        margin: 1em 0;
        overflow-x: auto;
        overflow-y: hidden;
    }
</style>
