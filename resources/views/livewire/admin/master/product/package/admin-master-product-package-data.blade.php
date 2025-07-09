<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Paket</h1>
            </div>
            <div>
                <button wire:click="save()" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Detail Paket
                </button>
            </div>
        </div>
    </div>
    <div class="p-6 bg-white shadow rounded-lg mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama <span class="text-red-600">*</span></label>
                <input type="text" class="mt-1 form-control" wire:model.live='name' id="name" placeholder="Masukkan Nama Paket" autocomplete="false">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-600">*</span></label>
                <input type="text" class="mt-1 form-control" wire:model.live='description' id="description" placeholder="Masukkan Deskripsi Paket" autocomplete="false">
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- <div>
                <label for="hpp_average" class="block text-sm font-medium text-gray-700">HPP Average <span class="text-red-600">*</span></label>
                <input type="text" onkeyup="convertToRupiah(this)" disabled class="mt-1 form-control" wire:model.live='hpp_average' id="hpp_average" placeholder="XXXXXXXXX" autocomplete="false">
                @error('hpp_average')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="hpp_average_total" class="block text-sm font-medium text-gray-700">Total HPP Average <span class="text-red-600">*</span></label>
                <input type="text" onkeyup="convertToRupiah(this)" disabled class="mt-1 form-control" wire:model.live='hpp_average_total' id="hpp_average_total" placeholder="XXXXXXXXX" autocomplete="false">
                @error('hpp_average_total')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
            <div>
                <label for="sub_total" class="block text-sm font-medium text-gray-700">Total Harga <span class="text-red-600">*</span></label>
                <input type="text" onkeyup="convertToRupiah(this)" disabled class="mt-1 form-control" wire:model.live='sub_total' id="sub_total" placeholder="XXXXXXXXX" autocomplete="false">
                @error('sub_total')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="sub_total_final" class="block text-sm font-medium text-gray-700">Total Harga Akhir <span class="text-red-600">*</span></label>
                <input type="text" onkeyup="convertToRupiah(this)" class="mt-1 form-control" wire:model.live='sub_total_final' id="sub_total_final" placeholder="XXXXXXXXX" autocomplete="false">
                @error('sub_total_final')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Produk</h1>
            </div>
            <div>
                <button wire:click="createProductPackage()" class="btn btn-warning">
                    <!-- Font Awesome File Icon -->
                    <i class="fa-solid fa-circle-plus text-xl me-1"></i>
                    Tambah Produk
                </button>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama Paket</th>
                        <th>Quantity</th>
                        <th>Hpp Average</th>
                        <th>Harga</th>
                        {{-- <th>Total</th> --}}
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($product_packages as $index => $product_package)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>
                                <div wire:key="select-{{ rand() }}">
                                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                        dropdownParent: 'body',
                                        allowClear: true,
                                        plugins: ['clear_button'],
                                        onChange: function(e) {
                                            @this.set('product_packages.{{ $index }}.product_id', e ? e : '')
                                        }
                                    });" wire:model.live="product_packages.{{ $index }}.product_id" id="product_packages.{{ $index }}.product_id">
                                        <option value="">-- Pilih Kategori Produk --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td><input type="number" wire:model.lazy="product_packages.{{ $index }}.quantity" class="mt-1 form-control" placeholder="Masukkan Jumlah" /></td>
                            <td>Rp{{ number_format($product_package['hpp_average'] ?? 0, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($product_package['price'] ?? 0, 0, ',', '.') }}</td>
                            {{-- <td>Rp{{ number_format($product_package['sub_total_price'] ?? 0, 0, ',', '.') }}</td> --}}
                            <td class="center">
                                <button class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn" wire:click="confirmDelete('{{ $index }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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
