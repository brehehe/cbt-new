<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Pembelian Detail</h1>
            </div>
            @if ($status == 'draft')
                <div>
                    <button wire:click="confirmSubmit()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Bayar Pembelian
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
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                <textarea rows="3" disabled class="mt-1 form-control" wire:model.live='description' id="description"
                    placeholder="Masukkan Deskripsi" autocomplete="false"></textarea>
            </div>
            <div>
                <label for="account_id" class="block text-sm font-medium text-gray-700">Akun <span
                        class="text-red-600">*</span></label>
                <select {{ $status == 'draft' ? null : 'disabled' }} wire:model.live='account_id' id="account_id"
                    class="mt-1 form-control">
                    <option value="">Pilih Akun</option>
                    @foreach ($accounts as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('account_id')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="grand_total" class="block text-sm font-medium text-gray-700">Total Biaya</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                        Rp
                    </span>
                    <input type="text" disabled wire:model='grand_total' class="form-control rounded-l-none"
                        placeholder="0" />
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Produk</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Pajak</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($details as $index => $detail)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>{{ $detail['product_name'] ?? '-' }}</td>
                            <td>{{ $detail['quantity'] ?? 0 }}</td>
                            <td>Rp{{ $detail['price'] ?? 0 }}</td>
                            <td>Rp{{ $detail['tax'] ?? 0 }}</td>
                            <td>Rp{{ $detail['sub_total'] ?? 0 }}</td>
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
