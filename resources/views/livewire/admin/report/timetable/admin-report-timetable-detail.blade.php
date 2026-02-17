    @section('title', 'Detail Riwayat Jadwal Ujian')
<div>
    {{-- Be like water. --}}
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Detail Riwayat Ujian</h1>
                <p class="text-gray-600 my-2">Rekap Nilai dari "{{$timetable_module->module->name}}"</p>
            </div>
            <div>
                <button wire:click="exportPdf" class="btn btn-primary">
                    <i class="fa-solid fa-file-pdf mr-2"></i>
                    Export PDF
                </button>
            </div>
        </div>
    </div>
    <!-- Table Controls -->
    <!-- Table Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center">
            <span class="text-sm text-gray-700 mr-2">Tampil</span>
            <select class="mt-1 form-control" wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-700 ml-2">data</span>
        </div>

        <div class="relative w-full sm:w-64">
            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container overflow-x-auto relative">
            <table class="w-full text-xs text-left text-gray-500 border-collapse">
                <thead class="bg-gray-50 text-xs text-gray-700 uppercase sticky top-0 z-10">
                    <tr>
                        <th rowspan="2" class="px-2 py-2 sticky left-0 z-20 bg-gray-50 border-b border-r border-gray-200 w-12 text-center shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">No</th>
                        <th rowspan="2" class="px-3 py-2 sticky left-12 z-20 bg-gray-50 border-b border-r border-gray-200 w-48 min-w-[12rem] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">Nama Mahasiswa</th>
                        <th colspan="{{ $timetable_questions->count() }}" class="px-2 py-1 text-center border-b border-gray-200 bg-gray-100 font-semibold tracking-wider">
                            Daftar Soal ({{ $timetable_questions->count() }})
                        </th>
                        <th rowspan="2" class="px-2 py-2 text-center border-b border-l border-gray-200 bg-gray-50 w-16">JB</th>
                        <th rowspan="2" class="px-2 py-2 text-center border-b border-gray-200 bg-gray-50 w-16">Nilai</th>
                    </tr>
                    <tr>
                        @foreach ($timetable_questions as $index => $question)
                            <th class="px-1 py-1 text-center border-b border-gray-200 min-w-[2.5rem] relative group cursor-help hover:bg-gray-100 transition-colors">
                                <span class="font-bold text-gray-600 block mb-1">{{ $index + 1 }}</span>
                                <div class="text-[10px] items-center justify-center hidden group-hover:flex absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white px-2 py-1 rounded shadow-lg whitespace-nowrap z-50">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($question->question?->question ?? $question->question ?? ''), 30) }}
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($user_timetables as $index => $user_timetable)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-2 py-1 text-center sticky left-0 z-10 bg-white border-r border-gray-100 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] group-hover:bg-gray-50">
                                {{ $user_timetables->firstItem() + $index }}
                            </td>
                            <td class="px-3 py-1 sticky left-12 z-10 bg-white border-r border-gray-100 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] group-hover:bg-gray-50">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 truncate max-w-[11rem]" title="{{ $user_timetable->user?->name ?? '-' }}">
                                        {{ $user_timetable->user?->name ?? '-' }}
                                    </span>
                                    <span class="text-[10px] text-gray-500">{{ $user_timetable->user?->username ?? '' }}</span>
                                </div>
                            </td>
                            
                            @foreach ($timetable_questions as $question)
                                @php
                                    $userAnswer = $user_timetable->userModuleQuestions
                                        ->firstWhere('timetable_question_id', $question->id);
                                    $status = $userAnswer?->status;
                                    $isCorrect = $status === 'correct';
                                    $hasAnswered = !is_null($status);
                                    
                                    $bgClass = '';
                                    $textClass = 'text-gray-400';
                                    $content = '-';
                                    
                                    if ($hasAnswered) {
                                        if ($isCorrect) {
                                            $bgClass = 'bg-green-50 text-green-700 font-bold';
                                            $content = '1';
                                        } else {
                                            $bgClass = 'bg-red-50 text-red-700';
                                            $content = '0';
                                        }
                                    }
                                @endphp
                                <td class="px-1 py-1 text-center border-r border-gray-50 {{ $bgClass }} {{ !$hasAnswered ? $textClass : '' }}">
                                    {{ $content }}
                                </td>
                            @endforeach

                            <td class="px-2 py-1 text-center font-bold text-gray-700 border-l border-gray-100 bg-gray-50/50">
                                {{ $user_timetable->userModuleQuestions->where('status', 'correct')->count() }}
                            </td>
                            <td class="px-2 py-1 text-center font-bold text-blue-600 bg-gray-50/50">
                                {{ $user_timetable->mark ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $timetable_questions->count() + 4 }}" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                                    <span class="text-sm">Tidak ada data partisipan ujian.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $user_timetables->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $user_timetables->lastItem() }}</span> dari <span
                        class="font-medium">{{ $user_timetables->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $user_timetables->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <!-- Question Reference Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-4">
        <details class="group">
            <summary class="flex justify-between items-center font-medium cursor-pointer list-none text-gray-700 hover:text-blue-600 transition-colors">
                <span>Lihat Detail Daftar Soal</span>
                <span class="transition group-open:rotate-180">
                    <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24" width="24"><path d="M6 9l6 6 6-6"></path></svg>
                </span>
            </summary>
            <div class="text-gray-600 mt-4 text-sm group-open:animate-fadeIn">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($timetable_questions as $index => $question)
                        <div class="flex gap-3 p-3 rounded-lg border border-gray-100 bg-gray-50/50 hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold text-xs">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-grow">
                                <div class="mb-1 font-semibold text-xs text-gray-500">Soal {{ $index + 1 }}</div>
                                <div class="prose prose-sm max-w-none text-gray-800">
                                    {!! $question->question?->question ?? $question->question ?? '-' !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </details>
    </div>
</div>
