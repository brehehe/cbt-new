<div>
    @include('livewire.admin.exam.timetable.admin-exam-timetable-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#3BA172]">Ujian</h1>
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
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Modul</th>
                        <th>Durasi</th>
                        <th style="width: 50%">Deskripsi</th>
                        <th style="width: 15%" class="center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timetables as $index => $timetable)
                        <tr>
                            <td class="center">{{ $timetables->firstItem() + $index }}</td>
                            <td>{{ $timetable->name ?? '-' }}</td>
                            <td>{{ $timetable->timetableModule->questionType->name ?? '-' }}</td>
                            <td>{{ $timetable->timetableModule->name ?? '-' }}</td>
                            <td>{{ $timetable->timetableModule->duration ?? 0 }} / Menit</td>
                            <td>{{ $timetable->timetableModule->description ?? '-' }}</td>
                            <td>
                                @if (!$timetable->userTimetable)
                                    <div class="flex justify-end items-center">
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            wire:click="openModalStartExam('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-book"></i> Masuk Ujian
                                        </button>
                                    </div>
                                @else
                                    <div class="flex justify-end items-center">
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            wire:click="confirmBackExam('{{ $timetable->userTimetable->id }}')">
                                            <i class="fa-regular fa-book-open-cover"></i> Kembali Ujian
                                        </button>
                                    </div>
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
                    Menampilkan <span class="font-medium">{{ $timetables->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $timetables->lastItem() }}</span> dari <span
                        class="font-medium">{{ $timetables->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $timetables->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
