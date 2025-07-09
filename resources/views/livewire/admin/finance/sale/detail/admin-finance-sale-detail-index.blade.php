<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Penjualan Detail</h1>
            </div>
            @if ($status == 'draft')
                <div>
                    <button wire:click="confirmSubmit()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Konfirmasi Penjualan
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="p-6 bg-white shadow rounded-lg mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Kode</label>
                <input disabled type="text" class="mt-1 form-control" wire:model.live='code' id="code"
                    placeholder="Masukkan Kode" autocomplete="false">
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input disabled type="date" class="mt-1 form-control" wire:model.live='date' id="date"
                    placeholder="Masukkan Tanggal" autocomplete="false">
            </div>
            <div>
                <label for="patient" class="block text-sm font-medium text-gray-700">Pasien</label>
                <input disabled type="text" class="mt-1 form-control"
                    value="{{ $finance->transaction->patient_name }}" id="patient" placeholder="Masukkan Pasien"
                    autocomplete="false">
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipe</label>
                <input disabled type="type" class="mt-1 form-control"
                    value="{{ Str::replace('-', ' ', Str::title($finance->transaction->type)) }}" id="type"
                    placeholder="Masukkan Tipe" autocomplete="false">
            </div>
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea rows="3" disabled class="mt-1 form-control" wire:model.live='description' id="description"
                    placeholder="Masukkan Deskripsi" autocomplete="false"></textarea>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Transaksi Detail</h1>
            </div>
        </div>
    </div>
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
                        <th class="center">Qty</th>
                        <th class="right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Detail Produk Regular --}}
                    @foreach ($details as $key_detail => $detail)
                        <tr>
                            <td class="py-2" colspan="5" style="width: 20%; border-top: 5px solid #0f357f;">
                                <p class="font-medium">
                                    {{ $detail['product_name'] }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    @Rp{{ $detail['price'] }}</p>
                            </td>
                            <td class="py-2 text-center" style="border-top: 5px solid #0f357f;">
                                {{ $detail['quantity'] }}
                            </td>
                            <td class="py-2 text-right" style="border-top: 5px solid #0f357f;">
                                Rp{{ $detail['sub_total'] }}</td>
                        </tr>
                    @endforeach

                    {{-- Detail Resep --}}
                    @foreach ($recipes as $key_recipe => $recipe)
                        <tr>
                            <td colspan="8" class="py-3 px-2" style="border-top: 5px solid #0f357f;">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-blue-600"
                                        style="width: {{ $recipe['is_single'] ? '5%' : '10%' }};">/R-{{ $key_recipe + 1 }}</span>
                                    <span class="font-medium text-blue-600">{{ $recipe['medicine_type'] }}</span>
                                    <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed"
                                        style="width: 50%;">
                                        <span class="text-gray-500 mr-2 select-none">Rp</span>
                                        <input type="text" disabled
                                            wire:model='recipes.{{ $key_recipe }}.price_service_one'
                                            placeholder="Jasa 1"
                                            class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                    </div>
                                    <input type="text" wire:model.lazy='recipes.{{ $key_recipe }}.numero_recipe'
                                        placeholder="Numero Resep"
                                        {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                        class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                        style="width: 50%;">
                                    @if (!$recipe['is_single'])
                                        <span class="font-medium text-blue-600">{{ $recipe['product_name'] }}</span>
                                        <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed"
                                            style="width: 50%;">
                                            <span class="text-gray-500 mr-2 select-none">Rp</span>
                                            <input type="text" disabled
                                                wire:model='recipes.{{ $key_recipe }}.sub_total_price'
                                                placeholder="Jasa 1"
                                                class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-2">
                                    {{ $recipe['how_to_use'] }}
                                    <span class="text-gray-500">|</span>
                                    {{ $recipe['description'] }}
                                    <span class="text-gray-500">|</span>
                                    {{ $recipe['route_coding_code'] }}
                                </div>
                            </td>
                        </tr>
                        @if (!empty($recipe['details']))
                            @foreach ($recipe['details'] as $index_detail => $item)
                                <tr class="border-b">
                                    <td class="py-2" colspan="{{ !$recipe['is_single'] ? 1 : 5 }}"
                                        style="width: 20%;">
                                        <p class="font-medium">
                                            {{ $item['product_name'] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @Rp{{ $item['price'] }}</p>
                                    </td>
                                    @if (!$recipe['is_single'])
                                        <td class="py-2">
                                            {{ $item['type'] }}
                                        </td>
                                        <td class="py-2">
                                            {{ $item['dosage_doctor'] ?? 0 }}
                                        </td>
                                        <td class="py-2">
                                            {{ $item['doctor_dosage_gram'] ?? 0 }}
                                        </td>
                                        <td class="py-2">
                                            {{ $item['dosage_drug'] ?? 0 }}
                                        </td>
                                    @endif
                                    <td class="py-2 text-center">
                                        {{ $item['quantity'] }}
                                    </td>
                                    <td class="py-2 text-right">
                                        Rp{{ $item['sub_total'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Detail Pembayaran --}}
                    @if (!empty($payments))
                        <tr>
                            <td colspan="7" class="py-3 px-2 bg-green-50" style="border-top: 5px solid #059669;">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-green-600 text-lg">
                                        💳 PEMBAYARAN
                                    </span>
                                </div>
                            </td>
                        </tr>
                        @foreach ($payments as $key_payment => $payment)
                            <tr class="bg-green-50/50">
                                <td class="py-2" colspan="5" style="width: 20%;">
                                    <p class="font-medium">
                                        {{ $payment['payment_method'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $payment['description'] ?? 'Pembayaran' }}</p>
                                </td>
                                <td class="py-2 text-center">
                                    1
                                </td>
                                <td class="py-2 text-right font-semibold text-green-600">
                                    Rp{{ $payment['amount'] }}
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    {{-- Total Transaksi --}}
                    <tr>
                        <td colspan="7" class="py-3 px-2 bg-blue-50" style="border-top: 5px solid #1E3A8A;">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-blue-600 text-lg">
                                    📊 RINGKASAN TOTAL
                                </span>
                            </div>
                        </td>
                    </tr>

                    <tr class="bg-blue-50/30">
                        <td class="py-2" colspan="6" style="width: 20%;">
                            <p class="font-medium text-gray-700">
                                Biaya Jasa 1
                            </p>
                        </td>
                        <td class="py-2 text-right font-medium">
                            Rp{{ number_format($finance->first_service_price ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-blue-50/30">
                        <td class="py-2" colspan="6" style="width: 20%;">
                            <p class="font-medium text-gray-700">
                                Biaya Produk Pendukung
                            </p>
                        </td>
                        <td class="py-2 text-right font-medium">
                            Rp{{ number_format($finance->transaction->price_product_price ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-blue-50/30">
                        <td class="py-2" colspan="6" style="width: 20%;">
                            <p class="font-medium text-gray-700">
                                Biaya Jasa 2
                            </p>
                        </td>
                        <td class="py-2 text-right font-medium">
                            Rp{{ number_format($finance->transaction->second_service_price ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    <tr class="bg-blue-50/30">
                        <td class="py-2" colspan="6" style="width: 20%;">
                            <p class="font-medium text-gray-700">
                                Embalage
                            </p>
                        </td>
                        <td class="py-2 text-right font-medium">
                            Rp{{ number_format($finance->transaction->embalage ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="bg-blue-50/30">
                        <td class="py-2" colspan="6" style="width: 20%;">
                            <p class="font-medium text-gray-700">
                                Biaya Produk
                            </p>
                        </td>
                        <td class="py-2 text-right font-medium">
                            Rp{{ number_format($finance->transaction->product_price ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Grand Total --}}
                    <tr class="bg-yellow-100 border-t-2 border-yellow-300">
                        <td class="py-3" colspan="6">
                            <p class="font-bold text-yellow-800 text-lg">
                                SUB TOTAL
                            </p>
                        </td>
                        <td class="py-3 text-right font-bold text-yellow-800 text-lg">
                            Rp{{ number_format($finance->transaction->sub_total_price ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Grand Total --}}
                    <tr class="bg-red-100 border-t-2 border-red-300">
                        <td class="py-3" colspan="6">
                            <p class="font-bold text-red-800 text-lg">
                                PEMBULATAN
                            </p>
                        </td>
                        <td class="py-3 text-right font-bold text-red-800 text-lg">
                            Rp{{ number_format($finance->transaction->rounding ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Grand Total --}}
                    <tr class="bg-blue-100 border-t-2 border-blue-300">
                        <td class="py-3" colspan="6">
                            <p class="font-bold text-blue-800 text-lg">
                                GRAND TOTAL
                            </p>
                        </td>
                        <td class="py-3 text-right font-bold text-blue-800 text-lg">
                            Rp{{ number_format($finance->grand_total, 0, ',', '.') }}
                        </td>
                    </tr>

                    {{-- Total Pembayaran --}}
                    <tr class="bg-green-100">
                        <td class="py-2" colspan="6">
                            <p class="font-semibold text-green-800">
                                Total Pembayaran
                            </p>
                        </td>
                        <td class="py-2 text-right font-semibold text-green-800">
                            Rp{{ number_format($finance->transaction->payment_amount, 0, ',', '.') }}
                        </td>
                    </tr>

                    @php
                        $balance = $finance->transaction->remaining_bill;
                    @endphp

                    {{-- Kembalian/Sisa --}}
                    <tr class="{{ $balance >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                        <td class="py-2" colspan="6">
                            <p class="font-semibold {{ $balance >= 0 ? 'text-green-800' : 'text-red-800' }}">
                                {{ $balance >= 0 ? 'Kembalian' : 'Kurang Bayar' }}
                            </p>
                        </td>
                        <td
                            class="py-2 text-right font-semibold {{ $balance >= 0 ? 'text-green-800' : 'text-red-800' }}">
                            Rp{{ number_format($finance->payment_change, 0, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
