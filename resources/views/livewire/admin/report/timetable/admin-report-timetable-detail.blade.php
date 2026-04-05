@section('title', 'Detail Riwayat Jadwal Ujian')
<div>
    {{-- Be like water. --}}
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#f58634' }}]">
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
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="w-full md:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-[{{ $companyData->color_primary ?? '#2b7fff' }}] focus:border-[{{ $companyData->color_primary ?? '#2b7fff' }}] sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Cari Sesuatu..." wire:model.live='search'>
            </div>
        </div>
    </div>
    <!-- Table Section -->
    <!-- Results Card List -->
    <div class="space-y-6">
        @forelse ($user_timetables as $index => $user_timetable)
            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300">
                <!-- Card Header: Student Info -->
                <div
                    class="p-6 border-b border-gray-50 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-xl bg-[{{ $companyData->color_primary ?? '#f58634' }}]/10 flex items-center justify-center text-[{{ $companyData->color_primary ?? '#f58634' }}] font-bold text-lg">
                            {{ $user_timetables->firstItem() + $index }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight">
                                {{ $user_timetable->user?->name ?? '-' }}
                            </h3>
                            <p class="text-sm text-gray-500 font-medium">NIM:
                                {{ $user_timetable->user?->nim ?? ($user_timetable->user?->username ?? '-') }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div
                            class="px-4 py-2 rounded-xl bg-green-50 border border-green-100 flex flex-col items-center min-w-[80px]">
                            <span class="text-[10px] uppercase tracking-wider font-bold text-green-600">Benar (JB)</span>
                            <span class="text-lg font-black text-green-700">
                                {{ $user_timetable->userModuleQuestions->where('status', 'correct')->count() }}
                            </span>
                        </div>
                        <div
                            class="px-4 py-2 rounded-xl bg-red-50 border border-red-100 flex flex-col items-center min-w-[80px]">
                            <span class="text-[10px] uppercase tracking-wider font-bold text-red-600">Salah</span>
                            <span class="text-lg font-black text-red-700">
                                {{ $user_timetable->userModuleQuestions->where('status', 'wrong')->count() }}
                            </span>
                        </div>
                        <div
                            class="px-4 py-2 rounded-xl bg-blue-600 flex flex-col items-center min-w-[100px] shadow-lg shadow-blue-200">
                            <span class="text-[10px] uppercase tracking-wider font-bold text-blue-50">Nilai Akhir</span>
                            <span class="text-xl font-black text-white">
                                {{ $user_timetable->mark ?? '0' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Body: Answer Pattern Grid -->
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fa-solid fa-braille text-gray-400"></i>
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Pola Jawaban</h4>
                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-[10px] font-bold text-gray-500">
                            {{ $timetable_questions->count() }} Soal
                        </span>
                    </div>

                    @php
                        // Optimize lookup by keying the collection
                        $userAnswers = $user_timetable->userModuleQuestions->keyBy('timetable_question_id');
                    @endphp

                    <div class="flex flex-wrap gap-1.5 overflow-hidden">
                        @foreach ($timetable_questions as $qIndex => $question)
                            @php
                                $userAnswer = $userAnswers[$question->id] ?? null;
                                $status = $userAnswer?->status;
                                $isCorrect = $status === 'correct';
                                $hasAnswered = !is_null($status);

                                $bgClass = 'bg-gray-100 text-gray-400';
                                if ($hasAnswered) {
                                    $bgClass = $isCorrect ? 'bg-green-500 text-white shadow-sm shadow-green-100' : 'bg-red-500 text-white shadow-sm shadow-red-100';
                                }
                            @endphp
                            <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg {{ $bgClass }} flex flex-col items-center justify-center transition-all hover:scale-110 cursor-default group relative"
                                title="Soal {{ $qIndex + 1 }}: {{ $hasAnswered ? ($isCorrect ? 'Benar' : 'Salah') : 'Belum Dijawab' }}">
                                <span class="text-[8px] opacity-70 font-bold leading-none mb-0.5">{{ $qIndex + 1 }}</span>
                                <span class="text-[11px] font-black leading-none">
                                    @if(!$hasAnswered) - @elseif($isCorrect) 1 @else 0 @endif
                                </span>

                                <!-- Tooltip -->
                                <div
                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-[10px] rounded opacity-0 group-hover:opacity-100 pointer-events-none whitespace-nowrap z-50 transition-opacity">
                                    Soal {{ $qIndex + 1 }}:
                                    {{ \Illuminate\Support\Str::limit(strip_tags($question->question?->question ?? $question->question ?? ''), 20) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Tidak ada data</h3>
                <p class="text-gray-500">Belum ada partisipan yang mengikuti ujian ini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $user_timetables->links('vendor.livewire.custom') }}
    </div>


    <!-- Question Reference Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-4">
        <details class="group">
            <summary
                class="flex justify-between items-center font-medium cursor-pointer list-none text-gray-700 hover:text-blue-600 transition-colors">
                <span>Lihat Detail Daftar Soal</span>
                <span class="transition group-open:rotate-180">
                    <svg fill="none" height="24" shape-rendering="geometricPrecision" stroke="currentColor"
                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" viewBox="0 0 24 24"
                        width="24">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </span>
            </summary>
            <div class="text-gray-600 mt-4 text-sm group-open:animate-fadeIn">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($timetable_questions as $index => $question)
                        <div
                            class="flex gap-3 p-3 rounded-lg border border-gray-100 bg-gray-50/50 hover:bg-white hover:shadow-sm transition-all">
                            <div
                                class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold text-xs">
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