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
                <h2 class="text-xl font-semibold text-gray-800">Modal Pasien</h2>
            </div>
            <button wire:click="closeModal('modal')"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 500px;">
            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Pasien <span
                            class="text-red-600">*</span></label>
                    <input autocomplete="false" id="name" type="text" wire:model.defer="name"
                        placeholder="Contoh : Admin" class="mt-1 form-control">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username <span class="text-red-600">*</span></label>
                    <input autocomplete="false" id="username" type="text" wire:model.defer="username" placeholder="Contoh : Admin" class="mt-1 form-control">
                    @error('username')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div> --}}
                <div class="mb-4">
                    <label for="identity_card" class="block text-sm font-medium text-gray-700">NIK <span
                            class="text-red-600">*</span></label>
                    <input autocomplete="false" id="identity_card" type="text" wire:model.defer="identity_card"
                        placeholder="Contoh : 12345678" class="mt-1 form-control">
                    @error('identity_card')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email <span
                            class="text-red-600">*</span></label>
                    <input autocomplete="false" id="email" type="email" wire:model.defer="email"
                        placeholder="Contoh : admin@gmail.com" class="mt-1 form-control">
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon <span
                            class="text-red-600">*</span></label>
                    <input autocomplete="false" id="phone" type="number" wire:model.defer="phone"
                        placeholder="Contoh : 081234567890" class="mt-1 form-control">
                    @error('phone')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4 md:col-span-2">
                    <label for="user_detail" class="block text-sm font-medium text-gray-700">Pasien Referensi</label>
                    @if ($data_id)
                        <input autocomplete="false" id="user_detail" type="text" wire:model.defer="user_detail"
                            placeholder="Contoh : Admin (Jalan Jalan )" class="mt-1 form-control" disabled>
                    @else
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <input autocomplete="false" id="user_detail" type="text" wire:model.defer="user_detail"
                                placeholder="Contoh : Admin" disabled class="form-control rounded-r-none">
                            <span wire:click='openModalUser'
                                class="cursor-pointer inline-flex items-center rounded-r-md border border-r-0 border-gray-300 bg-gray-100 px-3 text-gray-500 text-sm">
                                <i class="fa-solid fa-search"></i>
                            </span>
                        </div>
                    @endif
                    @error('user_detail')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
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
                <div class="md:col-span-2 mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat <span
                            class="text-red-600">*</span></label>
                    <textarea id="address" wire:model.defer="address" placeholder="Contoh : Jl. Raya No. 123"
                        class="mt-1 form-control"></textarea>
                    @error('address')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2 mb-4">
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode Pos <span
                            class="text-red-600">*</span></label>
                    <input id="postal_code" type="text" wire:model.defer="postal_code"
                        placeholder="Contoh : 12345" class="mt-1 form-control">
                    @error('postal_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="rt_code" class="block text-sm font-medium text-gray-700">RT <span
                            class="text-red-600">*</span></label>
                    <input id="rt_code" type="text" wire:model.defer="rt_code" placeholder="Contoh : 01"
                        class="mt-1 form-control">
                    @error('rt_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="rw_code" class="block text-sm font-medium text-gray-700">RW <span
                            class="text-red-600">*</span></label>
                    <input id="rw_code" type="text" wire:model.defer="rw_code" placeholder="Contoh : 02"
                        class="mt-1 form-control">
                    @error('rw_code')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="blood_group" class="block text-sm font-medium text-gray-700">Golongan Darah </label>
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
                    <label for="marital_status" class="block text-sm font-medium text-gray-700">Status Perkawinan
                        <span class="text-red-600">*</span></label>
                    <select id="marital_status" wire:model.defer="marital_status" class="mt-1 form-control">
                        <option value="">Pilih Status Perkawinan</option>
                        @foreach ($maritalStatusDetails as $maritalStatusDetail)
                            <option value="{{ $maritalStatusDetail['code'] }}">
                                {{ $maritalStatusDetail['display_ind'] }}</option>
                        @endforeach
                    </select>
                    @error('marital_status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="birth_date" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span
                            class="text-red-600">*</span></label>
                    <input autocomplete="false" id="birth_date" type="date" wire:model.defer="birth_date"
                        placeholder="Contoh : Jakarta" class="mt-1 form-control">
                    @error('birth_date')
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
            <button wire:click='submit()'
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
<div wire:ignore.self id="modal-user"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl max-w-full w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-none">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal User</h2>
            </div>
            <button wire:click="closeModalUser()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <div class="px-6 py-4 text-gray-600 space-y-4 overflow-y-auto flex-grow">
            <!-- Button Produk Baru dan Lama -->
            <!-- Search and Filter Section -->
            <div class="flex gap-4 mb-4">
                <select wire:model.live='perPagePatient'
                    class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
                <div class="relative flex-1">
                    <input autocomplete="false" type="text" wire:model.live='searchUser'
                        placeholder="Cari Pasien..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto scrollbar-custom" style="max-height: 60vh;">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Nomer Rekam Medis</th>
                            <th>Identity Card</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            {{-- <th>Role</th> --}}
                            {{-- <th>Username</th> --}}
                            {{-- <th>Email</th> --}}
                            {{-- <th>Username</th> --}}
                            <th class="w-1 center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td class="center">{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->companyRoles()->where('company_id', Auth::user()->company_id)->first()->medical_record_number }}
                                </td>
                                <td>{{ $user->userDetail->identity_card ?? '-' }}</td>
                                <td>{{ $user->name ?? '-' }}</td>
                                <td>{{ $user->userDetail->address ?? '-' }}</td>
                                {{-- <td>
                                @if ($user->companyRoles()->where('company_id', Auth::user()->company_id)->first())
                                    {{ $user->companyRoles()->where('company_id', Auth::user()->company_id)->first()->role->name }}
                                @else
                                    Tidak Ada Role
                                @endif
                            {{-- <td>
                                @if ($user->companyRoles()->where('company_id', Auth::user()->company_id)->first()->role->name)
                                    {{ $user->companyRoles()->where('company_id', Auth::user()->company_id)->first()->role->name }}
                                @else
                                    Tidak Ada Role
                                @endif
                            </td> --}}
                                {{-- <td>{{ $user->username ?? '-' }}</td> --}}
                                {{-- <td>{{ $user->email ?? '-' }}</td> --}}
                                {{-- <td>{{ $user->phone ?? '-' }}</td> --}}
                                <td class="center">
                                    <div class="flex items-center">
                                        <button
                                            class="btn btn-icon text-yellow-600 hover:text-yellow-800 transition-colors edit-btn"
                                            wire:click="getUser('{{ $user->id }}')">
                                            <i class="fa-solid fa-eye text-sm"></i>
                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg> --}}
                                        </button>
                                        {{-- <button class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn" wire:click="confirmDelete('{{ $patient->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex items-center justify-between border-t pt-4 mt-4">
                <div class="text-sm text-gray-500">
                    Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }}
                    data
                </div>

                {{ $users->links('vendor.livewire.paginate-pos') }}
            </div>
        </div>
    </div>
</div>
