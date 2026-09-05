<div>
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Detail Riwayat Ujian
                </h1>
                <p class="text-gray-600 text-sm mt-1">Lihat detail hasil ujian dan statistik pengerjaan Anda.</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" value="{{ $timetable['name'] }}" disabled placeholder="Masukkan Nama"
                class="mt-1 form-control">
        </div>
        <div>
            <label for="module_id" class="block text-sm font-medium text-gray-700">Modul</label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('module_id', e ? e : '');
                    }
                });" wire:model='module_id' id="module_id">
                    <option value="">-- Pilih Modul --</option>
                    @foreach ($modules as $key_module => $module)
                        <option value="{{ $key_module }}">{{ $module }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
            <input disabled type="text" id="start_time" value="{{ $start_time }}" placeholder="Masukkan"
                class="mt-1 form-control">
        </div>
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
            <input disabled type="text" id="end_time" value="{{ $end_time }}" placeholder="Masukkan"
                class="mt-1 form-control">
        </div>
        <div class="col-span-1 md:col-span-2 lg:col-span-4">
            <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas</label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('supervisors', e ? e : '');
                    }
                });" wire:model.lazy="supervisors" id="supervisors" multiple>
                    <option value="">-- Pilih Pengawas --</option>
                    @foreach ($getSupervisors as $key_getSupervisor => $getSupervisor)
                        <option value="{{ $key_getSupervisor }}">{{ $getSupervisor }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if($companyData->is_mark)
            @php
                $gradeDetail = $this->getGradeDetail($user_timetable->mark);
            @endphp
            <div>
                <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai</label>
                <input disabled type="text" id="nilai" value="{{ $user_timetable->mark ?? '-' }}" placeholder="Masukkan"
                    class="mt-1 form-control">
            </div>
            <div class="col-span-1 md:col-span-2 lg:col-span-3">
                <label for="rating_scale" class="block text-sm font-medium text-gray-700">Skala Penilaian</label>
                <input disabled type="text" id="rating_scale" value="{{ $gradeDetail?->grade_letter ?? '-' }}"
                    placeholder="Masukkan" class="mt-1 form-control">
            </div>
        @endif
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
    <!-- Desktop View (Table) -->
    <div
        class="hidden md:block bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 text-center">
                            No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jawaban Benar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jawaban Terpilih</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($userModuleQuestions as $index => $userModuleQuestion)
                        @php
                            $answers = $userModuleQuestion->timetableQuestion?->answers ?? collect();
                            $correctAnswer = $answers->firstWhere('is_correct', true);
                            $chosenAnswer = $userModuleQuestion->timetableAnswer;
                            $posCorrect = $answers->search(fn($a) => $a->is_correct);
                            $labelCorrect = $posCorrect !== false ? $posCorrect + 1 : null;
                            $posChosen = $answers->search(fn($a) => $a->id === optional($chosenAnswer)->id);
                            $labelChosen = $posChosen !== false ? $posChosen + 1 : null;
                            $letter = fn($n) => $n ? chr(64 + $n) : '-';
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $userModuleQuestions->firstItem() + $index }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium min-w-[200px] max-w-xs" x-data="{ expanded: false, truncated: false }" x-init="$nextTick(() => { truncated = $refs.t1.scrollWidth > $refs.t1.clientWidth })">
                                <div x-ref="t1" :style="expanded ? '' : 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'">
                                    {!! optional($userModuleQuestion->timetableQuestion)->question ?? '-' !!}
                                </div>
                                @if($userModuleQuestion->timetableQuestion?->type === 'essay')
                                    <span class="block text-[10px] text-blue-500 font-bold uppercase mt-1">ESSAY</span>
                                @endif
                                <button x-show="truncated || expanded" @click="expanded = !expanded" class="mt-1 text-xs text-primary hover:underline focus:outline-none" x-text="expanded ? 'Sembunyikan' : 'Lihat Selengkapnya'"></button>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 font-medium min-w-[200px] max-w-xs" x-data="{ expanded: false, truncated: false }" x-init="$nextTick(() => { truncated = $refs.t2.scrollWidth > $refs.t2.clientWidth })">
                                <div x-ref="t2" :style="expanded ? '' : 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'">
                                    {!! optional($userModuleQuestion->timetableQuestion)->description ?? '-' !!}
                                </div>
                                <button x-show="truncated || expanded" @click="expanded = !expanded" class="mt-1 text-xs text-primary hover:underline focus:outline-none" x-text="expanded ? 'Sembunyikan' : 'Lihat Selengkapnya'"></button>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px] max-w-[180px]" x-data="{ expanded: false, truncated: false }" x-init="$nextTick(() => { truncated = $refs.t3.scrollWidth > $refs.t3.clientWidth })">
                                @if($correctAnswer)
                                    <div x-ref="t3" :style="expanded ? '' : 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'">
                                        <span class="font-semibold text-gray-700 inline-block mr-1">{{ $letter($labelCorrect) }}.</span>
                                        {!! $correctAnswer->context ?? '-' !!}
                                    </div>
                                    <button x-show="truncated || expanded" @click="expanded = !expanded" class="mt-1 text-xs text-primary hover:underline focus:outline-none" x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></button>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 min-w-[150px] max-w-[180px]" x-data="{ expanded: false, truncated: false }" x-init="$nextTick(() => { truncated = $refs.t4.scrollWidth > $refs.t4.clientWidth })">
                                @if($userModuleQuestion->timetableQuestion?->type === 'essay')
                                    <div x-ref="t4" class="prose prose-sm max-w-none text-gray-700 italic" :style="expanded ? '' : 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'">
                                        {!! $userModuleQuestion->essay_answer ?: '<span class="text-gray-400">Tidak ada jawaban</span>' !!}
                                    </div>
                                    <button x-show="truncated || expanded" @click="expanded = !expanded" class="mt-1 text-xs text-primary hover:underline focus:outline-none" x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></button>
                                @elseif($chosenAnswer)
                                    <div x-ref="t4" :style="expanded ? '' : 'white-space:nowrap;overflow:hidden;text-overflow:ellipsis;'">
                                        <span class="font-semibold text-gray-700 inline-block mr-1">{{ $letter($labelChosen) }}.</span>
                                        {!! $chosenAnswer->context ?? '-' !!}
                                    </div>
                                    <button x-show="truncated || expanded" @click="expanded = !expanded" class="mt-1 text-xs text-primary hover:underline focus:outline-none" x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></button>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @if ($userModuleQuestion->status === 'correct')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Benar</span>
                                @elseif ($userModuleQuestion->status === 'wrong')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Salah</span>
                                @elseif ($userModuleQuestion->status === 'check')
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Menunggu
                                        Koreksi</span>
                                @else
                                    <span
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Tidak
                                        Terjawab</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    <span class="text-base font-medium">Tidak ada data hasil ujian</span>
                                    <p class="text-sm text-gray-400 mt-1">Coba sesuaikan filter pencarian anda</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile View (Cards) -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse ($userModuleQuestions as $index => $userModuleQuestion)
            @php
                $answers = $userModuleQuestion->timetableQuestion?->answers ?? collect();
                $correctAnswer = $answers->firstWhere('is_correct', true);
                $chosenAnswer = $userModuleQuestion->timetableAnswer;
                $posCorrect = $answers->search(fn($a) => $a->is_correct);
                $labelCorrect = $posCorrect !== false ? $posCorrect + 1 : null;
                $posChosen = $answers->search(fn($a) => $a->id === optional($chosenAnswer)->id);
                $labelChosen = $posChosen !== false ? $posChosen + 1 : null;
                $letter = fn($n) => $n ? chr(64 + $n) : '-';

                $statusColor = 'bg-gray-50 border-gray-200';
                $statusText = 'Tidak Terjawab';
                $statusTextColor = 'text-gray-600';

                if ($userModuleQuestion->status === 'correct') {
                    $statusColor = 'bg-green-50 border-green-200';
                    $statusText = 'Benar';
                    $statusTextColor = 'text-green-700';
                } elseif ($userModuleQuestion->status === 'wrong') {
                    $statusColor = 'bg-red-50 border-red-200';
                    $statusText = 'Salah';
                    $statusTextColor = 'text-red-700';
                } elseif ($userModuleQuestion->status === 'check') {
                    $statusColor = 'bg-blue-50 border-blue-200';
                    $statusText = 'Menunggu Koreksi';
                    $statusTextColor = 'text-blue-700';
                }
            @endphp

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center {{ $statusColor }}">
                    <span class="font-bold text-gray-700">Soal #{{ $userModuleQuestions->firstItem() + $index }}</span>
                    <span
                        class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }} {{ $statusTextColor }} border">
                        {{ $statusText }}
                    </span>
                </div>

                <div class="p-5 space-y-4">
                    <div class="text-gray-800 font-medium pb-3 border-b border-gray-50">
                        {{ optional($userModuleQuestion->timetableQuestion)->question ?? '-' }}
                    </div>

                    <div class="grid grid-cols-1 gap-3 text-sm">
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Jawaban Anda</span>
                            <div
                                class="font-medium {{ $userModuleQuestion->status === 'correct' ? 'text-green-600' : ($userModuleQuestion->status === 'wrong' ? 'text-red-600' : ($userModuleQuestion->status === 'check' ? 'text-blue-600' : 'text-gray-600')) }}">
                                @if($userModuleQuestion->timetableQuestion?->type === 'essay')
                                    {!! $userModuleQuestion->essay_answer ?: '<span class="text-gray-400">Tidak ada jawaban</span>' !!}
                                @elseif($chosenAnswer)
                                    {{ $letter($labelChosen) }}. {{ $chosenAnswer->context ?? '-' }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>

                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="block text-xs text-gray-500 uppercase tracking-wider mb-1">Jawaban Benar</span>
                            <div class="font-medium text-green-600">
                                @if($correctAnswer)
                                    {{ $letter($labelCorrect) }}. {{ $correctAnswer->context ?? '-' }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                    <span class="text-base font-medium text-gray-500">Tidak ada data hasil ujian</span>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-xl md:rounded-b-none">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700 text-center md:text-left">
                Menampilkan <span class="font-medium">{{ $userModuleQuestions->firstItem() }}</span> sampai <span
                    class="font-medium">{{ $userModuleQuestions->lastItem() }}</span> dari <span
                    class="font-medium">{{ $userModuleQuestions->total() }}</span> hasil
            </div>
            <div class="flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{ $userModuleQuestions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                </nav>
            </div>
        </div>
    </div>
</div>