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
    <div class="grid grid-cols-4 gap-4 mb-4">
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
        <div class="md:col-span-4">
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
            @php($gradeDetail = $this->getGradeDetail($user_timetable->mark))
            <div>
                <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai</label>
                <input disabled type="text" id="nilai" value="{{ $user_timetable->mark ?? '-' }}" placeholder="Masukkan"
                    class="mt-1 form-control">
            </div>
            <div class="md:col-span-3">
                <label for="rating_scale" class="block text-sm font-medium text-gray-700">Skala Penilaian</label>
                <div id="rating_scale" class="mt-1 form-control bg-gray-50">
                    <div class="flex flex-col">
                        <span class="font-semibold">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                        <span class="text-xs text-gray-500">{{ $gradeDetail?->description ?? '-' }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{-- <div class="grid grid-cols-5 gap-4 mb-4">
        <div>
            <label for="total_soal" class="block text-sm font-medium text-gray-700">Total Soal</label>
            <input disabled type="number" id="total_soal" value="{{ $user_timetable->userModuleQuestions->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="terjawab" class="block text-sm font-medium text-gray-700">Terjawab</label>
            <input disabled type="number" id="terjawab"
                value="{{ $user_timetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="tidak_terjawab" class="block text-sm font-medium text-gray-700">Tidak Terjawab</label>
            <input disabled type="number" id="tidak_terjawab"
                value="{{ $user_timetable->userModuleQuestions->whereNull('timetable_answer_id')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="benar" class="block text-sm font-medium text-gray-700">Benar</label>
            <input disabled type="number" id="benar"
                value="{{ $user_timetable->userModuleQuestions->where('status', 'correct')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div>
            <label for="salah" class="block text-sm font-medium text-gray-700">Salah</label>
            <input disabled type="number" id="salah"
                value="{{ $user_timetable->userModuleQuestions->where('status', 'wrong')->count() }}"
                placeholder="Masukkan" class="mt-1 form-control">
        </div>
        <div class="md:col-span-5">
            <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai</label>
            <input disabled type="number" id="nilai" value="{{ $user_timetable->mark }}" placeholder="Masukkan"
                class="mt-1 form-control">
        </div>
    </div> --}}
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
        <div class="table-container">
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
