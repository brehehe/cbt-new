<div wire:ignore.self id="modal-images"
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
                <h2 class="text-xl font-semibold text-gray-800">Data Gambar Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 85vh">
            <div class="mb-4">
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Pilih & Kelola Gambar</label>
                
                <!-- Grid Preview Gambar -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                    @foreach ($images as $index => $img)
                        <div class="relative group border rounded-lg overflow-hidden flex items-center justify-center bg-gray-50 h-32">
                            @if (is_string($img))
                                <img src="{{ asset('storage/' . ltrim($img, '/')) }}" class="max-h-full max-w-full object-contain" alt="Preview Image">
                            @else
                                <img src="{{ $img->temporaryUrl() }}" class="max-h-full max-w-full object-contain" alt="Preview Image">
                            @endif
                            <button type="button" wire:click="removeImage({{ $index }})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-70 group-hover:opacity-100 transition shadow">
                                <span class="sr-only">Hapus</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    @endforeach
                    
                    <!-- Tombol Tambah Gambar (Label bound to hidden file input) -->
                    <label class="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center h-32 hover:bg-gray-50 hover:border-gray-400 transition cursor-pointer text-gray-500 group">
                        <svg class="w-8 h-8 mb-1 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="text-xs font-medium text-center px-2">Klik untuk<br>Tambah Gambar</span>
                        <input type="file" wire:model="new_images" multiple accept=".jpg,.jpeg,.png,.webp" class="hidden">
                    </label>
                </div>

                <div wire:loading wire:target="new_images" class="text-sm text-blue-600 mb-2 font-medium flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mempersiapkan gambar...
                </div>
                
                @error('images')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('new_images')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
                @error('new_images.*')
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
            <button wire:click="submitQuestion"
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                Simpan
            </button>

        </div>
    </div>
</div>