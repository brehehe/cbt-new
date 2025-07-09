<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Stock Opname</h1>
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
                        <th>Quantity</th>
                        <th>Quantity Sistem</th>
                        <th>Quantity Perbedaan</th>
                        <th>Harga</th>
                        <th>Harga Kerugiaan</th>
                        <th>Harga Kelebihan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOpnameItems as $index => $stockOpnameItem)
                        <tr>
                            <td class="center">{{ $stockOpnameItems->firstItem() + $index }}</td>
                            <td>{{ $stockOpnameItem->product->name }} </td>
                            <td>{{ $stockOpnameItem?->quantity ?? '-' }} </td>
                            <td>{{ $stockOpnameItem?->quantity_system ?? '-' }} </td>
                            <td>{{ $stockOpnameItem?->quantity_difference ?? '-' }} </td>
                            <td>Rp. {{ number_format($stockOpnameItem->hpp_average, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($stockOpnameItem->loss_value, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($stockOpnameItem->excess_value, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $stockOpnameItems->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $stockOpnameItems->lastItem() }}</span> dari <span
                        class="font-medium">{{ $stockOpnameItems->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $stockOpnameItems->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
