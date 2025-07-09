<div wire:ignore.self id="modal" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Kategori Produk</h2>
            </div>
            <button wire:click="closeModal('modal')" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori <span class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name" placeholder="Masukkan nama Kategori Produk" class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description" wire:model.defer="description" rows="3" placeholder="Tambahkan deskripsi Kategori Produk" class="mt-1 form-control"></textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label for="normal" class="block text-sm font-medium text-gray-700">
                        Normal <span class="text-red-600">*</span>
                    </label>
                    <div class="relative mt-1">
                        <input type="number" id="normal" wire:model.defer="normal" placeholder="Normal" min="0" max="100" oninput="this.value = this.value.slice(0, 3); if (this.value > 100) this.value = 100;" class="mt-1 form-control" />
                        <div class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm">
                            %
                        </div>
                    </div>
                    @error('normal')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- <div>
                    <label for="recipe" class="block text-sm font-medium text-gray-700">
                        Recipe <span class="text-red-600">*</span>
                    </label>
                    <div class="relative mt-1">
                        <input type="number" id="recipe" wire:model.defer="recipe" placeholder="Recipe" min="0" max="100" oninput="this.value = this.value.slice(0, 3); if (this.value > 100) this.value = 100;" class="mt-1 form-control" />
                        <div class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm">
                            %
                        </div>
                    </div>
                    @error('recipe')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}
            </div>
            {{-- <div>
                <label for="price" class="block text-sm font-medium text-gray-700">
                    Harga Kategori
                </label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 flex items-center p-3 pointer-events-none text-gray-500 text-sm">
                        Rp
                    </div>
                    <input type="text" onkeyup="convertToRupiah(this);" id="price" wire:model.defer="price" placeholder="Masukkan harga kategori (+10000)" class="block w-full rounded-md border-gray-300 px-4 py-2 pl-10 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                </div>
                <div class="mt-1 text-sm text-gray-500">
                    * Tambahkan harga untuk menambahkan harga jual produk
                </div>
                @error('price')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div> --}}

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
