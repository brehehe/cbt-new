<div>
    @include('livewire.admin.pharmacy.sale.detail.admin-pharmacy-sale-detail-modal')
    @php
        $status = $transaction->status;
    @endphp
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Penjualan Detail</h1>
            </div>
            <div class="flex items-center gap-4">
                @if (in_array($status, ['sale_pharmacy']))
                    <button wire:click="confirmSaveTransaction('process')" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Simpan
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6 bg-white shadow rounded-lg mb-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Dokter</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $transaction->doctor->name ?? $transaction->doctor_name ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tipe</label>
                <p class="mt-1 text-gray-900">{{ Str::title(Str::replace('-',' ',$transaction->type)) ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Praktik</label>
                <p class="mt-1 text-gray-900">
                    {{ optional($transaction->controlDoctor)->start_time && optional($transaction->controlDoctor)->end_time
                        ? \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->end_time)->format('H:i') . ' WIB'
                        : 'Waktu tidak tersedia'
                    }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Antrian Saat Ini</label>
                <p class="mt-1 text-2xl font-bold text-blue-600">{{ $transaction->code_consultation ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                <p class="mt-1 text-gray-900">{{ $transaction->patient_name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Poli</label>
                <p class="mt-1 text-orange-600 font-medium">{{ $transaction->location->name ?? '-' }}</p>
            </div>
        </div>
    </div>
    @if (in_array($status, ['sale_pharmacy']))
        <div class="md:col-span-2 mb-4">
            <button wire:click="openModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md w-full"><i class="fa-solid fa-plus"></i> Tambahkan Resep</button>
        </div>
    @endif
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr class="border-b">
                        <th>Produk</th>
                        <th>Opsi Dosis</th>
                        <th>Dosis Dokter</th>
                        <th>Total Gramasi</th>
                        <th>Dosis Obat</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th class="py-2 w-8"></th>
                        {{-- @if ($status == 'draft')
                        @endif --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaction_details as $key_transaction_detail => $transaction_detail)
                        <tr class="border-t-4">
                            <td colspan="8" class="py-3 px-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-blue-600" style="width: {{ $transaction_detail['is_single'] ? '10%' : '15%' }};">/R-{{ $key_transaction_detail + 1 }}</span>
                                    <select {{ $status == 'sale_pharmacy' ? '' : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" wire:model.lazy='transaction_details.{{ $key_transaction_detail }}.medicine_type_id' style="width: 50%;">
                                        <option value="">Jenis Resep</option>
                                        @foreach ($medicine_types as $medicine_type)
                                            <option value="{{ $medicine_type['id'] }}">{{ $medicine_type['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed" style="width: 50%;">
                                        <span class="text-gray-500 mr-2 select-none">Rp</span>
                                        <input type="text" disabled wire:model='transaction_details.{{ $key_transaction_detail }}.price_service_one' placeholder="Jasa 1" class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                    </div>
                                    <input type="text" wire:model.lazy='transaction_details.{{ $key_transaction_detail }}.numero_recipe' placeholder="Numero Resep" {{ $status == 'sale_pharmacy' ? null : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" style="width: 50%;">
                                    @if (!$transaction_detail['is_single'])
                                        <select {{ $status == 'sale_pharmacy' ? null : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" wire:model.lazy='transaction_details.{{ $key_transaction_detail }}.product_id' style="width: 100%;">
                                            <option value="">Jenis Produk Pendukung</option>
                                            @foreach ($supporting_products as $supporting_product)
                                                <option value="{{ $supporting_product['id'] }}">{{ $supporting_product['name'] }} - {{ $supporting_product['product_stock']['quantity'] }} - Rp {{ number_format($supporting_product['product_price']['price'], 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                        <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed" style="width: 50%;">
                                            <span class="text-gray-500 mr-2 select-none">Rp</span>
                                            <input type="text" disabled wire:model='transaction_details.{{ $key_transaction_detail }}.sub_total_price' placeholder="Jasa 1" class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                        </div>
                                        @if ($status == 'sale_pharmacy')
                                            <button class="text-blue-500 hover:text-blue-700" wire:click="addDetail('{{ $transaction_detail['id'] }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        @endif
                                    @endif
                                   @if ($status == 'sale_pharmacy')
                                         <button class="text-red-600 hover:text-red-800 mx-1" wire:click="confirmDeleteTransactionRecipe('{{ $transaction_detail['id'] }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                   @endif
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <input type="text" wire:model.lazy='transaction_details.{{ $key_transaction_detail }}.description' placeholder="Aturan Pakai" {{ $status == 'sale_pharmacy' ? null : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} w-full border rounded px-2 py-1">
                                </div>
                            </td>
                        </tr>
                        @if (!empty($transaction_detail['details']))
                            @forelse ($transaction_detail['details'] as $index_detail => $item)
                                <tr class="border-b">
                                    <td class="py-2" colspan="{{ !$transaction_detail['is_single'] ? 1 : 5 }}">
                                        <p class="font-medium">{{ $item['product_name'] }}</p>
                                        <p class="text-xs text-gray-500">@Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </td>
                                    @if (!$transaction_detail['is_single'])
                                        <td class="py-2">
                                            <div class="flex items-center gap-2">
                                                <select wire:model.lazy='transaction_details.{{ $key_transaction_detail }}.details.{{ $index_detail }}.type' {{ $status == 'sale_pharmacy' ? null : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" style="width: 100%;">
                                                    <option value="single">Opsi Dosis</option>
                                                    <option value="partial">Partial</option>
                                                    <option value="gramasi">Gramasi</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <input wire:model.lazy="transaction_details.{{ $key_transaction_detail }}.details.{{ $index_detail }}.dosage_doctor" type="text" placeholder="Dosis Dokter" {{ $status == 'sale_pharmacy' ? null : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" style="width: 100%;">
                                        </td>
                                        <td class="py-2">
                                            <input type="text" disabled wire:model='transaction_details.{{ $key_transaction_detail }}.details.{{ $index_detail }}.doctor_dosage_gram' placeholder="Jasa 1" class="text-sm border rounded px-2 py-1  bg-gray-100 cursor-not-allowed" style="width: 100%;" />
                                        </td>
                                        <td class="py-2">
                                            <input wire:model.lazy="transaction_details.{{ $key_transaction_detail }}.details.{{ $index_detail }}.dosage_drug" type="text" placeholder="Dosis Obat" {{ $status == 'sale_pharmacy' ? null : 'disabled' }} class="{{ $status == 'sale_pharmacy' ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" style="width: 100%;">
                                        </td>
                                    @endif
                                    <td class="py-2 text-center">
                                        {{ $item['quantity'] }}
                                    </td>
                                    <td class="py-2 text-right">Rp{{ number_format($item['sub_total_price'], 0, ',', '.') }}</td>
                                 @if ($status == 'sale_pharmacy')
                                           <td class="py-2 text-center">
                                        <button wire:click="confirmDeleteTransactionDetail('{{ $item['id'] }}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                 @endif
                                </tr>
                            @empty
                                <tr class="border-b">
                                    <td colspan="7" class="py-2 text-center text-gray-500">Tidak ada detail produk</td>
                                </tr>
                            @endforelse
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
