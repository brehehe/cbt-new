<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name') }} - Register</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background: linear-gradient(135deg, #C3D4EC 0%, #E8F0F9 100%);
            }
        </style>
    </head>

    <body class="min-h-screen">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div
                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl w-full max-w-md p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC]"></div>

                <!-- Logo & Title -->
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ Vite::asset('resources/img/logo_m.png') }}" alt="PRO CBT Logo"
                        class="h-12 drop-shadow-md mb-4">
                    <h1
                        class="text-2xl font-bold {{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                        Create Your Account</h1>
                    <p class="text-gray-600 text-sm">Register to access your dashboard</p>
                </div>

                <!-- Register Form -->
                <form wire:submit.prevent="register" class="space-y-6" x-data="{ step: @entangle('step') }">

                    <!-- STEP 1 -->
                    <div x-show="step === 1" x-transition>
                        <div class="grid grid-cols-1 gap-4">
                            <!-- Nama Perusahaan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="name"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A]"
                                    placeholder="Contoh: Klinik Sehat Sentosa" required>
                                @error('name')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Resmi <span
                                        class="text-red-500">*</span></label>
                                <input type="email" wire:model.defer="email"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A]"
                                    placeholder="contoh: info@klinik.com" required>
                                @error('email')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Telepon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="phone"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A]"
                                    placeholder="contoh: 08123456789" required>
                                @error('phone')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" wire:click="nextStep"
                                class="bg-[#1E3A8A] text-white px-4 py-2.5 rounded-xl shadow hover:shadow-md transition">Lanjut</button>
                        </div>
                    </div>

                    <!-- STEP 2 -->
                    <div x-show="step === 2" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Website -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                <input type="text" wire:model.defer="website"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A]"
                                    placeholder="https://klinik.com">
                            </div>

                            <!-- Alamat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="address"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50 focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A]"
                                    placeholder="Jl. Kesehatan No. 10" required>
                                @error('address')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Kota -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="city"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="Jakarta" required>
                                @error('city')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Provinsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model.defer="state"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="DKI Jakarta" required>
                                @error('state')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" wire:click="prevStep"
                                class="bg-gray-300 text-gray-700 px-4 py-2.5 rounded-xl">Kembali</button>
                            <button type="button" wire:click="nextStep"
                                class="bg-[#1E3A8A] text-white px-4 py-2.5 rounded-xl">Lanjut</button>
                        </div>
                    </div>

                    <!-- STEP 3 -->
                    <div x-show="step === 3" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                <input type="text" wire:model.defer="postal_code"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="12345" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                                <input type="text" wire:model.defer="country"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="Indonesia" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Logo Perusahaan <span
                                        class="text-red-500">*</span></label>
                                <input type="file" wire:model="logo"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50" required>
                                @error('logo')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror

                                @if ($logo)
                                    <div class="mt-2">
                                        <img src="{{ $logo->temporaryUrl() }}" class="h-20 rounded shadow">
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NPWP / Tax ID</label>
                                <input type="text" wire:model.defer="tax_id"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="01.234.567.8-901.000" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. Registrasi</label>
                                <input type="text" wire:model.defer="registration_number"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="987654321" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Industri</label>
                                <input type="text" wire:model.defer="industry"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="Kesehatan / Medis" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi
                                    Perusahaan</label>
                                <textarea wire:model.defer="description" rows="3"
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl bg-white/50"
                                    placeholder="Deskripsikan layanan atau visi klinik Anda..." required></textarea>
                            </div>
                        </div>

                        <div class="flex justify-between mt-6">
                            <button type="button" wire:click="prevStep"
                                class="bg-gray-300 text-gray-700 px-4 py-2.5 rounded-xl">Kembali</button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="bg-green-600 text-white px-4 py-2.5 rounded-xl relative">
                                <span wire:loading.remove>Daftar</span>
                                <span wire:loading>
                                    <svg class="animate-spin h-5 w-5 text-white mx-auto"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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


                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>© 2025 {{ config('app.name') }}. All rights reserved.</p>
                    <p class="mt-0.5">Secure registration • Admin Portal</p>
                </div>
            </div>
        </div>
    </body>

</html>
