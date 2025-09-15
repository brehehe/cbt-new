<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 950px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">{{ $data_id ? 'Edit Data Dosen' : 'Tambah Data Dosen' }}
                </h2>
            </div>
            <button wire:click="closeModal('modal')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 600px;">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                            class="text-red-600">*</span></label>
                    <input id="name" type="text" wire:model.defer="name"
                        placeholder="Contoh : Dr. Ahmad Suharto" class="mt-1 form-control">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                            class="text-red-600">*</span></label>
                    <input id="email" type="email" wire:model.defer="email"
                        placeholder="Contoh : dosen@university.ac.id" class="mt-1 form-control">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_id" class="block text-sm font-medium text-gray-700">ID Dosen <span
                            class="text-red-600">*</span></label>
                    <input id="lecturer_id" type="text" wire:model.defer="lecturer_id" placeholder="Contoh : LEC001"
                        class="mt-1 form-control">
                    @error('lecturer_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_nidn" class="block text-sm font-medium text-gray-700">NIDN <span
                            class="text-red-600">*</span></label>
                    <input id="lecturer_nidn" type="text" wire:model.defer="lecturer_nidn"
                        placeholder="Contoh : 1234567890" class="mt-1 form-control">
                    @error('lecturer_nidn')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_nip" class="block text-sm font-medium text-gray-700">NIP</label>
                    <input id="lecturer_nip" type="text" wire:model.defer="lecturer_nip"
                        placeholder="Contoh : 196512121990031001" class="mt-1 form-control">
                    @error('lecturer_nip')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_employee_number" class="block text-sm font-medium text-gray-700">Nomor
                        Pegawai</label>
                    <input id="lecturer_employee_number" type="text" wire:model.defer="lecturer_employee_number"
                        placeholder="Contoh : EMP001" class="mt-1 form-control">
                    @error('lecturer_employee_number')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="studys" class="block text-sm font-medium text-gray-700">
                        Prodi <span class="text-red-600">*</span>
                    </label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('studys', e ? e : '');
                            }
                        });"
                            wire:model.live="studys" id="studys" multiple>
                            <option value="">Pilih Prodi</option>
                            @foreach ($getStudys as $key_get_study => $getStudy)
                                <option value="{{ $key_get_study }}">{{ $getStudy }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('studys')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="lecturer_functional_position" class="block text-sm font-medium text-gray-700">Jabatan
                        Fungsional</label>
                    <input id="lecturer_functional_position" type="text"
                        wire:model.defer="lecturer_functional_position" placeholder="Contoh : Pengajar Ahli Utama"
                        class="mt-1 form-control">
                    @error('lecturer_functional_position')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_education_level" class="block text-sm font-medium text-gray-700">Tingkat
                        Pendidikan <span class="text-red-600">*</span></label>
                    <select id="lecturer_education_level" wire:model.defer="lecturer_education_level"
                        class="mt-1 form-control">
                        <option value="">Pilih Pendidikan</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                        <option value="S3">S3</option>
                    </select>
                    @error('lecturer_education_level')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_specialization" class="block text-sm font-medium text-gray-700">Spesialisasi
                        <span class="text-red-600">*</span></label>
                    <input id="lecturer_specialization" type="text" wire:model.defer="lecturer_specialization"
                        placeholder="Contoh : Data Science" class="mt-1 form-control">
                    @error('lecturer_specialization')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_expertise" class="block text-sm font-medium text-gray-700">Keahlian</label>
                    <input id="lecturer_expertise" type="text" wire:model.defer="lecturer_expertise"
                        placeholder="Contoh : Machine Learning, AI" class="mt-1 form-control">
                    @error('lecturer_expertise')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_research_interest" class="block text-sm font-medium text-gray-700">Minat
                        Penelitian</label>
                    <input id="lecturer_research_interest" type="text"
                        wire:model.defer="lecturer_research_interest"
                        placeholder="Contoh : Natural Language Processing" class="mt-1 form-control">
                    @error('lecturer_research_interest')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_status" class="block text-sm font-medium text-gray-700">Status Dosen</label>
                    <select id="lecturer_status" wire:model.defer="lecturer_status" class="mt-1 form-control">
                        <option value="active">Aktif</option>
                        <option value="inactive">Non-Aktif</option>
                        <option value="retired">Pensiun</option>
                    </select>
                    @error('lecturer_status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lecturer_type" class="block text-sm font-medium text-gray-700">Tipe Dosen</label>
                    <select id="lecturer_type" wire:model.defer="lecturer_type" class="mt-1 form-control">
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Kontrak</option>
                    </select>
                    @error('lecturer_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat Lahir <span
                            class="text-red-600">*</span></label>
                    <input id="birth_place" type="text" wire:model.defer="birth_place"
                        placeholder="Contoh : Jakarta" class="mt-1 form-control">
                    @error('birth_place')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span
                            class="text-red-600">*</span></label>
                    <input id="birth_date" type="date" wire:model.defer="birth_date" class="mt-1 form-control">
                    @error('birth_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span
                            class="text-red-600">*</span></label>
                    <select id="gender" wire:model.defer="gender" class="mt-1 form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="religion" class="block text-sm font-medium text-gray-700">Agama</label>
                    <select id="religion" wire:model.defer="religion" class="mt-1 form-control">
                        <option value="">Pilih Agama</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Konghucu">Konghucu</option>
                    </select>
                    @error('religion')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nationality" class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                    <input id="nationality" type="text" wire:model.defer="nationality"
                        placeholder="Contoh : Indonesia" class="mt-1 form-control">
                    @error('nationality')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="identity_number" class="block text-sm font-medium text-gray-700">NIK</label>
                    <input id="identity_number" type="text" wire:model.defer="identity_number"
                        placeholder="Contoh : 3174123112800001" class="mt-1 form-control">
                    @error('identity_number')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2 mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span
                            class="text-red-600">*</span></label>
                    <textarea id="address" wire:model.defer="address" placeholder="Contoh : Jl. Raya No. 123"
                        class="mt-1 form-control" rows="3"></textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">Kota <span
                            class="text-red-600">*</span></label>
                    <input id="city" type="text" wire:model.defer="city" placeholder="Contoh : Jakarta"
                        class="mt-1 form-control">
                    @error('city')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="province" class="block text-sm font-medium text-gray-700">Provinsi <span
                            class="text-red-600">*</span></label>
                    <input id="province" type="text" wire:model.defer="province"
                        placeholder="Contoh : DKI Jakarta" class="mt-1 form-control">
                    @error('province')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                    <input id="phone" type="text" wire:model.defer="phone"
                        placeholder="Contoh : 021-12345678" class="mt-1 form-control">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="mobile_phone" class="block text-sm font-medium text-gray-700">HP/WhatsApp</label>
                    <input id="mobile_phone" type="text" wire:model.defer="mobile_phone"
                        placeholder="Contoh : 081234567890" class="mt-1 form-control">
                    @error('mobile_phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password (hanya untuk tambah) -->
                @if (!$data_id)
                    <div class="md:col-span-2 mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password <span
                                class="text-red-600">*</span></label>
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" id="password" wire:model.defer="password"
                                placeholder="Minimal 8 karakter"
                                class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10">
                            <button type="button" @click="show = !show"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500"
                                tabindex="-1">
                                <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal('modal')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-[#f58634] hover:bg-[#f58634] text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
