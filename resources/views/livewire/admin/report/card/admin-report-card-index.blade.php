@section('title', 'Cetak Kartu Peserta')
<div>
    {{-- Be like water. --}}
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Cetak Kartu Peserta</h1>
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
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Jadwal / Modul..."
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
                        <th>Jadwal Ujian</th>
                        <th>Modul</th>
                        <th>Waktu Pelaksanaan</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timetables as $index => $timetable)
                        <tr>
                            <td class="center">{{ $timetables->firstItem() + $index }}</td>
                            <td>{{ $timetable->name ?? '-' }}</td>
                            <td>{{ $timetable->module->name ?? '-' }}</td>
                            <td>{{ $timetable->start_time?->format('d F Y H:i') }} -
                                {{ $timetable->end_time?->format('H:i') }}
                            </td>
                            <td class="center">
                                <button wire:click="printParticipantCards('{{ $timetable->id }}')"
                                    class="btn btn-sm btn-primary">
                                    <i class="bi bi-printer me-2"></i> Cetak Kartu
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-data">Tidak ada jadwal ujian ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $timetables->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $timetables->lastItem() }}</span> dari <span
                        class="font-medium">{{ $timetables->total() }}</span> hasil
                </div>
                <div>
                    {{ $timetables->links() }}
                </div>
            </div>
        </div>
    </div>
</div>