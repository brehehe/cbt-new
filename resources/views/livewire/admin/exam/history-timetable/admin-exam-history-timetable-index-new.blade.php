<div>
    <!-- Modern Header Section -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 p-8 text-white shadow-2xl">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight drop-shadow-sm">
                    Riwayat Ujian 📜
                </h1>
                <p class="text-teal-100 mt-2 text-sm md:text-base font-medium">Cek hasil jerih payahmu di sini! Udah maksimal belum? ✨</p>
            </div>
        </div>
    </div>

    <!-- Table Controls -->
    <!-- Table Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white/70 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white px-4 py-2.5 w-full md:w-auto transition-all duration-300 hover:shadow-[0_8px_30px_rgb(16,185,129,0.08)]">
            <span class="text-sm font-bold text-slate-500 mr-3">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-emerald-600 font-black bg-transparent w-16 cursor-pointer"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm font-bold text-slate-500 ml-2">data</span>
        </div>

        <div class="w-full md:w-80">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-emerald-500 text-slate-400">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text"
                    class="block w-full pl-11 pr-4 py-3 border border-white rounded-2xl leading-5 bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 font-medium text-slate-700 sm:text-sm transition-all duration-300 hover:shadow-[0_8px_30px_rgb(16,185,129,0.08)]"
                    placeholder="Cari Sesuatu..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <!-- Desktop View (Table) -->
    <div class="hidden md:block bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden mb-6">
        <div class="table-container overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-emerald-50/50">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-16 rounded-tl-3xl">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Nama Ujian</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Jam Mulai</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Jam Akhir</th>
                        @if($companyData->is_mark)
                            <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Nilai</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-emerald-800 uppercase tracking-wider">Skala Penilaian</th>
                        @endif
                        <th class="px-6 py-4 text-center text-xs font-bold text-emerald-800 uppercase tracking-wider w-32 rounded-tr-3xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/50">
                    @forelse ($userTimetables as $index => $userTimetable)
                        <tr class="hover:bg-white/90 transition-all duration-300 group">
                            <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-bold text-emerald-300 group-hover:text-emerald-500">
                                {{ $userTimetables->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-black text-slate-800">
                                {{ $userTimetable->timetable->name ?? '-' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-slate-600">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-xl w-max">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ $userTimetable?->timetable?->module?->duration ?? 0 }} Menit
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-600">
                                <div class="flex flex-col">
                                    <span>{{ Carbon\Carbon::parse($userTimetable?->timetable?->start_time)->format('d M Y') ?? '-' }}</span>
                                    <span class="text-xs text-slate-400">{{ Carbon\Carbon::parse($userTimetable?->timetable?->start_time)->format('H:i') ?? '-' }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-bold text-slate-600">
                                <div class="flex flex-col">
                                    <span>{{ Carbon\Carbon::parse($userTimetable?->timetable?->end_time)->format('d M Y') ?? '-' }}</span>
                                    <span class="text-xs text-slate-400">{{ Carbon\Carbon::parse($userTimetable?->timetable?->end_time)->format('H:i') ?? '-' }} WIB</span>
                                </div>
                            </td>
                            @if($companyData->is_mark)
                                @php
                                    $gradeDetail = $this->getGradeDetail($userTimetable->mark);
                                @endphp
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-xl font-black bg-clip-text text-transparent bg-gradient-to-r from-emerald-500 to-teal-500">
                                        {{ $userTimetable->mark ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold">
                                            {{ $gradeDetail?->grade_letter ?? '-' }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">{{ $gradeDetail?->description ?? '-' }}</span>
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium">
                                <a href="/admin/exam/history-timetable/{{ $userTimetable->timetable_id }}/{{ $userTimetable->id }}"
                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl shadow-sm text-white bg-gradient-to-r from-teal-400 to-emerald-500 hover:from-teal-500 hover:to-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-105 active:scale-95">
                                    <i class="fas fa-eye mr-1.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $companyData->is_mark ? 8 : 6 }}" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center">
                                        <i class="fa-solid fa-scroll text-3xl text-slate-300"></i>
                                    </div>
                                    <span class="text-base font-bold text-slate-600">Belum ada riwayat ujian nih 😅</span>
                                    <p class="text-sm text-slate-400">Kerjakan ujianmu dulu, baru nanti muncul di sini!</p>
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
        @forelse ($userTimetables as $index => $userTimetable)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 space-y-4 relative overflow-hidden">
                <div class="flex justify-between items-start">
                    <div class="pr-16">
                        <h3 class="text-lg font-bold text-gray-900">{{ $userTimetable->timetable->name ?? '-' }}</h3>
                        <p class="text-sm text-gray-500 font-medium mt-1">
                            {{ Carbon\Carbon::parse($userTimetable?->timetable?->start_time)->format('d M Y H:i') }} -
                            {{ Carbon\Carbon::parse($userTimetable?->timetable?->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 py-3 border-t border-b border-gray-100">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Durasi</span>
                        <span class="text-sm font-semibold text-gray-700 flex items-center mt-1">
                            <i class="fa-regular fa-clock mr-1.5 text-gray-400"></i>
                            {{ $userTimetable?->timetable?->module?->duration ?? 0 }} Menit
                        </span>
                    </div>
                    @if($companyData->is_mark)
                        @php
                            $gradeDetail = $this->getGradeDetail($userTimetable->mark);
                        @endphp
                        <div class="flex flex-col items-end">
                            <span class="text-xs text-gray-400 uppercase tracking-wider">Nilai</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span
                                    class="text-lg font-bold text-[color:var(--primary)]">{{ $userTimetable->mark ?? '-' }}</span>
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold rounded bg-gray-100 text-gray-700">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="pt-1">
                    <a href="/admin/exam/history-timetable/{{ $userTimetable->timetable_id }}/{{ $userTimetable->id }}"
                        class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <i class="fas fa-eye mr-2"></i> Detail
                    </a>
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
                    <span class="text-base font-medium text-gray-500">Tidak ada data riwayat ujian</span>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200 rounded-b-xl md:rounded-b-none">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-sm text-gray-700 text-center md:text-left">
                Menampilkan <span class="font-medium">{{ $userTimetables->firstItem() }}</span> sampai <span
                    class="font-medium">{{ $userTimetables->lastItem() }}</span> dari <span
                    class="font-medium">{{ $userTimetables->total() }}</span> hasil
            </div>
            <div class="flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    {{ $userTimetables->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                </nav>
            </div>
        </div>
    </div>
</div>