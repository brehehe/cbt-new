<div>
    @include('livewire.admin.exam.timetable.admin-exam-timetable-modal')
    <!-- Modern Header Section -->
    <div class="mb-8 relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 p-8 text-white shadow-2xl">
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <h1 class="text-3xl md:text-4xl font-black tracking-tight drop-shadow-sm">
                    Daftar Ujian 📝
                </h1>
                <p class="text-indigo-100 mt-2 text-sm md:text-base font-medium">Ready buat ujian? Pilih jadwalmu di bawah ini! 🚀</p>
            </div>
        </div>
    </div>

    <!-- Table Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white/70 backdrop-blur-xl rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white px-4 py-2.5 w-full md:w-auto transition-all duration-300 hover:shadow-[0_8px_30px_rgb(99,102,241,0.08)]">
            <span class="text-sm font-bold text-slate-500 mr-3">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-indigo-600 font-black bg-transparent w-16 cursor-pointer"
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
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 group-focus-within:text-indigo-500 text-slate-400">
                    <i class="fas fa-search"></i>
                </div>
                <input type="text"
                    class="block w-full pl-11 pr-4 py-3 border border-white rounded-2xl leading-5 bg-white/70 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 font-medium text-slate-700 sm:text-sm transition-all duration-300 hover:shadow-[0_8px_30px_rgb(99,102,241,0.08)]"
                    placeholder="Cari Ujian, Modul..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <!-- Desktop View (Table) -->
    <div class="hidden md:block bg-white/70 backdrop-blur-xl rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-white overflow-hidden mb-6">
        <div class="table-container overflow-x-auto">
            <table class="table w-full">
                <thead class="bg-indigo-50/50">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-indigo-800 uppercase tracking-wider w-16 rounded-tl-3xl">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Modul</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-indigo-800 uppercase tracking-wider w-1/3">Deskripsi</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-indigo-800 uppercase tracking-wider w-32 rounded-tr-3xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-indigo-50/50">
                    @forelse ($timetables as $index => $timetable)
                        <tr class="hover:bg-white/90 transition-all duration-300 group">
                            <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-bold text-indigo-300 group-hover:text-indigo-500">
                                {{ $timetables->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap font-black text-slate-800">{{ $timetable->name ?? '-' }}</td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <span class="px-3 py-1.5 inline-flex text-xs font-bold rounded-xl bg-purple-100 text-purple-700">
                                    {{ $timetable->timetableModule->questionType->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-slate-600">
                                {{ $timetable->timetableModule->name ?? '-' }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-slate-600">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-xl w-max">
                                    <i class="fa-regular fa-clock"></i>
                                    {{ $timetable->timetableModule->duration ?? 0 }} Menit
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm text-slate-500 line-clamp-2 max-w-xs leading-relaxed" title="{{ $timetable->timetableModule->description ?? '-' }}">
                                {{ Str::limit($timetable->timetableModule->description ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-center text-sm font-medium">
                                @if (!$timetable->userTimetable)
                                    <button
                                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl shadow-sm text-white bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105 active:scale-95"
                                        wire:click="openModalStartExam('{{ $timetable->id }}')">
                                        <i class="fa-solid fa-rocket mr-1.5"></i> Masuk
                                    </button>
                                @else
                                    <button
                                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-xs font-bold rounded-xl shadow-sm text-white bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-500 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all transform hover:scale-105 active:scale-95"
                                        wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')">
                                        <i class="fa-solid fa-rotate-right mr-1.5"></i> Lanjut
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center">
                                        <i class="fa-regular fa-folder-open text-3xl text-slate-300"></i>
                                    </div>
                                    <span class="text-base font-bold text-slate-600">Yah, belum ada ujian nih 😴</span>
                                    <p class="text-sm text-slate-400">Coba cek lagi nanti atau reset filter pencarianmu.</p>
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
                <div class="absolute top-0 right-0 p-4">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $timetable->timetableModule->questionType->name ?? '-' }}
                    </span>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-900 pr-16">{{ $timetable->name ?? '-' }}</h3>
                    <p class="text-sm text-gray-500 font-medium">{{ $timetable->timetableModule->name ?? '-' }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 py-2 border-t border-b border-gray-100">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-400 uppercase tracking-wider">Durasi</span>
                        <span class="text-sm font-semibold text-gray-700 flex items-center mt-1">
                            <i class="fa-regular fa-clock mr-1.5 text-gray-400"></i>
                            {{ $timetable->timetableModule->duration ?? 0 }} Menit
                        </span>
                    </div>
                </div>

                @if($timetable->timetableModule->description)
                    <div>
                        <span class="text-xs text-gray-400 uppercase tracking-wider block mb-1">Deskripsi</span>
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ $timetable->timetableModule->description }}
                        </p>
                    </div>
                @endif

                <div class="pt-2">
                    @if (!$timetable->userTimetable)
                        <button
                            class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
                            wire:click="openModalStartExam('{{ $timetable->id }}')">
                            <i class="fa-solid fa-book mr-2"></i> Masuk Ujian
                        </button>
                    @else
                        <button
                            class="w-full flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all"
                            wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')">
                            <i class="fa-regular fa-book-open-cover mr-2"></i> Kembali Ujian
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
</div>