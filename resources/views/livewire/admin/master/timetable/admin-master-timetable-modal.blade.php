<div wire:ignore.self id="modal-timetable"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Form Jadwal Ujian</h2>
                    <p class="text-xs text-gray-500">Kelola informasi jadwal, waktu, peserta, dan pengaturan ujian</p>
                </div>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-2 rounded-xl transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body (Scrollable) -->
        <div class="px-6 py-5 text-gray-600 overflow-y-auto space-y-6 flex-1">
            <!-- Section 1: Informasi Utama & Pelaksanaan -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 border-b pb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-blue-500"></i> Informasi Utama & Pelaksanaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Left Col -->
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Jadwal <span class="text-red-600">*</span></label>
                            <input type="text" id="name" wire:model.defer="name" placeholder="Masukkan Nama Jadwal"
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                            @error('name')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="classmate_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Peserta / Kelas <span class="text-red-600">*</span></label>
                            <div wire:ignore wire:key="select-classmate-{{ $classmate_id }}">
                                <select class="form-control text-sm rounded-lg" x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('classmate_id', e ? e : '');
                                    }
                                });" wire:model.lazy="classmate_id" id="classmate_id">
                                    <option value="">-- Pilih Peserta --</option>
                                    @foreach ($classmates as $key_cl => $classmate)
                                        <option value="{{ $key_cl }}" {{ $classmate_id == $key_cl ? 'selected' : '' }}>{{ $classmate }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('classmate_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="module_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Modul Soal <span class="text-red-600">*</span></label>
                            <div wire:ignore wire:key="select-module-{{ $module_id }}">
                                <select class="form-control text-sm rounded-lg" x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('module_id', e ? e : '');
                                    }
                                });" wire:model.lazy="module_id" id="module_id">
                                    <option value="">-- Pilih Modul --</option>
                                    @foreach ($modules as $key_module => $module)
                                        <option value="{{ $key_module }}" {{ $module_id == $key_module ? 'selected' : '' }}>{{ $module }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('module_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Col -->
                    <div class="space-y-4">
                        <div>
                            <label for="exam_room_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Ruang Ujian <span class="text-red-600">*</span></label>
                            <div wire:ignore wire:key="select-room-{{ $exam_room_id }}">
                                <select class="form-control text-sm rounded-lg" x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('exam_room_id', e ? e : '');
                                    }
                                });" wire:model.lazy="exam_room_id" id="exam_room_id">
                                    <option value="">-- Pilih Ruang Ujian --</option>
                                    @foreach ($examRooms as $key => $value)
                                        <option value="{{ $value->id }}" {{ $exam_room_id == $value->id ? 'selected' : '' }}>{{ $value->name }} - [CODE]:{{ $value?->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('exam_room_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="exam_session_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Sesi Ujian <span class="text-red-600">*</span></label>
                            <div wire:ignore wire:key="select-session-{{ $exam_session_id }}">
                                <select class="form-control text-sm rounded-lg" x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('exam_session_id', e ? e : '');
                                    }
                                });" wire:model.lazy="exam_session_id" id="exam_session_id">
                                    <option value="">-- Pilih Sesi Ujian --</option>
                                    @foreach ($examSessions as $key => $value)
                                        <option value="{{ $value->id }}" {{ $exam_session_id == $value->id ? 'selected' : '' }}>{{ $value->name }} - [CODE]:{{ $value?->code }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('exam_session_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="supervisors" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Pengawas <span class="text-red-600">*</span></label>
                            <div wire:ignore wire:key="select-supervisors-{{ implode('-', $supervisors ?? []) }}">
                                <select class="form-control text-sm rounded-lg" x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('supervisors', e ? (typeof e === 'string' ? e.split(',') : e) : []);
                                    }
                                });" wire:model.lazy="supervisors" id="supervisors" multiple>
                                    <option value="">-- Pilih Pengawas --</option>
                                    @foreach ($getSupervisors as $key_getSupervisor => $getSupervisor)
                                        <option value="{{ $key_getSupervisor }}" {{ in_array($key_getSupervisor, $supervisors ?? []) ? 'selected' : '' }}>{{ $getSupervisor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('supervisors')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Waktu Pelaksanaan & Deskripsi -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 border-b pb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-clock text-blue-500"></i> Waktu Pelaksanaan & Deskripsi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Waktu Mulai <span class="text-red-600">*</span></label>
                        <input type="datetime-local" id="start_time" wire:model.live="start_time"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('start_time')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Waktu Selesai <span class="text-red-600">*</span></label>
                        <input type="datetime-local" id="end_time" wire:model.live="end_time"
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition">
                        @error('end_time')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Deskripsi / Catatan Ujian</label>
                    <textarea id="description" wire:model.defer="description" rows="2" placeholder="Masukkan deskripsi atau catatan ujian (opsional)"
                        class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 transition"></textarea>
                </div>
            </div>

            <!-- Section 3: Tipe Ujian & Fitur Keamanan -->
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200/80 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-gray-700 flex items-center justify-between">
                    <span class="flex items-center gap-1.5"><i class="fa-solid fa-sliders text-blue-600"></i> Pengaturan Tipe Ujian & Keamanan</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-3.5 rounded-lg border border-gray-200">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Ujian</label>
                        <select wire:model.live="is_simulation" class="w-full text-sm rounded-lg border-gray-300 py-1.5 focus:ring-blue-500">
                            <option value="false">Ujian Resmi</option>
                            <option value="true">Simulasi / Latihan</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" wire:model.live="allow_repeat" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                            <div>
                                <span class="text-xs font-bold text-gray-800 block">Izinkan Pengulangan</span>
                                <span class="text-[11px] text-gray-500">Peserta dapat mengulang simulasi</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none">
                            <input type="checkbox" wire:model.live="require_token" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500 border-gray-300">
                            <div>
                                <span class="text-xs font-bold text-gray-800 block">Wajib Token</span>
                                <span class="text-[11px] text-gray-500">Wajib memasukkan token saat masuk</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-blue-300 transition select-none">
                        <input type="checkbox" wire:model="is_camera" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-800">📷 Kamera Pengawas</span>
                            <span class="text-[11px] text-gray-500">Wajib mengaktifkan kamera</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-blue-300 transition select-none {{ !$is_camera ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="checkbox" wire:model="is_recording" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-800">🎙️ Recording</span>
                            <span class="text-[11px] text-gray-500">Rekam sesi pengerjaan</span>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 cursor-pointer hover:border-blue-300 transition select-none {{ !$is_camera ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <input type="checkbox" wire:model="is_streaming" class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-800">📡 Streaming Proctor</span>
                            <span class="text-[11px] text-gray-500">Live stream ke pengawas</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50/50">
            <button wire:click="closeModal()"
                class="px-5 py-2 text-sm font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-5 py-2 text-sm font-semibold bg-primary hover:opacity-90 text-white rounded-xl shadow-md transition cursor-pointer flex items-center gap-2">
                <i class="fa-solid fa-save"></i> Simpan
            </button>
        </div>
    </div>
</div>

<div wire:ignore.self id="modal-timetable-extra-time"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 100vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Jadwal</h2>
            </div>
            <button wire:click="closeModalExtraTime()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 80vh; overflow-y: auto;">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div>
                        <label for="extra_time" class="block text-sm font-medium text-gray-700">Extra Time <span
                                class="text-red-600">*</span></label>
                        <input type="datetime-local" id="extra_time" wire:model.live="extra_time" placeholder="Masukkan"
                            class="mt-1 form-control">
                        @error('extra_time')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalExtraTime()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitExtraTime'
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<div wire:ignore.self id="modal-change-supervisor-master"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 60vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Ubah Pengawas</h2>
            </div>
            <button wire:click="closeModalSupervisor()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 70vh; overflow-y: auto;">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pengawas Ujian</label>
                    <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                        @forelse ($availableSupervisors as $supervisorId => $supervisorName)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition cursor-pointer text-sm font-medium text-gray-700">
                                <input type="checkbox"
                                    wire:model="selectedSupervisors"
                                    value="{{ $supervisorId }}"
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span>{{ $supervisorName }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-3">Tidak ada data pengawas tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalSupervisor()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click="saveSupervisor()"
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition cursor-pointer">
                Simpan
            </button>
        </div>
    </div>
</div>