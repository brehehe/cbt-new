<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                    Riwayat Ujian Detail</h1>
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
                });"
                    wire:model='module_id' id="module_id">
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
                });"
                    wire:model.lazy="supervisors" id="supervisors" multiple>
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
                <input disabled type="text" id="rating_scale" value="{{ $gradeDetail?->grade_letter ?? '-' }}" placeholder="Masukkan"
                    class="mt-1 form-control">
            </div>
        @endif
    </div>
    
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
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        
        <!-- Desktop Table View -->
        <div class="hidden md:block table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Soal</th>
                        <th>Jawaban</th>
                        <th>Jawaban Terpilih</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userModuleQuestions as $index => $userModuleQuestion)
                        @php
                            // 1) Koleksi semua pilihan jawaban untuk soal ini
                            $answers = $userModuleQuestion->timetableQuestion?->answers ?? collect();

                            // 2) Jawaban BENAR
                            $correctAnswer = $answers->firstWhere('is_correct', true);

                            // 3) Jawaban yang dipilih user (relasi answer: belongsTo/hasOne)
                            $chosenAnswer = $userModuleQuestion->timetableAnswer; // <- bisa null

                            // 4) Posisi jawaban benar di koleksi (0‑based)
                            $posCorrect = $answers->search(fn($a) => $a->is_correct);
                            $labelCorrect = $posCorrect !== false ? $posCorrect + 1 : null;

                            // 5) Posisi jawaban yang dipilih user di koleksi (0‑based)
                            $posChosen = $answers->search(fn($a) => $a->id === optional($chosenAnswer)->id);
                            $labelChosen = $posChosen !== false ? $posChosen + 1 : null;

                            // 6) Fungsi util untuk A/B/C/… (opsional)
                            $letter = fn($n) => $n ? chr(64 + $n) : '-'; // 1→A, 2→B, …
                        @endphp
                        <tr>
                            <td class="center">{{ $userModuleQuestions->firstItem() + $index }}</td>

                            {{-- Pertanyaan --}}
                            <td>{{ optional($userModuleQuestion->timetableQuestion)->question ?? '-' }}</td>

                            {{-- Jawaban benar --}}
                            <td>
                                {{ $letter($labelCorrect) }}.
                                {{ optional($correctAnswer)->context ?? '-' }}
                            </td>

                            {{-- Jawaban yang dipilih user --}}
                            <td>
                                {{ $letter($labelChosen) }}.
                                {{ optional($chosenAnswer)->context ?? '-' }}
                            </td>
                            <td>
                                @if ($userModuleQuestion->status === 'correct')
                                    <span
                                        class="px-2 py-1 rounded bg-green-100 text-green-700 font-semibold">Benar</span>
                                @elseif ($userModuleQuestion->status === 'wrong')
                                    <span class="px-2 py-1 rounded bg-red-100 text-red-700 font-semibold">Salah</span>
                                @else
                                    <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 font-semibold">Tidak
                                        Terjawab</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4 p-4">
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
                    }
                @endphp
                
                <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div class="p-3 border-b border-gray-100 flex justify-between items-center {{ $statusColor }}">
                        <span class="font-bold text-gray-700">Soal #{{ $userModuleQuestions->firstItem() + $index }}</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }} {{ $statusTextColor }} border">
                            {{ $statusText }}
                        </span>
                    </div>
                    
                    <div class="p-4 space-y-3">
                        <div class="text-gray-800 font-medium pb-2 border-b border-gray-50">
                             {{ optional($userModuleQuestion->timetableQuestion)->question ?? '-' }}
                        </div>
                        
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div class="p-2 bg-gray-50 rounded border border-gray-100">
                                <span class="block text-xs text-gray-500 mb-1">Jawaban Anda</span>
                                <div class="font-medium {{ $userModuleQuestion->status === 'correct' ? 'text-green-600' : ($userModuleQuestion->status === 'wrong' ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ $letter($labelChosen) }}. {{ optional($chosenAnswer)->context ?? '-' }}
                                </div>
                            </div>
                            
                            <div class="p-2 bg-gray-50 rounded border border-gray-100">
                                <span class="block text-xs text-gray-500 mb-1">Jawaban Benar</span>
                                <div class="font-medium text-green-600">
                                     {{ $letter($labelCorrect) }}. {{ optional($correctAnswer)->context ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             @empty
                <div class="text-center p-8 text-gray-500">
                    Tidak ada data
                </div>
             @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $userModuleQuestions->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $userModuleQuestions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $userModuleQuestions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $userModuleQuestions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
