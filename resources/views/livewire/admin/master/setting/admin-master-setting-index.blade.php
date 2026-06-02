<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Pengaturan</h1>
                <p class="text-gray-600">Kelola pengaturan universitas Anda dengan mudah.</p>
            </div>
            @if($currentTab === 'universitas')
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
            @endif
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-4">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                @foreach($tabs as $tab)
                    <button wire:click="setTab('{{ $tab }}')" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors
                                @if($currentTab === $tab)
                                    border-primary text-[color:var(--primary)]
                                @else
                                    border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                                @endif">
                        {{ Str::title(Str::replace('-', ' ', $tab)) }}
                    </button>
                @endforeach
            </nav>
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
                                <input type="email" wire:model="email_company" placeholder="Contoh: info@universitas.com"
                                    class="mt-1 form-control" />
                                @error('email_company')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Telepon <span
                                        class="text-red-600">*</span></label>
                                <input type="text" wire:model="phone" placeholder="Contoh: 02112345678 atau 08123456789"
                                    class="mt-1 form-control" />
                                @error('phone')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" wire:model="website" placeholder="Contoh: https://www.universitas.com"
                                    class="mt-1 form-control" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea wire:model="description"
                                    placeholder="Ceritakan secara singkat tentang universitas Anda..."
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
                                            });" wire:model.live="country" id="country">
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
                                <input type="text" wire:model="address" placeholder="Contoh: Jl. Gatot Subroto No. 45"
                                    class="mt-1 form-control" />
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

                                <input type="file" wire:model.live="logo" class="block text-sm text-gray-500 w-full
                                                   file:px-2 file:py-1 file:rounded-md
                                                   file:border file:border-gray-300
                                                   file:text-xs file:font-medium
                                                   file:bg-blue-50 file:text-blue-700
                                                   hover:file:bg-blue-100" accept="image/*" />
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

                                <input type="file" wire:model.live="logo_potrait" class="block text-sm text-gray-500 w-full
                                                   file:px-2 file:py-1 file:rounded-md
                                                   file:border file:border-gray-300
                                                   file:text-xs file:font-medium
                                                   file:bg-blue-50 file:text-blue-700
                                                   hover:file:bg-blue-100" accept="image/*" />
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
                                        <img src="{{ asset('storage/' . $logo_potrait_old) }}" alt="Preview Logo Potrait"
                                            class="h-100 w-auto rounded border shadow" />
                                    </div>
                                @endif
                            @endif

                            <div class="{{ $background_login || $background_login_old ? null : 'md:col-span-2' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Background Login</label>

                                <input type="file" wire:model.live="background_login" class="block text-sm text-gray-500 w-full
                                                   file:px-2 file:py-1 file:rounded-md
                                                   file:border file:border-gray-300
                                                   file:text-xs file:font-medium
                                                   file:bg-blue-50 file:text-blue-700
                                                   hover:file:bg-blue-100" accept="image/*" />
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
                                        <img src="{{ asset('storage/' . $background_login_old) }}" alt="Preview Background Login"
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
                                    placeholder="Contoh: Teknologi Informasi, Konstruksi, dll." class="mt-1 form-control" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color Primary</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" wire:model.live="color_primary" class="mt-1 form-control" />

                                    <!-- PREVIEW -->
                                    <div class="w-8 h-8 rounded border" style="background-color: {{ $color_primary }};">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Color Secondary</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" wire:model.live="color_secondary" class="mt-1 form-control" />

                                    <!-- PREVIEW -->
                                    <div class="w-8 h-8 rounded border" style="background-color: {{ $color_secondary }};">
                                    </div>
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
                                <input type="text" wire:model="pic_position" placeholder="Contoh: Manajer Operasional"
                                    class="mt-1 form-control" />
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
                    {{-- Safe Exam Browser Configuration --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Safe Exam Browser (SEB)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">SEB Quit Password <span
                                        class="text-xs text-gray-500 mt-1">Password untuk keluar dari Safe Exam Browser
                                        (kosongkan untuk menggunakan default)</span></label>
                                <input type="text" wire:model="quit_password_seb" placeholder="Contoh: ProCBT@Quit2024!"
                                    class="mt-1 form-control" />
                                @error('quit_password_seb')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-end">
                                <a href="{{ route('seb.config.generic') }}" target="_blank"
                                    class="btn btn-info inline-flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Download SEB Config
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Lainya</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Munculkan Nilai Siswa Setelah
                                    Ujian</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="is_mark" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penerimaan Mahasiswa Baru (PMB)</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="is_pmb" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($currentTab === 'seb')
                <div class="space-y-6 mb-2">
                    {{-- General SEB Settings --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Pengaturan Umum SEB</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password Keluar (Quit
                                    Password)</label>
                                <input type="text" wire:model="quit_password_seb" placeholder="Contoh: ProCBT@Quit2024!"
                                    class="mt-1 form-control" />
                                <p class="text-xs text-gray-500 mt-1">Password yang diperlukan untuk keluar dari SEB Client.
                                </p>
                                @error('quit_password_seb') <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Browser Exam Key (Opsional)</label>
                                <input type="text" wire:model="seb_browser_exam_key" placeholder="Key Hashing"
                                    class="mt-1 form-control" />
                                <p class="text-xs text-gray-500 mt-1">Isi jika Anda ingin memvalidasi integritas browser
                                    secara spesifik.</p>
                                @error('seb_browser_exam_key') <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- User Interface Settings --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Antarmuka Pengguna (User Interface)</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tampilkan Taskbar</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_show_taskbar" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tombol Reload</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_show_reload_button" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tampilkan Waktu</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_show_time" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Input Bahasa (Keyboard)</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_show_input_language" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Security & Restrictions --}}
                    <div class="p-6 bg-white shadow rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Keamanan & Restriksi</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Izinkan Keluar (Quit)</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_allow_quit" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Spell Check</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_allow_spell_check" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Private Clipboard</label>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="seb_enable_private_clipboard" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Encryption (AES-256) --}}
                    <div class="p-6 bg-white shadow rounded-lg border-l-4 border-blue-500">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Enkripsi Konfigurasi (AES-256)</h2>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Aktifkan Enkripsi File
                                Konfigurasi</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="seb_use_encryption" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Jika aktif, file .seb yang diunduh akan dienkripsi dan
                                memerlukan password saat dibuka.</p>
                        </div>

                        @if($seb_use_encryption)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password Enkripsi</label>
                                <input type="text" wire:model="seb_encryption_key"
                                    placeholder="Masukkan password enkripsi yang kuat..." class="mt-1 form-control" />
                                <p class="text-xs text-gray-500 mt-1">Password ini HARUS diberitahukan kepada peserta ujian agar
                                    mereka bisa membuka file .seb</p>
                                @error('seb_encryption_key') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>

                    {{-- Download Preview --}}
                    <div class="p-6 bg-indigo-50 shadow rounded-lg flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-indigo-700">Preview Konfigurasi</h3>
                            <p class="text-sm text-indigo-600">Unduh file konfigurasi generic untuk menguji pengaturan saat
                                ini.</p>
                        </div>
                        <a href="{{ route('seb.config.generic') }}" target="_blank" class="btn btn-indigo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Test Config
                        </a>
                    </div>
                </div>

            @elseif ($currentTab === 'layanan')
                <div class="space-y-6 mb-2">
                    <!-- Mobile Card View -->
                    <div class="md:hidden space-y-4">
                        @forelse ($companyServices as $companyService)
                            <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-semibold text-gray-800">{{ $companyService->serviceMonth->name }}</span>
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full {{ $companyService->is_lifetime ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $companyService->is_lifetime ? 'Lifetime' : 'Bulanan' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-600">
                                    <div class="flex justify-between py-1 border-b border-gray-50">
                                        <span>Durasi:</span>
                                        <span class="font-medium">{{ $companyService->duration_days }} Hari</span>
                                    </div>
                                    <div class="flex justify-between py-1 mt-1">
                                        <span>Status:</span>
                                        <span
                                            class="font-medium {{ $companyService->start_date <= now() && ($companyService->is_lifetime || \Carbon\Carbon::parse($companyService->start_date)->addDays($companyService->duration_days) >= now()) ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $companyService->start_date <= now() && ($companyService->is_lifetime || \Carbon\Carbon::parse($companyService->start_date)->addDays($companyService->duration_days) >= now()) ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 bg-white rounded-lg shadow text-center text-gray-500">
                                Tidak ada data layanan
                            </div>
                        @endforelse
                    </div>

                    <!-- Desktop Table View -->
                    <div
                        class="hidden md:block bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="w-1 center">No</th>
                                        <th>Fitur</th>
                                        <th>Durasi</th>
                                        <th class="text-center">Lifetime</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($companyServices as $index => $companyService)
                                        <tr>
                                            <td class="center">{{ $index + 1 }}</td>
                                            <td>{{ $companyService->serviceMonth->name }}</td>
                                            <td>{{ $companyService->duration_days }} Hari</td>
                                            <td class="text-center">
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $companyService->is_lifetime ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $companyService->is_lifetime ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $companyService->start_date <= now() && ($companyService->is_lifetime || \Carbon\Carbon::parse($companyService->start_date)->addDays($companyService->duration_days) >= now()) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $companyService->start_date <= now() && ($companyService->is_lifetime || \Carbon\Carbon::parse($companyService->start_date)->addDays($companyService->duration_days) >= now()) ? 'Aktif' : 'Expired' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="no-data">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @elseif ($currentTab === 'aplikasi')
                <div class="space-y-6 mb-2">

                    {{-- File Upload Section --}}
                    <div class="p-6 bg-white shadow rounded-lg border-l-4 border-purple-500">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Upload Installer Aplikasi</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Windows --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Installer Windows
                                    (.exe/.msi)</label>
                                <input type="file" wire:model.live="app_windows" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-purple-50 file:text-purple-700
                                            hover:file:bg-purple-100" accept=".exe,.msi,.zip" />
                                <div wire:loading wire:target="app_windows" class="text-xs text-gray-500 mt-1">Uploading...
                                </div>
                                @error('app_windows') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                @if($app_windows_old)
                                    <p class="text-xs text-green-600 mt-1">File tersimpan: {{ basename($app_windows_old) }}</p>
                                @endif
                            </div>

                            {{-- Mac --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Installer Mac
                                    (.dmg/.pkg)</label>
                                <input type="file" wire:model.live="app_mac" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-purple-50 file:text-purple-700
                                            hover:file:bg-purple-100" accept=".dmg,.pkg,.zip,.app" />
                                <div wire:loading wire:target="app_mac" class="text-xs text-gray-500 mt-1">Uploading...
                                </div>
                                @error('app_mac') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                @if($app_mac_old)
                                    <p class="text-xs text-green-600 mt-1">File tersimpan: {{ basename($app_mac_old) }}</p>
                                @endif
                            </div>

                            {{-- Android --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Installer Android (.apk)</label>
                                <input type="file" wire:model.live="app_android" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-purple-50 file:text-purple-700
                                            hover:file:bg-purple-100" accept=".apk" />
                                <div wire:loading wire:target="app_android" class="text-xs text-gray-500 mt-1">Uploading...
                                </div>
                                @error('app_android') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                @if($app_android_old)
                                    <p class="text-xs text-green-600 mt-1">File tersimpan: {{ basename($app_android_old) }}</p>
                                @endif
                            </div>

                            {{-- iOS --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Installer iOS (.ipa /
                                    Config)</label>
                                <input type="file" wire:model.live="app_ios" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-purple-50 file:text-purple-700
                                            hover:file:bg-purple-100" accept=".ipa,.zip,.mobileconfig" />
                                <div wire:loading wire:target="app_ios" class="text-xs text-gray-500 mt-1">Uploading...
                                </div>
                                @error('app_ios') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                @if($app_ios_old)
                                    <p class="text-xs text-green-600 mt-1">File tersimpan: {{ basename($app_ios_old) }}</p>
                                @endif
                            </div>

                        </div>
                    </div>


                    <!-- Desktop App Section -->
                    <div class="p-6 bg-white shadow rounded-lg border-l-4 border-blue-600">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Install Aplikasi Desktop</h2>
                                <p class="text-sm text-gray-600">Unduh aplikasi pro-cbt versi desktop untuk pengalaman ujian
                                    yang lebih stabil dan aman.</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <!-- Windows Download -->
                            @if($app_windows_old)
                                <a href="{{ Storage::url($app_windows_old) }}" download
                                    class="btn btn-primary flex items-center gap-2 transition transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Download Windows</span>
                                </a>
                            @else
                                <button disabled
                                    class="btn bg-gray-300 text-gray-500 cursor-not-allowed flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Download Windows (Belum Ada)</span>
                                </button>
                            @endif

                            <!-- Mac Download -->
                            @if($app_mac_old)
                                <a href="{{ Storage::url($app_mac_old) }}" download
                                    class="btn btn-secondary flex items-center gap-2 transition transform hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Download Mac</span>
                                </a>
                            @else
                                <button disabled
                                    class="btn bg-gray-300 text-gray-500 cursor-not-allowed flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    <span>Download Mac (Belum Ada)</span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Mobile App Section -->
                    <div class="p-6 bg-white shadow rounded-lg border-l-4 border-green-500">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="p-3 bg-green-50 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800">Install Aplikasi Mobile</h2>
                                <p class="text-sm text-gray-600">Akses ujian melalui perangkat seluler Anda dengan mudah.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <!-- Android Download -->
                            @if($app_android_old)
                                <a href="{{ Storage::url($app_android_old) }}" download
                                    class="btn bg-green-600 hover:bg-green-700 text-white flex items-center gap-2 transition transform hover:scale-105 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M16.6026 12.0253L13.7118 16.9946L16.2737 21.432C16.5959 20.9126 17.0628 20.4862 17.6186 20.1983C18.1744 19.9103 18.7951 19.7728 19.4211 19.8021C20.0471 19.8315 20.6517 20.0264 21.1628 20.3638L21.8491 20.8166C21.7259 20.5746 21.6575 20.3065 21.6526 20.0353V3.96464C21.6575 3.69345 21.7259 3.42531 21.8491 3.18337L21.1628 3.63618C20.6517 3.97354 20.0471 4.16843 19.4211 4.19782C18.7951 4.2272 18.1744 4.08972 17.6186 3.80173C17.0628 3.51374 16.5959 3.08731 16.2737 2.56793L13.7118 7.00532L16.6026 11.9746V12.0253Z" />
                                        <path
                                            d="M7.39737 12.0253L10.2882 16.9946L7.72632 21.432C7.40411 20.9126 6.93722 20.4862 6.3814 20.1983C5.82559 19.9103 5.20489 19.7728 4.57889 19.8021C3.9529 19.8315 3.34832 20.0264 2.83721 20.3638L2.15091 20.8166C2.27415 20.5746 2.34251 20.3065 2.34743 20.0353V3.96464C2.34251 3.69345 2.27415 3.42531 2.15091 3.18337L2.83721 3.63618C3.34832 3.97354 3.9529 4.16843 4.57889 4.19782C5.20489 4.2272 5.82559 4.08972 6.3814 3.80173C6.93722 3.51374 7.40411 3.08731 7.72632 2.56793L10.2882 7.00532L7.39737 11.9746V12.0253Z" />
                                        <path
                                            d="M12 1.5C9.69741 1.5 7.49526 2.45134 5.86718 4.14446C4.2391 5.83758 3.32432 8.13369 3.32432 10.5273H20.6757C20.6757 8.13369 19.7609 5.83758 18.1328 4.14446C16.5047 2.45134 14.3026 1.5 12 1.5Z" />
                                    </svg>
                                    <span>Download Android</span>
                                </a>
                                {{-- Fallback to PWA Instruction if user wants --}}
                                <div class="mt-2 text-xs text-gray-500">
                                    Atau <a href="#"
                                        onclick="alert('Silahkan buka website ini di Chrome Android, lalu pilih menu dan \'Tambahkan ke Layar Utama\' (Add to Home Screen) untuk menginstall aplikasi.')"
                                        class="text-blue-600 hover:underline">Install PWA (Web App)</a>
                                </div>
                            @else
                                <button
                                    onclick="alert('Silahkan buka website ini di Chrome Android, lalu pilih menu dan \'Tambahkan ke Layar Utama\' (Add to Home Screen) untuk menginstall aplikasi.')"
                                    class="btn bg-green-600 hover:bg-green-700 text-white flex items-center gap-2 transition transform hover:scale-105 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M16.6026 12.0253L13.7118 16.9946L16.2737 21.432C16.5959 20.9126 17.0628 20.4862 17.6186 20.1983C18.1744 19.9103 18.7951 19.7728 19.4211 19.8021C20.0471 19.8315 20.6517 20.0264 21.1628 20.3638L21.8491 20.8166C21.7259 20.5746 21.6575 20.3065 21.6526 20.0353V3.96464C21.6575 3.69345 21.7259 3.42531 21.8491 3.18337L21.1628 3.63618C20.6517 3.97354 20.0471 4.16843 19.4211 4.19782C18.7951 4.2272 18.1744 4.08972 17.6186 3.80173C17.0628 3.51374 16.5959 3.08731 16.2737 2.56793L13.7118 7.00532L16.6026 11.9746V12.0253Z" />
                                        <path
                                            d="M7.39737 12.0253L10.2882 16.9946L7.72632 21.432C7.40411 20.9126 6.93722 20.4862 6.3814 20.1983C5.82559 19.9103 5.20489 19.7728 4.57889 19.8021C3.9529 19.8315 3.34832 20.0264 2.83721 20.3638L2.15091 20.8166C2.27415 20.5746 2.34251 20.3065 2.34743 20.0353V3.96464C2.34251 3.69345 2.27415 3.42531 2.15091 3.18337L2.83721 3.63618C3.34832 3.97354 3.9529 4.16843 4.57889 4.19782C5.20489 4.2272 5.82559 4.08972 6.3814 3.80173C6.93722 3.51374 7.40411 3.08731 7.72632 2.56793L10.2882 7.00532L7.39737 11.9746V12.0253Z" />
                                        <path
                                            d="M12 1.5C9.69741 1.5 7.49526 2.45134 5.86718 4.14446C4.2391 5.83758 3.32432 8.13369 3.32432 10.5273H20.6757C20.6757 8.13369 19.7609 5.83758 18.1328 4.14446C16.5047 2.45134 14.3026 1.5 12 1.5Z" />
                                    </svg>
                                    <span>Install Android (PWA)</span>
                                </button>
                            @endif

                            <!-- iOS Download -->
                            @if($app_ios_old)
                                <a href="{{ Storage::url($app_ios_old) }}" download
                                    class="btn bg-gray-800 hover:bg-gray-900 text-white flex items-center gap-2 transition transform hover:scale-105 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.79-1.31.02-2.3-1.23-3.14-2.47-1.72-2.5-3.03-7.07-1.26-10.13 0.88-1.5 2.45-2.47 4.16-2.5 1.3 0 2.52.88 3.3.88 0.77 0 2.22-1.09 3.73-0.93 0.64.03 2.43.26 3.58 1.94-0.09.06-2.14 1.25-2.12 3.72 0.03 2.96 2.59 3.96 2.65 4-0.02.06-0.41 1.41-1.37 2.82h0ZM13 3.5c.67-.82 1.13-1.95 1.01-3.09-0.97.04-2.14.65-2.83 1.46-.61.7-1.12 1.83-0.99 3.05 1.08.08 2.18-.59 2.81-1.42h0Z" />
                                    </svg>
                                    <span>Download iOS Config/App</span>
                                </a>
                            @else
                                <button
                                    onclick="alert('Buka di Safari, ketuk tombol Share (Bagikan), lalu pilih \'Add to Home Screen\' (Tambahkan ke Layar Utama).')"
                                    class="btn bg-gray-800 hover:bg-gray-900 text-white flex items-center gap-2 transition transform hover:scale-105 shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path
                                            d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.79-1.31.02-2.3-1.23-3.14-2.47-1.72-2.5-3.03-7.07-1.26-10.13 0.88-1.5 2.45-2.47 4.16-2.5 1.3 0 2.52.88 3.3.88 0.77 0 2.22-1.09 3.73-0.93 0.64.03 2.43.26 3.58 1.94-0.09.06-2.14 1.25-2.12 3.72 0.03 2.96 2.59 3.96 2.65 4-0.02.06-0.41 1.41-1.37 2.82h0ZM13 3.5c.67-.82 1.13-1.95 1.01-3.09-0.97.04-2.14.65-2.83 1.46-.61.7-1.12 1.83-0.99 3.05 1.08.08 2.18-.59 2.81-1.42h0Z" />
                                    </svg>
                                    <span>Install iOS (PWA)</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>