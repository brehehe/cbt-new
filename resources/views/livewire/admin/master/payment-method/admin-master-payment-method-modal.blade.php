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
                <h2 class="text-xl font-semibold text-gray-800">Modal Metode Pembayaran</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Metode Pembayaran <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name"
                    placeholder="Masukkan nama metode pembayaran" class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipe MDR</label>
                    <select name="type" id="type" wire:model.defer="type" class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="percentage">Persen</option>
                    </select>
                    @error('type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if ($type == 'percentage')
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700">Biaya MDR</label>
                        <input type="number" id="value" wire:model.defer="value" placeholder="Masukkan Nilai MDR"
                            class="mt-1 form-control">
                        @error('value')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($type == 'rupiah')
                    <div>
                        <label for="value" class="block text-sm font-medium text-gray-700">Biaya MDR</label>
                        <input type="text" onkeyup="convertToRupiah(this)" id="value" wire:model.defer="value"
                            placeholder="Masukkan Nilai MDR" class="mt-1 form-control">
                        @error('value')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div>
                    <label for="type_admin_fee" class="block text-sm font-medium text-gray-700">Tipe Biaya Admin</label>
                    <select name="type_admin_fee" id="type_admin_fee" wire:model.defer="type_admin_fee"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="percentage">Persen</option>
                    </select>
                    @error('type_admin_fee')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if ($type_admin_fee == 'percentage')
                    <div>
                        <label for="value_admin_fee" class="block text-sm font-medium text-gray-700">Biaya Admin</label>
                        <input type="number" id="value_admin_fee" wire:model.defer="value_admin_fee"
                            placeholder="Masukkan Biaya Admin" class="mt-1 form-control">
                        @error('value_admin_fee')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($type_admin_fee == 'rupiah')
                    <div>
                        <label for="value_admin_fee" class="block text-sm font-medium text-gray-700">Biaya Admin</label>
                        <input type="text" onkeyup="convertToRupiah(this)" id="value_admin_fee"
                            wire:model.defer="value_admin_fee" placeholder="Masukkan Biaya Admin"
                            class="mt-1 form-control">
                        @error('value_admin_fee')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
            <div class="mb-4 md:col-span-2">
                <label for="account_id" class="block text-sm font-medium text-gray-700">Akun <span
                        class="text-red-600">*</span></label>
                <select name="account_id" id="account_id" wire:model.defer="account_id" class="mt-1 form-control">
                    <option value="">Pilih Akun</option>
                    @foreach ($accounts as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('account_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div>
                    <label for="offline_payment" class="block text-sm font-medium text-gray-700">
                        Offline Payment
                    </label>

                    <input wire:model='is_offline_payment' type="checkbox" id="offline_payment"
                        class="mt-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="single_payment" class="block text-sm font-medium text-gray-700">
                        Single Payment
                    </label>

                    <input wire:model='is_single_payment' type="checkbox" id="single_payment"
                        class="mt-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
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
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
