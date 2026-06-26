@section('title', 'Detail Riwayat Jadwal Ujian')
<div>
    {{-- Be like water. --}}
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Detail Riwayat Ujian</h1>
                <p class="text-gray-600 my-2">Rekap Nilai dari "{{$timetable_module->module?->name ?? ($timetable_module->name ?? 'Unknown Module')}}"</p>
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
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
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
                            class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-[color:var(--primary)] font-bold text-lg">
                            {{ $user_timetables->firstItem() + $index }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 leading-tight">
                                {{ $user_timetable->user?->name ?? '-' }}
                            </h3>
                            <p class="text-sm text-gray-500 font-medium">NIM:
                                {{ $user_timetable->user?->nim ?? ($user_timetable->user?->username ?? '-') }}
                            </p>
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
                    @php
                        // Group questions by type and keep track of original indices
                        $indexedQuestions = $timetable_questions->map(function($q, $i) {
                            return (object)['question' => $q, 'originalIndex' => $i + 1];
                        });

                        $mcqGroup = $indexedQuestions->filter(fn($item) => $item->question->type !== 'essay');
                        $essayGroup = $indexedQuestions->filter(fn($item) => $item->question->type === 'essay');
                        
                        $userAnswers = $user_timetable->userModuleQuestions->keyBy('timetable_question_id');
                    @endphp

                    
                    @if($mcqGroup->isNotEmpty())
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pola Jawaban Pilihan Ganda</span>
                                <div class="flex-grow border-t border-gray-100 italic"></div>
                            </div>
                            <div class="flex flex-wrap gap-1.5 overflow-hidden">
                                @foreach ($mcqGroup as $item)
                                    @php
                                        $userAnswer = $userAnswers[$item->question->id] ?? null;
                                        $status = $userAnswer?->status;
                                        $isCorrect = $status === 'correct';
                                        $hasAnswered = !is_null($status);

                                        $bgClass = 'bg-gray-100 text-gray-400';
                                        if ($hasAnswered) {
                                            $bgClass = $isCorrect ? 'bg-green-500 text-white shadow-sm shadow-green-100' : 'bg-red-500 text-white shadow-sm shadow-red-100';
                                        }
                                    @endphp
                                    <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg {{ $bgClass }} flex flex-col items-center justify-center transition-all hover:scale-110 cursor-default group relative"
                                        title="Soal {{ $item->originalIndex }}">
                                        <span class="text-[8px] opacity-70 font-bold leading-none mb-0.5">{{ $item->originalIndex }}</span>
                                        <span class="text-[11px] font-black leading-none">
                                            @if(!$hasAnswered) - @elseif($isCorrect) 1 @else 0 @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($essayGroup->isNotEmpty())
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Pola Jawaban Essay</span>
                                <div class="flex-grow border-t border-orange-50 mt-1"></div>
                            </div>
                            <div class="flex flex-wrap gap-1.5 overflow-hidden">
                                @foreach ($essayGroup as $item)
                                    @php
                                        $userAnswer = $userAnswers[$item->question->id] ?? null;
                                        $status = $userAnswer?->status;
                                        $isGraded = in_array($status, ['correct', 'wrong']);
                                        $isCorrect = $status === 'correct';
                                        $isPending = $status === 'check';

                                        $bgClass = 'bg-gray-100 text-gray-400';
                                        if ($isGraded) {
                                            $bgClass = $isCorrect ? 'bg-green-500 text-white shadow-sm shadow-green-100' : 'bg-red-500 text-white shadow-sm shadow-red-100';
                                        } elseif ($isPending) {
                                            $bgClass = 'bg-orange-100 text-orange-500 border border-orange-200';
                                        }
                                    @endphp
                                    <div class="w-8 h-8 md:w-9 md:h-9 rounded-lg {{ $bgClass }} flex flex-col items-center justify-center transition-all hover:scale-110 cursor-default group relative"
                                        title="Soal {{ $item->originalIndex }}: Essay">
                                        <span class="text-[8px] opacity-70 font-bold leading-none mb-0.5">{{ $item->originalIndex }}</span>
                                        <span class="text-[11px] font-black leading-none">
                                            @if($isGraded) {{ $isCorrect ? '1' : '0' }} @elseif($isPending) ? @else - @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Essay Answers Toggle -->
                            <div class="mt-8 pt-6 border-t border-gray-100 px-4">
                                <details class="group/essay">
                                    <summary class="flex items-center justify-between cursor-pointer list-none text-blue-600 hover:text-blue-700 transition-colors">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-rectangle-list"></i>
                                            <span class="text-sm font-bold uppercase tracking-wide">Lihat Jawaban Essay</span>
                                        </div>
                                        <span class="transition group-open/essay:rotate-180">
                                            <i class="fa-solid fa-chevron-down text-sm"></i>
                                        </span>
                                    </summary>
                                    <div class="mt-4 grid grid-cols-1 gap-4 animate-fadeIn pb-4">
                                        @foreach ($essayGroup as $item)
                                            @php
                                                $userAnswer = $userAnswers[$item->question->id] ?? null;
                                                $status = $userAnswer?->status;
                                            @endphp
                                            <div class="p-4 rounded-xl bg-gray-50 border border-gray-100">
                                                <div class="flex items-center justify-between mb-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="w-6 h-6 flex items-center justify-center bg-gray-200 text-gray-700 rounded-full text-[10px] font-black">
                                                            {{ $item->originalIndex }}
                                                        </span>
                                                        <span class="text-xs font-bold text-gray-600">Jawaban Peserta:</span>
                                                    </div>
                                                    @if($status === 'correct')
                                                        <span class="text-[10px] font-black text-green-600 uppercase tracking-widest"><i class="fa-solid fa-check mr-1"></i> Benar</span>
                                                    @elseif($status === 'wrong')
                                                        <span class="text-[10px] font-black text-red-600 uppercase tracking-widest"><i class="fa-solid fa-xmark mr-1"></i> Salah</span>
                                                    @else
                                                        <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest"><i class="fa-solid fa-clock mr-1"></i> Pending</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-700 font-medium italic leading-relaxed whitespace-pre-wrap text-wrap break-words">
                                                    {{ $userAnswer?->essay_answer ?: '(Tidak ada jawaban)' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            </div>
                        </div>
                    @endif
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
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6 p-4 mt-4">
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
                @php
                    $mcqRefs = $timetable_questions->filter(fn($q) => $q->type !== 'essay');
                    $essayRefs = $timetable_questions->filter(fn($q) => $q->type === 'essay');
                    $allQuestionsKeyed = $timetable_questions->values();
                @endphp

                @if($mcqRefs->isNotEmpty())
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <h5 class="text-sm font-bold text-gray-700">Pilihan Ganda</h5>
                            <div class="flex-grow border-t border-gray-100"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($mcqRefs as $question)
                                @php $originalIdx = $allQuestionsKeyed->search($question) + 1; @endphp
                                <div class="flex gap-3 p-3 rounded-lg border border-gray-100 bg-gray-50/50 hover:bg-white hover:shadow-sm transition-all">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full font-bold text-xs">
                                        {{ $originalIdx }}
                                    </div>
                                    <div class="flex-grow">
                                        <div class="mb-1 font-semibold text-[10px] text-gray-400 uppercase">Soal {{ $originalIdx }}</div>
                                        <div class="prose prose-sm max-w-none text-gray-800">
                                            {!! $question->question?->question ?? $question->question ?? '-' !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($essayRefs->isNotEmpty())
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <h5 class="text-sm font-bold text-orange-600">Essay</h5>
                            <div class="flex-grow border-t border-orange-100"></div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($essayRefs as $question)
                                @php $originalIdx = $allQuestionsKeyed->search($question) + 1; @endphp
                                <div class="flex gap-3 p-3 rounded-lg border border-orange-50 bg-orange-50/20 hover:bg-white hover:shadow-sm transition-all">
                                    <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center bg-orange-100 text-orange-700 rounded-full font-bold text-xs">
                                        {{ $originalIdx }}
                                    </div>
                                    <div class="flex-grow">
                                        <div class="mb-1 font-semibold text-[10px] text-orange-400 uppercase">Soal {{ $originalIdx }} (ESSAY)</div>
                                        <div class="prose prose-sm max-w-none text-gray-800">
                                            {!! $question->question?->question ?? $question->question ?? '-' !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </details>
    </div>
</div>