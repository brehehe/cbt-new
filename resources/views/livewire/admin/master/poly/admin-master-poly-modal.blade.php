<div wire:ignore.self id="modal" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Poli</h2>
            </div>
            <button wire:click="closeModal()" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Poli <span class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name" placeholder="Masukkan nama Poli" class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-600">*</span></label>
                <textarea id="description" wire:model.defer="description" placeholder="Masukkan deskripsi poli" class="mt-1 form-control"></textarea>
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-600">*</span></label>
                <select id="status" wire:model.defer="status" class="mt-1 form-control">
                    <option value="">Pilih Status</option>
                    @foreach ($getStatuss as $getStatus)
                        <option value="{{ $getStatus['code'] }}">{{ $getStatus['display'] }}</option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="mode" class="block text-sm font-medium text-gray-700">Mode <span class="text-red-600">*</span></label>
                <select id="mode" wire:model.defer="mode" class="mt-1 form-control">
                    <option value="">Pilih Mode</option>
                    @foreach ($getModes as $getMode)
                        <option value="{{ $getMode['code'] }}">{{ $getMode['display'] }}</option>
                    @endforeach
                </select>
                @error('mode')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="physical_type" class="block text-sm font-medium text-gray-700">Physical Test <span class="text-red-600">*</span></label>
                <select id="physical_type" wire:model.defer="physical_type" class="mt-1 form-control">
                    <option value="">Pilih Physical Test</option>
                    @foreach ($getPhysicalTypes as $getPhysicalType)
                        <option value="{{ $getPhysicalType['code'] }}">{{ $getPhysicalType['display'] }}</option>
                    @endforeach
                </select>
                @error('physical_type')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="{{ $image || $image_old ? null : 'md:col-span-2' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo Poli </label>

                    <input type="file" wire:model.live="image" class="block text-sm text-gray-500 w-full
                                           file:px-2 file:py-1 file:rounded-md
                                           file:border file:border-gray-300
                                           file:text-xs file:font-medium
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100" accept="image/*" />
                    <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">
                        Uploading image...
                    </div>
                    @error('image')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @if ($image)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                            Logo:</label>
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview Logo" class="h-30 w-auto rounded border shadow" />
                    </div>
                @else
                    @if ($image_old)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                Logo:</label>
                            <img src="{{ asset('storage/' . $image_old) }}" alt="Preview Logo" class="h-30 w-auto rounded border shadow" />
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit' class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
