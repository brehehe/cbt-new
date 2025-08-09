<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 950px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $data_id ? 'Edit Data Pengawas' : 'Tambah Data Pengawas' }}
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
                <!-- Informasi Dasar Pengawas -->
                <div class="md:col-span-2 mb-4">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Dasar Pengawas</h4>
                </div>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                            class="text-red-600">*</span></label>
                    <input id="name" type="text" wire:model.defer="name"
                        placeholder="Contoh : Dr. Ahmad Supervisor" class="mt-1 form-control">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                            class="text-red-600">*</span></label>
                    <input id="email" type="email" wire:model.defer="email"
                        placeholder="Contoh : supervisor@university.ac.id" class="mt-1 form-control">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username <span
                            class="text-red-600">*</span></label>
                    <input id="username" type="text" wire:model.defer="username" placeholder="Contoh : supervisor01"
                        class="mt-1 form-control">
                    @error('username')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">ID Pengawas <span
                            class="text-red-600">*</span></label>
                    <input id="employee_id" type="text" wire:model.defer="employee_id" placeholder="Contoh : SUP001"
                        class="mt-1 form-control">
                    @error('employee_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Kontak -->
                <div class="md:col-span-2 mb-4 mt-6">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Kontak</h4>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon <span
                            class="text-red-600">*</span></label>
                    <input id="phone" type="text" wire:model.defer="phone" placeholder="Contoh : 081234567890"
                        class="mt-1 form-control">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea id="address" wire:model.defer="address" rows="3" placeholder="Contoh : Jl. Universitas No. 123"
                        class="mt-1 form-control"></textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Pengawasan -->
                <div class="md:col-span-2 mb-4 mt-6">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Pengawasan</h4>
                </div>

                <div class="mb-4">
                    <label for="supervisor_nip" class="block text-sm font-medium text-gray-700">NIP Pengawas</label>
                    <input id="supervisor_nip" type="text" wire:model.defer="supervisor_nip"
                        placeholder="Contoh : 198501012010011001" class="mt-1 form-control">
                    @error('supervisor_nip')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_department"
                        class="block text-sm font-medium text-gray-700">Departemen</label>
                    <input id="supervisor_department" type="text" wire:model.defer="supervisor_department"
                        placeholder="Contoh : Academic Affairs" class="mt-1 form-control">
                    @error('supervisor_department')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_unit" class="block text-sm font-medium text-gray-700">Unit Kerja</label>
                    <input id="supervisor_unit" type="text" wire:model.defer="supervisor_unit"
                        placeholder="Contoh : Quality Assurance Unit" class="mt-1 form-control">
                    @error('supervisor_unit')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_position" class="block text-sm font-medium text-gray-700">Jabatan</label>
                    <input id="supervisor_position" type="text" wire:model.defer="supervisor_position"
                        placeholder="Contoh : Kepala Pengawas" class="mt-1 form-control">
                    @error('supervisor_position')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_level" class="block text-sm font-medium text-gray-700">Level
                        Pengawas</label>
                    <select id="supervisor_level" wire:model.defer="supervisor_level" class="mt-1 form-control">
                        <option value="">Pilih Level</option>
                        <option value="Junior">Junior</option>
                        <option value="Senior">Senior</option>
                        <option value="Lead">Lead</option>
                        <option value="Principal">Principal</option>
                    </select>
                    @error('supervisor_level')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_area" class="block text-sm font-medium text-gray-700">Area
                        Pengawasan</label>
                    <select id="supervisor_area" wire:model.defer="supervisor_area" class="mt-1 form-control">
                        <option value="">Pilih Area</option>
                        <option value="Academic">Academic</option>
                        <option value="Administrative">Administrative</option>
                        <option value="Technical">Technical</option>
                        <option value="General">General</option>
                    </select>
                    @error('supervisor_area')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_specialization"
                        class="block text-sm font-medium text-gray-700">Spesialisasi</label>
                    <input id="supervisor_specialization" type="text" wire:model.defer="supervisor_specialization"
                        placeholder="Contoh : Educational Assessment" class="mt-1 form-control">
                    @error('supervisor_specialization')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_type" class="block text-sm font-medium text-gray-700">Tipe Pengawas</label>
                    <select id="supervisor_type" wire:model.defer="supervisor_type" class="mt-1 form-control">
                        <option value="">Pilih Tipe</option>
                        <option value="internal">Internal</option>
                        <option value="external">External</option>
                        <option value="contract">Contract</option>
                    </select>
                    @error('supervisor_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai
                        Bertugas</label>
                    <input id="supervisor_start_date" type="date" wire:model.defer="supervisor_start_date"
                        class="mt-1 form-control">
                    @error('supervisor_start_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="supervisor_experience_years"
                        class="block text-sm font-medium text-gray-700">Pengalaman (Tahun)</label>
                    <input id="supervisor_experience_years" type="number"
                        wire:model.defer="supervisor_experience_years" placeholder="Contoh : 5"
                        class="mt-1 form-control" min="0">
                    @error('supervisor_experience_years')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Informasi Akun -->
                <div class="md:col-span-2 mb-4 mt-6">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Informasi Akun</h4>
                </div>

                <!-- Password (hanya untuk tambah atau jika ingin mengubah) -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password
                        @if (!$data_id)
                            <span class="text-red-600">*</span>
                        @else
                            <small class="text-gray-500">(Kosongkan jika tidak ingin mengubah)</small>
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

                <!-- Profile Upload -->
                <div class="mb-4">
                    <label for="profile" class="block text-sm font-medium text-gray-700">Foto Profile</label>
                    <input id="profile" type="file" wire:model="profile" accept="image/*"
                        class="mt-1 form-control">
                    @if ($profile)
                        <div class="mt-2">
                            <img src="{{ $profile->temporaryUrl() }}" alt="Preview"
                                class="h-20 w-20 object-cover rounded-full">
                        </div>
                    @elseif($profile_old)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $profile_old) }}" alt="Current Profile"
                                class="h-20 w-20 object-cover rounded-full">
                        </div>
                    @endif
                    @error('profile')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status & Permissions -->
                <div class="md:col-span-2 mb-4 mt-6">
                    <h4 class="text-md font-medium text-gray-900 border-b pb-2">Status & Hak Akses</h4>
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input id="is_head" type="checkbox" wire:model.defer="is_head"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="is_head" class="ml-2 block text-sm text-gray-900">
                            Kepala Pengawas
                        </label>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Aktifkan jika merupakan kepala pengawas dengan hak akses
                        lebih tinggi</p>
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input id="is_active" type="checkbox" wire:model.defer="is_active"
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Status Aktif
                        </label>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Aktifkan untuk mengizinkan akses sistem</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal('modal')"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
