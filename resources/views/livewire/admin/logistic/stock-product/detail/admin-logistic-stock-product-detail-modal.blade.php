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

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <!-- Button Produk Baru dan Lama -->
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
                        wire:model.live='searchProduct'>
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                    </div>
                </div>
            </div>
            @if ($productOld)
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-1 center">No</th>
                                    <th>Sku Number</th>
                                    <th>Nama Produk</th>
                                    <th>Deskripsi</th>
                                    <th class="w-1 center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $index => $product)
                                    <tr>
                                        <td class="center">{{ $products->firstItem() + $index }}</td>
                                        <td>{{ $product->sku_number }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->description ?? '-' }}</td>
                                        <td class="center">
                                            <div class="flex items-center">
                                                <button
                                                    class="btn btn-icon text-yellow-500 hover:text-yellow-600 transition-colors edit-btn"
                                                    wire:click="choiceProduct('{{ $product->id }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="no-data">Tidak
                                            ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Menampilkan <span class="font-medium">{{ $products->firstItem() }}</span> sampai <span
                                    class="font-medium">{{ $products->lastItem() }}</span> dari <span
                                    class="font-medium">{{ $products->total() }}</span> hasil
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    aria-label="Pagination">
                                    {{ $products->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
