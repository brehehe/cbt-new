<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Pasien Konsultasi</h1>
            </div>
        </div>
    </div>
    <div class="p-6 bg-white shadow rounded-lg mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Pasien</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $user->name }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $user->email }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $user->phone }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                <p class="mt-1 text-gray-900 font-semibold">
                    {{ $user->userDetail->birth_date ? Carbon\Carbon::parse($user->userDetail->birth_date)->translatedFormat('d F Y') : 'Tidak diketahui' }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ Str::title($user->userDetail->administrative_gender) }}
                </p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status Perkawinan</label>
                <p class="mt-1 text-gray-900 font-semibold">
                    {{ $user->userDetail->marital_status ? $user->userDetail->marital_status : 'Tidak diketahui' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <p class="mt-1 text-gray-900 font-semibold">
                    {{ $user->userDetail->address ? $user->userDetail->address : 'Tidak diketahui' }}</p>
            </div>
            @php
                $birthDate = \Carbon\Carbon::parse($user->userDetail->birth_date);
                $now = \Carbon\Carbon::now();
                $diff = $birthDate->diff($now);
            @endphp

            <div>
                <label class="block text-sm font-medium text-gray-700">Umur</label>
                <p class="mt-1 text-gray-900 font-semibold">
                    {{ $diff->y }} Tahun {{ $diff->m }} Bulan {{ $diff->d }} Hari
                </p>
            </div>

        </div>
    </div>
    <div class="mb-4">
        <div class="overflow-x-auto  w-full">
            <nav class="flex w-full gap-2 px-2" aria-label="Tabs">
                @foreach ($get_tabs as $get_tab)
                    <button wire:click="changeTab('{{ $get_tab }}')"
                        class="text-center px-2 py-2 text-sm font-medium transition-all duration-300 cursor-pointer rounded-2xl
                {{ $tab === $get_tab ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-black' }}">
                        {{ Str::title(Str::replace('-', ' ', $get_tab)) }}
                    </button>
                @endforeach
            </nav>
        </div>
    </div>
    @if ($tab == 'diagnosa')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Subjective</th>
                            <th>Objective</th>
                            <th>Assessment</th>
                            <th>Plan</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diagnosas as $index => $diagnosa)
                            <tr>
                                <td class="center">{{ $diagnosas->firstItem() + $index }}</td>
                                <td>{{ $diagnosa->subjective ?? '-' }}</td>
                                <td>{{ $diagnosa->objective ?? '-' }}</td>
                                <td>{{ $diagnosa->assessment ?? '-' }}</td>
                                <td>{{ $diagnosa->plan ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($diagnosa->created_at)->translatedFormat('d F Y') ?? '-' }}
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

            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $diagnosas->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $diagnosas->lastItem() }}</span> dari <span
                            class="font-medium">{{ $diagnosas->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            {{ $diagnosas->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'alergi')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Alergi Obat</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alergis as $index => $alergi)
                            <tr>
                                <td class="center">{{ $alergis->firstItem() + $index }}</td>
                                <td>{{ $alergi->description ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($alergi->created_at)->translatedFormat('d F Y') ?? '-' }}
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

            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $alergis->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $alergis->lastItem() }}</span> dari <span
                            class="font-medium">{{ $alergis->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            {{ $alergis->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'icd-10')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Kode ICD-10</th>
                            <th>Display</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($icd10s as $index => $icd10)
                            <tr>
                                <td class="center">{{ $icd10s->firstItem() + $index }}</td>
                                <td>{{ $icd10->icd10->code ?? '-' }}</td>
                                <td>{{ $icd10->icd10->display ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($icd10->created_at)->translatedFormat('d F Y') ?? '-' }}
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
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $icd10s->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $icd10s->lastItem() }}</span> dari <span
                            class="font-medium">{{ $icd10s->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $icd10s->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'icd-9')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Kode ICD-10</th>
                            <th>Display</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($icd9s as $index => $icd9)
                            <tr>
                                <td class="center">{{ $icd9s->firstItem() + $index }}</td>
                                <td>{{ $icd9->icd9->code ?? '-' }}</td>
                                <td>{{ $icd9->icd9->display ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($icd9->created_at)->translatedFormat('d F Y') ?? '-' }}
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
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $icd9s->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $icd9s->lastItem() }}</span> dari <span
                            class="font-medium">{{ $icd9s->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $icd9s->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'tindakan')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Sku Number</th>
                            <th>Product Name</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($actions as $index => $action)
                            <tr>
                                <td class="center">{{ $actions->firstItem() + $index }}</td>
                                <td>{{ $action?->product?->sku_number ?? '-' }}</td>
                                <td>{{ $action?->product?->name ?? '-' }}</td>
                                <td>Rp{{ number_format($action->price, 0, ',', '.') ?? 0 }}</td>
                                <td>{{ $action->quantity ?? '-' }}</td>
                                <td>Rp{{ number_format($action->sub_total_price, 0, ',', '.') ?? 0 }}</td>
                                <td>{{ Carbon\Carbon::parse($action->created_at)->translatedFormat('d F Y') ?? '-' }}
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
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $actions->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $actions->lastItem() }}</span> dari <span
                            class="font-medium">{{ $actions->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $actions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'bukti-tindakan')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Deskripsi</th>
                            <th>Foto Sebelum</th>
                            <th>Foto Setelah</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($proofs as $index => $proof)
                            <tr>
                                <td class="center">{{ $proofs->firstItem() + $index }}</td>
                                <td>{{ $proof->description ?? '-' }}</td>
                                <td>
                                    <img src="{{ $proof->before_photo ? asset('storage/' . $proof->before_photo) : asset('asset/img/No-Image-Placeholder.svg.png') }}"
                                        alt="Sebelum Tindakan" style="width: 150px; height: 150px;"
                                        class=" object-cover rounded-lg cursor-pointer" data-easyzoom="true"
                                        id="before-image-{{ $proof->id }}">
                                </td>
                                <td>
                                    <img src="{{ $proof->after_photo ? asset('storage/' . $proof->after_photo) : asset('asset/img/No-Image-Placeholder.svg.png') }}"
                                        alt="Setelah Tindakan" style="width: 150px; height: 150px;"
                                        class=" object-cover rounded-lg cursor-pointer" data-easyzoom="true"
                                        id="after-image-{{ $proof->id }}">
                                </td>
                                <td>{{ Carbon\Carbon::parse($proof->created_at)->translatedFormat('d F Y') ?? '-' }}
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
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $proofs->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $proofs->lastItem() }}</span> dari <span
                            class="font-medium">{{ $proofs->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $proofs->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'jadwal-kontrol')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Tanggal Kontrol</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($userControls as $index => $userControl)
                            <tr>
                                <td class="center">{{ $userControls->firstItem() + $index }}</td>
                                <td>{{ $userControl->poly->name ?? '-' }}</td>
                                <td>{{ $userControl->doctor->name ?? '-' }}</td>
                                <td>{{ $userControl->date ? Carbon\Carbon::parse($userControl->date)->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td>{{ $userControl->description ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($userControl->created_at)->translatedFormat('d F Y') ?? '-' }}
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
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $userControls->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $userControls->lastItem() }}</span> dari <span
                            class="font-medium">{{ $userControls->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $userControls->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'rujukan')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Tanggal Kontrol</th>
                            <th>Rumah Sakit</th>
                            <th>Dokter</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($references as $index => $reference)
                            <tr>
                                <td class="center">{{ $references->firstItem() + $index }}</td>
                                <td>{{ $reference->date ? Carbon\Carbon::parse($reference->date)->translatedFormat('d F Y') : '-' }}
                                </td>
                                <td>{{ $reference->hospital ?? '-' }}</td>
                                <td>{{ $reference->doctor_name ?? '-' }}</td>
                                <td>{{ $reference->description ?? '-' }}</td>
                                <td>{{ Carbon\Carbon::parse($reference->created_at)->translatedFormat('d F Y') ?? '-' }}
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
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $references->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $references->lastItem() }}</span> dari <span
                            class="font-medium">{{ $references->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $references->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'resep')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="border-b">
                            <th>Produk</th>
                            <th>Opsi Dosis</th>
                            <th>Dosis Dokter</th>
                            <th>Total Gramasi</th>
                            <th>Dosis Obat</th>
                            <th class="center">Qty</th>
                            <th class="right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recipes as $key_recipe => $recipe)
                            <tr>
                                <td colspan="8" class="py-3 px-2" style="border-top: 3px solid #155dfc;">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-blue-600"
                                            style="width: {{ $recipe->medicineType->is_single ? '10%' : '15%' }};">/R</span>
                                        <span class="font-medium"
                                            style="width: 100%">{{ $recipe->medicineType->name ?? '' }}</span>
                                        <span class="font-medium" style="width: 100%">Rp
                                            {{ number_format($recipe->price_service_one ?? 0, 0, ',', '.') }}</span>
                                        <span class="font-medium"
                                            style="width: 100%">{{ $recipe->numero_recipe }}</span>
                                        @if (!$recipe->medicineType->is_single)
                                            <span class="font-medium"
                                                style="width: 100%">{{ $recipe->product->name ?? '' }}</span>
                                            <span class="font-medium" style="width: 100%">Rp
                                                {{ number_format($recipe->sub_total_price ?? 0, 0, ',', '.') }}</span>
                                        @else
                                            <span class="font-medium" style="width: 100%"></span>
                                            <span class="font-medium" style="width: 100%"></span>
                                        @endif
                                        <span class="font-medium"
                                            style="width: 100%">{{ Carbon\Carbon::parse($recipe->created_at)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" class="py-3 px-2">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="font-medium text-blue-600">{{ $recipe->description ?? '-' }}</span>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($recipe->transactionDetail as $detail)
                                <tr>
                                    @if (!$recipe->medicineType->is_single)
                                        <td class="py-3 px-2">
                                            <p class="font-medium">{{ $detail->product->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500">
                                                @Rp{{ number_format($detail->price, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="py-3 px-2">{{ Str::title($detail->type ?? '-') }}</td>
                                        <td class="py-3 px-2">{{ $detail->dosage_doctor ?? '-' }}</td>
                                        <td class="py-3 px-2">{{ $detail->doctor_dosage_gram ?? '-' }}</td>
                                        <td class="py-3 px-2">{{ $detail->dosage_drug ?? '-' }}</td>
                                        <td class="center py-3 px-2">{{ $detail->quantity ?? '-' }}</td>
                                        <td class="right py-3 px-2">Rp
                                            {{ number_format($detail->sub_total_price, 0, ',', '.') }}</td>
                                    @else
                                        <td colspan="5" class="py-3 px-2">
                                            <p class="font-medium">{{ $detail->product->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500">
                                                @Rp{{ number_format($detail->price, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="center py-3 px-2">{{ $detail->quantity ?? '-' }}</td>
                                        <td class="right py-3 px-2">Rp
                                            {{ number_format($detail->sub_total_price, 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $recipes->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $recipes->lastItem() }}</span> dari <span
                            class="font-medium">{{ $recipes->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $recipes->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'penjualan')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="border-b">
                            <th class="w-1 center">No</th>
                            <th>Sku Number</th>
                            <th>Produk</th>
                            <th>Tipe Produk</th>
                            <th>Harga</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key_product => $product)
                            <tr>
                                <td class="center">{{ $products->firstItem() + $key_product }}</td>
                                <td>{{ $product->product->sku_number ?? '-' }}</td>
                                <td>{{ $product->product->name ?? '-' }}</td>
                                <td>{{ $product->product->productType->name ?? '-' }}</td>
                                <td>Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $product->quantity ?? '-' }}</td>
                                <td>Rp {{ number_format($product->total ?? 0, 0, ',', '.') }}</td>
                                <td>{{ Carbon\Carbon::parse($product->created_at)->translatedFormat('d F Y') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $products->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $products->lastItem() }}</span> dari <span
                            class="font-medium">{{ $products->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $products->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'terima-bayar')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr class="border-b">
                            <th class="w-1 center">No</th>
                            <th>Nama Metode Bayar</th>
                            <th>Biaya Admin</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key_payment => $payment)
                            <tr>
                                <td class="center">{{ $payments->firstItem() + $key_payment }}</td>
                                <td>{{ $payment->paymentMethod->name ?? '-' }}</td>
                                <td>Rp {{ number_format($payment->admin_fee, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($payment->payment_real, 0, ',', '.') }}</td>
                                <td>{{ Carbon\Carbon::parse($payment->created_at)->translatedFormat('d F Y') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $payments->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $payments->lastItem() }}</span> dari <span
                            class="font-medium">{{ $payments->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $payments->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($tab == 'laba-rugi')
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex items-center">
                <span class="text-sm text-gray-700 mr-2">Tampil</span>
                <select class="mt-1 form-control" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-700 ml-2">data</span>
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Produk</th>
                            <th>Tipe Produk</th>
                            <th>Sub Total</th>
                            <th>Hpp Total</th>
                            <th>Quantity</th>
                            <th>Profit</th>
                            <th>Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($profits as $profit)
                            <tr>
                                <td class="center">
                                    {{ $loop->iteration + ($profits->currentPage() - 1) * $profits->perPage() }}</td>
                                <td>
                                    <p class="font-medium">{{ $profit->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $profit->product->sku_number }}</p>
                                </td>
                                <td>{{ $profit->product->productType->name }}</td>
                                <td>Rp{{ number_format($profit->total_penjualan, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($profit->total_hpp_total, 0, ',', '.') }}</td>
                                <td>{{ $profit->total_quantity }}</td>
                                <td>Rp{{ number_format($profit->total_profit, 0, ',', '.') }}</td>
                                <td>{{ number_format($profit->average_margin, 0, ',', '.') }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $profits->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $profits->lastItem() }}</span> dari <span
                            class="font-medium">{{ $profits->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                            aria-label="Pagination">
                            {{ $profits->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
