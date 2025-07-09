<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Retur Pembelian</h1>
            </div>
            <div>
                @if (!$status)
                    <button wire:click="confirmSave('draft')" class="btn btn-primary">
                        <!-- Font Awesome File Icon -->
                        <i class="fa-solid fa-circle-plus text-xl me-1"></i>
                        Draft
                    </button>
                    <button wire:click="confirmSave('completed')" class="btn btn-success">
                        <!-- Font Awesome File Icon -->
                        <i class="fa-solid fa-circle-plus text-xl me-1"></i>
                        Proses
                    </button>
                @else
                    @if ($status == 'draft')
                        <button wire:click="confirmSave('completed')" class="btn btn-success">
                            <!-- Font Awesome File Icon -->
                            <i class="fa-solid fa-circle-check text-xl me-1"></i>
                            Proses
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Kode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode <span
                            class="text-red-600">*</span></label>
                    <input type="text" wire:model="return_number" placeholder="Masukan Kode"
                        {{ !$status ? null : 'disabled' }} class="mt-1 form-control" />
                    @error('return_number')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal <span
                            class="text-red-600">*</span></label>
                    <input type="date" wire:model="date" placeholder="Tanggal" {{ !$status ? null : 'disabled' }}
                        class="mt-1 form-control" />
                    @error('date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Supplier <span
                            class="text-red-600">*</span></label>
                    <div>
                        @if ($status)
                            <div wire:key="select-{{ rand() }}">
                                <select disabled x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    {{-- plugins: ['clear_button'], --}}
                                    onChange: function(e) {
                                        @this.set('supplier_id', e ? e : '');
                                    }
                                });"
                                    wire:model.live="supplier_id" id="supplier_id">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('supplier_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        @else
                            <div wire:key="select-{{ rand() }}">
                                <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    plugins: ['clear_button'],
                                    onChange: function(e) {
                                        @this.set('supplier_id', e ? e : '');
                                    }
                                });"
                                    wire:model.live="supplier_id" id="supplier_id">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('supplier_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Purchase Order <span
                            class="text-red-600">*</span></label>
                    <div>
                        <div wire:key="select-{{ rand() }}">
                            @if ($status)
                                <select disabled x-data x-ref="input" x-init="$($refs.input).selectize({
                                    dropdownParent: 'body',
                                    allowClear: true,
                                    onChange: function(e) {
                                        @this.set('purchase_order_id', e ? e : '');
                                    }
                                });"
                                    wire:model.live="purchase_order_id" id="purchase_order_id">
                                    <option value="">-- Pilih Purchase Order --</option>
                                    @foreach ($purchaseOrders as $purchaseOrder)
                                        <option value="{{ $purchaseOrder['id'] }}">{{ $purchaseOrder['number'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select {{ !$status ? null : 'disabled' }} x-data x-ref="input"
                                    x-init="$($refs.input).selectize({
                                        dropdownParent: 'body',
                                        allowClear: true,
                                        plugins: ['clear_button'],
                                        onChange: function(e) {
                                            @this.set('purchase_order_id', e ? e : '');
                                        }
                                    });" wire:model.live="purchase_order_id" id="purchase_order_id">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($purchaseOrders as $purchaseOrder)
                                        <option value="{{ $purchaseOrder['id'] }}">{{ $purchaseOrder['number'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        @error('purchase_order_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe <span
                            class="text-red-600">*</span></label>
                    <select wire:model="type" {{ !$status ? null : 'disabled' }} class="mt-1 form-control"
                        style="height: 40px;">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="return">Pengembalian Barang</option>
                        <option value="exchange">Penukaran Barang</option>
                    </select>
                    @error('type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi <span
                            class="text-red-600">*</span></label>
                    <textarea wire:model="description" placeholder="Deskripsi..." {{ !$status ? null : 'disabled' }}
                        class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Produk</th>
                        <th>SKU</th>
                        <th>Jumlah</th>
                        <th>Jumlah Diterima</th>
                        <th>Jumlah Retur</th>
                        <th>Jumlah Retur Baru <span class="text-danger">*</span></th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchase_order_items as $index => $return)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>{{ $return['product_name'] ?? '-' }}</td>
                            <td>{{ $return['product_sku_number'] ?? '-' }}</td>
                            <td>{{ $return['quantity'] ?? '-' }} / {{ $return['product_unit_name'] }}</td>
                            <td>{{ $return['quantity_accepted'] ?? '-' }} / {{ $return['product_unit_name'] }}</td>
                            <td>{{ $return['quantity_returned'] ?? '-' }} / {{ $return['product_unit_name'] }}</td>
                            <td>
                                <div>
                                    <input type="number"
                                        wire:model.lazy="purchase_order_items.{{ $index }}.quantity_return"
                                        placeholder="Masukan Kode" {{ !$status ? null : 'disabled' }}
                                        class="mt-1 form-control" />
                                    @error('purchase_order_items.' . $index . '.quantity_return')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </td>
                            <td>Rp{{ number_format($return['price'], 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($return['sub_total'], 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
