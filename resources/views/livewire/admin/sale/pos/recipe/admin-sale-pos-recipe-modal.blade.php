<div wire:ignore.self id="modalProduct"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-full w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Produk</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <!-- Button Produk Baru dan Lama -->
            <!-- Search and Filter Section -->
            <div class="flex gap-4 mb-4">
                <select wire:model.live='perPageProduct'
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
                <div class="relative flex-1">
                    <input type="text" wire:model.live='searchProduct' placeholder="Cari Obat..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-custom" style="max-height: 60vh;">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-white">
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-center font-medium">No</th>
                            <th class="py-3 px-4 text-left font-medium">Sku Number</th>
                            <th class="py-3 px-4 text-left font-medium">Nama Produk</th>
                            <th class="py-3 px-4 text-left font-medium">Deskripsi</th>
                            <th class="py-3 px-4 text-left font-medium">Stok</th>
                            <th class="py-3 px-4 text-left font-medium">Harga</th>
                            <th class="py-3 px-4 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $index => $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4 text-center">{{ $products->firstItem() + $index }}</td>
                                <td class="py-3 px-4">{{ $product->sku_number }}</td>
                                <td class="py-3 px-4">{{ $product->name }}</td>
                                <td class="py-3 px-4">{{ $product->description ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $product->productStock?->quantity ?? 0 }}</td>
                                <td class="py-3 px-4">Rp
                                    {{ number_format($product->productPrice?->price ?? 0, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 mx-1"
                                        wire:click="getProduct('{{ $product->id }}')" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-3 px-4 text-center text-gray-500">Tidak ada data produk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between border-t pt-4 mt-4">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari
                    {{ $products->total() }} data
                </div>

                {{ $products->links('vendor.livewire.paginate-pos') }}
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self id="modalPayment"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Invoice</h2>
            </div>
            <div class="flex items-center gap-4">
                {{-- <div class="text-sm text-gray-500">
                    <span>2025-05-20 09:37:51</span>
                    <span class="mx-2">|</span>
                    <span class="font-medium">brehehe</span>
                </div> --}}
                <button wire:click="closeModalPayment()"
                    class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                    &times;
                </button>
            </div>
        </div>
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Pilih Metode Bayar <span
                        class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('payment_method_id', e ? e : '');
                            }
                        });" wire:model.live="payment_method_id"
                        id="payment_method_id">
                        <option value="">-- Pilih Metode Bayar --</option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('payment_method_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Total Pembayaran <span class="text-red-600">*</span>
                </label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        Rp
                    </span>
                    <input type="text" onkeyup="convertToRupiah(this)" wire:model.lazt="payment_amount"
                        placeholder="XXXXXXXXXXXX" {{ $is_single_payment ? 'disabled' : null }}
                        class="{{ $is_single_payment ? 'block w-full rounded-r-md border border-gray-300 bg-gray-100 text-gray-500 px-4 py-2 cursor-not-allowed focus:outline-none' : 'block w-full rounded-r-md border border-gray-300 px-4 py-2 focus:border-blue-500 focus:ring-blue-500' }}" />
                </div>
                @error('payment_amount')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if ($is_single_payment)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Biaya Admin</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <span
                            class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-500 text-sm">
                            Rp
                        </span>
                        <input type="text" onkeyup="convertToRupiah(this)" wire:model.lazt="admin_fee"
                            placeholder="XXXXXXXXXXXX" disabled
                            class="block w-full rounded-r-md border border-gray-300 bg-gray-100 text-gray-500 px-4 py-2 cursor-not-allowed focus:outline-none" />
                    </div>
                </div>
            @endif
            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea wire:model.lazy='description' rows="3" placeholder="Tambahkan catatan jika diperlukan"
                    class="mt-1 form-control"></textarea>
            </div>
        </div>
        <div class="flex justify-between items-center px-6 py-4 border-t bg-white">
            <div class="text-sm text-gray-500">
                {{-- <span class="font-medium">Status:</span>
                <span class="ml-1 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Draft</span> --}}
            </div>
            <div class="flex gap-2">
                <button wire:click="closeModalPayment()" wire:loading.attr="disabled" wire:target="submitPayment"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button wire:click='submitPayment()' wire:loading.attr="disabled" wire:target="submitPayment"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                    <span wire:loading.remove wire:target="submitPayment">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </span>
                    <span wire:loading wire:target="submitPayment">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
