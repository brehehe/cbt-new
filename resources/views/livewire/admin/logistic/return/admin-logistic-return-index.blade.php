<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Retur Pembelian</h1>
            </div>
            <div>
                <button wire:click="openDetail()" class="btn btn-primary">
                    <!-- Font Awesome File Icon -->
                    <i class="fa-solid fa-circle-plus text-xl me-1"></i>
                    Buat Retur Pembelian
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
                        <th>Purchase Order</th>
                        <th>Nota Retur</th>
                        <th>Supplier</th>
                        <th>Nota Retur</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($returns as $index => $return)
                        <tr>
                            <td class="center">{{ $returns->firstItem() + $index }}</td>
                            <td>{{ $return?->purchaseOrder->number ?? '-' }}</td>
                            <td>{{ $return?->return_number }}</td>
                            <td>{{ $return?->supplier->name ?? '-' }}</td>
                            <td>{{ $return?->return_number ?? '-' }}</td>
                            <td>{{ $return?->date ?? '-' }}</td>
                            <td>Rp{{ number_format($return?->grand_total ?? 0, 0, ',', '.') }}</td>
                            <td class="center">
                                @if ($return?->status == 'draft')
                                    <div class="flex items-center">
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            wire:click="openDetail('{{ $return->id }}')">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                        <button
                                            class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                            wire:click="confirmDelete('{{ $return->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                @elseif ($return?->status == 'completed')
                                    <div class="flex items-center">
                                        <button class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors"
                                            wire:click="openDetail('{{ $return->id }}')">
                                            <i class="fas fa-eye text-sm"></i>
                                        </button>
                                    </div>
                                @endif
                            </td>
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
                    Menampilkan <span class="font-medium">{{ $returns->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $returns->lastItem() }}</span> dari <span
                        class="font-medium">{{ $returns->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $returns->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
