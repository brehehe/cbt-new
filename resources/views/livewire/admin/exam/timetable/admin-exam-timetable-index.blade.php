<div>
    @include('livewire.admin.exam.timetable.admin-exam-timetable-modal')
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Daftar Ujian
                </h1>
                <p class="text-gray-600 text-sm mt-1">Kelola dan pantau jadwal ujian yang tersedia.</p>
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
                    placeholder="Cari Ujian, Modul..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
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
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $timetable->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $timetable->timetableModule->questionType->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $timetable->timetableModule->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fa-regular fa-clock mr-2 text-gray-400"></i>
                                    {{ $timetable->timetableModule->duration ?? 0 }} Menit
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 line-clamp-2 max-w-xs"
                                title="{{ $timetable->timetableModule->description ?? '-' }}">
                                {{ Str::limit($timetable->timetableModule->description ?? '-', 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                @if (!$timetable->userTimetable)
                                    <button
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg"
                                        wire:click="openModalStartExam('{{ $timetable->id }}')">
                                        <i class="fa-solid fa-book mr-1.5"></i> Masuk
                                    </button>
                                @else
                                    <button
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all shadow-md hover:shadow-lg"
                                        wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')">
                                        <i class="fa-regular fa-book-open-cover mr-1.5"></i> Kembali
                                    </button>
                                @endif
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