<div class="min-h-screen flex items-center justify-center p-4">
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl w-full max-w-lg p-6 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC]"></div>

        <!-- Logo & Title -->
        <div class="flex flex-col items-center mb-6">
            <img src="{{ asset('asset/img/logo.png') }}" alt="PRO CBT Logo" class="h-12 drop-shadow-md">
            <h1 class="text-2xl font-bold text-[#1E3A8A]">Buat Akun Anda</h1>
            <p class="text-gray-600 text-sm">Daftar untuk mengakses dashboard Anda</p>
        </div>

        <!-- Register Form -->
        <form wire:submit.prevent="register" class="space-y-6" x-data="{ step: @entangle('step') }">
            <!-- Top Thin Animated Bar -->
            <div
                class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC] animate-pulse rounded-full">
            </div>

            <!-- Multi-Step Progress Bar -->
            <!-- Full-width Progress Bar -->
            <div class="w-full mb-8">
                <!-- Step Labels -->
                <div class="flex justify-between text-sm text-gray-600 mb-2 font-medium">
                    <span :class="step >= 1 ? 'text-[#1E3A8A] font-semibold' : ''">Step 1</span>
                    <span :class="step >= 2 ? 'text-[#1E3A8A] font-semibold' : ''">Step 2</span>
                    <span :class="step >= 3 ? 'text-[#1E3A8A] font-semibold' : ''">Step 3</span>
                    <span :class="step >= 4 ? 'text-[#1E3A8A] font-semibold' : ''">Step 4</span>
                    <span :class="step >= 5 ? 'text-[#1E3A8A] font-semibold' : ''">Step 5</span>
                </div>

                <!-- Progress Bar Track -->
                <div class="w-full h-3 bg-gray-300 rounded-full relative overflow-hidden">
                    <!-- Animated Progress Fill -->
                    <div class="h-full bg-gradient-to-r from-[#1E3A8A] to-[#3B82F6] transition-all duration-500 rounded-full"
                        :style="'width: ' + ((step - 1) / 4 * 100) + '%'">
                    </div>
                </div>
            </div>

            <!-- STEP 1: Info Klinik -->
            <div x-show="step === 1" x-transition>
                <div class="grid grid-cols-1 gap-4">
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="name"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Nama Klinik">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-clinic-medical text-lg"></i>
                            </div>
                        </div>
                        @error('name')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="email_company"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Email Klinik">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                        </div>
                        @error('email_company')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Telepon Klinik -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="number" wire:model.defer="phone"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Telepon Klinik">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                        </div>
                        @error('phone')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <div class="relative">
                            <textarea wire:model.defer="address" rows="3"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50 resize-none"
                                placeholder="Alamat Lengkap"></textarea>
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-map-marker-alt text-lg"></i>
                            </div>
                        </div>
                        @error('address')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Website (opsional) -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="website"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Website (opsional)">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-globe text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button type="button" wire:click="nextStep" class="btn-primary cursor-pointer">Lanjut</button>
                </div>
            </div>

            <!-- STEP 2: Alamat Klinik -->
            <div x-show="step === 2" x-transition>
                <div class="grid grid-cols-1 gap-4">

                    <!-- Input Provinsi -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="province"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Provinsi">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-flag text-lg"></i>
                            </div>
                        </div>
                        @error('province')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Kota -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="city"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Kota">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-city text-lg"></i>
                            </div>
                        </div>
                        @error('city')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Kecamatan -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="district"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Kecamatan">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-building text-lg"></i>
                            </div>
                        </div>
                        @error('district')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Kelurahan -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="sub_district"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Kelurahan">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-home text-lg"></i>
                            </div>
                        </div>
                        @error('sub_district')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Input Kode Pos -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="postal_code"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Kode Pos">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-mail-bulk text-lg"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Input Negara -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="country"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Negara">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-globe-asia text-lg"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="prevStep" class="btn-secondary">Kembali</button>
                    <button type="button" wire:click="nextStep" class="btn-primary cursor-pointer">Lanjut</button>
                </div>
            </div>

            <!-- STEP 3: Info Tambahan -->
            <div x-show="step === 3" x-transition>
                <div class="grid grid-cols-1 gap-4">
                    <!-- Upload Logo -->
                    <div class="group">
                        <div x-data="{ logoName: '', logoPreview: '' }" class="relative">
                            <input autocomplete="off" type="file" wire:model="logo" class="hidden"
                                id="upload-logo" accept="image/*">

                            <label for="upload-logo"
                                class="inline-flex items-center px-4 py-2 bg-[#1E3A8A] text-white rounded-xl cursor-pointer hover:bg-[#1E3A8A] transition-all">
                                Upload Logo
                                <!-- Optional: Icon next to text -->
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v8m0-8l-3 3m3-3l3 3M12 4v4" />
                                </svg>
                            </label>
                        </div>

                        @error('logo')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror

                        @if ($logo)
                            <div class="mt-2">
                                <img src="{{ $logo->temporaryUrl() }}" class="h-20 rounded shadow mb-1">
                                <button type="button" wire:click="removeLogo"
                                    class="text-red-600 hover:underline text-sm">Hapus Logo</button>
                            </div>
                        @endif
                    </div>

                    <!-- Input NPWP / Tax ID -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="tax_id"
                                class="input-style w-full px-4 py-2.5 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all"
                                placeholder="NPWP / Tax ID">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <!-- Icon document -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6M12 4v4m-4 4h8M4 6a2 2 0 012-2h8l4 4v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Input Industri -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="industry"
                                class="input-style w-full px-4 py-2.5 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all"
                                placeholder="Industri">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <!-- Icon briefcase -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 9V7a2 2 0 012-2h0a2 2 0 012 2v2m-8 4h12m-12 0v6a2 2 0 002 2h8a2 2 0 002-2v-6M4 13h16" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Textarea Deskripsi Klinik -->
                    <textarea wire:model.defer="description" rows="3"
                        class="input-style w-full px-4 py-2.5 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all resize-none"
                        placeholder="Deskripsi Klinik"></textarea>
                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="prevStep" class="btn-secondary">Kembali</button>
                    <button type="button" wire:click="nextStep" class="btn-primary cursor-pointer">Lanjut</button>
                </div>
            </div>

            <!-- STEP 4: Akun Login -->
            <div x-show="step === 4" x-transition>
                <div class="grid grid-cols-1 gap-4">
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="pic_name"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Nama PIC">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-user text-lg"></i>
                            </div>
                        </div>
                        @error('pic_name')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="pic_position"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Jabatan PIC">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-user-tie text-lg"></i>
                            </div>
                        </div>
                        @error('pic_position')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="pic_email"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Email PIC">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-envelope text-lg"></i>
                            </div>
                        </div>
                        @error('pic_email')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="tel" wire:model.defer="pic_phone"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Telepon PIC" pattern="[0-9]{10,15}"
                                title="Masukkan nomor telepon yang valid (10-15 digit)">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <i class="fas fa-phone text-lg"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Masukkan nomor HP/WA yang aktif dan bisa dihubungi
                            (contoh: 081234567890)</p>
                        @error('pic_phone')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="prevStep" class="btn-secondary">Kembali</button>
                    <button type="button" wire:click="nextStep" class="btn-primary cursor-pointer">Lanjut</button>
                </div>
            </div>

            <!-- STEP 5: Akun Login -->
            <div x-show="step === 5" x-transition>
                <div class="grid grid-cols-1 gap-4">
                    {{-- <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="email" wire:model.defer="email" class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all" placeholder="Email Login">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <!-- Icon mail -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4-4-4 4m0 0l4 4 4-4m-4 4V8" />
                                </svg>
                            </div>
                        </div>
                        @error('email')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div> --}}

                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" wire:model.defer="username"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all"
                                placeholder="Username">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <!-- Icon user -->
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A9 9 0 1118.88 6.196 9 9 0 015.12 17.804zM12 12a3 3 0 100-6 3 3 0 000 6z" />
                                </svg>
                            </div>
                        </div>
                        @error('username')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div x-data="{ show: false, showConfirm: false }" class="space-y-4">
                        <!-- Password -->
                        <!-- Password -->
                        <div class="space-y-1">
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" wire:model.defer="password"
                                    class="input-style pr-10" placeholder="Password (min. 8 karakter)">
                                <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 transform text-gray-500 focus:outline-none">
                                    <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="!show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.985 9.985 0 012.241-3.715M6.633 6.633A9.978 9.978 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.978 9.978 0 01-1.348 2.708M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Password Confirmation -->
                        <!-- Password Confirmation -->
                        <div class="space-y-1">
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'"
                                    wire:model.defer="password_confirmation" class="input-style pr-10"
                                    placeholder="Konfirmasi Password">

                                <button type="button" @click="showConfirm = !showConfirm"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 focus:outline-none">
                                    <svg x-show="showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="!showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.985 9.985 0 012.241-3.715M6.633 6.633A9.978 9.978 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.978 9.978 0 01-1.348 2.708M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>

                            @error('password_confirmation')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                </div>

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="prevStep" class="btn-secondary">Kembali</button>
                    <button type="submit" wire:loading.attr="disabled" class="btn-submit">
                        <span wire:loading.remove>Daftar</span>
                        <span wire:loading>
                            <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>

        </form>

        <!-- Link ke Login -->
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-[#1E3A8A] hover:underline font-semibold">Login di
                    sini</a>
            </p>
        </div>


        <!-- Footer -->
        <div class="mt-6 text-center text-xs text-gray-500">
            <p>© 2024 PRO CBT. All rights reserved.</p>
            <p class="mt-0.5">Secure registration • Admin Portal</p>
        </div>
    </div>
</div>
