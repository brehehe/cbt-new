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

    <div class="space-y-6 mb-4">
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

                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk <span
                            class="text-red-600">*</span></label>
                    <input type="text" wire:model="name" placeholder="Nama produk" class="mt-1 form-control" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk Varian <span
                            class="text-red-600">*</span></label>
                    <input type="text" wire:model="code_coding_code" placeholder="Masukan Produk Varian"
                        class="mt-1 form-control" />
                    @error('code_coding_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bentuk Obat <span
                            class="text-red-600">*</span></label>
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
                    <label for="sub_total" class="block text-sm font-medium text-gray-700">Total Harga <span
                            class="text-red-600">*</span></label>
                    <input type="text" onkeyup="convertToRupiah(this)" disabled class="mt-1 form-control"
                        wire:model.live='sub_total' id="sub_total" placeholder="XXXXXXXXX" autocomplete="false">
                    @error('sub_total')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="sub_total_final" class="block text-sm font-medium text-gray-700">Total Harga Akhir <span
                            class="text-red-600">*</span></label>
                    <input type="text" onkeyup="convertToRupiah(this)" class="mt-1 form-control"
                        wire:model.live='sub_total_final' id="sub_total_final" placeholder="XXXXXXXXX"
                        autocomplete="false">
                    @error('sub_total_final')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Satuan Terkecil <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('denominator_code', e ? e : '');
                            }
                        });"
                            wire:model.live="denominator_code" id="denominator_code">
                            <option value="">-- Pilih Satuan Terkecil --</option>
                            @foreach ($master_medication_forms as $key_master_medication_form => $master_medication_form)
                                <option value="{{ $key_master_medication_form }}">
                                    {{ $master_medication_form }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('denominator_code')
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
                <div>
                    <label class="block text-sm font-medium text-gray-700" for="is_stock_ingredient">
                        is_stock_ingredient <span class="text-red-600">*</span>
                    </label>

                    <div class="flex items-start mt-2 space-x-2">
                        <input wire:model.live="is_stock_ingredient" type="checkbox" id="is_stock_ingredient"
                            class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <label for="is_stock_ingredient" class="text-sm text-gray-600">
                            Centang jika pengurangan stok dilakukan berdasarkan <strong>produk paket (resep)</strong>
                            yang terdiri dari beberapa <strong>produk bahan (ingredients)</strong>. <br>
                            Biarkan tidak dicentang jika pengurangan stok langsung dari <strong>produk utama</strong>.
                        </label>
                    </div>

                    @error('is_stock_ingredient')
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

            @if ($is_stock_ingredient == false)
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
            @endif
        </div>
    </div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Produk Paket</h1>
            </div>
            <div>
                <button wire:click="createProductPackage()" class="btn btn-warning">
                    <!-- Font Awesome File Icon -->
                    <i class="fa-solid fa-circle-plus text-xl me-1"></i>
                    Tambah Produk Paket
                </button>
            </div>
        </div>
    </div>
    @php
        $selectedIds = collect($details)->pluck('product_id')->filter()->toArray();
    @endphp
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama Produk</th>
                        {{-- <th>Hpp Average</th> --}}
                        {{-- <th>Harga</th> --}}
                        {{-- <th>Total</th> --}}
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $index => $detail)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>
                                <div wire:key="select-{{ rand() }}">
                                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                        dropdownParent: 'body',
                                        allowClear: true,
                                        plugins: ['clear_button'],
                                        onChange: function(e) {
                                            @this.set('details.{{ $index }}.product_id', e ? e : null)
                                        }
                                    });"
                                        wire:model.live="details.{{ $index }}.product_id"
                                        id="details.{{ $index }}.product_id">
                                        <option value="">-- Pilih Produk --</option>
                                        @foreach ($products as $key_product => $product)
                                            @php
                                                $isSelected = $detail['product_id'] == $key_product;
                                                $alreadySelected = in_array($key_product, $selectedIds) && !$isSelected;
                                            @endphp

                                            @if (!$alreadySelected)
                                                <option value="{{ $key_product }}">{{ $product }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @error('details.' . $index . '.product_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            {{-- <td>Rp{{ number_format($detail['hpp_average'] ?? 0, 0, ',', '.') }}</td> --}}
                            {{-- <td>Rp{{ number_format($detail['price'] ?? 0, 0, ',', '.') }}</td> --}}
                            {{-- <td>Rp{{ number_format($detail['sub_total_price'] ?? 0, 0, ',', '.') }}</td> --}}
                            <td class="center">
                                <button
                                    class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                    wire:click="confirmDelete('{{ $index }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data paket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
