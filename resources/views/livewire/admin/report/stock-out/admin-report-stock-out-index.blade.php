<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Laporan Stok Keluar</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg border border-gray-100 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-4">
            <!-- Supplier Filter -->
            <div>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('product_id', e ? e : '');
                        }
                    });"
                        wire:model.live="product_id" id="product_id">
                        <option value="">-- Pilih Produk --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div x-data="{
                startDate: @entangle('start_date').live,
                endDate: @entangle('end_date').live,
                init() {
                    flatpickr(this.$refs.startInput, {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.startDate,
                        onChange: ([date], dateStr) => {
                            this.startDate = dateStr;
                            if (this.endDate && this.endDate < dateStr) {
                                this.endDate = '';
                                this.$refs.endInput._flatpickr.clear();
                            }
                            this.$refs.endInput._flatpickr.set('minDate', dateStr);
                        }
                    });
            
                    flatpickr(this.$refs.endInput, {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.endDate,
                        onChange: ([date], dateStr) => {
                            this.endDate = dateStr;
                        }
                    });
                },
                resetDates() {
                    this.startDate = '';
                    this.endDate = '';
                    this.$refs.startInput._flatpickr.clear();
                    this.$refs.endInput._flatpickr.clear();
                }
            }" x-init="init()" class="flex items-center space-x-2">

                <!-- Tanggal Dari -->
                <div class="relative flex-1">
                    <input type="text" x-ref="startInput" x-model="startDate" placeholder="Tanggal Dari"
                        class="mt-1 form-control">
                </div>

                <!-- Tanggal Sampai -->
                <div class="relative flex-1">
                    <input type="text" x-ref="endInput" x-model="endDate" placeholder="Tanggal Sampai"
                        class="mt-1 form-control">
                </div>

                <!-- Tombol Reset -->
                @if ($start_date || $end_date)
                    <div>
                        <button type="button" @click="resetDates()" wire:click='resetDates()'
                            class="mt-1 px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none">
                            <i class="fa-regular fa-xmark text-white text-lg"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Table Controls -->
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

    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Sku Number</th>
                        <th>Nama Produk</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($productStockHistorys as $index => $productStockHistory)
                        <tr>
                            <td class="center">{{ $productStockHistorys->firstItem() + $index }}</td>
                            <td>{{ $productStockHistory?->product?->sku_number ?? '-' }}</td>
                            <td>{{ $productStockHistory?->product?->name ?? '-' }}</td>
                            <td>{{ $productStockHistory?->code }}</td>
                            <td>{{ $productStockHistory?->description }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span>@number($productStockHistory?->quantity ?? 0)</span>
                                    <span
                                        class="text-gray-500 text-sm">/{{ $productStockHistory->product->unit->name ?? '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">Tidak ada data
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
                    Menampilkan <span class="font-medium">{{ $productStockHistorys->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $productStockHistorys->lastItem() }}</span> dari <span
                        class="font-medium">{{ $productStockHistorys->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $productStockHistorys->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
