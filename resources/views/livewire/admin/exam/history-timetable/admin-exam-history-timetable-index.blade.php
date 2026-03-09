<div>
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                    Riwayat Ujian
                </h1>
                <p class="text-gray-600 text-sm mt-1">Lihat riwayat ujian yang telah Anda kerjakan.</p>
            </div>
        </div>
    </div>

    <!-- Table Controls -->
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
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-[{{ $companyData->color_primary ?? '#2b7fff' }}] focus:border-[{{ $companyData->color_primary ?? '#2b7fff' }}] sm:text-sm transition duration-150 ease-in-out" 
                    placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <!-- Desktop View (Table) -->
    <div class="hidden md:block bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Mulai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Akhir</th>
                        @if($companyData->is_mark)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skala Penilaian</th>
                        @endif
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($userTimetables as $index => $userTimetable)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $userTimetables->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $userTimetable->timetable->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="fa-regular fa-clock mr-2 text-gray-400"></i>
                                    {{ $userTimetable?->timetable?->module?->duration ?? 0 }} Menit
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Carbon\Carbon::parse($userTimetable?->timetable?->start_time)->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Carbon\Carbon::parse($userTimetable?->timetable?->end_time)->format('d M Y H:i') ?? '-' }}
                            </td>
                            @if($companyData->is_mark)
                            @php
                                $gradeDetail = $this->getGradeDetail($userTimetable->mark);
                            @endphp
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">{{ $userTimetable->mark ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-sm">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                                    <span class="text-xs text-gray-500">{{ $gradeDetail?->description ?? '-' }}</span>
                                </div>
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="/admin/exam/history-timetable/{{ $userTimetable->timetable_id }}/{{ $userTimetable->id }}"
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-1.5"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $companyData->is_mark ? 8 : 6 }}" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <span class="text-base font-medium">Tidak ada data riwayat ujian</span>
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
                            <span class="text-lg font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">{{ $userTimetable->mark ?? '-' }}</span>
                            <span class="px-2 py-0.5 text-xs font-semibold rounded bg-gray-100 text-gray-700">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
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
