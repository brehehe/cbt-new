<div class="fixed inset-0 bg-[#dbd2d244] bg-opacity-50 flex items-center justify-center z-50">
    <div class="flex space-x-1 text-4xl font-bold text-blue-600 animate-move">
        @php
            $companyName = auth()->user()?->company?->name ?? 'PRO CBT';
            $letters = mb_str_split(strtoupper($companyName));
        @endphp
        @foreach ($letters as $index => $char)
            <span class="animate-bounce" style="animation-delay: {{ $index * 0.1 }}s">
                {!! $char === ' ' ? '&nbsp;' : e($char) !!}
            </span>
        @endforeach
    </div>
</div>
