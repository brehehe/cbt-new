<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
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
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama Ujian</th>
                        {{-- <th>Nama Mahasiswa</th> --}}
                        <th>Durasi</th>
                        <th>Jam Mulai</th>
                        <th>Jam Akhir</th>
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
                            <td>{{ Carbon\Carbon::parse($userTimetable->timetable->start_time)->format('d F Y H:i') ?? '-' }}
                            </td>
                            <td>{{ Carbon\Carbon::parse($userTimetable->timetable->end_time)->format('d F Y H:i') ?? '-' }}
                            </td>
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
