<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Penjualan</h1>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        @foreach ($payments as $payment)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $payment->payment_method_name }}</h3>
                        </div>
                    </div>
                </div>
                {{-- <p class="text-sm text-gray-600 mb-1">Total Amount</p> --}}
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($payment->total_amount, 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>
    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                    <input type="date" wire:model.live="start_date" placeholder="Contoh: Dari Tanggal" class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                    <input type="date" wire:model.live="end_date" placeholder="Contoh: Sampai Tanggal" class="mt-1 form-control" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <select wire:model.live="type" class="mt-1 form-control">
                        <option value="">Semua Tipe</option>
                        <option value="non-resep">Non Resep</option>
                        <option value="resep">Resep</option>
                    </select>
                </div>
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
            <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..." wire:model.live='search'>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search h-3 w-3 text-gray-400"></i>
            </div>
        </div>
    </div>
    <!-- Grid Container for Cards -->

    <!-- Summary Card -->
    {{-- <div class="mt-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold mb-2">Total Revenue</h2>
                <p class="text-3xl font-bold">Rp 4,200,000</p>
                <p class="text-blue-100 mt-1">From all payment methods</p>
            </div>
            <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>
    </div> --}}

    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        @foreach ($paymentMethods as $item)
                            <th>{{ $item->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactionPayments as $index => $transactionPayment)
                        <tr>
                            <td class="center">{{ $transactionPayments->firstItem() + $index }}</td>
                            <td>{{ $transactionPayment->code ?? '-' }}</td>
                            <td>{{ $transactionPayment->created_at->format('d F Y') }}</td>
                            <td>{{ Str::title(Str::replace('-', ' ', $transactionPayment->type)) }}</td>
                            @foreach ($paymentMethods as $item)
                                <td>
                                    @php
                                        $payment = $transactionPayment->transactionPayments
                                            ->select(['payment_real', 'payment_method_id'])
                                            ->where('payment_method_id', $item->id)
                                            ->first();
                                    @endphp
                                    @if ($payment)
                                        Rp {{ number_format($payment['payment_real'], 0, ',', '.') }}
                                    @else
                                        Rp 0
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + count($paymentMethods) }}" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $transactionPayments->firstItem() }}</span> sampai <span class="font-medium">{{ $transactionPayments->lastItem() }}</span> dari <span class="font-medium">{{ $transactionPayments->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $transactionPayments->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
