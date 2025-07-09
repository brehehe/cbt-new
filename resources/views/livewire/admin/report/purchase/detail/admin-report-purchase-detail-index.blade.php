<div>
    @include('livewire.admin.logistic.good-come.detail.admin-logistic-good-come-detail-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Pembelian</h1>
            </div>
            @if ($purchaseOrder->status != 'success')
                <div>
                    <button class="btn btn-primary" wire:click="confirmSave()">
                        <i class="fa-solid fa-circle-check mr-2"></i> Akhiri Pembelian
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
                        <th class="w-1 center">No</th>
                        <th>Nama Produk</th>
                        <th class="center">Kuantitas</th>
                        <th>Harga</th>
                        <th>Pajak</th>
                        <th>Total</th>
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
                            <td>
                                Rp{{ number_format($purchaseOrderItem->hna ?? 0, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp{{ number_format($purchaseOrderItem->ppn ?? 0, 0, ',', '.') }}
                            </td>
                            <td>
                                Rp{{ number_format($purchaseOrderItem->sub_total ?? 0, 0, ',', '.') }}
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
