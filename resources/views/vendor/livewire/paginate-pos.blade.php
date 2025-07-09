@if ($paginator->hasPages())
    <div class="flex gap-2">
        {{-- Previous Page Link --}}
        <button wire:click="previousPage('pageProduct')" wire:loading.attr="disabled" class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" @if ($paginator->onFirstPage()) disabled @endif>
            <i class="fas fa-chevron-left"></i>
        </button>

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-3 py-1 border rounded text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <button wire:click="gotoPage({{ $page }}, 'pageProduct')" class="px-3 py-1 border rounded {{ $page == $paginator->currentPage() ? 'bg-blue-600 text-white' : 'hover:bg-gray-50' }}">
                        {{ $page }}
                    </button>
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        <button wire:click="nextPage('pageProduct')" wire:loading.attr="disabled" class="px-3 py-1 border rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed" @if (!$paginator->hasMorePages()) disabled @endif>
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
@endif
