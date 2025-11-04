@if ($paginator->hasPages())
    <div class="flex gap-2 mt-4">

        {{-- Tombol Sebelumnya --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 border rounded opacity-50 cursor-not-allowed">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a wire:navigate href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1 border rounded hover:bg-gray-50">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Nomor Halaman --}}
        @foreach ($elements as $element)
            {{-- Jika element adalah string "..." --}}
            @if (is_string($element))
                <span class="px-3 py-1 border rounded text-gray-400">{{ $element }}</span>
            @endif

            {{-- Jika element adalah array halaman --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 border rounded bg-blue-600 text-white">
                            {{ $page }}
                        </span>
                    @else
                        <a wire:navigate href="{{ $url }}" class="px-3 py-1 border rounded hover:bg-gray-50">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Tombol Selanjutnya --}}
        @if ($paginator->hasMorePages())
            <a wire:navigate href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1 border rounded hover:bg-gray-50">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="px-3 py-1 border rounded opacity-50 cursor-not-allowed">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif

    </div>
@endif
