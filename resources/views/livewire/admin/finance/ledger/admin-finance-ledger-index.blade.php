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
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700">Akun Biaya</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('account_id', e ? e : '');
                            }
                        });"
                            wire:model.lazy="account_id" id="account_id">
                            <option value="">-- Pilih Akun Biaya --</option>
                            @foreach ($accounts as $key_account => $account)
                                <option value="{{ $key_account }}">{{ $account }}</option>
                            @endforeach
                        </select>
                    </div>
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
                        <th>Akun Biaya</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th style="width: 15%">Debit</th>
                        <th style="width: 15%">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accountTransactions as $index => $accountTransaction)
                        <tr>
                            <td class="center">{{ $accountTransactions->firstItem() + $index }}</td>
                            <td>{{ $accountTransaction->journalItem->code ?? '-' }}</td>
                            <td>{{ $accountTransaction->account->name ?? '-' }}</td>
                            <td>{{ $accountTransaction->date ? \Carbon\Carbon::parse($accountTransaction->date)->locale('id')->isoFormat('D MMMM Y') : '-' }}
                            </td>
                            <td>{{ $accountTransaction->description ?? '-' }}</td>
                            <td>{{ $accountTransaction->debit ? 'Rp ' . number_format($accountTransaction->debit, 0, ',', '.') : '-' }}
                            </td>
                            <td>{{ $accountTransaction->credit ? 'Rp ' . number_format($accountTransaction->credit, 0, ',', '.') : '-' }}
                            </td>
                        </tr>
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
                    Menampilkan <span class="font-medium">{{ $accountTransactions->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $accountTransactions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $accountTransactions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $accountTransactions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
