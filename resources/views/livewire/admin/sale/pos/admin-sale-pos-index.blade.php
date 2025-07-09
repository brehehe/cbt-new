<div>
    @include('livewire.admin.sale.pos.admin-sale-pos-modal')

    <main class="max-w-full mx-auto p-4 pt-16 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-md p-4 h-[calc(100vh-7rem)] flex flex-col md:col-span-4">
            <!-- Header with Title and User Info -->
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-4">
                    <h2 class="font-semibold text-lg">
                        <i class="fas fa-history mr-2"></i>Transaksi
                    </h2>
                </div>
                <div class="text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        @if ($cashBank)
                            <button wire:click='confirmCloseCashier()' class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center gap-2">
                                <i class="fas fa-cash-register"></i>
                                <span>Tutup Kasir</span>
                            </button>
                        @endif
                        <button wire:click='openModal()' class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 flex items-center gap-2">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Data</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="flex gap-4 mb-4">
                <div class="relative flex-1">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari transaksi..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>

                <select wire:model.live="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="completed">Selesai</option>
                    <option value="process">Proses</option>
                    <option value="draft">Draft</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>

                <select wire:model.live="type_transaction" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tipe</option>
                    <option value="non-resep">Non-Resep</option>
                    <option value="resep">Resep</option>
                    <option value="konsultasi">Konsultasi</option>
                </select>

                <input wire:model.live="date" type="date" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />

                <button wire:click="resetFilters" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    <i class="fas fa-refresh"></i> Reset
                </button>
            </div>

            <!-- Patient Filter Section -->
            <div class="flex gap-4 mb-4">
                <div class="flex-1">
                    <select wire:model.live="patient_company_role_transaction_id" class="w-full border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-2">
                        <option value="">-- Pilih Pasien --</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}">
                                {{ $patient->user->name ?? 'Unknown' }} -
                                {{ $patient->user->userDetail->address ?? 'No Address' }} -
                                {{ $patient->medical_record_number }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div wire:loading wire:target="search,status,type_transaction,date,patient_company_role_transaction_id" class="mb-4">
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading...
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="flex-1 overflow-y-auto scrollbar-custom" wire:loading.class="opacity-50">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-white border-b-2">
                        <tr class="bg-gray-50">
                            <th class="py-3 px-4 text-left font-medium">No. Transaksi</th>
                            <th class="py-3 px-4 text-left font-medium">Tanggal</th>
                            <th class="py-3 px-4 text-left font-medium">Pelanggan</th>
                            <th class="py-3 px-4 text-left font-medium">Jenis</th>
                            <th class="py-3 px-4 text-left font-medium">Total</th>
                            <th class="py-3 px-4 text-left font-medium">Status</th>
                            <th class="py-3 px-4 text-center font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $index => $transaction)
                            <tr wire:key="transaction-row-{{ $transaction->id }}-{{ $loop->index }}" class="border-b hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">{{ $transaction->code }}</td>
                                <td class="py-3 px-4">{{ $transaction->created_at->format('Y-m-d') }}</td>
                                <td class="py-3 px-4">{{ $transaction->patient_name ?? 'Unknown' }}</td>
                                <td class="py-3 px-4">
                                    @if ($transaction->type == 'non-resep')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Non-Resep</span>
                                    @elseif ($transaction->type == 'resep')
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Resep</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Konsultasi</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">Rp {{ number_format($transaction->grand_total_price ?? 0, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    @switch($transaction->status)
                                        @case('take_medicine')
                                        @case('completed')
                                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Selesai</span>
                                        @break

                                        @case('process')
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Menunggu Pembayaran</span>
                                        @break

                                        @case('draft')
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Draft</span>
                                        @break

                                        @default
                                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Dibatalkan</span>
                                    @endswitch
                                </td>
                                <td class="py-3 px-4 text-center">
                                    @switch($transaction->status)
                                        @case('take_medicine')
                                        @case('completed')
                                            <button class="text-blue-600 hover:text-blue-800 mx-1" wire:click="openDetail('{{ $transaction->id }}')" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800 mx-1" title="Cetak">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        @break

                                        @case('process')
                                        @case('draft')
                                            <button class="text-blue-600 hover:text-blue-800 mx-1" wire:click="openDetail('{{ $transaction->id }}')" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-800 mx-1" wire:click="confirmDelete('{{ $transaction->id }}')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @break

                                        @default
                                            <button class="text-blue-600 hover:text-blue-800 mx-1" title="Lihat Detail" wire:click="openDetail('{{ $transaction->id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                    @endswitch
                                </td>
                            </tr>

                            @empty
                                <tr wire:key="empty-transactions-row">
                                    <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-2 block"></i>
                                        Tidak ada data transaksi
                                        @if ($search || $status || $type_transaction || $date || $patient_company_role_transaction_id)
                                            <div class="mt-2">
                                                <button wire:click="resetFilters" class="text-blue-600 hover:text-blue-800">
                                                    Reset filter untuk melihat semua data
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-between border-t pt-4 mt-4">
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $transactions->firstItem() }} sampai {{ $transactions->lastItem() }}
                        dari {{ $transactions->total() }} data
                    </div>

                    <div class="flex items-center space-x-2">
                        {{ $transactions->links('vendor.livewire.pos') }}
                    </div>
                </div>
            </div>
        </main>
    </div>
