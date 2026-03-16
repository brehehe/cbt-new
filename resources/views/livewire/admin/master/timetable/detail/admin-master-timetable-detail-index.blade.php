<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Nilai Ujian</h1>
            </div>
            <div class="flex gap-2">
                <button wire:click="exportPdf" class="btn btn-primary !bg-red-600 !border-red-700 hover:!bg-red-700">
                    <i class="fa-solid fa-file-pdf mr-2"></i>
                    Export PDF
                </button>
                <button wire:click="exportExcel" class="btn btn-primary !bg-green-600 !border-green-700 hover:!bg-green-700">
                    <i class="fa-solid fa-file-excel mr-2"></i>
                    Export Excel
                </button>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama <span
                    class="text-red-600">*</span></label>
            <input type="text" id="name" value="{{ $timetable['name'] }}" disabled placeholder="Masukkan Nama"
                class="mt-1 form-control">
            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="module_id" class="block text-sm font-medium text-gray-700">Modul <span
                    class="text-red-600">*</span></label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('module_id', e ? e : '');
                    }
                });"
                    wire:model='module_id' id="module_id">
                    <option value="">-- Pilih Modul --</option>
                    @foreach ($modules as $key_module => $module)
                        <option value="{{ $key_module }}">{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            @error('module_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="md:col-span-2">
            <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas <span
                    class="text-red-600">*</span></label>
            <div wire:key="select-{{ rand() }}">
                <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                    dropdownParent: 'body',
                    allowClear: true,
                    onChange: function(e) {
                        @this.set('supervisors', e ? e : '');
                    }
                });"
                    wire:model.lazy="supervisors" id="supervisors" multiple>
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
            <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai <span
                    class="text-red-600">*</span></label>
            <input disabled type="text" id="start_time" wire:model.defer="start_time" placeholder="Masukkan"
                class="mt-1 form-control">
            @error('start_time')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai <span
                    class="text-red-600">*</span></label>
            <input disabled type="text" id="end_time" wire:model.defer="end_time" placeholder="Masukkan"
                class="mt-1 form-control">
            @error('end_time')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        {{-- <div>
                <label for="room" class="block text-sm font-medium text-gray-700">Ruangan <span
                        class="text-red-600">*</span></label>
                <input type="text" id="room" wire:model.defer="room" placeholder="Masukkan"
                    class="mt-1 form-control">
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
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Terjawab</th>
                        <th>Tidak Terjawab</th>
                        <th>Benar</th>
                        <th>Salah</th>
                        <th>Nilai</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userTimetables as $index => $userTimetable)
                        <tr>
                            <td class="center">{{ $userTimetables->firstItem() + $index }}</td>
                            <td>{{ $userTimetable->user->nim ?? ($userTimetable->user->username ?? '-') }}
                            </td>
                            <td>{{ $userTimetable->user->name ?? '-' }}</td>
                            <td>{{ $userTimetable->userModuleQuestions->whereNotNull('timetable_answer_id')->count() }}
                            </td>
                            <td>{{ $userTimetable->userModuleQuestions->whereNull('timetable_answer_id')->count() }}
                            </td>
                            <td><span
                                    class="px-2 py-1 rounded bg-green-100 text-green-700 font-semibold">{{ $userTimetable->userModuleQuestions->where('status', 'correct')->count() }}</span>
                            </td>
                            <td><span
                                    class="px-2 py-1 rounded bg-red-100 text-red-700 font-semibold">{{ $userTimetable->userModuleQuestions->where('status', 'wrong')->count() }}</span>
                            </td>
                            <td>
                                {{ $userTimetable->mark }}
                                <span class="ml-2 px-2 py-1 rounded bg-blue-100 text-blue-700 font-semibold">
                                    {{ $this->getGrade($userTimetable->mark) }}
                                </span>
                            </td>
                            <td class="center">
                                <div class="flex items-center">
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors delete-btn"
                                        wire:click="confirmDetail('{{ $userTimetable->id }}')">
                                        <i class="fa-solid fa-eye"></i>
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
