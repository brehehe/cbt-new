<div wire:ignore.self id="modal" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in" style="max-width: 750px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Jadwal Dokter</h2>
            </div>
            <button wire:click="closeModal('modal')" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 500px;">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Dokter <span class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('user_id', e ? e : '');
                        }
                    });" wire:model.live="user_id" id="user_id">
                        <option value="">-- Pilih Dokter --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user['id'] }}">{{ $user['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('user_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="location_id" class="block text-sm font-medium text-gray-700">Poli <span class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('location_id', e ? e : '');
                        }
                    });" wire:model.live="location_id" id="location_id">
                        <option value="">-- Pilih Poli --</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location['id'] }}">{{ $location['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('location_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-sm font-medium text-gray-700">Jam Mulai <span class="text-red-600">*</span></label>
                <input type="time" class="mt-1 form-control" wire:model.live='start_time' id="start_time">
                @error('start_time')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="end_time" class="block text-sm font-medium text-gray-700">Jam Selesai <span class="text-red-600">*</span></label>
                <input type="time" class="mt-1 form-control" wire:model.live='end_time' id="end_time">
                @error('end_time')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="day" class="block text-sm font-medium text-gray-700">Hari <span class="text-red-600">*</span></label>
                <select class="mt-1 form-control" wire:model.live='day' id="day">
                    <option value="">-- Pilih Hari --</option>
                    @foreach ($getDays as $dayOption)
                        <option value="{{ $dayOption }}">{{ $dayOption }}</option>
                    @endforeach
                </select>
                @error('day')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="max_patients" class="block text-sm font-medium text-gray-700">Kuota Pasien <span class="text-red-600">*</span></label>
                <input type="number" class="mt-1 form-control" wire:model.live='max_patients' id="max_patients" min="1">
                @error('max_patients')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal('modal')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit' class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
