<div>
    @include('livewire.admin.purchase.mail-order.detail.admin-purchase-mail-order-detail-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Surat Pesanan</h1>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg border border-gray-100 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomer SP <span
                        class="text-red-600">*</span></label>
                <div class="flex items-center gap-2">
                    <input type="text" wire:model="number" placeholder="Contoh: SKU12345" disabled
                        class="mt-1 form-control" />
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Supplier <span class="text-red-600">*</span>
                </label>

                <div class="flex items-center gap-2">
                    @if ($status === 'draft')
                        <div class="flex-1" wire:key="select-{{ rand() }}">
                            <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                plugins: ['clear_button'],
                                onChange: function(e) {
                                    @this.set('supplier_id', e ? e : '');
                                }
                            });"
                                wire:model.live="supplier_id" id="supplier_id">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Tombol Buka Modal Supplier -->
                        <button type="button" wire:click="openModalSupplier()"
                            class="mt-1 px-4 py-2 h-965 bg-green-500 text-white rounded hover:bg-green-600 flex items-center gap-2">
                            <i class="fa-solid fa-plus text-white text-lg"></i> <!-- Icon Plus dari Font Awesome -->
                        </button>

                        <!-- Tombol Save Supplier -->
                        <button type="button" wire:click="saveSupplier()"
                            class="mt-1 px-4 py-2 h-965 bg-yellow-500 text-white rounded hover:bg-yellow-600 flex items-center gap-2">
                            <i class="fa-solid fa-save text-white text-lg"></i> <!-- Icon Save dari Font Awesome -->
                        </button>
                    @else
                        <div class="flex-1" wire:key="select-{{ rand() }}">
                            <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                onChange: function(e) {
                                    @this.set('supplier_id', e ? e : '');
                                }
                            });"
                                wire:model.live="supplier_id" id="supplier_id" disabled class="input-disabled">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier['id'] }}">{{ $supplier['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                @error('supplier_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
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
                        <th>Quantity Order</th>
                        <th>Quantity Diterima</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchaseRequisitionItems as $index => $purchaseRequisitionItem)
                        @php
                            $itemId = $purchaseRequisitionItem->id; // UUID
                        @endphp
                        <tr>
                            <td class="center">{{ $loop->iteration }} </td>
                            <td>{{ $purchaseRequisitionItem->product_name }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span>@number($purchaseRequisitionItem->quantity_detail)</span>
                                    <span
                                        class="text-gray-500 text-sm">/{{ $purchaseRequisitionItem->productUnit->unit->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span>@number($purchaseRequisitionItem->quantity_real)</span>
                                    <span
                                        class="text-gray-500 text-sm">/{{ $purchaseRequisitionItem->product->unit->name ?? '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
