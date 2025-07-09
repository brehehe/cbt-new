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
                <h2 class="text-xl font-semibold text-gray-800">Modal Kategori Akun Biaya</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori Akun Biaya <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name" wire:model.defer="name"
                    placeholder="Masukkan nama Kategori Akun Biaya" class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="detail_category_account_id" class="block text-sm font-medium text-gray-700">Kategori Detail
                    Akun
                    Biaya
                    <span class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('detail_category_account_id', e ? e : '');
                        }
                    });"
                        wire:model.lazy="detail_category_account_id" id="detail_category_account_id">
                        <option value="">-- Pilih Kategori Detail Akun Biaya --</option>
                        @foreach ($detail_category_accounts as $category)
                            <option value="{{ $category['id'] }}">{{ $category['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('detail_category_account_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="cash_flow" class="block text-sm font-medium text-gray-700">Arus Kas
                    <span class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('cash_flow', e ? e : '');
                        }
                    });"
                        wire:model.lazy="cash_flow" id="cash_flow">
                        <option value="">-- Pilih Arus Kas --</option>
                        @foreach ($get_cash_flows as $flow)
                            <option value="{{ $flow }}">{{ Str::title($flow) }}</option>
                        @endforeach
                    </select>
                </div>
                @error('cash_flow')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @error('detail_category_account_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
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
