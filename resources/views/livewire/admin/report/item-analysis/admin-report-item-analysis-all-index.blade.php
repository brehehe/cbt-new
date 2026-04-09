@section('title', 'Analisis Butir Soal')
@push('styles')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .gradient-text {
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-image: linear-gradient(to right, #4f46e5, #06b6d4);
        }
    </style>
@endpush

<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                Analisis Butir Soal <span class="gradient-text">(Semua)</span>
            </h1>
            <p class="mt-2 text-sm text-gray-500">
                Analisis mendalam untuk setiap butir soal yang telah diujikan
            </p>
        </div>
        <div>
            <button wire:click="generateAll" 
                    wire:loading.attr="disabled"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                <i class="fa-solid fa-wand-magic-sparkles mr-2 animate-pulse"></i>
                <span wire:loading.remove wire:target="generateAll">Generate Difficulty</span>
                <span wire:loading wire:target="generateAll">Generating...</span>
            </button>
        </div>
    </div>

    @php
        $pageAttempts = $items->sum('total_attempts');
        $pageCorrect = $items->sum('total_correct');
        $pageDifficultyIndex = $pageAttempts > 0 ? $pageCorrect / $pageAttempts : 0;
    @endphp

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Total Soal -->
        <div class="glass-card rounded-xl p-6 transition-all duration-300 hover:shadow-lg border-l-4 border-l-blue-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Soal</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                        {{ $items->total() }}
                    </p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full group-hover:bg-blue-100 transition-colors">
                    <i class="fas fa-list text-xl text-blue-500"></i>
                </div>
            </div>
        </div>

        <!-- Jawaban Benar -->
        <div class="glass-card rounded-xl p-6 transition-all duration-300 hover:shadow-lg border-l-4 border-l-amber-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Jawaban Benar</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 group-hover:text-amber-600 transition-colors">
                        {{ \Illuminate\Support\Number::format($pageCorrect) }}
                    </p>
                </div>
                <div class="p-3 bg-amber-50 rounded-full group-hover:bg-amber-100 transition-colors">
                    <i class="fas fa-check-circle text-xl text-amber-500"></i>
                </div>
            </div>
        </div>

        <!-- Indeks Kesukaran -->
        <div class="glass-card rounded-xl p-6 transition-all duration-300 hover:shadow-lg border-l-4 border-l-purple-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Rata-rata Indeks</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 group-hover:text-purple-600 transition-colors">
                        {{ number_format($pageDifficultyIndex, 3) }}
                    </p>
                </div>
                <div class="p-3 bg-purple-50 rounded-full group-hover:bg-purple-100 transition-colors">
                    <i class="fas fa-chart-line Index Ptext-xl text-purple-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <span class="text-sm text-gray-500 font-medium whitespace-nowrap">Tampilkan</span>
                <div class="relative">
                    <select wire:model.live='perPage' 
                            class="appearance-none block w-24 pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg transition-shadow duration-200 ease-in-out cursor-pointer hover:border-gray-400">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <span class="text-sm text-gray-500 font-medium whitespace-nowrap">data per halaman</span>
            </div>

            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                       wire:model.live='search'
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                       placeholder="Cari soal berdasarkan teks...">
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Soal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Difficulty
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Peserta
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Benar
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Index P
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                            Index D
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Tingkat
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-40">
                            Daya Pembeda
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($items as $index => $item)
                        @php
                            $totalAttempts = (int) ($item->total_attempts ?? 0);
                            $totalCorrect = (int) ($item->total_correct ?? 0);
                            $totalWrong = max(0, $totalAttempts - $totalCorrect);
                            $pIndex = $totalAttempts > 0 ? $totalCorrect / $totalAttempts : 0;
                            // Dummy D index for now as logic wasn't fully present in original
                            $dIndex = 0; 
                            
                            $tingkatLabel = $pIndex >= 0.7 ? 'Mudah' : ($pIndex >= 0.3 ? 'Sedang' : 'Sukar');
                            $tingkatColor = $pIndex >= 0.7 
                                ? 'bg-green-100 text-green-800 border-green-200' 
                                : ($pIndex >= 0.3 ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-red-100 text-red-800 border-red-200');

                            $discriminationLabel = $dIndex >= 0.4
                                ? 'Sangat Baik'
                                : ($dIndex >= 0.3
                                    ? 'Baik'
                                    : ($dIndex >= 0.2
                                        ? 'Cukup'
                                        : ($dIndex >= 0.1 ? 'Buruk' : 'Sangat Buruk')));
                                        
                            $discriminationColor = $dIndex >= 0.4
                                ? 'bg-indigo-100 text-indigo-800 border-indigo-200'
                                : ($dIndex >= 0.3
                                    ? 'bg-blue-100 text-blue-800 border-blue-200'
                                    : ($dIndex >= 0.2
                                        ? 'bg-yellow-100 text-yellow-800 border-yellow-200'
                                        : ($dIndex >= 0.1 ? 'bg-orange-100 text-orange-800 border-orange-200' : 'bg-red-100 text-red-800 border-red-200')));
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $items->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $item->question_type === 'essay' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ $item->question_type === 'essay' ? 'Essay' : 'PG' }}
                                    </span>
                                    <div class="text-sm text-gray-900 line-clamp-1" title="{{ strip_tags($item->question_text ?? '') }}">
                                        {!! \Str::limit(strip_tags($item->question_text ?? ''), 100) !!}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-medium">
                                {{ Str::title($item->difficulty) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 font-medium">
                                {{ $totalAttempts }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-medium">
                                {{ $totalCorrect }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ number_format($pIndex, 3) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ number_format($dIndex, 3) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $tingkatColor }}">
                                    {{ $tingkatLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $discriminationColor }}">
                                    {{ $discriminationLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-base font-medium text-gray-900">Tidak ada data</p>
                                    <p class="text-sm text-gray-500">Belum ada analisis butir soal yang tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $items->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $items->lastItem() }}</span> dari <span
                        class="font-medium">{{ $items->total() }}</span> hasil
                </div>
                <div>
                    {{ $items->links('vendor.livewire.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>
