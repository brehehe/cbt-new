<div>
    @include('livewire.admin.sale.pos.detail.admin-sale-pos-detail-modal')
    <main class="max-w-full mx-auto p-4 pt-16 grid grid-cols-1 lg:grid-cols-4 gap-6">


        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col md:col-span-3">
            <!-- Header with Cart Title and DateTime/User Info -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">
                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                </h2>
                @if (in_array($transaction->status,['draft']))
                    <div class="text-sm text-gray-500 flex flex-col items-end">
                        <div class="flex gap-2 w-full">
                            <div class="relative flex-1 md:w-94">
                                <input wire:model.lazy='search_sku' type="text" id="skuInput" placeholder="Masukkan SKU / Scan Barcode" class="w-full pl-10 pr-4 py-2 bg-blue-50 border border-blue-200 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none" autocomplete="off" />
                                <i class="fas fa-barcode absolute left-3 top-1/2 -translate-y-1/2 text-[#1E3A8A]"></i>
                            </div>
                            <!-- Right side buttons -->
                            <div class="flex gap-2">
                                <button class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-900 whitespace-nowrap transition-colors duration-150" wire:click="openModal()">
                                    <i class="fas fa-search mr-2"></i>Pilih Produk
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="flex-1 overflow-y-auto scrollbar-custom">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2 text-left">Produk</th>
                                <th class="py-2 text-left">Opsi Dosis</th>
                                <th class="py-2 text-left">Dosis Dokter</th>
                                <th class="py-2 text-left">Total Gramasi</th>
                                <th class="py-2 text-center">Dosis Obat</th>
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Subtotal</th>
                            @if (in_array($transaction->status,['draft']))
                                <th class="py-2 w-8"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($actions as $key_action => $action)
                            <tr class="border-b">
                                <td class="py-2" colspan="5">
                                    <p class="font-medium">{{ $action['product_name'] }}</p>
                                    <p class="text-xs text-gray-500">@Rp{{ number_format($action['price'], 0, ',', '.') }}</p>
                                </td>
                                <td class="py-2 text-center">
                                    {{ $action['quantity'] }}
                                </td>
                                <td class="py-2 text-right">Rp{{ number_format($action['sub_total_price'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                        @foreach ($transaction_details as $key => $transaction_detail)
                            <tr class="border-t-4">
                                <td colspan="7" class="py-3 px-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-blue-600">/R-{{ $key + 1 }}</span>
                                        <select {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" wire:model.lazy='transaction_details.{{ $key }}.medicine_type_id'>
                                            <option value="">Jenis Resep</option>
                                            @foreach ($medicine_types as $medicine_type)
                                                <option value="{{ $medicine_type['id'] }}">{{ $medicine_type['name'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="flex items-center border rounded px-2 py-1 w-30 bg-gray-100 cursor-not-allowed">
                                            <span class="text-gray-500 mr-2 select-none">Rp</span>
                                            <input type="text" disabled wire:model='transaction_details.{{ $key }}.price_service_one' placeholder="Jasa 1" class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                        </div>
                                        <input type="text" {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} wire:model.lazy='transaction_details.{{ $key }}.numero_recipe' placeholder="Numero Resep" class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1 w-30">
                                        @if (!$transaction_detail['is_single'])
                                            <select {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1" wire:model.lazy='transaction_details.{{ $key }}.product_id'>
                                                <option value="">Jenis Produk Pendukung</option>
                                                @foreach ($supporting_products as $supporting_product)
                                                    <option value="{{ $supporting_product['id'] }}">{{ $supporting_product['name'] }} - {{ $supporting_product['product_stock']['quantity'] }} - Rp {{ number_format($supporting_product['product_price']['price'], 0, ',', '.') }}</option>
                                                @endforeach
                                            </select>
                                            <div class="flex items-center border rounded px-2 py-1 w-30 bg-gray-100 cursor-not-allowed">
                                                <span class="text-gray-500 mr-2 select-none">Rp</span>
                                                <input type="text" disabled wire:model='transaction_details.{{ $key }}.sub_total_price' placeholder="Jasa 1" class="text-sm bg-gray-100 text-gray-500 focus:outline-none w-full cursor-not-allowed" />
                                            </div>
                                            @if (in_array($transaction->status,['draft']))
                                                <button class="text-blue-500 hover:text-blue-700" wire:click="addDetail('{{ $transaction_detail['id'] }}')">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            @endif
                                        @endif
                                        @if (in_array($transaction->status,['draft']))
                                            <button class="text-red-600 hover:text-red-800 mx-1" wire:click="confirmDeleteTransactionRecipe('{{ $transaction_detail['id'] }}')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <div class="mt-2 text-sm text-gray-600">
                                        <input type="text" {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} wire:model.lazy='transaction_details.{{ $key }}.description' placeholder="Aturan Pakai" class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} w-full border rounded px-2 py-1">
                                    </div>
                                </td>
                            </tr>
                            @if (!empty($transaction_detail['details']))
                                @foreach ($transaction_detail['details'] as $index => $item)
                                    <tr class="border-b">
                                        <td class="py-2" colspan="{{ !$transaction_detail['is_single'] ? 1 : 5 }}">
                                            <p class="font-medium">{{ $item['product_name'] }}</p>
                                            <p class="text-xs text-gray-500">@Rp{{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </td>
                                        @if (!$transaction_detail['is_single'])
                                            <td class="py-2">
                                                <div class="flex items-center gap-2">
                                                    <select {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} wire:model.lazy='transaction_details.{{ $key }}.details.{{ $index }}.type' class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1">
                                                        <option value="single">Opsi Dosis</option>
                                                        <option value="partial">Partial</option>
                                                        <option value="gramasi">Gramasi</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td class="py-2">
                                                <input wire:model.lazy="transaction_details.{{ $key }}.details.{{ $index }}.dosage_doctor" type="text" placeholder="Dosis Dokter" {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1 w-48">
                                            </td>
                                            <td class="py-2">
                                                <input type="text" disabled wire:model='transaction_details.{{ $key }}.details.{{ $index }}.doctor_dosage_gram' placeholder="Jasa 1" class="text-sm border rounded px-2 py-1 w-48 bg-gray-100 cursor-not-allowed" />
                                            </td>
                                            <td class="py-2">
                                                <input wire:model.lazy="transaction_details.{{ $key }}.details.{{ $index }}.dosage_drug" type="text" placeholder="Dosis Obat" {{ in_array($transaction->status,['draft']) ? null : 'disabled' }} class="{{ in_array($transaction->status,['draft']) ? null : 'bg-gray-100 cursor-not-allowed' }} text-sm border rounded px-2 py-1 w-48">
                                            </td>
                                            </td>
                                        @endif
                                        <td class="py-2 text-center">
                                            {{ $item['quantity'] }}
                                        </td>
                                        <td class="py-2 text-right">Rp{{ number_format($item['sub_total_price'], 0, ',', '.') }}</td>
                                        @if (in_array($transaction->status,['draft']))
                                            <td class="py-2 text-center">
                                                <button wire:click="confirmDeleteTransactionDetail('{{ $item['id'] }}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                        @foreach ($medicines as $key_medicine => $medicine)
                            <tr class="border-b border-t-4">
                                <td class="py-2" colspan="5">
                                    <p class="font-medium">{{ $medicine['product_name'] }}</p>
                                    <p class="text-xs text-gray-500">@Rp{{ number_format($medicine['price'], 0, ',', '.') }}</p>
                                </td>
                                <td class="py-2 text-center">
                                    {{ $medicine['quantity'] }}
                                </td>
                                <td class="py-2 text-right">Rp{{ number_format($medicine['sub_total_price'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg"><i class="fas fa-credit-card mr-2"></i>Pembayaran</h2>
            </div>

            <!-- Transaction Info Section -->
            <div class="bg-gray-50 rounded-lg p-3 mb-4">
                <div class="grid grid-cols-1 gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-receipt text-gray-500 w-5"></i>
                            <span class="text-gray-600">{{ $transaction->code }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-user text-gray-500 w-5"></i>
                            <span class="font-medium">{{ $transaction->patient_name }}</span>
                        </div>
                        @if ($transaction->type == 'resep')
                            <div class="flex items-center text-sm">
                                <i class="fas fa-user-md text-gray-500 w-5"></i>
                                <span class="text-gray-600">{{ $transaction->doctor_name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-id-badge text-gray-500 w-5"></i>
                                <span class="text-gray-600">{{ $transaction->number_recipe ?? '-' }}</span>
                            </div>
                        @endif
                        <div class="flex items-center text-sm">
                            <i class="fas fa-tag text-gray-500 w-5"></i>
                            <span class="text-gray-600">{{ Str::title($transaction->type) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 space-y-4">
                <!-- Bill and Discount Section -->
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Total Tagihan</label>
                        <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-lg font-semibold">
                            Rp {{ number_format($transaction->sub_total_price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div>
                        @if (in_array($transaction->status,['draft','process']))
                            <label class="block text-sm font-medium mb-1">Diskon</label>
                            <div class="relative">
                                <select wire:model.lazy='discount_type' disabled class="absolute left-0 top-0 h-full w-16 border-r border-gray-200 rounded-l-lg text-sm text-center appearance-none bg-gray-50 hover:bg-gray-100 focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none">
                                    <option value="rupiah">Rp</option>
                                    <option value="percentage">%</option>
                                </select>
                                @if ($discount_type == 'percentage')
                                    <input type="number" wire:model.lazy='discount' placeholder="Diskon (%)" {{ in_array($transaction->status,['draft','process']) ? null : 'disabled' }} class="w-full pl-18 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none" />
                                @else
                                    <input type="text" onkeyup="convertToRupiah(this)" wire:model.lazy='discount' {{ in_array($transaction->status,['draft','process']) ? null : 'disabled' }} class="w-full pl-18 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none" placeholder="0" />
                                @endif
                            </div>
                        @else
                            <label class="block text-sm font-medium mb-1">Total Tagihan</label>
                            <div class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-lg font-semibold">
                                @if ($transaction->discount_type == 'percentage')
                                    {{ $transaction->discount }} %
                                @else
                                    Rp {{ number_format($transaction->discount, 0, ',', '.') }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Payment Methods Section -->
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <label class="block text-sm font-medium">Metode Pembayaran</label>
                        @if (in_array($transaction->status,['draft','process']))
                            @if ((float) $transaction->remaining_bill > 0)
                                <button class="text-sm text-blue-600 hover:text-blue-800" wire:click="openModalPayment()">
                                    <i class="fas fa-plus-circle mr-1"></i>Tambah Metode
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
                <!-- Summary Section -->
                <div class="bg-gray-50 rounded-lg p-3">
                    {{-- <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jasa 1</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->first_service_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Produk Pendukung</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->price_product_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jasa 2</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->second_service_price, 0, ',', '.') }}</span>
                    </div> --}}
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Produk</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->product_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Embalage</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->embalage, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Diskon</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->discount_value, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pembulatan</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->rounding, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Pembayaran</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->grand_total_price, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-1">
                    @foreach ($transactionPayments as $transactionPayment)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">{{ $transactionPayment->paymentMethod->name }}</span>
                            <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transactionPayment->payment_amount, 0, ',', '.') }}
                                @if (in_array($transaction->status, ['draft', 'process']))
                                    <button wire:click="confirmDeleteTransactionPayment('{{ $transactionPayment->id }}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                @endif
                            </span>
                        </div>
                    @endforeach
                    <hr class="my-1">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Terbayar</span>
                        <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                    </div>
                    @if ($transaction->is_single_payment)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Biaya Admin</span>
                            <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->single_payment_admin_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total</span>
                            <span class="text-sm font-semibold text-[#1E3A8A]">Rp {{ number_format($transaction->grand_total_price_admin_fee, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sisa Tagihan</span>
                        <span class="text-sm font-semibold text-red-500">Rp {{ number_format($transaction->remaining_bill, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kembalian</span>
                        <span class="text-sm font-semibold text-red-500">Rp {{ number_format($transaction->payment_change, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if ($transaction->status == 'draft')
                <div class="grid grid-cols-3 gap-3 mt-4">
                    <button wire:click='confirmResetTransaction()' type="button" class="px-4 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Reset</span>
                    </button>
                    <button wire:click="confirmSaveTransaction('draft')" type="button" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center justify-center gap-2">
                        <i class="fas fa-file-lines"></i>
                        <span>Draft</span>
                    </button>
                    <button wire:click="confirmSaveTransaction('process')" type="button" class="px-4 py-2 bg-[#1E3A8A] text-white rounded-lg hover:bg-blue-900 flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i>
                        <span>Proses</span>
                    </button>
                </div>
            @elseif ($transaction->status == 'process')
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <button wire:click="confirmDeleteTransaction()" type="button" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i>
                        <span>Batalkan</span>
                    </button>
                    <button wire:click="confirmSaveTransaction('completed')" type="button" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center justify-center gap-2">
                        <i class="fas fa-file-lines"></i>
                        <span>Selesai</span>
                    </button>
                </div>
            @elseif ($transaction->status == 'completed')
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <button wire:click="printInvoice()" type="button" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center justify-center gap-2">
                        <i class="fas fa-file-invoice"></i>
                        <span>Invoice</span>
                    </button>
                    <button wire:click="printReceipt()" type="button" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center justify-center gap-2">
                        <i class="fas fa-file-lines"></i>
                        <span>Struk</span>
                    </button>
                </div>
            @endif
        </div>
    </main>
</div>
