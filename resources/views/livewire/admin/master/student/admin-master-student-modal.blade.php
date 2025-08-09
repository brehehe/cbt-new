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
            <!-- Basic Information Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">ID Mahasiswa</label>
                        <input id="student_id" type="text" wire:model.defer="student_id" placeholder="Contoh: STD001"
                            class="mt-1 form-control">
                        @error('student_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
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
                        <label for="phone" class="block text-sm font-medium text-gray-700">No. Telepon <span
                                class="text-red-600">*</span></label>
                        <input id="phone" type="tel" wire:model.defer="phone" placeholder="Contoh: 081234567890"
                            class="mt-1 form-control">
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Academic Information Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Akademik</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="student_program" class="block text-sm font-medium text-gray-700">Program
                            Studi</label>
                        <input id="student_program" type="text" wire:model.defer="student_program"
                            placeholder="Contoh: Teknik Informatika" class="mt-1 form-control">
                        @error('student_program')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="student_faculty" class="block text-sm font-medium text-gray-700">Fakultas</label>
                        <input id="student_faculty" type="text" wire:model.defer="student_faculty"
                            placeholder="Contoh: Fakultas Teknik" class="mt-1 form-control">
                        @error('student_faculty')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="student_department" class="block text-sm font-medium text-gray-700">Jurusan</label>
                        <input id="student_department" type="text" wire:model.defer="student_department"
                            placeholder="Contoh: Teknik Informatika" class="mt-1 form-control">
                        @error('student_department')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="student_class" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <input id="student_class" type="text" wire:model.defer="student_class"
                            placeholder="Contoh: A" class="mt-1 form-control">
                        @error('student_class')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="student_semester" class="block text-sm font-medium text-gray-700">Semester</label>
                        <select id="student_semester" wire:model.defer="student_semester" class="mt-1 form-control">
                            <option value="">Pilih Semester</option>
                            @for ($i = 1; $i <= 14; $i++)
                                <option value="{{ $i }}">Semester {{ $i }}</option>
                            @endfor
                        </select>
                        @error('student_semester')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="student_academic_year" class="block text-sm font-medium text-gray-700">Tahun
                            Akademik</label>
                        <input id="student_academic_year" type="text" wire:model.defer="student_academic_year"
                            placeholder="Contoh: 2024/2025" class="mt-1 form-control">
                        @error('student_academic_year')
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
                        <label for="student_gpa" class="block text-sm font-medium text-gray-700">IPK</label>
                        <input id="student_gpa" type="number" step="0.01" min="0" max="4"
                            wire:model.defer="student_gpa" placeholder="Contoh: 3.75" class="mt-1 form-control">
                        @error('student_gpa')
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
                        <label for="birth_place" class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                        <input id="birth_place" type="text" wire:model.defer="birth_place"
                            placeholder="Contoh: Jakarta" class="mt-1 form-control">
                        @error('birth_place')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input id="birth_date" type="date" wire:model.defer="birth_date"
                            class="mt-1 form-control">
                        @error('birth_date')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
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
                        <label for="nationality"
                            class="block text-sm font-medium text-gray-700">Kewarganegaraan</label>
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
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap <span
                                class="text-red-600">*</span></label>
                        <textarea id="address" wire:model.defer="address" placeholder="Contoh: Jl. Raya No. 123, RT 01/RW 02"
                            class="mt-1 form-control" rows="3"></textarea>
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
                        <input id="province" type="text" wire:model.defer="province"
                            placeholder="Contoh: DKI Jakarta" class="mt-1 form-control">
                        @error('province')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                        <input id="postal_code" type="text" wire:model.defer="postal_code"
                            placeholder="Contoh: 12345" class="mt-1 form-control">
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

            <!-- Additional Information Section -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Tambahan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="special_needs" class="flex items-center">
                            <input wire:model='special_needs' type="checkbox" id="special_needs"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 mr-2">
                            <span class="text-sm font-medium text-gray-700">Membutuhkan Akomodasi Khusus</span>
                        </label>
                    </div>
                    <div>
                        <label for="is_active" class="flex items-center">
                            <input wire:model='is_active' type="checkbox" id="is_active"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 mr-2">
                            <span class="text-sm font-medium text-gray-700">Aktif</span>
                        </label>
                    </div>
                    <div class="md:col-span-2">
                        <label for="special_needs_description"
                            class="block text-sm font-medium text-gray-700">Deskripsi Kebutuhan Khusus</label>
                        <textarea id="special_needs_description" wire:model.defer="special_needs_description"
                            placeholder="Jelaskan kebutuhan khusus jika ada..." class="mt-1 form-control" rows="3"></textarea>
                        @error('special_needs_description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan</label>
                        <textarea id="notes" wire:model.defer="notes" placeholder="Catatan tambahan..." class="mt-1 form-control"
                            rows="3"></textarea>
                        @error('notes')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                {{ $data_id ? 'Update' : 'Simpan' }}
            </button>
        </div>
    </div>
</div>
