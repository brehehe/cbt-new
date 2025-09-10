<div wire:ignore.self id="modal-timetable"
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
                <h2 class="text-xl font-semibold text-gray-800">Modal Jadwal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 80vh; overflow-y: auto;">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-4">
                    <div>
                        <label for="module_id" class="block text-sm font-medium text-gray-700">Modul <span
                                class="text-red-600">*</span></label>
                        <div wire:key="select-{{ rand() }}">
                            <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                plugins: ['clear_button'],
                                onChange: function(e) {
                                    @this.set('module_id', e ? e : '');
                                }
                            });"
                                wire:model.lazy="module_id" id="module_id">
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
                    @if ($module_id)
                        <div class="bg-white/80">
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Prodi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($studys as $index => $study)
                                            <tr>
                                                <td>{{ $study ?? '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="center" colspan="2">Tidak ada data</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama <span
                                class="text-red-600">*</span></label>
                        <input type="text" id="name" wire:model.defer="name" placeholder="Masukkan Nama"
                            class="mt-1 form-control">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="classmate_id" class="block text-sm font-medium text-gray-700">Kelas <span
                                class="text-red-600">*</span></label>
                        <div wire:key="select-{{ rand() }}">
                            <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                plugins: ['clear_button'],
                                onChange: function(e) {
                                    @this.set('classmate_id', e ? e : '');
                                }
                            });"
                                wire:model.lazy="classmate_id" id="classmate_id">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($classmates as $key_cl => $classmate)
                                    <option value="{{ $key_cl }}">{{ $classmate }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('classmate_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="supervisors" class="block text-sm font-medium text-gray-700">Pengawas <span
                                class="text-red-600">*</span></label>
                        <div wire:key="select-{{ rand() }}">
                            <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                plugins: ['clear_button'],
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
                        <input type="datetime-local" id="start_time" wire:model.defer="start_time"
                            placeholder="Masukkan" class="mt-1 form-control">
                        @error('start_time')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai <span
                                class="text-red-600">*</span></label>
                        <input type="datetime-local" id="end_time" wire:model.defer="end_time" placeholder="Masukkan"
                            class="mt-1 form-control">
                        @error('end_time')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea id="description" wire:model.defer="description" placeholder="Masukkan Deskripsi" class="mt-1 form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-[#f58634] hover:bg-[#f58634] text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
