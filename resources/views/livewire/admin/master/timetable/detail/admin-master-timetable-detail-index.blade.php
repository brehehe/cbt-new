<div>
    @if(is_lemes())
        <!-- ========================================================================= -->
        <!-- LEMES FORMAT (IS_LEMES = true): Rekap Kehadiran & Nilai Hasil Ujian       -->
        <!-- ========================================================================= -->
        <div class="space-y-6 mb-6">
            <!-- Header Kartu Utama -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                                <i class="fas fa-calendar-days"></i>
                            </span>
                            <h1 class="text-xl sm:text-2xl font-black text-slate-800">
                                {{ $timetable['name'] }}
                            </h1>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800">
                                <i class="fas fa-users text-[10px]"></i>
                                Kelas: {{ $timetableModel?->classmate?->name ?? 'Semua Kelas' }}
                            </span>
                        </div>
                        @if(!empty($timetable['description']))
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $timetable['description'] }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Action Export Sesuai Tab Aktif -->
                        @if($activeTab === 'attendance')
                            <button wire:click="exportAttendancePdf"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-sm transition cursor-pointer">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>Export PDF Presensi</span>
                            </button>
                            <button wire:click="exportAttendanceExcel"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition cursor-pointer">
                                <i class="fa-solid fa-file-excel"></i>
                                <span>Export Excel Presensi</span>
                            </button>
                        @else
                            <button wire:click="exportPdf"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-sm transition cursor-pointer">
                                <i class="fa-solid fa-file-pdf"></i>
                                <span>Export PDF Nilai</span>
                            </button>
                            <button wire:click="exportExcel"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition cursor-pointer">
                                <i class="fa-solid fa-file-excel"></i>
                                <span>Export Excel Nilai</span>
                            </button>
                        @endif

                        <!-- Tombol Kembali -->
                        <a href="{{ route('admin.master.timetable') }}"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 shadow-xs transition cursor-pointer">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>

                <!-- Filter Sesi Detail (Pill Selector) -->
                @if($timetableModel && $timetableModel->timetableDetails->isNotEmpty())
                    <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-500 mr-1">Filter Sesi:</span>
                        <button type="button" wire:click="filterDetail(null)"
                            class="px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer {{ is_null($selectedDetailId) ? 'bg-indigo-600 text-white shadow-xs' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                            Semua Sesi ({{ $timetableModel->timetableDetails->count() }})
                        </button>
                        @foreach($timetableModel->timetableDetails as $d)
                            <button type="button" wire:click="filterDetail('{{ $d->id }}')"
                                class="px-3 py-1.5 rounded-xl text-xs font-semibold transition cursor-pointer flex items-center gap-1.5 {{ $selectedDetailId == $d->id ? 'bg-indigo-600 text-white shadow-xs font-bold' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                                @if($d->isMaterial())
                                    <i class="fas fa-book-open text-[10px] {{ $selectedDetailId == $d->id ? 'text-amber-300' : 'text-amber-500' }}"></i>
                                @else
                                    <i class="fas fa-file-lines text-[10px] {{ $selectedDetailId == $d->id ? 'text-blue-200' : 'text-blue-500' }}"></i>
                                @endif
                                <span>{{ $d->examRoom?->name ?? 'Ruang' }} - {{ $d->examSession?->name ?? 'Sesi' }}</span>
                                <span class="opacity-75 text-[10px]">({{ $d->isMaterial() ? ($d->digitalBook?->title ?? 'Materi') : ($d->module?->name ?? 'Ujian') }})</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Tab Selector Bar -->
            <div class="flex items-center gap-2 border-b border-slate-200 px-1">
                <button type="button" wire:click="switchTab('attendance')"
                    class="pb-3 px-4 font-bold text-sm transition cursor-pointer relative flex items-center gap-2 {{ $activeTab === 'attendance' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-clipboard-user"></i>
                    <span>Rekap Presensi Kehadiran</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] {{ $activeTab === 'attendance' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $attendanceStats['present'] }} / {{ $attendanceStats['total'] }} Hadir
                    </span>
                </button>

                <button type="button" wire:click="switchTab('scores')"
                    class="pb-3 px-4 font-bold text-sm transition cursor-pointer relative flex items-center gap-2 {{ $activeTab === 'scores' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-800' }}">
                    <i class="fas fa-chart-simple"></i>
                    <span>Hasil & Nilai Ujian</span>
                    <span class="px-2 py-0.5 rounded-full text-[11px] {{ $activeTab === 'scores' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                        {{ $userTimetables->total() }} Data
                    </span>
                </button>
            </div>

            <!-- TAB 1: REKAP KEHADIRAN / PRESENSI -->
            @if($activeTab === 'attendance')
                <div class="space-y-4">
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl border border-blue-100">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total {{ student_label() }}</div>
                                <div class="text-2xl font-black text-slate-800">{{ $attendanceStats['total'] }} <span class="text-xs font-normal text-slate-400">Orang</span></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-emerald-200 shadow-xs flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xl border border-emerald-200">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Sudah Hadir</div>
                                <div class="text-2xl font-black text-emerald-800">{{ $attendanceStats['present'] }} <span class="text-xs font-normal text-emerald-600">({{ $attendanceStats['percent'] }}%)</span></div>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-white border border-rose-200 shadow-xs flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xl border border-rose-200">
                                <i class="fas fa-user-xmark"></i>
                            </div>
                            <div>
                                <div class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Belum Hadir</div>
                                <div class="text-2xl font-black text-rose-800">{{ $attendanceStats['absent'] }} <span class="text-xs font-normal text-rose-600">Orang</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Controls -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-xs font-semibold text-slate-500">Tampil:</span>
                            <select wire:model.live="perPage" class="form-select text-xs font-bold rounded-xl border-slate-200 py-1.5 px-3 bg-slate-50">
                                <option value="10">10 data</option>
                                <option value="25">25 data</option>
                                <option value="50">50 data</option>
                                <option value="100">100 data</option>
                            </select>
                        </div>

                        <div class="w-full sm:w-72 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-search text-xs"></i>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Cari nama, NIM, atau username..."
                                class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white shadow-xs">
                        </div>
                    </div>

                    <!-- Tabel Rekap Kehadiran -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4 w-12 text-center">#</th>
                                        <th class="py-3 px-4">{{ student_label() === 'Kenshi' ? 'Nomer Kenshi / Username' : 'NIM / Username' }}</th>
                                        <th class="py-3 px-4">Nama Lengkap {{ student_label() }}</th>
                                        <th class="py-3 px-4 text-center">Status Kehadiran</th>
                                        <th class="py-3 px-4 text-center">Waktu Presensi</th>
                                        <th class="py-3 px-4 text-center">Metode</th>
                                        <th class="py-3 px-4 text-center w-36">Aksi Manual</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @if($attendanceList && $attendanceList->isNotEmpty())
                                        @foreach($attendanceList as $idx => $st)
                                            <tr class="hover:bg-slate-50 transition">
                                                <td class="py-3.5 px-4 text-center font-semibold text-slate-400">
                                                    {{ $attendanceList->firstItem() + $idx }}
                                                </td>
                                                <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                                                    {{ $st['username'] }}
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <div class="font-extrabold text-slate-800">{{ $st['name'] }}</div>
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    @if($st['has_attended'])
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-xs">
                                                            <i class="fas fa-check-circle"></i> Hadir
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 border border-rose-300 shadow-xs">
                                                            <i class="fas fa-clock"></i> Belum Hadir
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-center font-mono text-slate-600">
                                                    {{ $st['attended_at'] }}
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    @if($st['method'] !== '-')
                                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                            {{ $st['method'] }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 text-center">
                                                    @if($st['has_attended'])
                                                        <button wire:click="markManualAttendance('{{ $st['id'] }}', 'absent')"
                                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition cursor-pointer"
                                                            title="Batalkan kehadiran peserta">
                                                            Batalkan Hadir
                                                        </button>
                                                    @else
                                                        <button wire:click="markManualAttendance('{{ $st['id'] }}', 'present')"
                                                            class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 transition cursor-pointer"
                                                            title="Tandai peserta hadir manual">
                                                            Tandai Hadir
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="py-10 text-center text-slate-400 italic">
                                                Tidak ada data peserta ditemukan pada jadwal atau filter ini.
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Kehadiran -->
                        @if($attendanceList && $attendanceList->hasPages())
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                                <div class="text-xs text-slate-600">
                                    Menampilkan <span class="font-bold">{{ $attendanceList->firstItem() }}</span> - <span class="font-bold">{{ $attendanceList->lastItem() }}</span> dari <span class="font-bold">{{ $attendanceList->total() }}</span> peserta
                                </div>
                                <div>
                                    {{ $attendanceList->links('vendor.livewire.custom') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            <!-- TAB 2: HASIL & NILAI UJIAN -->
            @elseif($activeTab === 'scores')
                <div class="space-y-4">
                    <!-- Filter Controls -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-xs font-semibold text-slate-500">Tampil:</span>
                            <select wire:model.live="perPage" class="form-select text-xs font-bold rounded-xl border-slate-200 py-1.5 px-3 bg-slate-50">
                                <option value="10">10 data</option>
                                <option value="25">25 data</option>
                                <option value="50">50 data</option>
                                <option value="100">100 data</option>
                            </select>
                        </div>

                        <div class="w-full sm:w-72 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-search text-xs"></i>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Cari nama atau NIM..."
                                class="w-full pl-9 pr-3 py-1.5 text-xs rounded-xl border border-slate-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white shadow-xs">
                        </div>
                    </div>

                    <!-- Tabel Hasil Ujian -->
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead class="bg-slate-50 text-slate-600 font-bold uppercase tracking-wider border-b border-slate-200">
                                    <tr>
                                        <th class="py-3 px-4 w-12 text-center">#</th>
                                        <th class="py-3 px-4">{{ student_label() === 'Kenshi' ? 'Nomer Kenshi' : 'NIM' }}</th>
                                        <th class="py-3 px-4">Nama Peserta</th>
                                        <th class="py-3 px-4 text-center">Total Soal</th>
                                        <th class="py-3 px-4 text-center">Terjawab</th>
                                        <th class="py-3 px-4 text-center">Tidak Terjawab</th>
                                        <th class="py-3 px-4 text-center">Benar</th>
                                        <th class="py-3 px-4 text-center">Salah</th>
                                        <th class="py-3 px-4 text-center">Nilai Akhir</th>
                                        <th class="py-3 px-4 text-center w-24">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-700">
                                    @forelse ($userTimetables as $index => $userTimetable)
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="py-3.5 px-4 text-center font-semibold text-slate-400">
                                                {{ $userTimetables->firstItem() + $index }}
                                            </td>
                                            <td class="py-3.5 px-4 font-mono font-bold text-slate-700">
                                                {{ $userTimetable->user->nim ?? ($userTimetable->user->username ?? '-') }}
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <div class="font-extrabold text-slate-800">{{ $userTimetable->user->name ?? '-' }}</div>
                                            </td>
                                            <td class="py-3.5 px-4 text-center font-semibold">
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                                                    {{ $userTimetable->userModuleQuestions->count() }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                                {{ $userTimetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count() }}
                                            </td>
                                            <td class="py-3.5 px-4 text-center font-semibold text-slate-400">
                                                {{ $userTimetable->userModuleQuestions->whereNull('timetable_answer_id')->count() }}
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold border border-emerald-200">
                                                    {{ $userTimetable->userModuleQuestions->where('status', 'correct')->count() }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 font-bold border border-rose-200">
                                                    {{ $userTimetable->userModuleQuestions->where('status', 'wrong')->count() }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="text-sm font-black text-slate-800">{{ $userTimetable->mark ?? 0 }}</span>
                                                <span class="ml-1 px-1.5 py-0.5 rounded text-[10px] font-extrabold bg-blue-100 text-blue-800 border border-blue-200">
                                                    {{ $this->getGrade($userTimetable->mark) }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <button wire:click="confirmDetail('{{ $userTimetable->id }}')"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition cursor-pointer shadow-xs"
                                                    title="Lihat Rincian Jawaban Peserta">
                                                    <i class="fa-solid fa-eye text-[11px]"></i>
                                                    <span>Jawaban</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="py-10 text-center text-slate-400 italic">
                                                Belum ada data pengerjaan ujian yang tersimpan pada jadwal ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Nilai -->
                        @if($userTimetables->hasPages())
                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex justify-between items-center">
                                <div class="text-xs text-slate-600">
                                    Menampilkan <span class="font-bold">{{ $userTimetables->firstItem() }}</span> - <span class="font-bold">{{ $userTimetables->lastItem() }}</span> dari <span class="font-bold">{{ $userTimetables->total() }}</span> hasil
                                </div>
                                <div>
                                    {{ $userTimetables->links('vendor.livewire.custom') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    @else
        <!-- ========================================================================= -->
        <!-- LEGACY FORMAT (IS_LEMES = false): Format Asli Ujian Standar Tanpa Perubahan-->
        <!-- ========================================================================= -->
        <div class="mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                        {{ auth()->user()->hasRole(['Pengawas', 'pengawas']) ? 'Detail Ujian' : 'Nilai Ujian' }}
                    </h1>
                </div>
                @if(!auth()->user()->hasRole(['Pengawas', 'pengawas']))
                    <div class="flex gap-2">
                        <button wire:click="exportPdf" class="btn btn-primary !bg-red-600 !border-red-700 hover:!bg-red-700">
                            <i class="fa-solid fa-file-pdf mr-2"></i>
                            Export PDF
                        </button>
                        <button wire:click="exportExcel"
                            class="btn btn-primary !bg-green-600 !border-green-700 hover:!bg-green-700">
                            <i class="fa-solid fa-file-excel mr-2"></i>
                            Export Excel
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama <span class="text-red-600">*</span></label>
                <input type="text" id="name" value="{{ $timetable['name'] }}" disabled placeholder="Masukkan Nama" class="mt-1 form-control">
            </div>
            <div>
                <label for="module_id" class="block text-sm font-medium text-gray-700">Modul <span class="text-red-600">*</span></label>
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
            <div class="md:col-span-2">
                <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas <span class="text-red-600">*</span></label>
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
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai <span class="text-red-600">*</span></label>
                <input disabled type="text" id="start_time" value="{{ $start_time }}" placeholder="Masukkan" class="mt-1 form-control">
            </div>
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai <span class="text-red-600">*</span></label>
                <input disabled type="text" id="end_time" value="{{ $end_time }}" placeholder="Masukkan" class="mt-1 form-control">
            </div>
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" disabled value="{{ $timetable['description'] }}" placeholder="Masukkan Deskripsi" class="mt-1 form-control"></textarea>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
                <span class="text-sm text-gray-600 mr-2">Tampil</span>
                <select class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12" wire:model.live='perPage'>
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
                            <th>NIM</th>
                            <th>Nama</th>
                            @if(!auth()->user()->hasRole(['Pengawas', 'pengawas']))
                                <th>Total Soal</th>
                                <th>Terjawab</th>
                                <th>Tidak Terjawab</th>
                                <th>Benar</th>
                                <th>Salah</th>
                                <th>Nilai</th>
                            @endif
                            <th class="w-1 center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($userTimetables as $index => $userTimetable)
                            <tr>
                                <td class="center">{{ $userTimetables->firstItem() + $index }}</td>
                                <td>{{ $userTimetable->user->nim ?? ($userTimetable->user->username ?? '-') }}</td>
                                <td>{{ $userTimetable->user->name ?? '-' }}</td>
                                @if(!auth()->user()->hasRole(['Pengawas', 'pengawas']))
                                    <td><span class="px-2 py-1 rounded bg-slate-100 text-slate-700 font-semibold">{{ $userTimetable->userModuleQuestions->count() }}</span></td>
                                    <td>{{ $userTimetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count() }}</td>
                                    <td>{{ $userTimetable->userModuleQuestions->whereNull('timetable_answer_id')->count() }}</td>
                                    <td><span class="px-2 py-1 rounded bg-green-100 text-green-700 font-semibold">{{ $userTimetable->userModuleQuestions->where('status', 'correct')->count() }}</span></td>
                                    <td><span class="px-2 py-1 rounded bg-red-100 text-red-700 font-semibold">{{ $userTimetable->userModuleQuestions->where('status', 'wrong')->count() }}</span></td>
                                    <td>
                                        {{ $userTimetable->mark }}
                                        <span class="ml-2 px-2 py-1 rounded bg-blue-100 text-blue-700 font-semibold">
                                            {{ $this->getGrade($userTimetable->mark) }}
                                        </span>
                                    </td>
                                @endif
                                <td class="center">
                                    <div class="flex items-center">
                                        <button class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors delete-btn"
                                            wire:click="confirmDetail('{{ $userTimetable->id }}')">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->hasRole(['Pengawas', 'pengawas']) ? 4 : 10 }}" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $userTimetables->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $userTimetables->lastItem() }}</span> dari <span
                            class="font-medium">{{ $userTimetables->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            {{ $userTimetables->links('vendor.livewire.custom') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>