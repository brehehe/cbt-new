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
                <h2 class="text-xl font-semibold text-gray-800">Modal Produk</h2>
            </div>
            <button wire:click="closeModal('modal')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4">
            <!-- Button Produk Baru dan Lama -->
            <div class="flex gap-4">
                <button wire:click="openModalProduct('modalProductOld')"
                    class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    Produk Terdaftar
                </button>
                <button wire:click="openModalProduct('modalProductNew')"
                    class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-xl flex items-center justify-center gap-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Produk Baru
                </button>
            </div>
        </div>
    </div>
</div>

<div wire:ignore.self id="modalProductNew"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-full w-full h-screen flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Produk</h2>
            </div>
            <button wire:click="closeModalProduct('modalProductNew')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- SKU Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU Number <span
                            class="text-red-600">*</span></label>
                    <input type="text" wire:model="sku_number" placeholder="Contoh: SKU12345"
                        class="mt-1 form-control" />
                    @error('sku_number')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk <span
                            class="text-red-600">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama produk" class="mt-1 form-control" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan Terkecil <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('unit_id', e ? e : '');
                            }
                        });"
                            wire:model.live="unit_id" id="unit_id">
                            <option value="">-- Pilih Satuan Terkecil --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('unit_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Produk <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('product_type_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_type_id" id="product_type_id">
                            <option value="">-- Pilih Tipe Produk --</option>
                            @foreach ($productTypes as $productType)
                                <option value="{{ $productType['id'] }}">{{ $productType['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_type_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk Varian</label>
                    <input type="text" wire:model="code_coding_code" placeholder="Masukan Produk Varian"
                        class="mt-1 form-control" />
                    @error('code_coding_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bentuk Obat</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('form_coding_code', e ? e : '');
                            }
                        });"
                            wire:model.live="form_coding_code" id="form_coding_code">
                            <option value="">-- Pilih Bentuk Obat --</option>
                            @foreach ($master_medication_forms as $key_master_medication_form => $master_medication_form)
                                <option value="{{ $key_master_medication_form }}">
                                    {{ $master_medication_form }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('form_coding_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bahan Baku Kode</label>
                    <input type="text" wire:model="item_code" placeholder="Masukan Bahan Baku Kode"
                        class="mt-1 form-control" />
                    @error('item_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bahan Baku Nama</label>
                    <input type="text" wire:model="item_display" placeholder="Masukan Bahan Baku Nama"
                        class="mt-1 form-control" />
                    @error('item_display')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dosis Obat</label>
                    <input type="number" wire:model="medicine_dosage" placeholder="Contoh: 500"
                        class="mt-1 form-control" />
                    @error('medicine_dosage')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan Dosis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan Dosis</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('numerator_code', e ? e : '');
                            }
                        });"
                            wire:model.live="numerator_code" id="numerator_code">
                            <option value="">-- Pilih Bentuk Obat --</option>
                            @foreach ($master_medication_request_value_quantities as $key_master_medication_request_value_quantitie => $master_medication_request_value_quantitie)
                                <option value="{{ $key_master_medication_request_value_quantitie }}">
                                    {{ $master_medication_request_value_quantitie }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('numerator_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi <span
                            class="text-red-600">*</span></label>
                    <textarea wire:model="description" placeholder="Deskripsi produk..." class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kategori Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('product_category_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_category_id" id="product_category_id">
                            <option value="">-- Pilih Kategori Produk --</option>
                            @foreach ($productCategorys as $productCategory)
                                <option value="{{ $productCategory['id'] }}">{{ $productCategory['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_category_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pabrik Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pabrik Produk (Opsional)</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('product_factory_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_factory_id" id="product_factory_id">
                            <option value="">-- Pilih Pabrik Produk --</option>
                            @foreach ($productFactorys as $productFactory)
                                <option value="{{ $productFactory['id'] }}">{{ $productFactory['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_factory_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rak Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rak Produk</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('product_rack_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_rack_id" id="product_rack_id">
                            <option value="">-- Pilih Rak Produk --</option>
                            @foreach ($productRacks as $productRack)
                                <option value="{{ $productRack['id'] }}">{{ $productRack['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_rack_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Deviasi Harga -->
                <div>
                    <label for="normal" class="block text-sm font-medium text-gray-700">
                        Normal
                    </label>
                    <div class="relative mt-1">
                        <input type="number" id="normal" wire:model.defer="normal" placeholder="Normal"
                            min="0" max="100"
                            oninput="this.value = this.value.slice(0, 3); if (this.value > 100) this.value = 100;"
                            class="mt-1 form-control" />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm">
                            %
                        </div>
                    </div>
                    @error('normal')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="flex items-center mt-6">
                        <input type="checkbox" wire:model="is_narcotics"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                        <label class="ml-2 block text-sm text-gray-700">Narkotika</label>
                    </div>
                    @error('is_narcotics')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <!-- Narkotika -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Minimum - Safety - Maximum Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Minimum <span
                            class="text-red-600">*</span></label>
                    <input type="number" wire:model="minimun_stock" placeholder="Contoh: 10"
                        class="mt-1 form-control" />
                    @error('minimun_stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Aman <span
                            class="text-red-600">*</span></label>
                    <input type="number" wire:model="safety_stock" placeholder="Contoh: 20"
                        class="mt-1 form-control" />
                    @error('safety_stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Maksimum <span
                            class="text-red-600">*</span></label>
                    <input type="number" wire:model="maximum_stock" placeholder="Contoh: 100"
                        class="mt-1 form-control" />
                    @error('maximum_stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Stok Request <span
                            class="text-red-600">*</span></label>
                    <input type="number" wire:model="stock" placeholder="Contoh: 10" class="mt-1 form-control" />
                    @error('stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="p-4 border-t flex justify-end space-x-2">
            <button wire:click="closeModalProduct('modalProductNew')"
                class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                Batal
            </button>
            <button wire:click="saveProduct"
                class="cursor-pointer px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                Simpan Produk
            </button>
        </div>

    </div>
</div>

<div wire:ignore.self id="modalProductChoice"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-full w-full h-screen flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Produk</h2>
            </div>
            <button wire:click="closeModalProductChoice('modalProductChoice')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- SKU Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU Number</label>
                    <input disabled type="text" wire:model="sku_number" placeholder="Contoh: SKU12345"
                        class="mt-1 form-control" />
                    @error('sku_number')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input disabled type="text" wire:model="name" placeholder="Nama produk"
                        class="mt-1 form-control" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan Terkecil</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('unit_id', e ? e : '');
                            }
                        });"
                            wire:model.live="unit_id" id="unit_id">
                            <option value="">-- Pilih Satuan Terkecil --</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('unit_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipe Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Produk</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('product_type_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_type_id" id="product_type_id">
                            <option value="">-- Pilih Tipe Produk --</option>
                            @foreach ($productTypes as $productType)
                                <option value="{{ $productType['id'] }}">{{ $productType['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_type_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk Varian</label>
                    <input type="text" disabled wire:model="code_coding_code" placeholder="Masukan Produk Varian"
                        class="mt-1 form-control" />
                    @error('code_coding_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bentuk Obat</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('form_coding_code', e ? e : '');
                            }
                        });"
                            wire:model.live="form_coding_code" id="form_coding_code">
                            <option value="">-- Pilih Bentuk Obat --</option>
                            @foreach ($master_medication_forms as $key_master_medication_form => $master_medication_form)
                                <option value="{{ $key_master_medication_form }}">
                                    {{ $master_medication_form }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('form_coding_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bahan Baku Kode</label>
                    <input disabled type="text" wire:model="item_code" placeholder="Masukan Bahan Baku Kode"
                        class="mt-1 form-control" />
                    @error('item_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bahan Baku Nama</label>
                    <input disabled type="text" wire:model="item_display" placeholder="Masukan Bahan Baku Nama"
                        class="mt-1 form-control" />
                    @error('item_display')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dosis Obat</label>
                    <input disabled type="number" wire:model="medicine_dosage" placeholder="Contoh: 500"
                        class="mt-1 form-control" />
                    @error('medicine_dosage')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan Dosis -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Satuan Dosis</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('numerator_code', e ? e : '');
                            }
                        });"
                            wire:model.live="numerator_code" id="numerator_code">
                            <option value="">-- Pilih Bentuk Obat --</option>
                            @foreach ($master_medication_request_value_quantities as $key_master_medication_request_value_quantitie => $master_medication_request_value_quantitie)
                                <option value="{{ $key_master_medication_request_value_quantitie }}">
                                    {{ $master_medication_request_value_quantitie }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('numerator_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea disabled wire:model="description" placeholder="Deskripsi produk..." class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Kategori Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('product_category_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_category_id" id="product_category_id">
                            <option value="">-- Pilih Kategori Produk --</option>
                            @foreach ($productCategorys as $productCategory)
                                <option value="{{ $productCategory['id'] }}">{{ $productCategory['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_category_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pabrik Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pabrik Produk (Opsional)</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('product_factory_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_factory_id" id="product_factory_id">
                            <option value="">-- Pilih Pabrik Produk --</option>
                            @foreach ($productFactorys as $productFactory)
                                <option value="{{ $productFactory['id'] }}">{{ $productFactory['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_factory_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rak Produk -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Rak Produk</label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('product_rack_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_rack_id" id="product_rack_id">
                            <option value="">-- Pilih Rak Produk --</option>
                            @foreach ($productRacks as $productRack)
                                <option value="{{ $productRack['id'] }}">{{ $productRack['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('product_rack_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Deviasi Harga -->
                <div>
                    <label for="normal" class="block text-sm font-medium text-gray-700">
                        Normal
                    </label>
                    <div class="relative mt-1">
                        <input disabled type="number" id="normal" wire:model.defer="normal" placeholder="Normal"
                            min="0" max="100"
                            oninput="this.value = this.value.slice(0, 3); if (this.value > 100) this.value = 100;"
                            class="mt-1 form-control" />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm">
                            %
                        </div>
                    </div>
                    @error('normal')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="flex items-center mt-6">
                        <input disabled type="checkbox" wire:model="is_narcotics"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                        <label class="ml-2 block text-sm text-gray-700">Narkotika</label>
                    </div>
                    @error('is_narcotics')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>


            </div>

            <!-- Narkotika -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Minimum - Safety - Maximum Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Minimum</label>
                    <input disabled type="number" wire:model="minimun_stock" placeholder="Contoh: 10"
                        class="mt-1 form-control" />
                    @error('minimun_stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Aman</label>
                    <input disabled type="number" wire:model="safety_stock" placeholder="Contoh: 20"
                        class="mt-1 form-control" />
                    @error('safety_stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Maksimum</label>
                    <input disabled type="number" wire:model="maximum_stock" placeholder="Contoh: 100"
                        class="mt-1 form-control" />
                    @error('maximum_stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Stok Request <span
                            class="text-red-600">*</span></label>
                    <input type="number" wire:model="stock" placeholder="Contoh: 10" class="mt-1 form-control" />
                    @error('stock')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="p-4 border-t flex justify-end space-x-2">
            <button wire:click="closeModalProductChoice('modalProductChoice')"
                class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                Batal
            </button>
            <button wire:click="saveChoiceProduct"
                class="cursor-pointer px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                Pilih Produk
            </button>
        </div>

    </div>
</div>

<div wire:ignore.self id="modalProductOld"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-full w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Produk</h2>
            </div>
            <button wire:click="closeModalProduct('modalProductOld')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <!-- Button Produk Baru dan Lama -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-2">Tampil</span>
                    <select class="mt-1 form-control" wire:model.live='perPageProduct'>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data</span>
                </div>

                <div class="relative w-full sm:w-64">
                    <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                        wire:model.live='searchProduct'>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            @if ($productOld)
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-1 center">No</th>
                                    <th>Sku Number</th>
                                    <th>Nama Produk</th>
                                    <th>Deskripsi</th>
                                    <th class="w-1 center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $product)
                                    <tr>
                                        <td class="center">{{ $products->firstItem() + $index }}</td>
                                        <td>{{ $product->sku_number }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description ?? '-' }}</td>
                                        <td class="center">
                                            <div class="flex items-center">
                                                <button
                                                    class="btn btn-icon text-yellow-500 hover:text-yellow-600 transition-colors edit-btn"
                                                    wire:click="choiceProduct('{{ $product->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="no-data">
                                            Tidak
                                            ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $products->firstItem() }}</span> sampai <span
                                    class="font-medium">{{ $products->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $products->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{ $products->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<div wire:ignore.self id="modalCategory"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Kategori Produk</h2>
            </div>
            <button wire:click="closeModalCategory('modalCategory')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name_category" class="block text-sm font-medium text-gray-700">Nama Kategori <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name_category" wire:model.defer="name_category"
                    placeholder="Masukkan nama Kategori Produk" class="mt-1 form-control">
                @error('name_category')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description_category" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description_category" wire:model.defer="description_category" rows="3"
                    placeholder="Tambahkan deskripsi Kategori Produk" class="mt-1 form-control"></textarea>
                @error('description_category')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label for="normal_category" class="block text-sm font-medium text-gray-700">
                        Harga <span class="text-red-600">*</span>
                    </label>
                    <div class="relative mt-1">
                        <input type="number" id="normal_category" wire:model.defer="normal_category"
                            placeholder="Harga" min="0" max="100"
                            oninput="this.value = this.value.slice(0, 3); if (this.value > 100) this.value = 100;"
                            class="mt-1 form-control" />
                        <div
                            class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm">
                            %
                        </div>
                    </div>
                    @error('normal_category')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalCategory('modalCategory')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitCategory()'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
<div wire:ignore.self id="modalRack"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Rak</h2>
            </div>
            <button wire:click="closeModalRack('modalRack')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name_rack" class="block text-sm font-medium text-gray-700">Nama Rak <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name_rack" wire:model.defer="name_rack" placeholder="Masukkan nama rak"
                    class="mt-1 form-control">
                @error('name_rack')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description_rack" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description_rack" wire:model.defer="description_rack" rows="3"
                    placeholder="Tambahkan deskripsi rak" class="mt-1 form-control"></textarea>
                @error('description_rack')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalRack('modalRack')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitRack'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
<div wire:ignore.self id="modalFactory"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Pabrik</h2>
            </div>
            <button wire:click="closeModalFactory('modalFactory')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name_factory" class="block text-sm font-medium text-gray-700">Nama Pabrik <span
                        class="text-red-600">*</span></label>
                <input type="text" id="name_factory" wire:model.defer="name_factory"
                    placeholder="Masukkan nama Pabrik" class="mt-1 form-control">
                @error('name_factory')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description_factory" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea id="description_factory" wire:model.defer="description_factory" rows="3"
                    placeholder="Tambahkan deskripsi Pabrik" class="mt-1 form-control"></textarea>
                @error('description_factory')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalFactory('modalFactory')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitFactory'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
<div wire:ignore.self id="modalProductUnit"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-lg w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Satuan Produk</h2>
            </div>
            <button wire:click="closeModalProductUnit('modalProductUnit')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk <span
                        class="text-red-600">*</span></label>
                <input id="name" type="text" wire:model.defer="name" placeholder="Contoh : Paracetamol"
                    disabled class="mt-1 form-control">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Satuan <span
                        class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('unit_product_unit_id', e ? e : '');
                        }
                    });"
                        wire:model.live="unit_product_unit_id" id="unit_product_unit_id">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit['id'] }}">{{ $unit['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                @error('unit_product_unit_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="quantity_product_unit" class="block text-sm font-medium text-gray-700">Quantity <span
                        class="text-red-600">*</span></label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input id="quantity_product_unit" type="number" wire:model.defer="quantity_product_unit"
                        placeholder="Masukkan nominal"
                        class="block w-full rounded-l-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <span
                        class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                        {{ $product_unit_name }}
                    </span>
                </div>

                @error('quantity_product_unit')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalProductUnit('modalProductUnit')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitProductUnit'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
