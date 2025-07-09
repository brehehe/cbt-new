<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Pengambilan Obat</h1>
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
    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nomor Antrian</th>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Poli</th>
                        <th>Tanggal</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $index => $transaction)
                        <tr>
                            <td class="center">{{ $transactions->firstItem() + $index }}</td>
                            <td>
                                <p>{{ $transaction->code_consultation ?? '-' }}</p>
                                @if ($transaction->type == 'konsultasi')
                                    <span class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $transaction->controlDoctor->end_time)->format('H:i') }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $transaction->patient_name ?? '-' }}</td>
                            <td>{{ $transaction->doctor_name ?? '-' }}</td>
                            <td>{{ $transaction->location_name ?? '-' }}</td>
                            <td>
    {{
        $transaction->date
            ? \Carbon\Carbon::parse($transaction->date)->locale('id')->isoFormat('D MMMM Y')
            : '-'
    }}
</td>

                            <td>{{ Str::title(Str::replace('-',' ', $transaction->type)) ?? '-' }}</td>
                            <td>
                                @if($transaction->status === 'take_medicine')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Pengambilan Obat
                                    </span>
                                @elseif($transaction->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Selesai
                                    </span>
                                @elseif($transaction->status === 'pharmacy')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Di Apotek
                                    </span>
                                @elseif($transaction->status === 'call_pharmacy')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        Panggil Apotek
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Status Tidak Dikenal
                                    </span>
                                @endif
                            </td>
                            <td class="center">
                                <div class="flex items-center">
                                    <button class="btn btn-icon text-yellow-600 hover:text-yellow-800 transition-colors edit-btn" wire:click="confirmDetail('{{ $transaction->id }}')">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data
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
                    Menampilkan <span class="font-medium">{{ $transactions->firstItem() }}</span> sampai <span class="font-medium">{{ $transactions->lastItem() }}</span> dari <span class="font-medium">{{ $transactions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $transactions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
