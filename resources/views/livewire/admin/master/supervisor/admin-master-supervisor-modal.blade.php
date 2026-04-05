<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 750px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Pengawas</h2>
            </div>
            <button wire:click="closeModal('modal')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 500px;">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Pengawas <span
                        class="text-red-600">*</span></label>
                <input id="name" type="name" wire:model.defer="name" placeholder="Contoh : Pengawas"
                    class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Userusername <span
                        class="text-red-600">*</span></label>
                <input id="username" type="username" wire:model.defer="username" placeholder="Contoh : Pengawas"
                    class="mt-1 form-control">
                @error('username')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username <span
                        class="text-red-600">*</span></label>
                <input id="username" type="name" wire:model.defer="username" placeholder="Contoh : pengawas"
                    class="mt-1 form-control">
                @error('username')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                        class="text-red-600">*</span></label>
                <input id="email" type="email" wire:model.defer="email" placeholder="Contoh : pengawas@gmail.com"
                    class="mt-1 form-control">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon <span
                        class="text-red-600">*</span></label>
                <input id="phone" type="number" wire:model.defer="phone" placeholder="Contoh : 081234567890"
                    class="mt-1 form-control">
                @error('phone')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password @if (!$data_id)
                    <span class="text-red-600">*</span>
                @else
                    @endif
                </label>
                <div x-data="{ show: false }" class="relative">
                    <input :type="show ? 'text' : 'password'" id="password" wire:model.defer="password"
                        placeholder="Contoh : 12345678" class="mt-1 form-control">

                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500" tabindex="-1">
                        <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                    </button>
                </div>

                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- <div class="mb-4">
                <label for="identity_card" class="block text-sm font-medium text-gray-700">NIK </label>
                <input id="identity_card" type="identity_card" wire:model.defer="identity_card"
                    placeholder="Contoh : 12345678" class="mt-1 form-control">
            </div> --}}
            {{-- <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span
                        class="text-red-600">*</span></label>
                <textarea id="address" wire:model.defer="address" placeholder="Contoh : Jl. Raya No. 123"
                    class="mt-1 form-control"></textarea>
                @error('address')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal('modal')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>