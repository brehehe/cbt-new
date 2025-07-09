<div>
    @include('livewire.admin.logistic.good-come.detail.admin-logistic-good-come-detail-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Penerimaan Barang</h1>
            </div>
            @if ($purchaseOrder->status != 'success')
                <div>
                    <button class="btn btn-primary" wire:click="confirmSave()">
                        <i class="fa-solid fa-circle-check mr-2"></i> Akhiri Penerimaan Barang
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg border border-gray-100 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomer SP <span
                        class="text-red-600">*</span></label>
                <input type="text" value="{{ $purchaseOrder->purchaseRequisition->number ?? '-' }}" disabled
                    class="mt-1 form-control" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomer PO <span
                        class="text-red-600">*</span></label>
                <input type="text" value="{{ $purchaseOrder->number ?? '-' }}" disabled class="mt-1 form-control" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Supplier <span
                        class="text-red-600">*</span></label>
                <input type="text" value="{{ $purchaseOrder->supplier->name ?? '-' }}" disabled
                    class="mt-1 form-control" />
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Grand Total <span class="text-red-600">*</span>
                </label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                        Rp
                    </span>
                    <input type="text" disabled value="@number($purchaseOrder->grand_total ?? 0)" class="form-control rounded-l-none"
                        placeholder="0" />
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center" rowspan="2">No</th>
                        <th rowspan="2">Nama Produk</th>
                        <th colspan="3" class="center">Kuantitas</th>
                        <th rowspan="2" class="center">Status</th>
                        @if ($purchaseOrder->status != 'success')
                            <th class="w-1 center" rowspan="2">Aksi</th>
                        @endif
                    </tr>
                    <tr>
                        <th class="center">Dipesan</th>
                        <th class="center">Diterima</th>
                        <th class="center">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $items = optional($purchaseOrder)->purchaseOrderItems
                            ? $purchaseOrder->purchaseOrderItems->sortBy('order')
                            : collect();
                    @endphp
                    @forelse ($items as $index => $purchaseOrderItem)
                        <tr>
                            <td class="w-1 center">{{ $index + 1 }}</td>
                            <td>{{ $purchaseOrderItem->product->name }}</td>
                            <td class="center">{{ $purchaseOrderItem->quantity }}</td>
                            <td class="center">{{ $purchaseOrderItem->quantity_accepted }}</td>
                            <td class="center">{{ $purchaseOrderItem->productUnit->unit->name }}</td>
                            <td class="center">
                                @if ($purchaseOrderItem->quantity_accepted == $purchaseOrderItem->quantity)
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-md text-sm">Selesai</span>
                                @elseif ($purchaseOrderItem->quantity_accepted != $purchaseOrderItem->quantity)
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-md text-sm">Sebagian</span>
                                @else
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-md text-sm">Belum
                                        Selesai</span>
                                @endif
                            </td>
                            <td class="w-1 center">
                                @if ($purchaseOrder->status != 'success')
                                    @if ($purchaseOrderItem->quantity_accepted < $purchaseOrderItem->quantity)
                                        <button
                                            class="btn btn-icon text-yellow-600 hover:text-yellow-800 transition-colors edit-btn"
                                            wire:click="detail('{{ $purchaseOrderItem->id }}')"
                                            aria-label="Lihat Detail">
                                            <i class="fa-regular fa-memo-circle-info text-yellow-600 text-lg"></i>
                                            <!-- FontAwesome Eye Icon -->
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="center no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
