<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Pengaturan</h1>
                <p class="text-gray-600">Kelola pengaturan universitas Anda dengan mudah.</p>
            </div>
            <div>
                <button wire:click="save()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
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
        <div class="mt-4">
            @if ($currentTab === 'universitas')
                <div class="space-y-6 mb-2">
                    {{-- Informasi Universitas --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Universitas</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Code Universitas <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="code_name" placeholder="Contoh: 9640007"
                                    class="mt-1 form-control" />
                                @error('code_name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Universitas <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="name" placeholder="Contoh: PT Maju Jaya"
                                    class="mt-1 form-control" />
                                @error('name')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Code Wilayah <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="code_region" placeholder="Contoh: 64"
                                    class="mt-1 form-control" />
                                @error('code_region')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Wilayah <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="region" placeholder="Contoh: Jawa Timur"
                                    class="mt-1 form-control" />
                                @error('region')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Resmi</label>
                                <input type="email" wire:model="email_company"
                                    placeholder="Contoh: info@universitas.com" class="mt-1 form-control" />
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
                                    placeholder="Contoh: https://www.universitas.com" class="mt-1 form-control" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea wire:model="description" placeholder="Ceritakan secara singkat tentang universitas Anda..."
                                    class="mt-1 form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Universitas --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Alamat Universitas</h2>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Universitas</label>

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
                            @if (is_object($logo))
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

                            <div class="{{ $logo_potrait || $logo_potrait_old ? null : 'md:col-span-2' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Potrait
                                    Universitas</label>

                                <input type="file" wire:model.live="logo_potrait"
                                    class="block text-sm text-gray-500 w-full
                                           file:px-2 file:py-1 file:rounded-md
                                           file:border file:border-gray-300
                                           file:text-xs file:font-medium
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100"
                                    accept="image/*" />
                                <div wire:loading wire:target="logo_potrait" class="text-sm text-gray-500 mt-1">
                                    Uploading Logo Potrait...
                                </div>
                                @error('logo_potrait')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @if (is_object($logo_potrait))
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                        Logo Potrait:</label>
                                    <img src="{{ $logo_potrait->temporaryUrl() }}" alt="Preview Logo Potrait"
                                        class="h-100 w-auto rounded border shadow" />
                                </div>
                            @else
                                @if ($logo_potrait_old)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                            Logo Potrait:</label>
                                        <img src="{{ asset('storage/' . $logo_potrait_old) }}"
                                            alt="Preview Logo Potrait" class="h-100 w-auto rounded border shadow" />
                                    </div>
                                @endif
                            @endif

                            <div class="{{ $background_login || $background_login_old ? null : 'md:col-span-2' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Background Login</label>

                                <input type="file" wire:model.live="background_login"
                                    class="block text-sm text-gray-500 w-full
                                           file:px-2 file:py-1 file:rounded-md
                                           file:border file:border-gray-300
                                           file:text-xs file:font-medium
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100"
                                    accept="image/*" />
                                <div wire:loading wire:target="background_login" class="text-sm text-gray-500 mt-1">
                                    Uploading Background Login...
                                </div>
                                @error('background_login')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            @if (is_object($background_login))
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                        Background Login:</label>
                                    <img src="{{ $background_login->temporaryUrl() }}" alt="Preview Background Login"
                                        class="h-100 w-auto rounded border shadow" />
                                </div>
                            @else
                                @if ($background_login_old)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                            Background Login:</label>
                                        <img src="{{ asset('storage/' . $background_login_old) }}"
                                            alt="Preview Background Login" class="h-100 w-auto rounded border shadow" />
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color Primary</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" wire:model.live="color_primary" class="mt-1 form-control" />

                                    <!-- PREVIEW -->
                                    <div class="w-8 h-8 rounded border"
                                        style="background-color: {{ $color_primary }};"></div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color Secondary</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" wire:model.live="color_secondary" class="mt-1 form-control" />

                                    <!-- PREVIEW -->
                                    <div class="w-8 h-8 rounded border"
                                        style="background-color: {{ $color_secondary }};"></div>
                                </div>
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
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Lainya</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sembunyikan Nilai</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="is_mark" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
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
