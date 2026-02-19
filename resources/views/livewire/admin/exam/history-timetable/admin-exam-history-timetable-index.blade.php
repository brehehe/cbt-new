<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                    Riwayat Ujian</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
        </div>
    </div>

    <!-- Table Controls -->
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
    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        
        <!-- Desktop Table View -->
        <div class="hidden md:block table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama Ujian</th>
                        {{-- <th>Nama Siswa</th> --}}
                        <th>Durasi</th>
                        <th>Jam Mulai</th>
                        <th>Jam Akhir</th>
                        @if($companyData->is_mark)
                        <th>Nilai</th>
                        <th>Skala Penilaian</th>
                        @endif
                        <th style="width: 1%" class="center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userTimetables as $index => $userTimetable)
                        <tr>
                            <td class="center">{{ $userTimetables->firstItem() + $index }}</td>
                            <td>{{ $userTimetable->timetable->name ?? '-' }}</td>
                            {{-- <td>{{ $userTimetable->user->name ?? '-' }}</td> --}}
                            <td>{{ $userTimetable?->timetable?->module?->duration ?? 0 }} / Menit</td>
                            <td>{{ Carbon\Carbon::parse($userTimetable?->timetable?->start_time)->format('d F Y H:i') ?? '-' }}
                            </td>
                            <td>{{ Carbon\Carbon::parse($userTimetable?->timetable?->end_time)->format('d F Y H:i') ?? '-' }}
                            </td>
                            @if($companyData->is_mark)
                            @php
                                $gradeDetail = $this->getGradeDetail($userTimetable->mark);
                            @endphp
                            <td>{{ $userTimetable->mark ?? '-' }}</td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-semibold">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                                    <span class="text-xs text-gray-500">{{ $gradeDetail?->description ?? '-' }}</span>
                                </div>
                            </td>
                            @endif
                            <td>
                                <div class="flex justify-end items-center">
                                    <a href="/admin/exam/history-timetable/{{ $userTimetable->timetable_id }}/{{ $userTimetable->id }}"
                                        class="btn text-blue-600 btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $companyData->is_mark ? 8 : 6 }}" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden space-y-4 p-4">
            @forelse ($userTimetables as $index => $userTimetable)
                <div class="bg-white rounded-lg shadow border border-gray-200 p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                             <h3 class="font-bold text-lg text-gray-800">{{ $userTimetable->timetable->name ?? '-' }}</h3>
                             <div class="text-sm text-gray-500">
                                {{ Carbon\Carbon::parse($userTimetable?->timetable?->start_time)->format('d M Y H:i') }} - 
                                {{ Carbon\Carbon::parse($userTimetable?->timetable?->end_time)->format('H:i') }}
                             </div>
                        </div>
                         @if($companyData->is_mark)
                            @php
                                $gradeDetail = $this->getGradeDetail($userTimetable->mark);
                            @endphp
                            <div class="text-right">
                                <span class="block text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                                    {{ $userTimetable->mark ?? '-' }}
                                </span>
                                <span class="text-sm font-semibold text-gray-600">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 mb-3">
                        <div>
                            <span class="block text-xs text-gray-400">Durasi</span>
                            {{ $userTimetable?->timetable?->module?->duration ?? 0 }} Menit
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-gray-100">
                         <a href="/admin/exam/history-timetable/{{ $userTimetable->timetable_id }}/{{ $userTimetable->id }}"
                            class="w-full btn text-blue-600 btn-sm flex justify-center items-center gap-2 bg-blue-50 hover:bg-blue-100 py-2 rounded-md">
                            <i class="fas fa-eye"></i> Detail
                        </a>
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
                    Menampilkan <span class="font-medium">{{ $userTimetables->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $userTimetables->lastItem() }}</span> dari <span
                        class="font-medium">{{ $userTimetables->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $userTimetables->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
