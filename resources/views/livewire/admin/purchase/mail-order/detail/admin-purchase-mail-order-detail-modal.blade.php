<div wire:ignore.self id="modalSupplier" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Supplier</h2>
            </div>
            <button wire:click="closeModalSupplier()" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name_supplier" class="block text-sm font-medium text-gray-700">Nama Supplier <span class="text-red-600">*</span></label>
                <input id="name_supplier" type="name_supplier" wire:model.defer="name_supplier" placeholder="Contoh : PT MAKMUR JAYA" class="mt-1 form-control">
                @error('name_supplier')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email_supplier" class="block text-sm font-medium text-gray-700">Email <span class="text-red-600">*</span></label>
                <input id="email_supplier" type="email" wire:model.defer="email_supplier" placeholder="Contoh : admin@gmail.com" class="mt-1 form-control">
                @error('email_supplier')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="phone_supplier" class="block text-sm font-medium text-gray-700">Nomor Telepon <span class="text-red-600">*</span></label>
                <input id="phone_supplier" type="number" wire:model.defer="phone_supplier" placeholder="Contoh : 081234567890" class="mt-1 form-control">
                @error('phone_supplier')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="address_supplier" class="block text-sm font-medium text-gray-700">Alamat </label>
                <textarea id="address_supplier" wire:model.defer="address_supplier" placeholder="Contoh : Jl. Raya Bogor" class="mt-1 form-control"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalSupplier()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitSupplier' class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
