<div>
    @include('livewire.admin.exam.timetable.admin-exam-timetable-modal')
    @if(is_lemes())
        @include('livewire.admin.exam.timetable.admin-exam-timetable-modal-camera-scan')
    @endif
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    {{ is_lemes() ? 'Jadwal Kegiatan & Ujian' : 'Daftar Ujian' }}
                </h1>
                <p class="text-gray-600 text-sm mt-1">
                    {{ is_lemes() ? 'Akses materi pembelajaran dan ujian sesuai jadwal Anda.' : 'Kelola dan pantau jadwal ujian yang tersedia.' }}
                </p>
            </div>
            @if(is_lemes())
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <!-- Tombol Utama: Scan QRCODE Presensi (Buka Kamera) -->
                    <button wire:click="openStudentScanner"
                        class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-extrabold text-xs rounded-xl shadow-md transition cursor-pointer animate-pulse hover:animate-none">
                        <i class="fas fa-camera text-sm"></i>
                        <span>Scan QR Presensi</span>
                    </button>

                    <!-- Tombol Opsi: QRCODE Identitas Saya -->
                    <button wire:click="showMyQrCode"
                        class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-300 transition cursor-pointer shrink-0"
                        title="Tampilkan QRCODE Identitas Anda">
                        <i class="fas fa-qrcode text-sm"></i>
                        <span class="hidden sm:inline">QRCODE Saya</span>
                    </button>
                </div>
            @endif
        </div>
        @if(is_lemes())
            <div class="mt-4 p-3.5 bg-amber-50 border border-amber-200 rounded-2xl flex items-center justify-between gap-3 text-xs text-amber-950 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-xl bg-amber-400 text-slate-950 flex items-center justify-center font-bold shrink-0 shadow-xs">
                        <i class="fas fa-qrcode text-xs"></i>
                    </span>
                    <span>
                        <b>Alur Presensi:</b> Untuk sesi yang mewajibkan absensi, jadwal ujian/materi akan <b>otomatis muncul</b> setelah Anda melakukan scan QRCODE sesi yang disediakan atau dicetak oleh Pengawas.
                    </span>
                </div>
                <button wire:click="openStudentScanner" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-bold rounded-xl shadow-xs transition shrink-0 cursor-pointer">
                    <i class="fas fa-camera text-xs"></i>
                    <span>Scan Presensi</span>
                </button>
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
                    placeholder="Cari Ujian, Modul..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    @if(is_lemes())
        @include('livewire.admin.exam.timetable.admin-exam-timetable-index-lemes')
    @else
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Durasi</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">
                            Deskripsi</th>
                        <th
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($timetables as $index => $timetable)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ $timetables->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                <div>{{ $timetable->name ?? '-' }}</div>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @if ($timetable->isSimulation())
                                        <span class="inline-flex items-center rounded bg-purple-100 px-1.5 py-0.5 text-xs font-semibold text-purple-700">
                                            Simulasi
                                        </span>
                                    @endif
                                    @if ($timetable->allowsRepeat())
                                        <span class="inline-flex items-center rounded bg-blue-100 px-1.5 py-0.5 text-xs font-semibold text-blue-700">
                                            Dapat Diulang
                                        </span>
                                    @endif
                                    @if (!$timetable->requiresToken())
                                        <span class="inline-flex items-center rounded bg-emerald-100 px-1.5 py-0.5 text-xs font-semibold text-emerald-700">
                                            Tanpa Token
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $timetable->timetableModule?->questionType?->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $timetable->timetableModule?->name ?? $timetable->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fa-regular fa-clock mr-2 text-gray-400"></i>
                                    {{ $timetable->timetableModule?->duration ?? 0 }} Menit
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 line-clamp-2 max-w-xs"
                                title="{{ $timetable->timetableModule?->description ?? $timetable->description ?? '-' }}">
                                {{ Str::limit($timetable->timetableModule?->description ?? $timetable->description ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-1.5">
                                    @if (!$timetable->userTimetable)
                                        <button
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg"
                                            wire:click="openModalStartExam('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-book mr-1.5"></i> Masuk
                                        </button>
                                    @else
                                        @if ($timetable->userTimetable->status === 'done')
                                            @if ($timetable->allowsRepeat())
                                                <button
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none transition-all shadow-md"
                                                    wire:click="repeatExam('{{ $timetable->userTimetable->id }}')">
                                                    <i class="fa-solid fa-rotate-right mr-1.5"></i> Ulang Ujian
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.exam.history-timetable.detail', ['timetable_id' => $timetable->id, 'user_timetable_id' => $timetable->userTimetable->id]) }}"
                                                class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                                                <i class="fa-solid fa-eye mr-1.5"></i> Hasil
                                            </a>
                                        @else
                                            <button
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-md hover:shadow-lg"
                                                wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')">
                                                <i class="fa-regular fa-book-open-cover mr-1.5"></i> Kembali
                                            </button>
                                        @endif
                                    @endif

                                    @if(auth()->user()->hasRole(['admin', 'superadmin', 'Admin', 'Super Admin']))
                                        <button
                                            class="inline-flex items-center px-2.5 py-1.5 border border-indigo-200 text-xs font-semibold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-all shadow-sm"
                                            wire:click="openModalSupervisor('{{ $timetable->id }}')"
                                            title="Ubah Pengawas Ujian">
                                            <i class="fa-solid fa-user-shield mr-1"></i> Pengawas
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                        </path>
                                    </svg>
                                    <span class="text-base font-medium">Tidak ada data ujian tersedia</span>
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
        @forelse ($timetables as $timetable)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 flex flex-col items-end gap-1">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $timetable->timetableModule?->questionType?->name ?? '-' }}
                    </span>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 pr-16">{{ $timetable->name ?? '-' }}</h3>
                    <div class="flex flex-wrap gap-1 mt-1">
                        @if ($timetable->isSimulation())
                            <span class="inline-flex items-center rounded bg-purple-100 px-1.5 py-0.5 text-xs font-semibold text-purple-700">
                                Simulasi
                            </span>
                        @endif
                        @if ($timetable->allowsRepeat())
                            <span class="inline-flex items-center rounded bg-blue-100 px-1.5 py-0.5 text-xs font-semibold text-blue-700">
                                Dapat Diulang
                            </span>
                        @endif
                        @if (!$timetable->requiresToken())
                            <span class="inline-flex items-center rounded bg-emerald-100 px-1.5 py-0.5 text-xs font-semibold text-emerald-700">
                                Tanpa Token
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 font-medium mt-1">{{ $timetable->timetableModule?->name ?? $timetable->name ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 py-2 border-t border-b border-gray-100">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Durasi</span>
                        <span class="text-sm font-semibold text-gray-700 flex items-center mt-1">
                            <i class="fa-regular fa-clock mr-1.5 text-gray-400"></i>
                            {{ $timetable->timetableModule?->duration ?? 0 }} Menit
                        </span>
                    </div>
                </div>

                @if($timetable->timetableModule?->description ?? $timetable->description)
                    <div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Deskripsi</span>
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $timetable->timetableModule?->description ?? $timetable->description }}
                        </p>
                    </div>
                @endif

                <div class="pt-2 flex flex-wrap gap-2">
                    @if (!$timetable->userTimetable)
                        <button
                            class="flex-1 flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                            wire:click="openModalStartExam('{{ $timetable->id }}')">
                            <i class="fa-solid fa-book mr-2"></i> Masuk Ujian
                        </button>
                    @else
                        @if ($timetable->userTimetable->status === 'done')
                            @if ($timetable->allowsRepeat())
                                <button
                                    class="flex-1 flex justify-center items-center px-3 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 transition-all"
                                    wire:click="repeatExam('{{ $timetable->userTimetable->id }}')">
                                    <i class="fa-solid fa-rotate-right mr-1.5"></i> Ulang Ujian
                                </button>
                            @endif
                            <a href="{{ route('admin.exam.history-timetable.detail', ['timetable_id' => $timetable->id, 'user_timetable_id' => $timetable->userTimetable->id]) }}"
                                class="flex-1 flex justify-center items-center px-3 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all">
                                <i class="fa-solid fa-eye mr-1.5"></i> Lihat Hasil
                            </a>
                        @else
                            <button
                                class="flex-1 flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all"
                                wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')">
                                <i class="fa-regular fa-book-open-cover mr-2"></i> Kembali Ujian
                            </button>
                        @endif
                    @endif

                    @if(auth()->user()->hasRole(['admin', 'superadmin', 'Admin', 'Super Admin']))
                        <button
                            class="flex justify-center items-center px-3 py-2.5 border border-indigo-200 text-sm font-semibold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-all"
                            wire:click="openModalSupervisor('{{ $timetable->id }}')"
                            title="Ubah Pengawas Ujian">
                            <i class="fa-solid fa-user-shield mr-1"></i> Pengawas
                        </button>
                    @endif
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
                    <span class="text-base font-medium text-gray-500">Tidak ada data ujian tersedia</span>
                </div>
            </div>
        @endforelse
    </div>
    @endif

    <!-- Pagination -->
    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700 text-center md:text-left">
                Menampilkan <span class="font-medium">{{ $timetables->firstItem() }}</span> sampai <span
                    class="font-medium">{{ $timetables->lastItem() }}</span> dari <span
                    class="font-medium">{{ $timetables->total() }}</span> hasil
            </div>
            <div class="flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{ $timetables->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                </nav>
            </div>
        </div>
    </div>
</div>