<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Aturan Pakai</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Aturan Pakai <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name" placeholder="Masukkan nama Aturan Pakai"
                    class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi <span
                        class="text-red-600">*</span></label>
                <textarea id="description" wire:model.defer="description" placeholder="Masukkan deskripsi poli"
                    class="mt-1 form-control"></textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700">Aturan Pakai (Berapa x / Hari)
                    <span class="text-red-600">*</span></label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" wire:model.defer="time" class="form-control rounded-r-none" placeholder="0" />
                    <span class="inline-flex items-center border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                        /Hari
                    </span>
                    <input type="text" wire:model.defer="day" class="form-control rounded-l-none" placeholder="0" />
                </div>
                @error('time')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('day')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
