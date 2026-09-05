@props(['paginator', 'elements', 'companyData'])

@php
    $pageName = $paginator->getPageName();
    $primaryColor = $companyData->color_primary ?? '#f58634';
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();

    // Buat daftar halaman pintar (maksimal ~40-60 item agar sangat ringan & tidak crash saat ribuan page)
    $selectablePages = [];
    if ($lastPage <= 60) {
        $selectablePages = range(1, $lastPage);
    } else {
        $pagesSet = [1, 2, 3, $currentPage, $lastPage - 2, $lastPage - 1, $lastPage];

        // Jendela sekitar halaman aktif (-10 sampai +10)
        for ($i = max(1, $currentPage - 10); $i <= min($lastPage, $currentPage + 10); $i++) {
            $pagesSet[] = $i;
        }

        // Lompatan kelipatan dinamis (misal: kelipatan 10, 25, 50, 100)
        $step = $lastPage > 1000 ? 100 : ($lastPage > 200 ? 25 : 10);
        for ($i = $step; $i < $lastPage; $i += $step) {
            $pagesSet[] = $i;
        }

        $selectablePages = array_unique($pagesSet);
        sort($selectablePages);
    }
@endphp

@if ($paginator->hasPages())
<div class="w-full flex flex-col items-center">
    {{-- ── Mobile View (Compact & Clean on Screen < 640px) ── --}}
    <div class="flex sm:hidden items-center justify-between w-full gap-2">
        {{-- Previous Button --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 bg-gray-100 text-xs font-semibold text-gray-400 cursor-not-allowed select-none">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>Sebelumnya</span>
            </span>
        @else
            <button wire:click="previousPage('{{ $pageName }}')" rel="prev" class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 active:bg-gray-100 transition shadow-2xs cursor-pointer">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                <span>Sebelumnya</span>
            </button>
        @endif

        {{-- Smart Interactive Page Select Dropdown --}}
        <div class="relative inline-flex items-center">
            <select
                wire:change="gotoPage($event.target.value, '{{ $pageName }}')"
                class="appearance-none bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-800 text-xs font-bold py-1.5 pl-3 pr-7 rounded-lg shadow-2xs cursor-pointer focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
            >
                @foreach ($selectablePages as $p)
                    <option value="{{ $p }}" {{ $p == $currentPage ? 'selected' : '' }}>
                        Hal. {{ $p }} / {{ $lastPage }}
                    </option>
                @endforeach
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2 text-gray-500">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>

        {{-- Next Button --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage('{{ $pageName }}')" rel="next" class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50 active:bg-gray-100 transition shadow-2xs cursor-pointer">
                <span>Berikutnya</span>
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        @else
            <span class="flex items-center gap-1 px-3 py-1.5 rounded-lg border border-gray-200 bg-gray-100 text-xs font-semibold text-gray-400 cursor-not-allowed select-none">
                <span>Berikutnya</span>
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif
    </div>

    {{-- ── Desktop & Tablet View (Numbered Pagination with Scroll Wrapper) ── --}}
    <div class="hidden sm:block max-w-full overflow-x-auto py-1">
        <nav class="relative z-0 inline-flex rounded-lg shadow-2xs -space-x-px border border-gray-200 overflow-hidden bg-white" aria-label="Pagination">
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-3 py-2 bg-gray-50 border-r border-gray-200 text-sm font-medium text-gray-300 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <button wire:click="previousPage('{{ $pageName }}')" rel="prev" class="relative inline-flex items-center px-3 py-2 bg-white border-r border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @endif

            {{-- Link Halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="relative inline-flex items-center px-3 py-2 border-r border-gray-200 bg-gray-50 text-xs font-semibold text-gray-400 select-none">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="relative inline-flex items-center px-3.5 py-2 border-r border-gray-200 text-white text-xs font-bold transition shadow-xs select-none"
                                style="background-color: {{ $primaryColor }}; border-color: {{ $primaryColor }};">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }}, '{{ $pageName }}')" class="relative inline-flex items-center px-3.5 py-2 border-r border-gray-200 bg-white text-xs font-semibold text-gray-700 hover:bg-gray-100 transition cursor-pointer">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <button wire:click="nextPage('{{ $pageName }}')" rel="next" class="relative inline-flex items-center px-3 py-2 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            @else
                <span class="relative inline-flex items-center px-3 py-2 bg-gray-50 text-sm font-medium text-gray-300 cursor-not-allowed">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
</div>
@endif