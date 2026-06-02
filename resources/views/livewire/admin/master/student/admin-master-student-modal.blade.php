<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 1000px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $data_id ? 'Edit' : 'Tambah' }} Data Mahasiswa
                </h2>
            </div>
            <button wire:click="closeModal"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 600px;">
            <div class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700">
                    Tipe <span class="text-red-600">*</span>
                </label>


                <div class="mt-2 flex space-x-2">
                    <button type="button" wire:click="$set('type_study', 'mahasiswa')"
                        class="px-4 py-2 rounded-md border
                            {{ $type_study === 'mahasiswa' ? 'bg-[color:var(--primary)] text-white border-[color:var(--primary)]' : 'bg-white text-gray-700 border-gray-300' }}">
                        Kelas
                    </button>

                    <button type="button" wire:click="$set('type_study', 'general')"
                        class="px-4 py-2 rounded-md border
                            {{ $type_study === 'general' ? 'bg-[color:var(--primary)] text-white border-[color:var(--primary)]' : 'bg-white text-gray-700 border-gray-300' }}">
                        General
                    </button>
                </div>


                @error('type_study')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            @if ($type_study === 'mahasiswa')
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                                    class="text-red-600">*</span></label>
                            <input id="name" type="text" wire:model.defer="name" placeholder="Contoh: Ahmad Fauzi"
                                class="mt-1 form-control">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nim" class="block text-sm font-medium text-gray-700">NIM <span
                                    class="text-red-600">*</span></label>
                            <input id="nim" type="text" wire:model.defer="nim" placeholder="Contoh: 20241001"
                                class="mt-1 form-control">
                            @error('nim')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                                    class="text-red-600">*</span></label>
                            <input id="email" type="email" wire:model.defer="email"
                                placeholder="Contoh: ahmad.fauzi@student.university.ac.id" class="mt-1 form-control">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password
                                @if (!$data_id)
                                    <span class="text-red-600">*</span>
                                @endif
                            </label>
                            <div x-data="{ show: false }" class="relative">
                                <input :type="show ? 'text' : 'password'" id="password" wire:model.defer="password"
                                    placeholder="Minimum 8 karakter" class="mt-1 form-control">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500" tabindex="-1">
                                    <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <input id="phone" type="tel" wire:model.defer="phone" placeholder="Contoh: 081234567890"
                                class="mt-1 form-control">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <div class="{{ $profile || $profile_old ? null : 'md:col-span-2' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>

                        <input type="file" wire:model.live="profile" class="block text-sm text-gray-500 w-full
                                                       file:px-2 file:py-1 file:rounded-md
                                                       file:border file:border-gray-300
                                                       file:text-xs file:font-medium
                                                       file:bg-blue-50 file:text-blue-700
                                                       hover:file:bg-blue-100" accept="image/*" />
                        <div wire:loading wire:target="profile" class="text-sm text-gray-500 mt-1">
                            Uploading profile...
                        </div>
                        @error('profile')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @if (is_object($profile))
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                profile:</label>
                            <img src="{{ $profile->temporaryUrl() }}" alt="Preview profile"
                                class="h-100 w-auto rounded border shadow" />
                        </div>
                    @else
                        @if ($profile_old)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                    profile:</label>
                                <img src="{{ asset('storage/' . $profile_old) }}" alt="Preview profile"
                                    class="h-100 w-auto rounded border shadow" />
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Academic Information Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Akademik</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="study_id" class="block text-sm font-medium text-gray-700">Prodi</label>
                            <select class="mt-1 form-control" wire:model.defer="study_id">
                                <option value="">Pilih Prodi</option>
                                @foreach ($studys as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('study_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="student_status" class="block text-sm font-medium text-gray-700">Status
                                Mahasiswa</label>
                            <select id="student_status" wire:model.defer="student_status" class="mt-1 form-control">
                                <option value="active">Aktif</option>
                                <option value="graduate">Lulus</option>
                                <option value="dropout">Dropout</option>
                                <option value="transfer">Pindah</option>
                                <option value="leave">Cuti</option>
                            </select>
                            @error('student_status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="student_entry_date" class="block text-sm font-medium text-gray-700">Tanggal
                                Masuk</label>
                            <input id="student_entry_date" type="date" wire:model.defer="student_entry_date"
                                class="mt-1 form-control">
                            @error('student_entry_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat
                                Lahir</label>
                            <input id="birth_place" type="text" wire:model.defer="birth_place" placeholder="Contoh: Jakarta"
                                class="mt-1 form-control">
                            @error('birth_place')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal
                                Lahir</label>
                            <input id="birth_date" type="date" wire:model.defer="birth_date" class="mt-1 form-control">
                            @error('birth_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Jenis
                                Kelamin</label>
                            <select id="gender" wire:model.defer="gender" class="mt-1 form-control">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                            @error('gender')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="religion" class="block text-sm font-medium text-gray-700">Agama</label>
                            <select id="religion" wire:model.defer="religion" class="mt-1 form-control">
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Khonghucu">Khonghucu</option>
                            </select>
                            @error('religion')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nationality" class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
                            <input id="nationality" type="text" wire:model.defer="nationality"
                                placeholder="Contoh: Indonesian" class="mt-1 form-control">
                            @error('nationality')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="marital_status" class="block text-sm font-medium text-gray-700">Status
                                Pernikahan</label>
                            <select id="marital_status" wire:model.defer="marital_status" class="mt-1 form-control">
                                <option value="single">Belum Menikah</option>
                                <option value="married">Menikah</option>
                                <option value="divorced">Bercerai</option>
                                <option value="widowed">Janda/Duda</option>
                            </select>
                            @error('marital_status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="blood_group" class="block text-sm font-medium text-gray-700">Golongan
                                Darah</label>
                            <select id="blood_group" wire:model.defer="blood_group" class="mt-1 form-control">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                            @error('blood_group')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="identity_type" class="block text-sm font-medium text-gray-700">Jenis
                                Identitas</label>
                            <select id="identity_type" wire:model.defer="identity_type" class="mt-1 form-control">
                                <option value="KTP">KTP</option>
                                <option value="Passport">Passport</option>
                                <option value="SIM">SIM</option>
                            </select>
                            @error('identity_type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="identity_number" class="block text-sm font-medium text-gray-700">Nomor
                                Identitas</label>
                            <input id="identity_number" type="text" wire:model.defer="identity_number"
                                placeholder="Contoh: 3201234567890123" class="mt-1 form-control">
                            @error('identity_number')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Kontak</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea id="address" wire:model.defer="address"
                                placeholder="Contoh: Jl. Raya No. 123, RT 01/RW 02" class="mt-1 form-control"
                                rows="3"></textarea>
                            @error('address')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">Kota</label>
                            <input id="city" type="text" wire:model.defer="city" placeholder="Contoh: Jakarta"
                                class="mt-1 form-control">
                            @error('city')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Provinsi</label>
                            <input id="province" type="text" wire:model.defer="province" placeholder="Contoh: DKI Jakarta"
                                class="mt-1 form-control">
                            @error('province')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                            <input id="postal_code" type="text" wire:model.defer="postal_code" placeholder="Contoh: 12345"
                                class="mt-1 form-control">
                            @error('postal_code')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="mobile_phone" class="block text-sm font-medium text-gray-700">No. HP</label>
                            <input id="mobile_phone" type="tel" wire:model.defer="mobile_phone"
                                placeholder="Contoh: 08123456789" class="mt-1 form-control">
                            @error('mobile_phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Kontak Darurat</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Nama
                                Kontak Darurat</label>
                            <input id="emergency_contact_name" type="text" wire:model.defer="emergency_contact_name"
                                placeholder="Contoh: Ayah/Ibu" class="mt-1 form-control">
                            @error('emergency_contact_name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">No.
                                Telepon Darurat</label>
                            <input id="emergency_contact_phone" type="tel" wire:model.defer="emergency_contact_phone"
                                placeholder="Contoh: 08123456789" class="mt-1 form-control">
                            @error('emergency_contact_phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="emergency_contact_relation"
                                class="block text-sm font-medium text-gray-700">Hubungan</label>
                            <select id="emergency_contact_relation" wire:model.defer="emergency_contact_relation"
                                class="mt-1 form-control">
                                <option value="">Pilih Hubungan</option>
                                <option value="Parent">Orang Tua</option>
                                <option value="Spouse">Pasangan</option>
                                <option value="Sibling">Saudara</option>
                                <option value="Friend">Teman</option>
                                <option value="Guardian">Wali</option>
                            </select>
                            @error('emergency_contact_relation')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            @elseif($type_study === 'general')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama <span
                            class="text-red-600">*</span></label>
                    <input id="name" type="name" wire:model.defer="name" placeholder="Contoh : Admin"
                        class="mt-1 form-control">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="nim" class="block text-sm font-medium text-gray-700">Nomer Peserta <span
                            class="text-red-600">*</span></label>
                    <input id="nim" type="text" wire:model.defer="nim" placeholder="Contoh: 20251001"
                        class="mt-1 form-control">
                    @error('nim')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username <span
                            class="text-red-600">*</span></label>
                    <input id="username" type="name" wire:model.defer="username" placeholder="Contoh : Admin"
                        class="mt-1 form-control">
                    @error('username')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                            class="text-red-600">*</span></label>
                    <input id="email" type="email" wire:model.defer="email" placeholder="Contoh : admin@gmail.com"
                        class="mt-1 form-control">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="{{ $profile || $profile_old ? null : 'md:col-span-2' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>

                        <input type="file" wire:model.live="profile" class="block text-sm text-gray-500 w-full
                                                       file:px-2 file:py-1 file:rounded-md
                                                       file:border file:border-gray-300
                                                       file:text-xs file:font-medium
                                                       file:bg-blue-50 file:text-blue-700
                                                       hover:file:bg-blue-100" accept="image/*" />
                        <div wire:loading wire:target="profile" class="text-sm text-gray-500 mt-1">
                            Uploading profile...
                        </div>
                        @error('profile')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    @if (is_object($profile))
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                profile:</label>
                            <img src="{{ $profile->temporaryUrl() }}" alt="Preview profile"
                                class="h-100 w-auto rounded border shadow" />
                        </div>
                    @else
                        @if ($profile_old)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Preview
                                    profile:</label>
                                <img src="{{ asset('storage/' . $profile_old) }}" alt="Preview profile"
                                    class="h-100 w-auto rounded border shadow" />
                            </div>
                        @endif
                    @endif
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <input id="phone" type="number" wire:model.defer="phone" placeholder="Contoh : 081234567890"
                        class="mt-1 form-control">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password @if (!$data_id)
                        <span class="text-red-600">*</span>
                    @else
                        @endif
                    </label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" wire:model.defer="password"
                            placeholder="Contoh : 12345678" class="mt-1 form-control">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500" tabindex="-1">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea id="address" wire:model.defer="address" placeholder="Contoh: Jl. Raya No. 123, RT 01/RW 02"
                        class="mt-1 form-control" rows="3"></textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                {{ $data_id ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </div>
</div>