<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Buku Besar</h1>
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
    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                        <th>Deskripsi</th>
                        <th>Akun Biaya</th>
                        <th>Debit Debit</th>
                        <th>Debit Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($journals as $index => $journal)
                        <tr>
                            <td rowspan="{{ $journal->items->count() + 1 }}" class="center">
                                {{ $journals->firstItem() + $index }}</td>
                            <td rowspan="{{ $journal->items->count() + 1 }}">{{ $journal->code ?? '-' }}</td>
                            <td rowspan="{{ $journal->items->count() + 1 }}">
                                {{ $journal->date ? \Carbon\Carbon::parse($journal->date)->locale('id')->isoFormat('D MMMM Y') : '-' }}
                            </td>
                            <td rowspan="{{ $journal->items->count() + 1 }}">{{ $journal->description ?? '-' }}</td>
                            <td class="border-t bg-blue-200">Total</td>
                            <td class="border-t bg-blue-200">
                                Rp{{ number_format($journal->items->sum(fn($item) => $item->accountTransaction->debit ?? 0), 0, ',', '.') }}
                            </td>
                            <td class="border-t bg-blue-200">
                                Rp{{ number_format($journal->items->sum(fn($item) => $item->accountTransaction->credit ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                        @forelse ($journal->items as $item)
                            <tr>
                                <td>{{ $item->account->name ?? '-' }}</td>
                                <td>{{ $item->accountTransaction->debit ? 'Rp ' . number_format($item->accountTransaction->debit, 0, ',', '.') : '-' }}
                                </td>
                                <td>{{ $item->accountTransaction->credit ? 'Rp ' . number_format($item->accountTransaction->credit, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="no-data">Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $journals->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $journals->lastItem() }}</span> dari <span
                        class="font-medium">{{ $journals->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $journals->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
