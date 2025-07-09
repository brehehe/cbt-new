<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Insentif</h1>
            </div>
            <div>
                <div class="text-xl">
                    Rp {{ number_format($totals, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                     <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('user_id', e ? e : '');
                            }
                        });" wire:model.live="user_id" id="user_id">
                            <option value="">-- Pilih User --</option>
                            @foreach ($getUsers as $getUser)
                                <option value="{{ $getUser['id'] }}">{{ $getUser['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('type', e ? e : '');
                            }
                        });" wire:model.live="type" id="type">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="dokter">Dokter</option>
                            <option value="apoteker">Apoteker</option>
                            <option value="perawat">Perawat</option>
                            <option value="kasir">Kasir</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('month', e ? e : '');
                            }
                        });" wire:model.live="month" id="month">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach ($getMonths as $getMonth)
                                <option value="{{ $getMonth['number'] }}">{{ $getMonth['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('year', e ? e : '');
                            }
                        });" wire:model.live="year" id="year">
                            <option value="">-- Pilih Tahun --</option>
                            @foreach ($getYears as $getYear)
                                <option value="{{ $getYear }}">{{ $getYear }}</option>
                            @endforeach
                        </select>
                    </div>
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
     <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama</th>
                        <th>Insentif</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userIncentives as $index => $userIncentive)
                        <tr>
                            <td class="center">{{ $userIncentives->firstItem() + $index }}</td>
                            <td>{{ $userIncentive->user?->name ?? '-' }}</td>
                            <td>Rp @number($userIncentive->amount)</td>
                            <td>{{ Str::title($userIncentive->status) ?? '-' }}</td>
                            <td>{{ $userIncentive->created_at ? $userIncentive->created_at->format('d/m/Y') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $userIncentives->firstItem() }}</span> sampai <span class="font-medium">{{ $userIncentives->lastItem() }}</span> dari <span class="font-medium">{{ $userIncentives->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $userIncentives->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
