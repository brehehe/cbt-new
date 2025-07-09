<div>
    @include('livewire.admin.sale.pos.detail.admin-sale-pos-detail-modal')
    <main class="max-w-full mx-auto p-4 pt-16 grid grid-cols-1 lg:grid-cols-4 gap-6">


        <div class="bg-white rounded-xl shadow-md p-4 flex flex-col md:col-span-3">
            <!-- Header with Cart Title and DateTime/User Info -->
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-lg">
                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                </h2>
                @if ($transaction->status == 'draft')
                    <div class="text-sm text-gray-500 flex flex-col items-end">
                        <div class="flex gap-2 w-full">
                            <div class="relative flex-1 md:w-94">
                                <input wire:model.live='search_sku' type="text" id="skuInput" placeholder="Masukkan SKU / Scan Barcode" class="w-full pl-10 pr-4 py-2 bg-blue-50 border border-blue-200 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none" autocomplete="off" />
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
                            <th class="py-2 text-center">Qty</th>
                            <th class="py-2 text-right">Subtotal</th>
                            @if ($transaction->status == 'draft')
                                <th class="py-2 w-8"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaction_details as $key_transaction_detail => $transaction_detail)
                            <tr class="border-b">
                                <td class="py-2">
                                    <p class="font-medium">{{ $transaction_detail['product_name'] }}</p>
                                    <p class="text-xs text-gray-500">@Rp{{ number_format($transaction_detail['price'], 0, ',', '.') }}</p>
                                </td>
                                <td class="py-2 text-center">
                                    @if ($transaction->status == 'draft')
                                        <div class="flex justify-center items-center gap-2">
                                            <button wire:click="updateQuantity('{{ $transaction_detail['id'] }}','decrement')" class="w-6 h-6 bg-gray-100 rounded-full hover:bg-gray-200"><i class="fas fa-minus text-xs"></i></button>
                                            <input type="number" wire:model.lazy='transaction_details.{{ $key_transaction_detail }}.quantity' class="w-20 h-6 text-center border rounded" />
                                            <button wire:click="updateQuantity('{{ $transaction_detail['id'] }}','increment')" class="w-6 h-6 bg-gray-100 rounded-full hover:bg-gray-200"><i class="fas fa-plus text-xs"></i></button>
                                        </div>
                                    @else
                                        {{ number_format($transaction_detail['quantity'], 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="py-2 text-right">Rp{{ number_format($transaction_detail['sub_total_price'], 0, ',', '.') }}</td>
                                @if ($transaction->status == 'draft')
                                    <td class="py-2 text-center">
                                        <button wire:click="confirmDeleteTransactionDetail('{{ $transaction_detail['id'] }}')" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-2 text-center text-gray-500">Tidak ada produk dalam keranjang</td>
                            </tr>
                        @endforelse
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
                        @if ($transaction->status == 'draft')
                            <label class="block text-sm font-medium mb-1">Diskon</label>
                            <div class="relative">
                                <select wire:model.live='discount_type' disabled class="absolute left-0 top-0 h-full w-16 border-r border-gray-200 rounded-l-lg text-sm text-center appearance-none bg-gray-50 hover:bg-gray-100 focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none">
                                    <option value="rupiah">Rp</option>
                                    <option value="percentage">%</option>
                                </select>
                                @if ($discount_type == 'percentage')
                                    <input type="number" wire:model.lazy='discount' placeholder="Diskon (%)" {{ $transaction->status == 'draft' ? null : 'disabled' }} class="w-full pl-18 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none" />
                                @else
                                    <input type="text" onkeyup="convertToRupiah(this)" wire:model.lazy='discount' {{ $transaction->status == 'draft' ? null : 'disabled' }} class="w-full pl-18 px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#1E3A8A] focus:outline-none" placeholder="0" />
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
                        @if (in_array($transaction->status, ['draft', 'process']))
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
