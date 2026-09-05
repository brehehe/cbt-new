@include('partials.latex-core')

<script>
    /**
     * Admin-only LaTeX logic.
     * This file handles the automatic rendering in Blade/Livewire environments
     * including Summernote and real-time previews.
     */
    (function () {
        // Shared Summernote Initializer with LaTeX integration
        window.initSummernote = function (el, wireProperty, options = {}) {
            if (typeof $ === 'undefined' || typeof $.summernote === 'undefined') return;
            const $el = $(el);
            const config = {
                height: options.height || 200,
                placeholder: options.placeholder || 'Tulis di sini...',
                toolbar: options.toolbar || [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video', 'latexBtn', 'formulaBtn']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                buttons: {
                    latexBtn: function (context) {
                        var ui = $.summernote.ui;
                        return ui.button({
                            contents: '<span class=\'font-bold text-blue-600\'>Σ</span> LaTeX',
                            tooltip: 'Kirim ke LaTeX (PNG)',
                            click: function () {
                                var text = context.invoke('editor.getSelectedText');
                                if (text) {
                                    const wireTarget = options.latexTarget || 'latex';
                                    @this.set(wireTarget, text);
                                    setTimeout(() => {
                                        const btn = document.querySelector(`[data-latex-render][data-latex-source='#${wireTarget}']`);
                                        if (btn) btn.click();
                                    }, 400);
                                } else {
                                    Swal.fire({ icon: 'info', title: 'Pilih Teks', text: 'Pilih rumus terlebih dahulu.' });
                                }
                            }
                        }).render();
                    },
                    formulaBtn: function (context) {
                        var ui = $.summernote.ui;
                        return ui.button({
                            contents: '<span class=\'font-bold text-green-600\'>√x</span> Formula',
                            tooltip: 'Masukkan Rumus (Live Preview)',
                            click: function () { window.insertLatexToSummernote(context); }
                        }).render();
                    }
                },
                callbacks: {
                    onChange: function (contents) { @this.set(wireProperty, contents); }
                }
            };
            $el.summernote(config);
            if (options.initialCode) $el.summernote('code', options.initialCode);
        };

        // Advanced LaTeX insertion modal with Live Preview
        window.insertLatexToSummernote = function (context) {
            if (typeof Swal === 'undefined') return;
            Swal.fire({
                title: 'Masukkan Rumus LaTeX',
                html: `<div class="text-left"><textarea id="swal-latex-input" class="w-full p-3 border-2 rounded-xl mb-4" rows="4"></textarea><div id="swal-latex-preview" class="p-6 border-2 border-dashed rounded-xl bg-gray-50 min-h-[80px] flex items-center justify-center"></div></div>`,
                showCancelButton: true,
                didOpen: () => {
                    const input = document.getElementById('swal-latex-input');
                    const preview = document.getElementById('swal-latex-preview');
                    let t;
                    input.addEventListener('input', () => {
                        clearTimeout(t);
                        t = setTimeout(() => {
                            const latex = input.value.trim();
                            if (!latex) { preview.innerHTML = ''; return; }
                            try {
                                preview.innerHTML = '';
                                const s = document.createElement('span');
                                preview.appendChild(s);
                                if (window.katex) katex.render(latex, s, { ...window.katexOptions, displayMode: true });
                            } catch (e) { preview.innerHTML = e.message; }
                        }, 300);
                    });
                    input.focus();
                }
            }).then((r) => {
                if (r.isConfirmed) {
                    const input = document.getElementById('swal-latex-input');
                    let v = (input.value || '').trim();
                    if (v) {
                        while (/^(\\\(|\\\[|\$\$|\$)/.test(v) || /(\\\)|\\\]|\$\$|\$)$/.test(v)) {
                            v = v.replace(/^(\\\(|\\\[|\$\$|\$)+/, '').replace(/(\\\)|\\\]|\$\$|\$)+$/, '').trim();
                        }
                        context.invoke('editor.insertText', ` \\( ${v} \\) `);
                    }
                }
            });
        };

        // MutationObserver for Admin view auto-rendering
        const setupAdminObserver = () => {
            const observer = new MutationObserver((mutations) => {
                let hasNewContent = false;
                for (const mutation of mutations) {
                    const isInternal = Array.from(mutation.addedNodes || []).some(n => 
                        n.nodeType === 1 && (n.classList.contains('katex') || n.classList.contains('katex-html'))
                    );
                    if (!isInternal && mutation.addedNodes.length > 0) {
                        hasNewContent = true;
                        break;
                    }
                }
                if (hasNewContent) {
                    document.querySelectorAll('.prose').forEach(el => window.renderLatex(el));
                }
            });
            observer.observe(document.body, { childList: true, subtree: true });
            document.querySelectorAll('.prose').forEach(el => window.renderLatex(el));
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupAdminObserver);
        } else {
            setupAdminObserver();
        }

        // Livewire processed support
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', () => {
                document.querySelectorAll('.prose').forEach(el => window.renderLatex(el));
            });
        }
    })();
</script>