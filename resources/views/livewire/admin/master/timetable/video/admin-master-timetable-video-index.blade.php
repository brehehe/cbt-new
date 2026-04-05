<div>
    @php
        // app/Helpers/FormatHelper.php
        if (!function_exists('formatBytes')) {
            function formatBytes($bytes, $precision = 2)
            {
                $units = ['B', 'KB', 'MB', 'GB', 'TB'];

                $bytes = max($bytes, 0);
                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                $pow = min($pow, count($units) - 1);

                $bytes /= pow(1024, $pow);

                return round($bytes, $precision) . ' ' . $units[$pow];
            }
        }

    @endphp
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-primary">
                    Video Recording
                </h1>
            </div>
            <div class="flex gap-3">
                <button wire:click="downloadZip"
                    class="btn btn-primary !bg-blue-600 !border-blue-700 shadow-sm hover:!bg-blue-700 transition-all rounded-xl">
                    <i class="fa-solid fa-file-zipper mr-2"></i>
                    Download ZIP (Semua Video)
                </button>
                <button wire:click="confirmDeleteAll"
                    class="btn btn-danger !bg-red-600 !border-red-700 shadow-sm hover:!bg-red-700 transition-all rounded-xl">
                    <i class="fa-solid fa-trash-can mr-2"></i>
                    Hapus Semua Video
                </button>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" value="{{ $timetable['name'] }}" disabled placeholder="Masukkan Nama"
                class="mt-1 form-control">
        </div>
        <div>
            <label for="module_id" class="block text-sm font-medium text-gray-700">Modul</label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('module_id', e ? e : '');
                    }
                });" wire:model='module_id' id="module_id">
                    <option value="">-- Pilih Modul --</option>
                    @foreach ($modules as $key_module => $module)
                        <option value="{{ $key_module }}">{{ $module }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="md:col-span-2">
            <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas</label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('supervisors', e ? e : '');
                    }
                });" wire:model.lazy="supervisors" id="supervisors" multiple>
                    <option value="">-- Pilih Pengawas --</option>
                    @foreach ($getSupervisors as $key_getSupervisor => $getSupervisor)
                        <option value="{{ $key_getSupervisor }}">{{ $getSupervisor }}</option>
                    @endforeach
                </select>
            </div>
            @error('supervisors')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
            <input disabled type="text" id="start_time" value="{{ $start_time }}" placeholder="Masukkan"
                class="mt-1 form-control">
        </div>
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
            <input disabled type="text" id="end_time" value="{{ $end_time }}" placeholder="Masukkan"
                class="mt-1 form-control">
        </div>
        {{-- <div>
            <label for="room" class="block text-sm font-medium text-gray-700">Ruangan <span
                    class="text-red-600">*</span></label>
            <input type="text" id="room" wire:model.defer="room" placeholder="Masukkan" class="mt-1 form-control">
            @error('room')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div> --}}
        <div class="md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea id="description" disabled value="{{ $timetable['description'] }}" placeholder="Masukkan Deskripsi"
                class="mt-1 form-control"></textarea>
        </div>
    </div>
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
                    placeholder="Cari Sesuatu..." wire:model.live='search'>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>File Size</th>
                        <th>Status</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examRecordings as $index => $examRecording)
                        <tr>
                            <td class="center">{{ $examRecordings->firstItem() + $index }}</td>
                            <td>{{ $examRecording->userTimetable->user->nim ?? ($examRecording->userTimetable->user->username ?? '-') }}
                            </td>
                            <td>{{ $examRecording->userTimetable->user->name ?? '-' }}</td>
                            <td>{{ $examRecording->file_size ? formatBytes($examRecording->file_size) : '-' }}</td>
                            <td>{{ $examRecording->status ?? '-' }}</td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <a target="_blank" href="{{ asset('storage/' . $examRecording->video_path) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl text-green-600 hover:bg-green-50 transition-all active:scale-90"
                                        title="Play Video">
                                        <i class="fa-solid fa-video"></i>
                                    </a>
                                    <button wire:click="confirmDelete('{{ $examRecording->id }}')"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl text-red-600 hover:bg-red-50 transition-all active:scale-90"
                                        title="Hapus Video">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
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
                    Menampilkan <span class="font-medium">{{ $examRecordings->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $examRecordings->lastItem() }}</span> dari <span
                        class="font-medium">{{ $examRecordings->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $examRecordings->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>