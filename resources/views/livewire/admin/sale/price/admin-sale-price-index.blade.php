<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Update Harga Jual</h1>
            </div>
            <div>
                <button wire:click="generate()" class="btn bg-yellow-500 text-white hover:bg-yellow-400">
                    <i class="fa-regular fa-repeat"></i>
                    Generate Harga Jual
                </button>
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
                        <th>Sku Number</th>
                        <th>Nama Produk</th>
                        <th>HPP Average</th>
                        <th>Harga Normal</th>
                        {{-- <th>Harga Resep</th> --}}
                        {{-- <th class="w-1 center">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productPrices as $index => $productPrice)
                        <tr>
                            <td class="center">{{ $productPrices->firstItem() + $index }}</td>
                            <td>{{ $productPrice->product?->sku_number ?? '-' }}</td>
                            <td>{{ $productPrice->product?->name ?? '-' }}</td>
                            <td>Rp @number($productPrice->hpp_average)</td>
                            <td>Rp @number($productPrice->price_generate)</td>
                            {{-- <td>Rp @number($productPrice->recipe_generate)</td> --}}
                            {{-- <td class="center">
                                <div class="flex items-center">
                                    <button class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn" wire:click="edit('{{ $productPrice->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn" wire:click="confirmDelete('{{ $productPrice->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $productPrices->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $productPrices->lastItem() }}</span> dari <span
                        class="font-medium">{{ $productPrices->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $productPrices->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <button wire:click="confirmUpdatePrice()" class="btn btn-primary">
                    <i class="fa-regular fa-circle-check"></i>
                    Simpan Harga Jual
                </button>
            </div>
        </div>
    </div>
</div>
