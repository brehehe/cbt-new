<div>
    @include('livewire.admin.logistic.good-come.admin-logistic-good-come-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Penerimaan Barang</h1>
            </div>
        </div>
    </div>
    {{-- <hr class="my-3 mb-4 mt-4"> --}}
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
                        <th>No SP</th>
                        <th>No PO</th>
                        <th>Supplier</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchaseRequisitions as $index => $purchaseRequisition)
                        <tr>
                            <td class="w-1 center">{{ $purchaseRequisitions->firstItem() + $index }}</td>
                            <td>{{ $purchaseRequisition->number }}</td>
                            <td>
                                @if ($purchaseRequisition->purchaseOrder)
                                    {{ $purchaseRequisition->purchaseOrder->number ?? '-' }}
                                @else
                                    <a href="javascript:void(0)"
                                        wire:click="openCreatePO('{{ $purchaseRequisition->id }}')"
                                        class="text-blue-500 hover:text-red-50">Beri No Invoice</a>
                                @endif
                            </td>
                            <td>{{ $purchaseRequisition->supplier->name ?? '-' }}</td>
                            <td class="center">
                                <div class="flex items-center">
                                    <!-- Tombol Detail -->
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="detail('{{ $purchaseRequisition->id }}')" aria-label="Lihat Detail">
                                        <i class="fas fa-eye text-blue-600 text-lg"></i> <!-- FontAwesome Eye Icon -->
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="no-data">Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $purchaseRequisitions->firstItem() }}</span> sampai
                    <span class="font-medium">{{ $purchaseRequisitions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $purchaseRequisitions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $purchaseRequisitions->links('vendor.livewire.custom') }}
                        <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
