<div>
    @if (in_array(config('app.name_slug'), ['ikmb', 'medical_school']))
        <div class="h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900-2">Institut Kesehatan Mitra Bunda</h1>
                    <h2 class="text-xl text-gray-600 mt-2">Daftar Peserta CBT</h2>
                </div>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
                <div
                    class="bg-white py-8 px-6 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-200 backdrop-blur-sm">
                    <!-- Enhanced Step Indicator -->
                    <div class="mb-10">
                        <!-- Progress Bar Background -->
                        <div class="relative mb-8">
                            <div
                                class="absolute top-1/2 left-0 w-full h-2 bg-gray-200 rounded-full transform -translate-y-1/2">
                            </div>
                            <div id="overall-progress"
                                class="absolute top-1/2 left-0 h-2 bg-green-500 rounded-full transform -translate-y-1/2 transition-all duration-700 ease-out shadow-lg"
                                style="width: {{ $progress_bar }}%">
                            </div>
                        </div>

                        <!-- Step Indicators -->
                        <div class="flex items-center justify-between relative px-1 sm:px-2">
                            <div class="flex flex-col items-center group cursor-pointer min-w-0 flex-1">
                                <div id="step1-indicator"
                                    class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-green-700 text-white rounded-full text-sm font-bold shadow-xl transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-2xl border-2 sm:border-4 border-white">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </div>
                                <span id="step1-text"
                                    class="mt-2 sm:mt-3 text-xs sm:text-sm font-bold text-green-600 text-center leading-tight px-1">Data
                                    Diri</span>
                            </div>

                            <div class="flex flex-col items-center group cursor-pointer min-w-0 flex-1">
                                <div id="step1-indicator"
                                    class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 {{ $step >= 2 ? 'bg-gradient-to-br from-green-500 to-green-700 text-white' : 'bg-gray-300 text-gray-500' }} rounded-full text-sm font-bold shadow-xl transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-2xl border-2 sm:border-4 border-white">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                                <span id="step1-text"
                                    class="mt-2 sm:mt-3 text-xs sm:text-sm font-bold {{ $step >= 2 ? 'text-green-600' : 'text-gray-500' }} text-center leading-tight px-1">Data
                                    Program Studi</span>
                            </div>

                            <div class="flex flex-col items-center group cursor-pointer min-w-0 flex-1">
                                <div id="step2-indicator"
                                    class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 {{ $step >= 3 ? 'bg-gradient-to-br from-green-500 to-green-700 text-white' : 'bg-gray-300 text-gray-500' }} rounded-full text-sm font-bold shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl border-2 sm:border-4 border-white">
                                    {{-- <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg> --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-files w-4 h-4 sm:w-5 sm:h-5">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M15 3v4a1 1 0 0 0 1 1h4" />
                                        <path
                                            d="M18 17h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h4l5 5v7a2 2 0 0 1 -2 2z" />
                                        <path d="M16 17v2a2 2 0 0 1 -2 2h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                                    </svg>
                                </div>
                                <span id="step2-text"
                                    class="mt-2 sm:mt-3 text-xs sm:text-sm font-semibold {{ $step >= 3 ? 'text-green-600' : 'text-gray-500' }} text-center leading-tight px-1">Berkas</span>
                            </div>

                            {{-- <div class="flex flex-col items-center group cursor-pointer min-w-0 flex-1">
                                <div id="step3-indicator"
                                    class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 {{ $step >= 4 ? 'bg-gradient-to-br from-blue-500 to-blue-700 text-white' : 'bg-gray-300 text-gray-500' }} rounded-full text-sm font-bold shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl border-2 sm:border-4 border-white">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <span id="step3-text"
                                    class="mt-2 sm:mt-3 text-xs sm:text-sm font-semibold {{ $step >= 4 ? 'text-blue-600' : 'text-gray-500' }} text-center leading-tight px-1">Jadwal</span>
                            </div> --}}

                            {{-- <div class="flex flex-col items-center group cursor-pointer min-w-0 flex-1">
                                <div id="step4-indicator"
                                    class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 {{ $step >= 5 ? 'bg-gradient-to-br from-blue-500 to-blue-700 text-white' : 'bg-gray-300 text-gray-500' }} rounded-full text-sm font-bold shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:shadow-xl border-2 sm:border-4 border-white">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                </div>
                                <span id="step4-text"
                                    class="mt-2 sm:mt-3 text-xs sm:text-sm font-semibold {{ $step >= 5 ? 'text-blue-600' : 'text-gray-500' }} text-center leading-tight px-1">Data
                                    Diri</span>
                            </div> --}}
                        </div>
                    </div>

                    <form id="registration-form">
                        @csrf
                        <input type="hidden" name="doctor_id" id="selected_doctor_id">
                        <input type="hidden" name="visit_time" id="selected_visit_time">

                        @if ($step === 1)
                            <!-- Step 1: Pilih Cabang -->
                            <div id="step2" class="step animate-fade-in">
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900-2">Informasi Data Diri</h3>
                                    <p class="text-gray-600">Silakan isi data diri</p>
                                </div>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="name"
                                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            Nama Lengkap <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="name" name="name"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                            placeholder="Masukkan nama lengkap" wire:model.defer="name">
                                        @error('name')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="birth_place"
                                                class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-cake w-5 h-5 mr-2 text-green-400">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M3 20h18v-8a3 3 0 0 0 -3 -3h-12a3 3 0 0 0 -3 3v8z" />
                                                    <path
                                                        d="M3 14.803c.312 .135 .654 .204 1 .197a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1a2.4 2.4 0 0 0 2 -1a2.4 2.4 0 0 1 2 -1a2.4 2.4 0 0 1 2 1a2.4 2.4 0 0 0 2 1c.35 .007 .692 -.062 1 -.197" />
                                                    <path d="M12 4l1.465 1.638a2 2 0 1 1 -3.015 .099l1.55 -1.737z" />
                                                </svg>
                                                Tempat, Tanggal Lahir <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="birth_place" name="birth_place"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                                placeholder="Masukkan tempat lahir" wire:model.defer="birth_place">
                                            @error('birth_place')
                                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="birth_date"
                                                class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                &nbsp;
                                            </label>
                                            <input type="date" id="birth_date" name="birth_date"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                                placeholder="" wire:model.defer="birth_date">
                                            @error('birth_date')
                                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label for="email"
                                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-tabler icons-tabler-outline icon-tabler-mail w-5 h-5 mr-2 text-green-400">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" />
                                                <path d="M3 7l9 6l9 -6" />
                                            </svg>
                                            Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" id="email" name="email"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                            placeholder="Masukkan email aktif" wire:model.defer="email">
                                        @error('email')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="password"
                                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="ti ti-key text-xl mr-2 text-green-400"></i>
                                            Kata Sandi <span class="text-red-500">*</span>
                                        </label>
                                        <x-ts-password placeholder='******' wire:model.defer="password" />
                                    </div>
                                </div>
                                <button type="button" id="next-step1" wire:click="nextStep()"
                                    class="mt-8 w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                    <span class="flex items-center justify-center">
                                        Lanjutkan
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        @elseif ($step === 2)
                            <!-- Step 1: Pilih Poli -->
                            <div id="step2" class="step animate-fade-in">
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900-2">Data Program Studi</h3>
                                    <p class="text-gray-600">Silakan isi program studi yang sedang berlangsung</p>
                                </div>
                                <div class="grid grid-cols-1 gap-4 mb-8">
                                    <div>
                                        <label for="nim"
                                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="ti ti-id-badge-2 text-xl mr-2 text-green-400"></i>
                                            NIM <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="nim" name="nim"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                            placeholder="Masukkan NIM" wire:model.defer="nim">
                                        @error('nim')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="program_study"
                                                class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="ti ti-school mr-2 text-green-400 text-xl"></i>
                                                Program Studi/Jurusan <span class="text-red-500">*</span>
                                            </label>
                                            <div wire:key="select-{{ rand() }}">
                                                <select
                                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                                    wire:model.lazy="program_study" id="program_study">
                                                    <option value="">-- Pilih program studi --</option>
                                                    @foreach ($program_studies as $value)
                                                        <option value="{{ $value }}">{{ $value }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('program_study')
                                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label for="semester"
                                                class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="ti ti-align-left-2 mr-2 text-green-400 text-xl"></i>
                                                Semester <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" id="semester" name="semester"
                                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                                placeholder="Masukkan semester" wire:model.defer="semester">
                                            @error('semester')
                                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-4">
                                    <button type="button" id="prev-step2" wire:click="prevStep()"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200-600 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                            </svg>
                                            Kembali
                                        </span>
                                    </button>
                                    <button type="button" id="next-step2" wire:click="nextStep()"
                                        class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                        <span class="flex items-center justify-center">
                                            Lanjutkan
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @elseif ($step === 3)
                            <!-- Step 2: Pilih Dokter -->
                            <div id="step3" class="step animate-fade-in">
                                <div class="text-center mb-6">
                                    <h3 class="text-2xl font-bold text-gray-900-2">Berkas Pendukung</h3>
                                    <p class="text-gray-600">Silahkan lengkapi berkas pendukung berikut</p>
                                </div>
                                <div id="doctor-list" class="space-y-4 mb-8">
                                    <div>
                                        <label for="krs_file"
                                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="ti ti-file mr-2 text-green-400 text-xl"></i>
                                            Upload KRS <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" id="krs_file" name="krs_file"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                            placeholder="" wire:model.defer="krs_file">
                                        @error('krs_file')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="payment_registration"
                                            class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                            <i class="ti ti-cash-banknote mr-2 text-green-400 text-xl"></i>
                                            Upload Berbayaran <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" id="payment_registration" name="payment_registration"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500-all duration-300 hover:border-green-300"
                                            placeholder="Masukkan nama lengkap"
                                            wire:model.defer="payment_registration">
                                        @error('payment_registration')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="flex space-x-4">
                                    <button type="button" id="prev-step2" wire:click="prevStep()"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200-600 text-gray-700 py-3 px-6 rounded-xl font-semibold transition-all duration-300 transform hover:scale-[1.02] shadow-md hover:shadow-lg">
                                        <span class="flex items-center justify-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                            </svg>
                                            Kembali
                                        </span>
                                    </button>
                                    <button type="button" id="next-step2" wire:click="registration()"
                                        class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-3 px-6 rounded-xl font-semibold transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                                        <span class="flex items-center justify-center">
                                            Daftar
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium">
                    ← Kembali ke Halaman Login
                </a>
            </div>
        </div>
    @else
        <div class="min-h-screen flex items-center justify-center p-4">
            <div
                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl w-full max-w-lg p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC]"></div>

                <!-- Logo & Title -->
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset('asset/img/logo.png') }}" alt="PRO CBT Logo" class="h-12 drop-shadow-md">
                    <h1
                        class="text-2xl font-bold {{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                        Buat Akun Anda</h1>
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
                            <span
                                :class="step >= 1 ?
                                    '{{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }} font-semibold' :
                                    ''">Step
                                1</span>
                            <span
                                :class="step >= 2 ?
                                    '{{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }} font-semibold' :
                                    ''">Step
                                2</span>
                            <span
                                :class="step >= 3 ?
                                    '{{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }} font-semibold' :
                                    ''">Step
                                3</span>
                            <span
                                :class="step >= 4 ?
                                    '{{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }} font-semibold' :
                                    ''">Step
                                4</span>
                            <span
                                :class="step >= 5 ?
                                    '{{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }} font-semibold' :
                                    ''">Step
                                5</span>
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
                            <button type="button" wire:click="nextStep"
                                class="btn-primary cursor-pointer">Lanjut</button>
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
                            <button type="button" wire:click="nextStep"
                                class="btn-primary cursor-pointer">Lanjut</button>
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
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                            <button type="button" wire:click="nextStep"
                                class="btn-primary cursor-pointer">Lanjut</button>
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
                                <p class="text-xs text-gray-500 mt-1">Masukkan nomor HP/WA yang aktif dan bisa
                                    dihubungi
                                    (contoh: 081234567890)</p>
                                @error('pic_phone')
                                    <span class="text-sm text-red-500">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="flex justify-between mt-6">
                            <button type="button" wire:click="prevStep" class="btn-secondary">Kembali</button>
                            <button type="button" wire:click="nextStep"
                                class="btn-primary cursor-pointer">Lanjut</button>
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
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="!show" x-cloak xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
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
                                                class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="!showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
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

                <!-- Link ke Login -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="{{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }} hover:underline font-semibold">Login
                            di
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
    @endif
</div>
