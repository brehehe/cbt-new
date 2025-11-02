<div>
    @include('livewire.admin.master.timetable.admin-master-timetable-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold {{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                    Jadwal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="openModal()" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Jadwal
                </button>
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
                        <th>Peserta</th>
                        <th>Nama</th>
                        <th>Modul</th>
                        <th>Ruang</th>
                        <th>Sesi</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Selesai</th>
                        <th>Token</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($timetables as $index => $timetable)
                        <tr>
                            <td class="center">{{ $timetables->firstItem() + $index }}</td>
                            <td>{{ $timetable?->classmate->name ?? '-' }}</td>
                            <td>{{ $timetable?->name ?? '-' }}</td>
                            <td>{{ $timetable?->module->name ?? '-' }}</td>
                            <td>{{ $timetable?->examRoom?->name ?? '-' }}</td>
                            <td>{{ $timetable?->examSession?->name ?? '-' }}</td>
                            <td>{{ $timetable?->start_time }}</td>
                            <td>{{ $timetable?->end_time }}</td>
                            <td>
                                @if ($timetable->code)
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-mono text-sm bg-gray-100 px-2 py-1 rounded">{{ $timetable->code }}</span>
                                        <button onclick="copyToClipboard('{{ trim($timetable->code) }}')"
                                            class="btn btn-icon text-gray-600 hover:text-blue-600 transition-colors"
                                            title="Copy Token">
                                            <i class="fa-solid fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="center">
                                <div class="flex items-center">
                                    @if (!$timetable->code)
                                        <button
                                            class="btn btn-icon text-green-600 hover:text-green-800 transition-colors edit-btn"
                                            wire:click="confirmGenerateToken('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-square-binary"></i> <!-- atau fa-edit (versi lama) -->
                                        </button>
                                    @endif
                                    @if (!$timetable->code)
                                        <!-- Tombol Edit -->
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            wire:click="edit('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-pen-to-square"></i> <!-- atau fa-edit (versi lama) -->
                                        </button>

                                        <!-- Tombol Delete -->
                                        <button
                                            class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                            wire:click="confirmDelete('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @else
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors delete-btn"
                                            wire:click="liveSession('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-camera"></i>
                                        </button>
                                        <button
                                            class="btn btn-icon text-indigo-600 hover:text-indigo-800 transition-colors delete-btn"
                                            wire:click="sessionIndex('{{ $timetable->id }}')" title="Kelola Sesi">
                                            <i class="fa-solid fa-users"></i>
                                        </button>
                                        {{-- <button
                                            class="btn btn-icon {{ config('app.name_slug') === 'ups_tegal' ? 'text-blue-600' : 'text-orange-600' }} hover:{{ config('app.name_slug') === 'ups_tegal' ? 'text-blue-800' : 'text-orange-800' }} transition-colors delete-btn"
                                            wire:click="confirmSuspend('{{ $timetable->id }}')"
                                            title="Suspend Sesi Ujian">
                                            <i class="fa-solid fa-user-slash"></i>
                                        </button> --}}
                                        <button
                                            class="btn btn-icon text-green-600 hover:text-green-800 transition-colors delete-btn"
                                            wire:click="confirmVideo('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-video"></i>
                                        </button>
                                        <button
                                            class="btn btn-icon text-yellow-600 hover:text-yellow-800 transition-colors delete-btn"
                                            wire:click="confirmAlert('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </button>
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors delete-btn"
                                            wire:click="confirmDetail('{{ $timetable->id }}')">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    @endif
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
@push('scripts')
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Tampilkan notifikasi sukses
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Token berhasil disalin!'
                });
            }).catch(function(err) {
                // Fallback untuk browser lama
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                alert('Token berhasil disalin!');
            });
        }
    </script>
@endpush
