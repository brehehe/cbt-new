<div>
    @include('livewire.admin.master.classmate.detail.admin-master-classmate-detail-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Data Peserta Detail</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="submit()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
    <div class="space-y-6">
        <div class="p-5 bg-white shadow rounded-lg">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">
                    Tipe <span class="text-red-600">*</span>
                </label>

                @php
                    $brandBg = "bg-[color:var(--primary)]";
                    $brandBorder = "border-[color:var(--primary)]";
                @endphp



                <div class="mt-2 flex space-x-2">
                    <button type="button" wire:click="$set('type_study', 'mahasiswa')"
                        class="px-4 py-2 rounded-md border
            {{ $type_study === 'mahasiswa' ? "$brandBg text-white $brandBorder" : 'bg-white text-gray-700 border-gray-300' }}">
                        Kelas
                    </button>

                    <button type="button" wire:click="$set('type_study', 'general')"
                        class="px-4 py-2 rounded-md border
            {{ $type_study === 'general' ? "$brandBg text-white $brandBorder" : 'bg-white text-gray-700 border-gray-300' }}">
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
        <div class="p-5 bg-white shadow rounded-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <div class="flex items-center">
                <div class="flex flex-col mb-2 sm:mb-0">
                    <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                        Data Mahasiswa
                    </h1>
                    <div class="flex items-center mt-2 text-sm text-gray-500">
                        <span class="mr-2">Tampilkan</span>
                        <select class="form-control-sm w-20 py-1 px-2 border-gray-300 rounded text-sm" wire:model.live='perPage'>
                            <option value="8">8</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="ml-2">data per halaman</span>
                    </div>
                </div>
                </div>
                <div class="flex items-center w-full sm:w-auto gap-2">
                    <div class="relative w-full sm:w-64">
                        <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                            wire:model.live='search'>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button wire:click="openModalStudent()" class="mt-1 px-3 py-2 btn btn-warning">
                        Tambah
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th class="w-1 center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classmateStudents as $index => $result)
                            <tr>
                                <td class="center">{{ ($classmateStudents->currentPage() - 1) * $classmateStudents->perPage() + $loop->iteration }}</td>
                                <td>{{ $result?->user?->userDetail?->nim }}</td>
                                <td>{{ $result?->user?->name }}</td>
                                <td>{{ $result?->user?->email }}</td>
                                <td class="center">
                                    <div class="flex items-center">
                                        <button
                                            class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                            wire:click="confirmDelete('{{ $result->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
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
            
            <div class="mt-4">
                {{ $classmateStudents->links() }}
            </div>
        </div>
    </div>
</div>