<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-primary">
                    Nilai Ujian Detail</h1>
            </div>
            <div>
                <button wire:click="exportPdf" class="btn btn-primary">
                    <i class="fa-solid fa-file-pdf mr-2"></i>
                    Export PDF
                </button>
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
        <div class="md:col-span-4">
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
    </div>
    <div class="grid grid-cols-5 gap-4 mb-4">
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
    </div>
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
                            <td class="whitespace-nowrap">
                                {{ $letter($labelCorrect) }}.
                                {{ optional($correctAnswer)->context ?? '-' }}
                            </td>

                            {{-- Jawaban yang dipilih user --}}
                            <td class="whitespace-nowrap">
                                {{ $letter($labelChosen) }}.
                                {{ optional($chosenAnswer)->context ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap">
                                @if ($userModuleQuestion->status === 'correct')
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-700 font-semibold">Benar</span>
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