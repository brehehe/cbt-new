<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Penjualan Produk</h1>
            </div>
        </div>
    </div>
    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" wire:model.live="start_date" placeholder="Contoh: Dari Tanggal"
                        class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" wire:model.live="end_date" placeholder="Contoh: Sampai Tanggal"
                        class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select wire:model.live="type" class="mt-1 form-control">
                        <option value="">Semua Tipe</option>
                        <option value="non-resep">Non Resep</option>
                        <option value="resep">Resep</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Produk</label>
                    <div wire:key="select-{{ rand() }}">

                        <select class="mt-1" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('product_id', e ? e : '');
                            }
                        });"
                            wire:model.live="product_id" id="product_id">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center">
            <span class="text-sm text-gray-700 mr-2">Tampil</span>
            <select class="mt-1 form-control" wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-700 ml-2">data</span>
        </div>

        <div class="relative w-full sm:w-64">
            <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                wire:model.live='search'>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search h-3 w-3 text-gray-400"></i>
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
                        <th>Tipe Produk</th>
                        <th>Sub Total</th>
                        <th>Hpp Total</th>
                        <th>Quantity</th>
                        <th>Profit</th>
                        <th>Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactionProducts as $transactionProduct)
                        <tr>
                            <td class="center">
                                {{ $loop->iteration + ($transactionProducts->currentPage() - 1) * $transactionProducts->perPage() }}
                            </td>
                            <td>
                                <p class="font-medium">{{ $transactionProduct->product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $transactionProduct->product->sku_number }}</p>
                            </td>
                            <td>{{ $transactionProduct->product->productType->name }}</td>
                            <td>Rp{{ number_format($transactionProduct->total_penjualan, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($transactionProduct->total_hpp_total, 0, ',', '.') }}</td>
                            <td>{{ $transactionProduct->total_quantity }}</td>
                            <td>Rp{{ number_format($transactionProduct->total_profit, 0, ',', '.') }}</td>
                            <td>{{ number_format($transactionProduct->average_margin, 0, ',', '.') }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $transactionProducts->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $transactionProducts->lastItem() }}</span> dari <span
                        class="font-medium">{{ $transactionProducts->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $transactionProducts->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
