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
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Pembuat</th>
                        <th>Approval</th>
                        <th>Kerugian</th>
                        <th>Kelebihan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOpnames as $index => $stockOpname)
                        <tr>
                            <td class="center">{{ $stockOpnames->firstItem() + $index }}</td>
                            <td>{{ $stockOpname->code }}</td>
                            <td>{{ date('Y/m/d', strtotime($stockOpname->date)) ?? '-' }}</td>
                            <td>{{ $stockOpname->user->name }} </td>
                            <td>{{ $stockOpname?->approvedBy?->name ?? '-' }} </td>
                            <td>Rp. {{ number_format($stockOpname->total_loss_value, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($stockOpname->total_excess_value, 0, ',', '.') }}</td>
                            <td>
                                @if ($stockOpname->status == 'draft')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Draft</span>
                                @elseif ($stockOpname->status == 'process')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Menunggu</span>
                                @elseif ($stockOpname->status == 'approve')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Disetujui</span>
                                @elseif ($stockOpname->status == 'rejected')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Ditolak</span>
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
                    Menampilkan <span class="font-medium">{{ $stockOpnames->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $stockOpnames->lastItem() }}</span> dari <span
                        class="font-medium">{{ $stockOpnames->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $stockOpnames->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
