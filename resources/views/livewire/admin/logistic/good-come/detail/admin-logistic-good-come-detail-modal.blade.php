<div wire:ignore.self id="modalAccepted" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-full w-full h-screen flex flex-col">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Penerimaan Barang</h2>
            </div>
            <button wire:click="closeModal()" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk</label>
                    <input type="text" placeholder="Contoh: SKU12345" value="{{ $purchase_order_item?->product?->name }}" disabled class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU Number</label>
                    <input type="text" placeholder="Contoh: SKU12345" value="{{ $purchase_order_item?->product?->sku_number }}" disabled class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jumlah Dipesan</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" value="{{ $purchase_order_item?->quantity }}" disabled class="form-control rounded-r-none" placeholder="0" />
                        <span class="inline-flex items-center rounded-r-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                            {{ $purchase_order_item?->productUnit?->unit?->name }}
                        </span>
                    </div>
                </div>
            </div>
            <hr class="my-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Jumlah Diterima -->
                <div class="border-end">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">HNA</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-l-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">Rp</span>
                                <input type="text" onkeyup="convertToRupiah(this);" wire:model.live='hna' {{ $purchase_order_item && $purchase_order_item->quantity_accepted ? 'disabled' : null }} class="form-control rounded-l-none" placeholder="0" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">HNA PPN</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-l-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">Rp</span>
                                <input type="text" onkeyup="convertToRupiah(this);" wire:model.live='hna_ppn' {{ $purchase_order_item && $purchase_order_item->quantity_accepted ? 'disabled' : null }} class="form-control rounded-l-none" placeholder="0" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sub Total</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center rounded-l-md border border-l-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">Rp</span>
                                <input type="text" onkeyup="convertToRupiah(this);" wire:model.live='sub_total' {{ $purchase_order_item && $purchase_order_item->quantity_accepted ? 'disabled' : null }} class="form-control rounded-l-none" placeholder="0" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-end">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Datang</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" wire:model.live='quantity_arrival' oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="form-control rounded-r-none" placeholder="XXXXXXXXX" />
                                <span class="inline-flex items-center rounded-r-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                                    {{ $purchase_order_item?->productUnit?->unit?->name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Terima</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" value="{{ $quantity_arrival + $purchase_order_item?->quantity_accepted }}" disabled class="form-control rounded-r-none" placeholder="XXXXXXXXX" />
                                <span class="inline-flex items-center rounded-r-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                                    {{ $purchase_order_item?->productUnit?->unit?->name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Kurang</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" value="{{ $quantity_arrival - $purchase_order_item?->quantity_less }}" disabled class="form-control rounded-r-none" placeholder="XXXXXXXXX" />
                                <span class="inline-flex items-center rounded-r-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                                    {{ $purchase_order_item?->productUnit?->unit?->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Expired Date <span class="text-red-600">*</span></label>
                            <button wire:click="addBatchNumber()" {{ $quantity_arrival ? '' : 'disabled' }} class="mt-1 block w-full px-4 py-2 text-sm h-10 font-semibold text-white {{ $quantity_arrival ? 'btn-yellow-500 hover:btn-yellow-500' : 'btn-secondary-500 hover:btn-secondary-500' }} rounded-lg transition">
                                Tambah Expired Date
                            </button>
                        </div>
                        <div>
                            @foreach ($batch_numbers as $key_batch_number => $batch_number)
                                <div class="grid grid-cols-3 md:grid-cols-3 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Expired Date</label>
                                        <input type="date" wire:model.live="batch_numbers.{{ $key_batch_number }}.expired_date" class="mt-1 form-control" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="text" wire:model.live="batch_numbers.{{ $key_batch_number }}.stok" placeholder="Masukan Jumlah" oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="block w-full rounded-l-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                            <span class="inline-flex items-center rounded-r-md border border-l-0 border-gray-300 bg-gray-100 px-3 shadow-sm text-gray-500 text-sm">
                                                {{ $purchase_order_item?->productUnit?->unit?->name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Batch Number</label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="text" wire:model.live="batch_numbers.{{ $key_batch_number }}.batch_number" placeholder="Masukan Batch Number" class="block w-full rounded-l-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                            <button wire:click="deleteBatchNumber('{{ $key_batch_number }}')" class="inline-flex items-center rounded-r-md border border-l-0 px-3 bg-red-500 hover:bg-red-500 text-white"><i class="fa-regular fa-trash"></i></button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}
            </div>

        </div>

        <div class="p-4 border-t flex justify-end space-x-2">
            <button wire:click="closeModal()" class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                Batal
            </button>
            <button wire:click="saveProduct" class="cursor-pointer px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                Simpan Produk
            </button>
        </div>

    </div>
</div>
