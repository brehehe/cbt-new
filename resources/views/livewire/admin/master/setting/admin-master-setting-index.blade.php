<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#f58634]">Pengaturan</h1>
                <p class="text-gray-600">Kelola pengaturan perusahaan Anda dengan mudah.</p>
            </div>
            <div>
                <button wire:click="save()" class="btn btn-warning">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan {{ Str::title(Str::replace('-', ' ', $currentTab)) }}
                </button>
            </div>
        </div>
    </div>


    <div class="mb-4">
        <div class="overflow-x-auto  w-full">
            <nav class="flex w-full gap-2 px-2" aria-label="Tabs">
                @foreach ($tabs as $tab)
                    <button wire:click="setTab('{{ $tab }}')"
                        class="text-center px-4 py-2 text-sm font-medium transition-all duration-300 cursor-pointer rounded-2xl
                               {{ $currentTab === $tab ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-black' }}">
                        {{ Str::title(Str::replace('-', ' ', $tab)) }}
                    </button>
                @endforeach
            </nav>
        </div>

        <div class="mt-4">
            @if ($currentTab === 'perusahaan')
                <div class="space-y-6 mb-2">
                    {{-- Informasi Perusahaan --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Perusahaan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Kode Perusahaan
                                </label>
                                <input type="text" wire:model="code" placeholder="Contoh: ABC123" disabled
                                    class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm input-disabled focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Perusahaan <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="name" placeholder="Contoh: PT Maju Jaya"
                                    class="mt-1 form-control" />
                                @error('name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Resmi</label>
                                <input type="email" wire:model="email_company"
                                    placeholder="Contoh: info@perusahaan.com" class="mt-1 form-control" />
                                @error('email_company')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telepon <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="phone"
                                    placeholder="Contoh: 02112345678 atau 08123456789" class="mt-1 form-control" />
                                @error('phone')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" wire:model="website"
                                    placeholder="Contoh: https://www.perusahaan.com" class="mt-1 form-control" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea wire:model="description" placeholder="Ceritakan secara singkat tentang perusahaan Anda..."
                                    class="mt-1 form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Perusahaan --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Alamat Perusahaan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Negara <span
                                        class="text-red-600">*</span></label>
                                <div wire:key="select-{{ rand() }}">
                                    <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                        dropdownParent: 'body',
                                        allowClear: true,
                                        plugins: ['clear_button'],
                                        onChange: function(e) {
                                            @this.set('country', e ? e : '');
                                        }
                                    });"
                                        wire:model.live="country" id="country">
                                        <option value="">-- Pilih Negara --</option>
                                        @foreach ($getCountrys as $getCountry)
                                            <option value="{{ $getCountry['code'] }}">{{ $getCountry['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Kode Pos <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="postal_code" placeholder="Contoh: 40123"
                                    class="mt-1 form-control" />
                                @error('postal_code')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input type="text" wire:model="address"
                                    placeholder="Contoh: Jl. Gatot Subroto No. 45" class="mt-1 form-control" />
                            </div>
                        </div>
                    </div>

                    {{-- Informasi Tambahan --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Tambahan <span
                                class="text-red-600">*</span></h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="{{ $logo || $logo_old ? null : 'md:col-span-2' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Perusahaan</label>

                                <input type="file" wire:model.live="logo"
                                    class="block text-sm text-gray-500 w-full
                                           file:px-2 file:py-1 file:rounded-md
                                           file:border file:border-gray-300
                                           file:text-xs file:font-medium
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100"
                                    accept="image/*" />
                                <div wire:loading wire:target="logo" class="text-sm text-gray-500 mt-1">
                                    Uploading logo...
                                </div>
                                @error('logo')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @if ($logo)
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                        Logo:</label>
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview Logo"
                                        class="h-100 w-auto rounded border shadow" />
                                </div>
                            @else
                                @if ($logo_old)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                            Logo:</label>
                                        <img src="{{ asset('storage/' . $logo_old) }}" alt="Preview Logo"
                                            class="h-100 w-auto rounded border shadow" />
                                    </div>
                                @endif
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">NPWP / Tax ID</label>
                                <input type="text" wire:model="tax_id" placeholder="Contoh: 01.234.567.8-901.000"
                                    class="mt-1 form-control" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Industri</label>
                                <input type="text" wire:model="industry"
                                    placeholder="Contoh: Teknologi Informasi, Konstruksi, dll."
                                    class="mt-1 form-control" />
                            </div>
                        </div>
                    </div>

                    {{-- Informasi PIC --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi PIC</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama PIC <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="pic_name" placeholder="Contoh: Budi Santoso"
                                    class="mt-1 form-control" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jabatan PIC <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="pic_position"
                                    placeholder="Contoh: Manajer Operasional" class="mt-1 form-control" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telepon PIC <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="pic_phone" placeholder="Contoh: 081234567890"
                                    class="mt-1 form-control" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telepon Email <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="pic_email" placeholder="Contoh: 081234567890"
                                    class="mt-1 form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($currentTab === 'layanan')
                <div
                    class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="w-1 center">No</th>
                                    <th>Fitur</th>
                                    {{-- <th>Fitur Bulanan</th> --}}
                                    <th>Durasi</th>
                                    <th class="text-center">Lifetime</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($companyServices as $index => $companyService)
                                    <tr>
                                        <td class="center">{{ $index + 1 }}</td>
                                        {{-- <td>{{ $companyService->service->name }}</td> --}}
                                        <td>{{ $companyService->serviceMonth->name }}</td>
                                        <td>{{ $companyService->duration_days }}</td>
                                        <td>{{ $companyService->is_lifetime ? 'Ya' : 'Tidak' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="no-data">
                                            Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
