<div>
    @include('livewire.admin.logistic.import-stock-product.admin-logistic-import-stock-product-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Import Stok Barang</h1>
            </div>
            <div>
                <a href="{{ asset('import/product.xlsx') }}" download class="btn btn-success">
                    <!-- Font Awesome Download Icon -->
                    <i class="fa-solid fa-download text-xl me-1"></i>
                    Download
                </a>
                <button wire:click="openModal('modal')" class="btn btn-warning">
                    <!-- Font Awesome Import Icon -->
                    <i class="fa-solid fa-file-import text-xl me-1"></i>
                    Import
                </button>
                <button wire:click="confirmSave()" class="btn btn-primary" {{ !empty($importStockProducts) ? '' : 'disabled' }}>
                    <!-- Font Awesome File Icon -->
                    <i class="fa-solid fa-file-lines text-xl me-1"></i>
                    Simpan
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
                        <th style="width: 200px;">SKU Number</th>
                        <th>Nama Produk</th>
                        <th>Tipe Produk</th>
                        {{-- <th>Batch Number</th> --}}
                        {{-- <th>Expired Date</th> --}}
                        <th>Quantity</th>
                        <th>HPP Average</th>
                        <th>Harga Jual</th>
                        {{-- <th>Harga Resep</th> --}}
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($importStockProducts as $key => $importStockProduct)
                        <tr>
                            <td class="w-1 center">{{ $key + 1 }}</td>
                            <td><input type="text" style="width: 150px;" wire:model='importStockProducts.{{ $key }}.sku_number' disabled class="mt-1 form-control"></td>
                            <td><input type="text" style="width: 150px;" wire:model='importStockProducts.{{ $key }}.name' disabled class="mt-1 form-control"></td>
                            <td>
                                <select wire:model.live='importStockProducts.{{ $key }}.product_type_id' class="mt-1 form-control" style="width: 150px">
                                    <option value="">Pilih Tipe Produk</option>
                                    @foreach ($productTypes as $productType)
                                        <option value="{{ $productType['id'] }}">{{ $productType['name'] }}</option>
                                    @endforeach
                                </select>
                                @error("importStockProducts.$key.product_type_id")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            {{-- <td>
                                <input type="text" style="width: 150px;" wire:model.lazy='importStockProducts.{{ $key }}.batch_number' class="mt-1 form-control">
                                @error("importStockProducts.$key.batch_number")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="date" style="width: 150px;" wire:model.lazy='importStockProducts.{{ $key }}.expired_date' class="block w-full rounded-md border-gray-300 px-4 py-2 pr-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error("importStockProducts.$key.expired_date")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td> --}}
                            <td>
                                <input type="text" style="width: 150px;" onkeyup="convertToRupiah(this);" wire:model.lazy='importStockProducts.{{ $key }}.quantity' class="mt-1 form-control">
                                @error("importStockProducts.$key.quantity")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" style="width: 150px;" onkeyup="convertToRupiah(this);" wire:model.lazy='importStockProducts.{{ $key }}.hpp_average' class="mt-1 form-control">
                                @error("importStockProducts.$key.hpp_average")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <input type="text" style="width: 150px;" onkeyup="convertToRupiah(this);" wire:model.lazy='importStockProducts.{{ $key }}.selling_price' class="mt-1 form-control">
                                @error("importStockProducts.$key.selling_price")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td>
                            {{-- <td>
                                <input type="text" style="width: 150px;" onkeyup="convertToRupiah(this);" wire:model.lazy='importStockProducts.{{ $key }}.selling_price_recipe' class="mt-1 form-control">
                                @error("importStockProducts.$key.selling_price_recipe")
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </td> --}}
                            <td class="center">
                                <div class="flex items-center">
                                    <button class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn" wire:click="confirmDelete('{{ $key }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
