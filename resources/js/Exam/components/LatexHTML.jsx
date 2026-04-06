import React, { useLayoutEffect, useRef, memo } from 'react';

/**
 * Stable LaTeX-aware HTML renderer for React.
 * - Manages innerHTML manually to prevent React from overwriting KaTeX output.
 * - Uses useLayoutEffect to render math synchronously before browser paint.
 */
const LatexHTML = memo(({ html, className = "" }) => {
    const containerRef = useRef(null);
    const prevHtml = useRef(null);

    useLayoutEffect(() => {
        const el = containerRef.current;
        if (!el) return;

        // Only re-render if content actually changed
        if (prevHtml.current === html) return;
        prevHtml.current = html;

        // Set the raw HTML
        el.innerHTML = html || '';

        // Run KaTeX rendering
        const runKatex = (attempt = 0) => {
            if (window.renderMathInElement) {
                try {
                    window.renderMathInElement(el, window.katexOptions || {
                        delimiters: [
                            { left: '$$', right: '$$', display: true },
                            { left: '\\[', right: '\\]', display: true },
                            { left: '\\(', right: '\\)', display: false },
                            { left: '$', right: '$', display: false }
                        ],
                        throwOnError: false
                    });
                } catch (e) {
                    console.error('[LatexHTML] KaTeX error:', e);
                }
            } else if (attempt < 20) {
                setTimeout(() => runKatex(attempt + 1), 100);
            }
        };

        runKatex();

    }, [html]);

    return <div ref={containerRef} className={className} />;
}, (prevProps, nextProps) => {
    return prevProps.html === nextProps.html && prevProps.className === nextProps.className;
});

export default LatexHTML;
