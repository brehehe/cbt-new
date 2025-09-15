@if ($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal User</h2>
            </div>
            <button wire:click="closeModal('modal')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ $editMode ? 'Edit Data Dosen' : 'Tambah Data Dosen' }}
                    </h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="mt-4 max-h-96 overflow-y-auto">
                    <form wire:submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Basic Information -->
                            <div class="md:col-span-2">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Dasar</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" class="form-control mt-1">
                                @error('name')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" wire:model="email" class="form-control mt-1">
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            @if (!$editMode)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password <span
                                            class="text-red-500">*</span></label>
                                    <input type="password" wire:model="password" class="form-control mt-1">
                                    @error('password')
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">ID Dosen <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="lecturer_id" class="form-control mt-1">
                                @error('lecturer_id')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIDN <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="lecturer_nidn" class="form-control mt-1">
                                @error('lecturer_nidn')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                <input type="text" wire:model="lecturer_nip" class="form-control mt-1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="lecturer_department" class="form-control mt-1">
                                @error('lecturer_department')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan Fungsional</label>
                                <input type="text" wire:model="lecturer_functional_position"
                                    class="form-control mt-1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Pendidikan <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="lecturer_education_level" class="form-control mt-1">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                @error('lecturer_education_level')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="lecturer_specialization" class="form-control mt-1">
                                @error('lecturer_specialization')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Keahlian</label>
                                <input type="text" wire:model="lecturer_expertise" class="form-control mt-1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Dosen</label>
                                <select wire:model="lecturer_status" class="form-control mt-1">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non-Aktif</option>
                                    <option value="retired">Pensiun</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Dosen</label>
                                <select wire:model="lecturer_type" class="form-control mt-1">
                                    <option value="full_time">Full Time</option>
                                    <option value="part_time">Part Time</option>
                                    <option value="contract">Kontrak</option>
                                </select>
                            </div>

                            <!-- Personal Information -->
                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Pribadi</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="birth_place" class="form-control mt-1">
                                @error('birth_place')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span
                                        class="text-red-500">*</span></label>
                                <input type="date" wire:model="birth_date" class="form-control mt-1">
                                @error('birth_date')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span
                                        class="text-red-500">*</span></label>
                                <select wire:model="gender" class="form-control mt-1">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                                @error('gender')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                                <select wire:model="religion" class="form-control mt-1">
                                    <option value="">Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <!-- Contact Information -->
                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Kontak</h4>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span
                                        class="text-red-500">*</span></label>
                                <textarea wire:model="address" rows="3" class="form-control mt-1"></textarea>
                                @error('address')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kota <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="city" class="form-control mt-1">
                                @error('city')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span
                                        class="text-red-500">*</span></label>
                                <input type="text" wire:model="province" class="form-control mt-1">
                                @error('province')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                                <input type="text" wire:model="phone" class="form-control mt-1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">HP/WhatsApp</label>
                                <input type="text" wire:model="mobile_phone" class="form-control mt-1">
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end space-x-3 pt-4 mt-4 border-t">
                            <button type="button" wire:click="closeModal"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-150">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150">
                                {{ $editMode ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
