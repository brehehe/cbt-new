<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Produk</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="confirmSubmit()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Produk
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Umum Produk</h2>
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
                @if ($getProductType)

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
                @endif
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
        </div>

        <!-- SECTION 2: Detail Kategori & Lokasi -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Kategori & Lokasi Produk</h2>
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
        </div>

        <!-- SECTION 3: Detail Stok & Obat -->
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Stok & Informasi Obat</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <!-- Deviasi Harga -->
                <div class="md:col-span-2">
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

                <!-- Is Non Stock -->
                <div>
                    <div class="flex items-center mt-6">
                        <input type="checkbox" wire:model="is_non_stock"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                        <label class="ml-2 block text-sm text-gray-700">Is Non Stock</label>
                    </div>
                    @error('is_non_stock')
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
            </div>
        </div>
    </div>
</div>
