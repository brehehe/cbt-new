<div>
    @include('livewire.admin.pharmacy.consultation.detail.admin-pharmacy-consultation-detail-modal')
    @php
        $status = $transaction->status;
    @endphp
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Konsultasi Detail</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="mt-1">
                    <label class="inline-flex items-center">
                        <input type="checkbox" {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                            wire:model.lazy="is_outside_pharmacy" class="form-checkbox" />
                        <span class="ml-2">Apakah resep di ambil di luar klinik?</span>
                    </label>
                </div>
                @if (in_array($status, ['pharmacy', 'call_pharmacy']))
                    <button wire:click="confirmSave()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
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
                <p class="mt-1 text-gray-900 font-semibold">
                    {{ $transaction->doctor->name ?? $transaction->doctor_name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                <p class="mt-1 text-gray-900">{{ $transaction->doctor->userDetail->specialization ?? '-' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jam Praktik</label>
                <p class="mt-1 text-gray-900">
                    {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->end_time)->format('H:i') }}
                    WIB</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Antrian Saat Ini</label>
                <p class="mt-1 text-2xl font-bold text-blue-600">{{ $transaction->code_consultation }}</p>
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
    @if (in_array($status, ['pharmacy', 'call_pharmacy']))
        <div class="md:col-span-2 mb-4">
            <button wire:click="changeProduct()"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md w-full"><i
                    class="fa-solid fa-plus"></i> Tambahkan Obat</button>
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
                        <th class="center">Qty</th>
                        <th class="right">Subtotal</th>
                        @if (in_array($status, ['pharmacy', 'call_pharmacy']))
                            <th class="py-2 w-8"></th>
                        @endif
                        {{-- @if ($status == 'draft')
                            @endif --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach ($actions as $key_action => $action)
                        <tr class="border-b">
                            <td class="py-2" colspan="5">
                                <p class="font-medium">{{ $action['product_name'] }}</p>
                                <p class="text-xs text-gray-500">@Rp{{ number_format($action['price'], 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="py-2 text-center">
                                {{ $action['quantity'] }}
                            </td>
                            <td class="py-2 text-right">Rp{{ number_format($action['sub_total_price'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($recipes as $key_recipe => $recipe)
                        <tr class="border-t-4">
                            <td colspan="8" class="py-3 px-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-blue-600"
                                        style="width: {{ $recipe['is_single'] ? '10%' : '15%' }};">/R-{{ $key_recipe + 1 }}</span>
                                    <select disabled
                                        class="bg-gray-100 cursor-not-allowed text-sm border rounded px-2 py-1"
                                        wire:model.lazy='recipes.{{ $key_recipe }}.medicine_type_id'
                                        style="width: 50%;">
                                        <option value="">Jenis Resep</option>
                                        @foreach ($medicine_types as $medicine_type)
                                            <option value="{{ $medicine_type['id'] }}">{{ $medicine_type['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                        <select
                                            {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                            class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                            wire:model.lazy='recipes.{{ $key_recipe }}.product_id'
                                            style="width: 100%;">
                                            <option value="">Jenis Produk Pendukung</option>
                                            @foreach ($supporting_products as $supporting_product)
                                                <option value="{{ $supporting_product['id'] }}">
                                                    {{ $supporting_product['name'] }} -
                                                    {{ $supporting_product['product_stock']['quantity'] }} - Rp
                                                    {{ number_format($supporting_product['product_price']['price'], 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="flex items-center border rounded px-2 py-1 bg-gray-100 cursor-not-allowed"
                                            style="width: 50%;">
                                            <span class="text-gray-500 mr-2 select-none">Rp</span>
                                            <input type="text" disabled
                                                wire:model='recipes.{{ $key_recipe }}.sub_total_price'
                                                placeholder="Jasa 1"
                                                class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                        </div>
                                        {{-- <button class="text-blue-500 hover:text-blue-700" wire:click="addDetail('{{ $recipe['id'] }}')">
                                            <i class="fas fa-plus"></i>
                                        </button> --}}
                                    @endif
                                    {{-- <button class="text-red-600 hover:text-red-800 mx-1" wire:click="confirmDeleteTransactionRecipe('{{ $recipe['id'] }}')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button> --}}
                                </div>
                                <div class="flex items-center gap-2">
                                    <div wire:key="select-{{ rand() }}" class="flex-grow">
                                        <select
                                            {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                            class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control w-full"
                                            x-data x-ref="input" x-init="$($refs.input).selectize({
                                                dropdownParent: 'body',
                                                allowClear: true,
                                                plugins: ['clear_button'],
                                                onChange: function(e) {
                                                    @this.set('recipes.{{ $key_recipe }}.how_to_use_id', e ? e : '');
                                                }
                                            });"
                                            wire:model.lazy="recipes.{{ $key_recipe }}.how_to_use_id"
                                            id="recipes.{{ $key_recipe }}.how_to_use_id">
                                            <option value="">-- Pilih Rute Pemberian Obat --</option>
                                            @foreach ($how_to_uses as $key_how_to_use => $how_to_use)
                                                <option value="{{ $key_how_to_use }}">{{ $how_to_use }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if (in_array($status, ['pharmacy', 'call_pharmacy']))
                                        <button type="button" class="btn btn-warning px-3 py-1 ml-auto"
                                            wire:click="openModalHowToUse('{{ $recipe['id'] }}')">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    @endif
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <input type="text" wire:model.lazy='recipes.{{ $key_recipe }}.description'
                                        placeholder="Informasi Tambahan Aturan Pakai"
                                        {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                        class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} w-full border rounded px-2 py-1">
                                </div>
                                <div class="mt-2 text-sm text-gray-600">
                                    <div wire:key="select-{{ rand() }}">
                                        <select
                                            {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                            class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} mt-1 form-control"
                                            x-data x-ref="input" x-init="$($refs.input).selectize({
                                                dropdownParent: 'body',
                                                allowClear: true,
                                                plugins: ['clear_button'],
                                                onChange: function(e) {
                                                    @this.set('recipes.{{ $key_recipe }}.route_coding_code', e ? e : '');
                                                }
                                            });"
                                            wire:model.lazy="recipes.{{ $key_recipe }}.route_coding_code"
                                            id="recipes.{{ $key_recipe }}.route_coding_code">
                                            <option value="">-- Pilih Rute Pemberian Obat --</option>
                                            @foreach ($master_medication_request_dosage_routes as $key_master_medication_request_dosage_route => $master_medication_request_dosage_route)
                                                <option value="{{ $key_master_medication_request_dosage_route }}">
                                                    {{ $master_medication_request_dosage_route }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                            @if (in_array($status, ['pharmacy', 'call_pharmacy']))
                                                <button wire:click="changeProduct('{{ $item['id'] }}')"
                                                    class="text-yellow-500 hover:text-yellow-700"><i
                                                        class="fas fa-pen"></i></button>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            @Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </td>
                                    @if (!$recipe['is_single'])
                                        <td class="py-2">
                                            <div class="flex items-center gap-2">
                                                <select
                                                    wire:model.lazy='recipes.{{ $key_recipe }}.details.{{ $index_detail }}.type'
                                                    {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                                    class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                    style="width: 100%;">
                                                    <option value="single">Opsi Dosis</option>
                                                    <option value="partial">Partial</option>
                                                    <option value="gramasi">Gramasi</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <input
                                                wire:model.lazy="recipes.{{ $key_recipe }}.details.{{ $index_detail }}.dosage_doctor"
                                                type="text" placeholder="Dosis Dokter"
                                                {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                                class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                style="width: 100%;">
                                        </td>
                                        <td class="py-2">
                                            <input type="text" disabled
                                                wire:model='recipes.{{ $key_recipe }}.details.{{ $index_detail }}.doctor_dosage_gram'
                                                placeholder="Jasa 1"
                                                class="text-sm border rounded px-2 py-1  bg-gray-100 cursor-not-allowed"
                                                style="width: 100%;" />
                                        </td>
                                        <td class="py-2">
                                            <input
                                                wire:model.lazy="recipes.{{ $key_recipe }}.details.{{ $index_detail }}.dosage_drug"
                                                type="text" placeholder="Dosis Obat"
                                                {{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'disabled' }}
                                                class="{{ in_array($status, ['pharmacy', 'call_pharmacy']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1"
                                                style="width: 100%;">
                                        </td>
                                    @endif
                                    <td class="py-2 text-center">
                                        {{ $item['quantity'] }}
                                    </td>
                                    <td class="py-2 text-right">
                                        Rp{{ number_format($item['sub_total_price'], 0, ',', '.') }}</td>
                                    {{-- <td class="py-2 text-center">
                                        <button wire:click="confirmDeleteTransactionDetail('{{ $item['id'] }}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td> --}}
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                    @foreach ($medicines as $key_medicine => $medicine)
                        <tr class="border-b bg-blue-50">
                            <td class="py-2" colspan="5">
                                <p class="font-medium">{{ $medicine['product_name'] }}</p>
                                <p class="text-xs text-gray-500">
                                    @Rp{{ number_format($medicine['price'], 0, ',', '.') }}</p>
                            </td>
                            <td class="py-2 text-center">
                                @if (in_array($status, ['pharmacy', 'call_pharmacy']))
                                    <input type="number" wire:model.lazy="medicines.{{ $key_medicine }}.quantity"
                                        placeholder="Qty" class="text-sm border rounded px-2 py-1 w-full"
                                        style="width: 100%;">
                                @else
                                    <span class="text-sm">{{ $medicine['quantity'] }}</span>
                                @endif
                            </td>
                            <td class="py-2 text-right">
                                Rp{{ number_format($medicine['sub_total_price'], 0, ',', '.') }}</td>
                            @if (in_array($status, ['pharmacy', 'call_pharmacy']))
                                <td>
                                    <button wire:click="confirmDeleteMedicine('{{ $medicine['id'] }}')"
                                        class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tbody>
                    <tr>
                        <th colspan="6" class="right font-bold">
                            Total Produk
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->product_price, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6" class="right font-bold">
                            Embalage
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->embalage, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6" class="right font-bold">
                            Diskon
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->discount_value, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6" class="right font-bold">
                            Pembulatan
                        </th>
                        <th class="font-bold right">
                            Rp{{ number_format($transaction->rounding, 0, ',', '.') }}
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6" class="right font-bold">
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
