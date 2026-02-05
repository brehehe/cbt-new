<div>
    @include('livewire.admin.master.timetable.admin-master-timetable-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Jadwal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
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
        <!-- Table Wrapper -->
        <div class="table-container overflow-x-auto">
            <table class="min-w-full table-auto border-collapse text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="w-1 text-center px-3 py-2">No</th>
                        <th class="px-3 py-2 text-left">Peserta</th>
                        <th class="px-3 py-2 text-left">Nama</th>
                        <th class="px-3 py-2 text-left">Modul</th>
                        <th class="px-3 py-2 text-left">Ruang</th>
                        <th class="px-3 py-2 text-left">Sesi</th>
                        <th class="px-3 py-2 text-left">Waktu Mulai</th>
                        <th class="px-3 py-2 text-left">Waktu Selesai</th>
                        <th class="px-3 py-2 text-left">Token</th>
                        <th class="px-3 py-2 text-center">Recording</th>
                        <th class="px-3 py-2 text-center">Streaming</th>
                        <th class="w-1 text-center px-3 py-2">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse ($timetables as $index => $timetable)
                        <tr class="hover:bg-gray-50">
                            <td class="text-center px-3 py-2">
                                {{ $timetables->firstItem() + $index }}
                            </td>
                            <td class="px-3 py-2">{{ $timetable?->classmate->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $timetable?->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $timetable?->module->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $timetable?->examRoom?->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $timetable?->examSession?->name ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $timetable?->start_time }}</td>
                            <td class="px-3 py-2">{{ $timetable?->end_time }}</td>

                            <!-- Token -->
                            <td class="px-3 py-2">
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

                            <td class="px-3 py-2 text-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer"
                                        wire:click="toggleRecording('{{ $timetable->id }}')"
                                        @checked($timetable->is_recording)>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 relative transition">
                                        <span
                                            class="absolute top-0.5 left-[2px] h-5 w-5 bg-white rounded-full transition-all peer-checked:translate-x-5"></span>
                                    </div>
                                </label>
                            </td>
                            <td class="px-3 py-2 text-center">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer"
                                        wire:click="toggleStreaming('{{ $timetable->id }}')"
                                        @checked($timetable->is_streaming)>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 relative transition">
                                        <span
                                            class="absolute top-0.5 left-[2px] h-5 w-5 bg-white rounded-full transition-all peer-checked:translate-x-5"></span>
                                    </div>
                                </label>
                            </td>

                            <!-- Aksi -->
                            <td class="text-center px-3 py-2 relative">
                                <div x-data="{ open: false, x: 0, y: 0 }" class="inline-block text-left">
                                    <button
                                        @click="
                                    open = !open;
                                    const rect = $el.getBoundingClientRect();
                                    x = rect.right - 200;
                                    y = rect.bottom + window.scrollY;
                                "
                                        class="px-3 py-2 bg-gray-100 rounded-md hover:bg-gray-200 transition text-gray-700">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>

                                    <!-- Dropdown keluar body -->
                                    <template x-teleport="body">
                                        <div x-show="open" x-transition.opacity @click.away="open = false"
                                            class="fixed z-50 w-52 bg-white border border-gray-200 rounded-lg shadow-xl"
                                            :style="`top:${y}px; left:${x}px`">

                                            <ul class="py-1 text-sm text-gray-700">
                                                @if (!$timetable->code)
                                                    <li>
                                                        <button
                                                            wire:click="confirmGenerateToken('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i
                                                                class="fa-solid fa-square-binary mr-2 text-green-600"></i>
                                                            Generate Token
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.print.daftar-hadir', $timetable->id) }}"
                                                            target="_blank" class="block px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-file-lines mr-2 text-blue-600"></i>
                                                            Cetak Daftar Hadir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.print.berita-acara', $timetable->id) }}"
                                                            target="_blank" class="block px-4 py-2 hover:bg-gray-100">
                                                            <i
                                                                class="fa-solid fa-file-signature mr-2 text-green-600"></i>
                                                            Cetak Berita Acara
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button wire:click="edit('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-pen-to-square mr-2 text-blue-600"></i>
                                                            Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button wire:click="confirmDelete('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                            <i class="fa-solid fa-trash mr-2"></i> Hapus
                                                        </button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button wire:click="liveSession('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-camera mr-2 text-blue-600"></i>
                                                            Live Session
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button wire:click="sessionIndex('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-users mr-2 text-indigo-600"></i>
                                                            Kelola Sesi
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button wire:click="confirmVideo('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-video mr-2 text-green-600"></i>
                                                            Video
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button wire:click="confirmAlert('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i
                                                                class="fa-solid fa-triangle-exclamation mr-2 text-yellow-600"></i>
                                                            Alert
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.print.daftar-hadir', $timetable->id) }}"
                                                            target="_blank" class="block px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-file-lines mr-2 text-blue-600"></i>
                                                            Cetak Daftar Hadir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.print.berita-acara', $timetable->id) }}"
                                                            target="_blank" class="block px-4 py-2 hover:bg-gray-100">
                                                            <i
                                                                class="fa-solid fa-file-signature mr-2 text-green-600"></i>
                                                            Cetak Berita Acara
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button wire:click="confirmDetail('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                                            <i class="fa-solid fa-eye mr-2 text-blue-600"></i>
                                                            Detail
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button wire:click="confirmDelete('{{ $timetable->id }}')"
                                                            class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600">
                                                            <i class="fa-solid fa-trash mr-2"></i> Hapus
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-center py-4 text-gray-500">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            class="px-5 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between text-sm text-gray-700">
            <div>
                Menampilkan <span class="font-medium">{{ $timetables->firstItem() }}</span> sampai
                <span class="font-medium">{{ $timetables->lastItem() }}</span> dari
                <span class="font-medium">{{ $timetables->total() }}</span> hasil
            </div>
            <div>
                {{ $timetables->links('vendor.livewire.custom') }}
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
