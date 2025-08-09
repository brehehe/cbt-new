<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 1000px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{ $data_id ? 'Edit Data Pengawas' : 'Tambah Data Pengawas' }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $data_id ? 'Perbarui informasi pengawas' : 'Masukkan informasi pengawas baru' }}
                    </p>
                </div>
            </div>
            <button wire:click="closeModal('modal')"
                class="text-gray-400 hover:text-gray-600 transition-colors text-2xl leading-none cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-auto" style="max-height: 600px;">
            <form wire:submit.prevent="submit">
                <!-- Informasi Dasar -->
                <div class="mb-8">
                    <div class="border-l-4 border-purple-500 pl-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                        <p class="text-sm text-gray-600">Data identitas dan kontak pengawas</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input id="name" type="text" wire:model.defer="name"
                                placeholder="Contoh: Dr. Ahmad Supervisor"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input id="email" type="email" wire:model.defer="email"
                                placeholder="supervisor@university.ac.id"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <input id="username" type="text" wire:model.defer="username" placeholder="supervisor01"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                            @error('username')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                                @if (!$data_id)
                                    <span class="text-red-500">*</span>
                                @else
                                    <span class="text-gray-500">(Kosongkan jika tidak diubah)</span>
                                @endif
                            </label>
                            <input id="password" type="password" wire:model.defer="password"
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                            @error('password')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input id="phone" type="text" wire:model.defer="phone" placeholder="081234567890"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                            @error('phone')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto Profil -->
                        <div>
                            <label for="profile" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Profil
                            </label>
                            <input id="profile" type="file" wire:model="profile" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors">
                            @error('profile')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                            @if ($profile)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Preview:</p>
                                    <img src="{{ $profile->temporaryUrl() }}" alt="Preview"
                                        class="w-16 h-16 rounded-lg object-cover mt-1">
                                </div>
                            @elseif ($profile_old)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Foto saat ini:</p>
                                    <img src="{{ asset('storage/' . $profile_old) }}" alt="Current Profile"
                                        class="w-16 h-16 rounded-lg object-cover mt-1">
                                </div>
                            @endif
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat
                            </label>
                            <textarea id="address" wire:model.defer="address" rows="3"
                                placeholder="Jl. Universitas No. 123, Kota, Provinsi"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-colors"></textarea>
                            @error('address')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Kepegawaian -->
                <div class="mb-8">
                    <div class="border-l-4 border-indigo-500 pl-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Kepegawaian</h3>
                        <p class="text-sm text-gray-600">Data kepegawaian dan identifikasi</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ID Pengawas -->
                        <div>
                            <label for="supervisor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                ID Pengawas <span class="text-red-500">*</span>
                            </label>
                            <input id="supervisor_id" type="text" wire:model.defer="supervisor_id"
                                placeholder="SUP001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            @error('supervisor_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div>
                            <label for="supervisor_nip" class="block text-sm font-medium text-gray-700 mb-2">
                                NIP Pengawas
                            </label>
                            <input id="supervisor_nip" type="text" wire:model.defer="supervisor_nip"
                                placeholder="199001012020031001"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            @error('supervisor_nip')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Departemen -->
                        <div>
                            <label for="supervisor_department" class="block text-sm font-medium text-gray-700 mb-2">
                                Departemen
                            </label>
                            <input id="supervisor_department" type="text" wire:model.defer="supervisor_department"
                                placeholder="Fakultas Teknologi Informasi"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            @error('supervisor_department')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Unit -->
                        <div>
                            <label for="supervisor_unit" class="block text-sm font-medium text-gray-700 mb-2">
                                Unit/Bagian
                            </label>
                            <input id="supervisor_unit" type="text" wire:model.defer="supervisor_unit"
                                placeholder="Program Studi Informatika"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            @error('supervisor_unit')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi -->
                        <div>
                            <label for="supervisor_position" class="block text-sm font-medium text-gray-700 mb-2">
                                Posisi/Jabatan
                            </label>
                            <input id="supervisor_position" type="text" wire:model.defer="supervisor_position"
                                placeholder="Dosen/Pengawas Senior"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                            @error('supervisor_position')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Level -->
                        <div>
                            <label for="supervisor_level" class="block text-sm font-medium text-gray-700 mb-2">
                                Level/Tingkat
                            </label>
                            <select id="supervisor_level" wire:model.defer="supervisor_level"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-colors">
                                <option value="">Pilih Level</option>
                                <option value="Junior">Junior</option>
                                <option value="Senior">Senior</option>
                                <option value="Lead">Lead</option>
                                <option value="Manager">Manager</option>
                                <option value="Director">Director</option>
                            </select>
                            @error('supervisor_level')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Pengawasan -->
                <div class="mb-8">
                    <div class="border-l-4 border-green-500 pl-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Pengawasan</h3>
                        <p class="text-sm text-gray-600">Detail area dan spesialisasi pengawasan</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Area Pengawasan -->
                        <div>
                            <label for="supervisor_area" class="block text-sm font-medium text-gray-700 mb-2">
                                Area Pengawasan
                            </label>
                            <input id="supervisor_area" type="text" wire:model.defer="supervisor_area"
                                placeholder="Gedung A, Laboratorium, dll"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                            @error('supervisor_area')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Spesialisasi -->
                        <div>
                            <label for="supervisor_specialization"
                                class="block text-sm font-medium text-gray-700 mb-2">
                                Spesialisasi
                            </label>
                            <input id="supervisor_specialization" type="text"
                                wire:model.defer="supervisor_specialization"
                                placeholder="Ujian Online, Pengawasan Lab, dll"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                            @error('supervisor_specialization')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Pengawas -->
                        <div>
                            <label for="supervisor_status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pengawas
                            </label>
                            <select id="supervisor_status" wire:model.defer="supervisor_status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                                <option value="">Pilih Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                                <option value="temporary">Sementara</option>
                                <option value="permanent">Tetap</option>
                            </select>
                            @error('supervisor_status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Pengawas -->
                        <div>
                            <label for="supervisor_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Pengawas
                            </label>
                            <select id="supervisor_type" wire:model.defer="supervisor_type"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                                <option value="">Pilih Tipe</option>
                                <option value="internal">Internal</option>
                                <option value="external">External</option>
                                <option value="contract">Kontrak</option>
                            </select>
                            @error('supervisor_type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="supervisor_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai
                            </label>
                            <input id="supervisor_start_date" type="date" wire:model.defer="supervisor_start_date"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                            @error('supervisor_start_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pengalaman (Tahun) -->
                        <div>
                            <label for="supervisor_experience_years"
                                class="block text-sm font-medium text-gray-700 mb-2">
                                Pengalaman (Tahun)
                            </label>
                            <input id="supervisor_experience_years" type="number"
                                wire:model.defer="supervisor_experience_years" placeholder="5" min="0"
                                max="50"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-colors">
                            @error('supervisor_experience_years')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pengaturan Sistem -->
                <div class="mb-8">
                    <div class="border-l-4 border-orange-500 pl-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan Sistem</h3>
                        <p class="text-sm text-gray-600">Konfigurasi peran dan status dalam sistem</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kepala/Koordinator -->
                        <div class="flex items-center space-x-3">
                            <input id="is_head" type="checkbox" wire:model.defer="is_head"
                                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            <label for="is_head" class="text-sm font-medium text-gray-700">
                                Kepala/Koordinator Pengawas
                            </label>
                        </div>

                        <!-- Status Aktif -->
                        <div class="flex items-center space-x-3">
                            <input id="is_active" type="checkbox" wire:model.defer="is_active"
                                class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Footer Buttons -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button type="button" wire:click="closeModal('modal')"
                class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                Batal
            </button>
            <button type="submit"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                {{ $data_id ? 'Perbarui' : 'Simpan' }} Pengawas
            </button>
        </div>
    </div>
</div>
