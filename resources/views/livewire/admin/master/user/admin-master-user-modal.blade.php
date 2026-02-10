<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 750px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
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

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 500px;">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama User <span
                            class="text-red-600">*</span></label>
                    <input id="name" type="name" wire:model.defer="name" placeholder="Contoh : Admin"
                        class="mt-1 form-control">
                    @error('name')
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
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon <span
                            class="text-red-600">*</span></label>
                    <input id="phone" type="number" wire:model.defer="phone" placeholder="Contoh : 081234567890"
                        class="mt-1 form-control">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="role_id" class="block text-sm font-medium text-gray-700">Role <span
                            class="text-red-600">*</span></label>
                    <select id="role_id" wire:model.lazy="role_id" class="mt-1 form-control">
                        <option value="">Pilih Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="is_active" class="block text-sm font-medium text-gray-700">Aktif</label>
                    <input wire:model='is_active' type="checkbox" id="is_active"
                        class="mt-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="is_head" class="block text-sm font-medium text-gray-700">Supervisor</label>
                    <input wire:model='is_head' type="checkbox" id="is_head"
                        class="mt-2 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="province_code" class="block text-sm font-medium text-gray-700">Provinsi <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('province_code', e ? e : '');
                            }
                        });"
                            wire:model.lazy="province_code" id="province_code">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinces as $province)
                                <option value="{{ $province['code'] }}">{{ $province['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('province_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="city_code" class="block text-sm font-medium text-gray-700">Kota <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('city_code', e ? e : '');
                            }
                        });"
                            wire:model.lazy="city_code" id="city_code">
                            <option value="">-- Pilih Kota --</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city['code'] }}">{{ $city['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('city_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="district_code" class="block text-sm font-medium text-gray-700">Kecamatan <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('district_code', e ? e : '');
                            }
                        });"
                            wire:model.lazy="district_code" id="district_code">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district['code'] }}">{{ $district['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('district_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="sub_district_code" class="block text-sm font-medium text-gray-700">Kelurahan <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('sub_district_code', e ? e : '');
                            }
                        });"
                            wire:model.lazy="sub_district_code" id="sub_district_code">
                            <option value="">-- Pilih Kelurahan --</option>
                            @foreach ($subDistricts as $subDistrict)
                                <option value="{{ $subDistrict['code'] }}">{{ $subDistrict['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('sub_district_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="md:col-span-2 mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span
                            class="text-red-600">*</span></label>
                    <textarea id="address" wire:model.defer="address" placeholder="Contoh : Jl. Raya No. 123"
                        class="mt-1 form-control"></textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="rt_code" class="block text-sm font-medium text-gray-700">RT</label>
                    <input autocomplete="false" id="rt_code" type="text" wire:model.defer="rt_code"
                        placeholder="Contoh : 001" class="mt-1 form-control">
                </div>
                <div class="mb-4">
                    <label for="rw_code" class="block text-sm font-medium text-gray-700">RW</label>
                    <input autocomplete="false" id="rw_code" type="text" wire:model.defer="rw_code"
                        placeholder="Contoh : 001" class="mt-1 form-control">
                </div>
                <div class="md:col-span-2 mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password @if (!$data_id)
                            <span class="text-red-600">*</span>
                        @else
                        @endif
                    </label>
                    <div x-data="{ show: false }" class="relative">
                        <input :type="show ? 'text' : 'password'" id="password" wire:model.defer="password"
                            placeholder="Contoh : 12345678"
                            class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 pr-10">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500" tabindex="-1">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="{{ $profile || $profile_old ? null : 'md:col-span-2' }} mb-4">
                    <label class="block text-sm font-medium text-gray-700">Profile</label>

                    <input type="file" wire:model.live="profile"
                        class="block text-sm text-gray-500 w-full
                                           file:px-2 file:py-1 file:rounded-md
                                           file:border file:border-gray-300
                                           file:text-xs file:font-medium
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100"
                        accept="image/*" />
                    <div wire:loading wire:target="profile" class="text-sm text-gray-500 mt-1">
                        Uploading profile...
                    </div>
                    @error('profile')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @if ($profile)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Preview
                            Profile:</label>
                        <img src="{{ $profile->temporaryUrl() }}" alt="Preview Profile"
                            class="h-20 w-auto rounded border shadow" />
                    </div>
                @else
                    @if ($profile_old)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Preview
                                Profile:</label>
                            <img src="{{ asset('storage/' . $profile_old) }}" alt="Preview Profile"
                                class="h-20 w-auto rounded border shadow" />
                        </div>
                    @endif
                @endif
                <div class="mb-4">
                    <label for="identity_card" class="block text-sm font-medium text-gray-700">NIK </label>
                    <input id="identity_card" type="identity_card" wire:model.defer="identity_card"
                        placeholder="Contoh : 12345678" class="mt-1 form-control">
                </div>
                <div class="mb-4">
                    <label for="identity_card" class="block text-sm font-medium text-gray-700">Golongan Darah </label>
                    <select class="mt-1 form-control" wire:model='blood_group'>
                        <option value="">Pilih Golongan Darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                        <option value="Tidak Tahu">Tidak Tahu</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="administrative_gender" class="block text-sm font-medium text-gray-700">Gender <span
                            class="text-red-600">*</span></label>
                    <select id="administrative_gender" wire:model.defer="administrative_gender"
                        class="mt-1 form-control">
                        <option value="">Pilih Gender</option>
                        @foreach ($administrativeGenderDetails as $administrativeGenderDetail)
                            <option value="{{ $administrativeGenderDetail['code'] }}">
                                {{ $administrativeGenderDetail['display'] }}</option>
                        @endforeach
                    </select>
                    @error('administrative_gender')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="marital_status" class="block text-sm font-medium text-gray-700">Status
                        Perkawinan</label>
                    <select id="marital_status" wire:model.defer="marital_status" class="mt-1 form-control">
                        <option value="">Pilih Status Perkawinan</option>
                        @foreach ($maritalStatusDetails as $maritalStatusDetail)
                            <option value="{{ $maritalStatusDetail['code'] }}">
                                {{ $maritalStatusDetail['display_ind'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span
                            class="text-red-600">*</span></label>
                    <input id="birth_date" type="date" wire:model.defer="birth_date"
                        placeholder="Contoh : Jakarta" class="mt-1 form-control">
                    @error('birth_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="deceased_date" class="block text-sm font-medium text-gray-700">Tanggal
                        Kematian</label>
                    <input id="deceased_date" type="date" wire:model.defer="deceased_date"
                        placeholder="Contoh : Jakarta" class="mt-1 form-control">
                    @error('deceased_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <hr class="my-3">
            <div class="mb-4 md:col-span-2">
                <label for="">Insentif Pegawai</label>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <label for="type_incentive_nurse" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Perawat </label>
                    <select id="type_incentive_nurse" wire:model.lazy="type_incentive_nurse"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_nurse')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_nurse" class="block text-sm font-medium text-gray-700">Insentif Perawat /
                        Terapis </label>
                    @if ($type_incentive_nurse == 'rupiah')
                        <input id="incentive_nurse" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_nurse" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_nurse" type="number" wire:model.lazy="incentive_nurse"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_nurse')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="type_incentive_pharmacy" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Apoteker </label>
                    <select id="type_incentive_pharmacy" wire:model.lazy="type_incentive_pharmacy"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_pharmacy')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_pharmacy" class="block text-sm font-medium text-gray-700">Insentif Apoteker
                    </label>
                    @if ($type_incentive_pharmacy == 'rupiah')
                        <input id="incentive_pharmacy" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_pharmacy" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_pharmacy" type="number" wire:model.lazy="incentive_pharmacy"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_pharmacy')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type_incentive_cashier" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Kasir </label>
                    <select id="type_incentive_cashier" wire:model.lazy="type_incentive_cashier"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_cashier')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_cashier" class="block text-sm font-medium text-gray-700">Insentif Kasir
                    </label>
                    @if ($type_incentive_cashier == 'rupiah')
                        <input id="incentive_cashier" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_cashier" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_cashier" type="number" wire:model.lazy="incentive_cashier"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_cashier')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
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
                class="px-4 py-2 bg-[{{$companyData->color_primary}}] hover:bg-[{{$companyData->color_primary}}] text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>


<div wire:ignore.self id="modal-price"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 750px;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Insentif</h2>
            </div>
            <button wire:click="closeModalPrice()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 500px;">
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="mb-4">
                    <label for="type_incentive_doctor" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Dokter </label>
                    <select id="type_incentive_doctor" wire:model.lazy="type_incentive_doctor"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_doctor')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_doctor" class="block text-sm font-medium text-gray-700">Insentif Dokter
                    </label>
                    @if ($type_incentive_doctor == 'rupiah')
                        <input id="incentive_doctor" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_doctor" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_doctor" type="number" wire:model.lazy="incentive_doctor"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_doctor')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="type_incentive_nurse" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Perawat </label>
                    <select id="type_incentive_nurse" wire:model.lazy="type_incentive_nurse"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_nurse')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_nurse" class="block text-sm font-medium text-gray-700">Insentif Perawat /
                        Terapis </label>
                    @if ($type_incentive_nurse == 'rupiah')
                        <input id="incentive_nurse" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_nurse" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_nurse" type="number" wire:model.lazy="incentive_nurse"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_nurse')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="type_incentive_pharmacy" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Apoteker </label>
                    <select id="type_incentive_pharmacy" wire:model.lazy="type_incentive_pharmacy"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_pharmacy')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_pharmacy" class="block text-sm font-medium text-gray-700">Insentif Apoteker
                    </label>
                    @if ($type_incentive_pharmacy == 'rupiah')
                        <input id="incentive_pharmacy" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_pharmacy" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_pharmacy" type="number" wire:model.lazy="incentive_pharmacy"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_pharmacy')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="type_incentive_cashier" class="block text-sm font-medium text-gray-700">Tipe Insentif
                        Kasir </label>
                    <select id="type_incentive_cashier" wire:model.lazy="type_incentive_cashier"
                        class="mt-1 form-control">
                        <option value="rupiah">Rupiah</option>
                        <option value="persen">Persen</option>
                    </select>
                    @error('type_incentive_cashier')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="incentive_cashier" class="block text-sm font-medium text-gray-700">Insentif Kasir
                    </label>
                    @if ($type_incentive_cashier == 'rupiah')
                        <input id="incentive_cashier" onkeyup="convertToRupiah(this);" type="text"
                            wire:model.lazy="incentive_cashier" placeholder="Contoh : 100000"
                            class="mt-1 form-control">
                    @else
                        <input id="incentive_cashier" type="number" wire:model.lazy="incentive_cashier"
                            placeholder="Contoh : 10" class="mt-1 form-control">
                    @endif
                    @error('incentive_cashier')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalPrice()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitPrice'
                class="px-4 py-2 bg-[{{$companyData->color_primary}}] hover:bg-[{{$companyData->color_primary}}] text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
