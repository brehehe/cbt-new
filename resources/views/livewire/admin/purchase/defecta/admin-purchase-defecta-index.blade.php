<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Defecta</h1>
            </div>
            <div>
                <button wire:click="confirmReject()" class="btn btn-danger">
                    <i class="fa-solid fa-circle-xmark text-xl"></i>
                    Reject
                </button>
                <button wire:click="confirmSave()" class="btn btn-primary">
                    <i class="fa-solid fa-file-lines text-xl"></i>
                    Terima
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
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
            <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..." wire:model.live='search'>
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
                        <th class="w-1 center">
                            <input type="checkbox" wire:model.live="selectAll">
                        </th>
                        <th>Produk</th>
                        <th>Max Stok</th>
                        <th>Stok Saat Ini</th>
                        <th>Stok Permintaan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($defectas as $defecta)
                        <tr>
                            <td class="center">
                                <input type="checkbox" value="{{ $defecta->id }}" wire:model.live="selected">
                            </td>
                            <td>{{ $defecta->product->name }}</td>
                            <td>{{ $defecta->product->maximum_stock }}</td>
                            <td>{{ $defecta->productStock->quantity }}</td>
                            <td>{{ $defecta->minimum_stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-600">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $defectas->firstItem() }}</span> sampai
                    <span class="font-medium">{{ $defectas->lastItem() }}</span> dari <span class="font-medium">{{ $defectas->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $defectas->links('vendor.livewire.custom') }}
                        <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
