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
            <button wire:click="openModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md w-full"><i class="fa-solid fa-plus"></i> Tambahkan Obat</button>
        </div>
    @endif
     <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr class="border-b">
                        <th>Produk</th>
                        <th class="center">Qty</th>
                        <th class="right">Subtotal</th>
                        @if (in_array($status, ['sale_pharmacy']))
                            <th class="py-2 w-8"></th>
                        @endif
                        {{-- @if ($status == 'draft')
                            @endif --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction_details as $key_transaction_detail => $transaction_detail)
                        <tr class="border-b bg-blue-50">
                            <td class="py-2" >
                                <p class="font-medium">{{ $transaction_detail['product_name'] }}</p>
                                <p class="text-xs text-gray-500">@Rp{{ number_format($transaction_detail['price'], 0, ',', '.') }}</p>
                            </td>
                            <td class="py-2 text-center">
                                @if (in_array($status, ['sale_pharmacy']))
                                    <input type="number" wire:model.lazy="transaction_details.{{ $key_transaction_detail }}.quantity" placeholder="Qty" class="text-sm border rounded px-2 py-1 w-full" style="width: 100%;">
                                @else
                                    <span class="text-sm">{{ $transaction_detail['quantity'] }}</span>
                                @endif
                            </td>
                            <td class="py-2 text-right">Rp{{ number_format($transaction_detail['sub_total_price'], 0, ',', '.') }}</td>
                            @if (in_array($status, ['sale_pharmacy']))
                                <td>
                                    <button wire:click="confirmDeleteMedicine('{{ $transaction_detail['id'] }}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tbody>
                    <tr>
                        <th colspan="2" class="right font-bold">
                            Total Produk
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->sub_total_price, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="right font-bold">
                            Diskon
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->discount_value, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="right font-bold">
                            Pembulatan
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->rounding, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="right font-bold">
                            Total Pembayaran
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->grand_total_price, 0, ',', '.') }}
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
