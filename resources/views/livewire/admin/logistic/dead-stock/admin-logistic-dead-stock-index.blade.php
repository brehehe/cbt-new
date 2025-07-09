<div>
    @include('livewire.admin.logistic.dead-stock.admin-logistic-dead-stock-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Dead Stock</h1>
            </div>
            <div>
                <button wire:click="confirmSave()" class="btn btn-primary">
                    <!-- Font Awesome File Icon -->
                    <i class="fa-solid fa-file-lines text-xl me-1"></i>
                    Simpan Dead Stock
                </button>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg border border-gray-100 mb-6">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <div class="flex items-center justify-between gap-4">
                    <input type="text" class="mt-1 form-control" placeholder="Cari SKU Number..."
                        wire:model.live='search_sku'>
                    <div>
                        <button wire:click="openModal()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full">
                            <span class="fa-solid fa-box mr-3"></span>
                            Produk
                        </button>
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
                        <th>Sku Number</th>
                        <th>Nama Produk</th>
                        <th>Quantity Saat Ini</th>
                        <th>Quantity Rusak</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deadStocks as $index => $deadStock)
                        <tr>
                            <td class="w-1 center">{{ $index + 1 }}</td>
                            <td>{{ $deadStock['sku_number'] }}</td>
                            <td>{{ $deadStock['name'] }}</td>
                            <td>{{ $deadStock['quantity_old'] }}</td>
                            <td>
                                <input type="text" style="width: 150px;"
                                    wire:model.live='deadStocks.{{ $index }}.quantity' class="mt-1 form-control">
                            </td>
                            <td>Rp{{ number_format($deadStock['price'], 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($deadStock['total'], 0, ',', '.') }}</td>
                            <td class="center">
                                <div class="flex items-center">
                                    <!-- Tombol Detail -->
                                    <button
                                        class="btn btn-icon text-red-600 hover:text-red-800 transition-colors edit-btn"
                                        wire:click="confirmDelete('{{ $deadStock['id'] }}')" aria-label="Lihat Detail">
                                        <i class="fas fa-trash text-red-600 text-lg"></i> <!-- FontAwesome Eye Icon -->
                                    </button>
                                </div>
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
    </div>
</div>
