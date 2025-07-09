<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Keuangan Detail</h1>
            </div>
            <div>
                <button wire:click="confirmSubmit()" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Keuangan Detail
                </button>
            </div>
        </div>
    </div>
    <div class="p-6 bg-white shadow rounded-lg mb-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700">Kode <span
                        class="text-red-600">*</span></label>
                <input type="text" class="mt-1 form-control" wire:model.live='code' id="code"
                    placeholder="Masukkan Kode" autocomplete="false">
                @error('code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi <span
                        class="text-red-600">*</span></label>
                <input type="text" class="mt-1 form-control" wire:model.live='description' id="description"
                    placeholder="Masukkan Deskripsi Paket" autocomplete="false">
                @error('description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">Tanggal <span
                        class="text-red-600">*</span></label>
                <input type="date" class="mt-1 form-control" wire:model.live='date' id="date"
                    placeholder="XXXXXXXXX" autocomplete="false">
                @error('date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700">Akun Biaya <span
                        class="text-red-600">*</span></label>
                <div wire:key="select-{{ rand() }}">
                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                        dropdownParent: 'body',
                        allowClear: true,
                        plugins: ['clear_button'],
                        onChange: function(e) {
                            @this.set('account_id', e ? e : '');
                        }
                    });"
                        wire:model.lazy="account_id" id="account_id">
                        <option value="">-- Pilih Akun Biaya --</option>
                        @foreach ($account_cashs as $key_account_cash => $account_cash)
                            <option value="{{ $key_account_cash }}">{{ $account_cash }}</option>
                        @endforeach
                    </select>
                </div>
                @error('account_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Jenis Transaksi <span
                        class="text-red-600">*</span></label>
                <select class="mt-1 form-control" wire:model.live='type' id="type">
                    <option value="">-- Pilih Jenis Transaksi --</option>
                    @foreach ($get_types as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('type')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="grand_total" class="block text-sm font-medium text-gray-700">Total Biaya <span
                        class="text-red-600">*</span></label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                        Rp
                    </span>
                    <input type="text" disabled wire:model='grand_total' class="form-control rounded-l-none"
                        placeholder="0" />
                </div>
                @error('grand_total')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Transaksi</h1>
            </div>
            <div>
                <button wire:click="createDetail()" class="btn btn-warning">
                    <!-- Font Awesome File Icon -->
                    <i class="fa-solid fa-circle-plus text-xl me-1"></i>
                    Tambah Transaksi
                </button>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama Paket</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        {{-- <th>Total</th> --}}
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $index => $detail)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>
                                <div wire:key="select-{{ rand() }}">
                                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                        dropdownParent: 'body',
                                        allowClear: true,
                                        plugins: ['clear_button'],
                                        onChange: function(e) {
                                            @this.set('details.{{ $index }}.account_id', e ? e : '')
                                        }
                                    });"
                                        wire:model.live="details.{{ $index }}.account_id"
                                        id="details.{{ $index }}.account_id">
                                        <option value="">-- Pilih Akun Biaya --</option>
                                        @foreach ($accounts as $key_account => $account)
                                            <option value="{{ $key_account }}">{{ $account }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('details.' . $index . '.account_id')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td><input type="text" wire:model.lazy="details.{{ $index }}.description"
                                    class="mt-1 form-control" placeholder="Masukkan Deskripsi" />
                                @error('details.' . $index . '.description')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span
                                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                                        Rp
                                    </span>
                                    <input type="text" onkeyup="convertToRupiah(this)"
                                        wire:model.lazy='details.{{ $index }}.sub_total'
                                        class="form-control rounded-l-none" placeholder="0" />
                                </div>
                                @error('details.' . $index . '.sub_total')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            {{-- <td>Rp{{ number_format($detail['sub_total_price'] ?? 0, 0, ',', '.') }}</td> --}}
                            <td class="center">
                                <button
                                    class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                    wire:click="confirmDelete('{{ $index }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data paket.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
