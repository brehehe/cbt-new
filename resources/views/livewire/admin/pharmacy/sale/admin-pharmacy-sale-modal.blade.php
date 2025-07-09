<div wire:ignore.self id="modal" class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-4">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modal Transaksi</h2>
            </div>
            <div class="flex items-center gap-4">
                {{-- <div class="text-sm text-gray-500">
                    <span>2025-05-20 09:37:51</span>
                    <span class="mx-2">|</span>
                    <span class="font-medium">brehehe</span>
                </div> --}}
                <button wire:click="closeModal()" class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                    &times;
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 70vh; overflow-y: auto;">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tipe Transaksi</label>
                <div class="mt-1 flex gap-4">
                    <button class="{{ $type == 'non-resep' ? 'bg-blue-500 text-white' : 'bg-white-200 border border-blue-400' }} px-4 py-2 rounded-lg flex items-center gap-4" wire:click="$set('type', 'non-resep')">
                        <i class="fas fa-pills"></i>
                        <span>Non-Resep</span>
                    </button>
                    <button class="{{ $type == 'resep' ? 'bg-blue-500 text-white' : 'bg-white-200 border border-blue-400' }} px-4 py-2 rounded-lg flex items-center gap-4" wire:click="$set('type', 'resep')">
                        <i class="fas fa-prescription"></i>
                        <span>Resep</span>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Tipe Customer <span class="text-red-600">*</span></label>
                <select wire:model.lazy='type_customer' class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Pelanggan</option>
                    <option value="umum">Umum</option>
                    <option value="new">Baru</option>
                    <option value="member">Terdaftar</option>
                </select>
                @error('type_customer')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if ($type_customer == 'member')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Pilih Pasien <span class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            {{-- plugins: ['clear_button'], --}}
                            onChange: function(e) {
                                @this.set('patient_company_role_id', e ? e : '');
                            }
                        });" wire:model.live="patient_company_role_id" id="patient_company_role_id">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->user->name }} - {{ $patient->user->userDetail->address }} - {{ $patient->medical_record_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('patient_company_role_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @elseif($type_customer == 'new')
                <div class="grid grid-cols-3 gap-4">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama <span class="text-red-600">*</span></label>
                        <input type="text" wire:model.lazy='patient_name' placeholder="Nama Pasien" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('patient_name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">No. Telepon <span class="text-red-600">*</span></label>
                        <input type="number" wire:model.lazy='patient_phone' placeholder="No. Telepon Pasien" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('patient_phone')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <select wire:model.lazy='patient_gender' class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Gender</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender['code'] }}">{{ $gender['display'] }}</option>
                            @endforeach
                        </select>
                        @error('patient_gender')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4 md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700">Alamat <span class="text-red-600">*</span></label>
                        <textarea wire:model.lazy='patient_address' placeholder="Alamat Pasien" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('patient_address')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @elseif ($type_customer == 'umum')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nama <span class="text-red-600">*</span></label>
                    <input type="text" wire:model.lazy='patient_name' placeholder="Umum" disabled class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('patient_name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            @if ($type == 'resep')
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Resep <span class="text-red-600">*</span></label>
                        <input type="text" wire:model.lazy='number_recipe' placeholder="Nomor Resep" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('number_recipe')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipe Dokter <span class="text-red-600">*</span></label>
                        <select wire:model.lazy='type_doctor' class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500" style="height: 38px">
                            <option value="">Pilih Dokter</option>
                            <option value="old">Lama</option>
                            <option value="new">Baru</option>
                        </select>
                        @error('type_doctor')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @if ($type_doctor == 'old')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Pilih Dokter <span class="text-red-600">*</span></label>
                        <div wire:key="select-{{ rand() }}">
                            <select x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                {{-- plugins: ['clear_button'], --}}
                                onChange: function(e) {
                                    @this.set('doctor_id', e ? e : '');
                                }
                            });" wire:model.live="doctor_id" id="doctor_id">
                                <option value="">-- Pilih Dokter --</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }} - {{ $doctor['specialization'] }} ({{ $doctor['type'] }})</option>
                                @endforeach
                            </select>
                        </div>
                        @error('doctor_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @elseif ($type_doctor == 'new')
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Dokter <span class="text-red-600">*</span></label>
                            <input type="text" wire:model.lazy='name_doctor' placeholder="Nama Dokter" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name_doctor')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Spesialis</label>
                            <input type="text" wire:model.lazy='specialization' placeholder="Spesialis Dokter" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('specialization')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4 md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Rumah Sakit / Klinik</label>
                            <input type="text" wire:model.lazy='hospital' placeholder="Rumah Sakit / Klinik Dokter" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('hospital')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif
            @endif
            <!-- Notes -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Catatan</label>
                <textarea rows="3" placeholder="Tambahkan catatan jika diperlukan" class="mt-1 block w-full rounded-md border-gray-300 px-4 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center px-6 py-4 border-t bg-gray-50">
            <div class="text-sm text-gray-500">
                <span class="font-medium">Status:</span>
                <span class="ml-1 px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Draft</span>
            </div>
            <div class="flex gap-4">
                <button wire:click="closeModal()" wire:loading.attr="disabled" wire:target="saveTransaction" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button wire:click='saveTransaction()' wire:loading.attr="disabled" wire:target="saveTransaction" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                    <span wire:loading.remove wire:target="saveTransaction">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </span>
                    <span wire:loading wire:target="saveTransaction">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
