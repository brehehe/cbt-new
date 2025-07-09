<div>
    @include('livewire.admin.logistic.stock-product.detail.admin-logistic-stock-product-detail-modal')

    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Detail Stok Opname</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                @if (!$status || $status == 'draft')
                    <button wire:click="confirmSave('draft')" class="btn btn-primary">
                        <span class="fa-solid fa-file-lines mr-1"></span>
                        Buat Draft
                    </button>
                    <button wire:click="confirmSave('process')" class="btn btn-warning">
                        <span class="fa-solid fa-gears mr-1"></span>
                        Buat Proses
                    </button>
                @elseif ($status == 'process')
                    <button wire:click="confirmApprove('rejected')" class="btn btn-danger">
                        <span class="fa-solid fa-file-lines mr-1"></span>
                        Tolak
                    </button>

                    <button wire:click="confirmApprove('approve')" class="btn btn-success">
                        <span class="fa-solid fa-check mr-1"></span>
                        Setujui
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if (!$status || $status == 'draft')
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-5 shadow-lg border border-gray-100 mb-6">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <div class="flex items-center justify-between gap-4">
                        <input type="text" class="form-control-search" placeholder="Cari SKU Number..." wire:model.live='search_sku'>
                        <div>
                            <button wire:click="openModal()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 w-full">
                                <span class="fa-solid fa-box mr-3"></span>
                                Produk
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kode -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode <span class="text-red-600">*</span></label>
                    <input type="text" wire:model="code" placeholder="Contoh: Kode" {{ !$status || $status == 'draft' ? null : 'disabled' }} class="mt-1 form-control" />
                    @error('code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal <span class="text-red-600">*</span></label>
                    <input type="date" wire:model="date" placeholder="Tanggal" {{ !$status || $status == 'draft' ? null : 'disabled' }} class="mt-1 form-control" />
                    @error('date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi <span class="text-red-600">*</span></label>
                    <textarea wire:model="description" placeholder="Deskripsi produk..." {{ !$status || $status == 'draft' ? null : 'disabled' }} class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="w-1 text-center py-3 px-4">No</th>
                        <th class="py-3 px-4" style="width: 150px;">SKU Number</th>
                        <th class="py-3 px-4">Produk</th>
                        <th class="py-3 px-4">Detail</th>
                        <th>Deskripsi</th>
                        @if ($status == 'draft')
                            <th class="w-1 text-center py-3 px-4">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detailOpnames as $index => $detailOpname)
                        <tr class="hover:bg-gray-50">
                            <td class="text-center align-top border-t py-4" rowspan="3">{{ $index + 1 }}</td>
                            <td class="align-top border-t py-4" rowspan="3">
                                {{ $detailOpname['sku_number'] }}
                            </td>
                            <td class="align-top border-t py-4" rowspan="3" style="width: 150px">
                                {{ $detailOpname['product_name'] }}
                            </td>
                            <td class="border-t py-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1"> Fisik <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model.lazy="detailOpnames.{{ $index }}.quantity" class="mt-1 form-control" {{ !$status || $status == 'draft' ? null : 'disabled' }}>
                                    @error("detailOpnames.$index.quantity")
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </td>
                            <td class="border-t py-2" rowspan="3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model.lazy="detailOpnames.{{ $index }}.description" placeholder="Masukan Deskripsi" class="mt-1 form-control" {{ !$status || $status == 'draft' ? null : 'input-disabled' }} rows="10" cols="50" style="resize: none" {{ !$status || $status == 'draft' ? null : 'disabled' }}></textarea>
                                </div>
                            </td>
                            @if (!$status || $status == 'draft')
                                <td class="text-center align-top border-t py-4" rowspan="3">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button class="p-2 text-red-600 hover:text-red-800 transition-colors" wire:click="confirmDelete('{{ $detailOpname['id'] }}')" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td class="py-2">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1"> Sistem</label>
                                        <input type="text" value="{{ $detailOpname['quantity_system'] }}" disabled class="mt-1 form-control">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Selisih </label>
                                        <input type="text" value="{{ $detailOpname['quantity_difference'] }}" disabled class="mt-1 form-control">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2">
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">HPP Average</label>
                                        <input type="text" value="{{ $detailOpname['hpp_average'] }}" disabled class="mt-1 form-control">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Loss Value</label>
                                        <input type="text" value="{{ $detailOpname['loss_value'] }}" disabled class="mt-1 form-control">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Excess Value</label>
                                        <input type="text" value="{{ $detailOpname['excess_value'] }}" disabled class="mt-1 form-control">
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
