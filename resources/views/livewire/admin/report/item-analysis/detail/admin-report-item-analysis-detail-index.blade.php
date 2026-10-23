@section('title', 'Detail Analisis Butir Soal')
<div>
@include('livewire.admin.report.shared.overview-styles')
<div class="flex flex-wrap justify-between items-center gap-4 mb-6">
    <div><h1 class="text-2xl font-bold">Analisis Butir Soal</h1><p>{{ $timetable->name }} · {{ count($itemAnalysisData) }} soal</p></div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.report.item-analysis') }}" class="btn btn-secondary">Kembali</a>
        <button wire:click="exportPdf" wire:loading.attr="disabled" class="btn btn-primary">Unduh PDF Overview</button>
    </div>
</div>
<div wire:loading wire:target="exportPdf" class="mb-4 text-sm text-gray-500">Menyiapkan PDF…</div>

<!-- Question Filters -->
@include('livewire.admin.report.shared.question-filter-bar')

<!-- Search & Sorting Controls -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <!-- Sorting Controls -->
        <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200 px-3 py-1.5 gap-2 w-full md:w-auto">
            <span class="text-xs text-gray-500 font-semibold">Urutkan:</span>
            <select wire:model.live="sortBy" class="form-select text-xs border-none focus:ring-0 p-0 text-gray-700 font-bold bg-transparent cursor-pointer">
                <option value="order">No. Urut Soal</option>
                <option value="difficulty">Tingkat Kesulitan (Index P)</option>
                <option value="discrimination">Daya Pembeda (Index D)</option>
                <option value="participants">Jumlah Peserta</option>
                <option value="correct">Jumlah Jawaban Benar</option>
            </select>
            <button type="button" 
                    wire:click="toggleSortDirection" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-white border border-gray-200 hover:bg-gray-100 text-xs font-bold text-gray-700 transition cursor-pointer shadow-sm"
                    title="{{ $sortDirection === 'asc' ? 'Urutkan dari Awal ke Akhir (A-Z / Nilai Terkecil). Klik untuk beralih ke DESC.' : 'Urutkan dari Akhir ke Awal (Z-A / Nilai Terbesar). Klik untuk beralih ke ASC.' }}">
                @if ($sortDirection === 'asc')
                    <i class="fas fa-arrow-down-a-z text-indigo-600"></i>
                    <span>ASC</span>
                @else
                    <i class="fas fa-arrow-down-z-a text-indigo-600"></i>
                    <span>DESC</span>
                @endif
            </button>
        </div>

        <div class="relative w-full md:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms='search'
                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out"
                   placeholder="Cari teks soal, pembahasan, opsi jawaban, topik, materi...">
        </div>
    </div>
</div>

@forelse($itemAnalysisData as $analysis)
@include('livewire.admin.report.shared.overview-card', ['number' => $analysis['question']->order ?? $loop->iteration])
@empty
<div class="bg-white rounded-xl p-8 text-center text-gray-500 border border-gray-100">
    <i class="fas fa-filter text-gray-300 text-3xl mb-2"></i>
    <p>Belum ada data soal atau tidak ada soal yang sesuai dengan filter.</p>
</div>
@endforelse
</div>
