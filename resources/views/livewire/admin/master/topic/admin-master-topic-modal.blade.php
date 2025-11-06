<div wire:ignore.self id="modal"
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
                <h2 class="text-xl font-semibold text-gray-800">Modal Topik Ujian</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="study_id" class="block text-sm font-medium text-gray-700">Prodi <span
                        class="text-red-600">*</span></label>
                <select class="mt-1 form-control" wire:model.lazy='study_id'>
                    <option value="">Pilih prodi</option>
                    @foreach ($studies as $key_study => $study)
                        <option value="{{ $key_study }}">{{ $study }}</option>
                    @endforeach
                </select>
                @error('study_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Topik <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name" placeholder="Nama Topik"
                    class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Topik</label>
                <input type="text" id="description" wire:model.defer="description" placeholder="Deskripsi Topik"
                    class="mt-1 form-control">
                @error('description')
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
                class="px-4 py-2 {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'bg-[#2b7fff]' : 'bg-[#f58634]' }} hover:{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'bg-[#2b7fff]' : 'bg-[#f58634]' }} text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
