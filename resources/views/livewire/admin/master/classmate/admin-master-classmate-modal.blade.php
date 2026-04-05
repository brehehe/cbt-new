<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 100vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Peserta</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 80vh">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Tipe <span class="text-red-600">*</span>
                </label>
                @php
                    $brandBg = "bg-[{$companyData->color_primary}]";
                    $brandBorder = "border-[{$companyData->color_primary}]";
                @endphp

                <div class="mt-2 flex space-x-2">
                    <button type="button" wire:click="setType('mahasiswa')"
                        class="px-4 py-2 rounded-md border
                            {{ $type_study === 'mahasiswa' ? $brandBg . ' text-white ' . $brandBorder : 'bg-white text-gray-700 border-gray-300' }}">
                        Kelas
                    </button>

                    @php
                        $brandBg2 = "bg-[{$companyData->color_primary}]";
                        $brandBorder2 = "border-[{$companyData->color_primary}]";
                    @endphp

                    <button type="button" wire:click="setType('general')"
                        class="px-4 py-2 rounded-md border
                            {{ $type_study === 'general' ? "$brandBg2 text-white $brandBorder2" : 'bg-white text-gray-700 border-gray-300' }}">
                        General
                    </button>
                </div>

                @error('type_study')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if ($type_study == 'mahasiswa')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="name" wire:model.defer="name" class="mt-1 form-control"
                        placeholder="Nama Peserta" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Dosen <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                                dropdownParent: 'body',
                                allowClear: true,
                                plugins: ['clear_button'],
                                onChange: function(e) {
                                    @this.set('user_id', e ? e : '');
                                }
                            });" wire:model.lazy="user_id" id="user_id">
                            <option value="">-- Pilih Dosen --</option>
                            @foreach ($users as $key_user => $user)
                                <option value="{{ $key_user }}">{{ $user }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('user_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" wire:model.defer="description" placeholder="Deskripsi Peserta"
                        class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @elseif($type_study == 'general')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="name" wire:model.defer="name" class="mt-1 form-control"
                        placeholder="Nama Peserta" />
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea id="description" wire:model.defer="description" placeholder="Deskripsi Peserta"
                        class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-4 py-2 bg-[{{ $companyData->color_primary ?? '#f58634' }}] hover:bg-[{{ $companyData->color_primary ?? '#f58634' }}] text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>