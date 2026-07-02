<div>
    @include('livewire.admin.master.student.admin-master-student-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Manajemen Data Mahasiswa</h1>
                <p class="text-gray-600">Kelola data mahasiswa dalam sistem CBT</p>
            </div>
            <div class="flex gap-2">
                <!-- Template Dropdown -->
                <div x-data="{ openTemplate: false }" class="relative">
                    <button type="button" @click="openTemplate = !openTemplate" @click.away="openTemplate = false"
                        class="btn btn-success">
                        <i class="fa-solid fa-file-download mr-1"></i>
                        Template
                        <i class="fa-solid fa-chevron-down ml-2"></i>
                    </button>
                    <div x-show="openTemplate" x-transition
                        class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-20">
                        @if(in_array(auth()->user()->company->is_pmb, ['non_pmb', 'all']))
                            <button type="button" wire:click="downloadTemplate()"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Template Mahasiswa
                            </button>
                        @endif
                        @if(in_array(auth()->user()->company->is_pmb, ['pmb', 'all']))
                            <button type="button" wire:click="downloadTemplateGeneral()"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Template {{ auth()->user()->company->is_pmb === 'pmb' ? 'PMB' : 'PMB / General' }}
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Import Dropdown -->
                <div x-data="{ openImport: false }" class="relative">
                    <button type="button" @click="openImport = !openImport" @click.away="openImport = false"
                        class="btn btn-warning">
                        <i class="fa-solid fa-file-import mr-1"></i>
                        Import
                        <i class="fa-solid fa-chevron-down ml-2"></i>
                    </button>
                    <div x-show="openImport" x-transition
                        class="absolute right-0 mt-2 w-52 bg-white border border-gray-200 rounded-lg shadow-lg z-20">
                        @if(in_array(auth()->user()->company->is_pmb, ['non_pmb', 'all']))
                            <label
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer block">
                                Import Mahasiswa
                                <input type="file" wire:model="importFileMahasiswa" accept=".xlsx,.xls"
                                    class="hidden" />
                            </label>
                        @endif
                        @if(in_array(auth()->user()->company->is_pmb, ['pmb', 'all']))
                            <label
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer block">
                                Import {{ auth()->user()->company->is_pmb === 'pmb' ? 'PMB' : 'PMB / General' }}
                                <input type="file" wire:model="importFileGeneral" accept=".xlsx,.xls"
                                    class="hidden" />
                            </label>
                        @endif
                    </div>
                </div>

                <!-- Delete Selected Button -->
                @if(count($selectedRows) > 0)
                    <button wire:click="confirmDeleteSelected" class="btn btn-error mr-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus Terpilih ({{ count($selectedRows) }})
                    </button>
                @endif

                <!-- Add Button -->
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Mahasiswa
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live="search" placeholder="NIM, Nama, Email..." class="form-control mt-1">
            </div>

            <!-- Program Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prodi</label>
                <select wire:model.live="programFilter" class="form-control mt-1">
                    <option value="">Semua Prodi</option>
                    @foreach ($studys as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="statusFilter" class="form-control mt-1">
                    <option value="">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="graduate">Lulus</option>
                    <option value="dropout">Dropout</option>
                    <option value="transfer">Pindah</option>
                    <option value="leave">Cuti</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select wire:model.live="isStudentFilter" class="form-control mt-1">
                    <option value="">Semua Kategori</option>
                    @if(in_array(auth()->user()->company->is_pmb, ['non_pmb', 'all']))
                        <option value="mahasiswa">Mahasiswa</option>
                    @endif
                    @if(in_array(auth()->user()->company->is_pmb, ['pmb', 'all']))
                        <option value="general">{{ auth()->user()->company->is_pmb === 'pmb' ? 'PMB' : 'PMB / General' }}</option>
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Entries</label>
                <select wire:model.live="perPage" class="form-control mt-1">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                            <input type="checkbox" wire:model.live="selectAll" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mahasiswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIM/Username
                        </th>
                        {{--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Password
                        </th>--}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Prodi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sesi / Ruang
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        {{--<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>--}}
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" wire:model.live="selectedRows" value="{{ $admin->id }}" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($admin->profile)
                                            <img src="{{ asset('storage/' . $admin->profile) }}" alt="Foto {{ $admin->name }}"
                                                class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-700">
                                                    {{ substr($admin->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $admin->nim ?? ($admin->username ?? '-') }}
                                </div>
                                @if ($admin->userDetail && $admin->userDetail->student_id)
                                    <div class="text-sm text-gray-500">ID: {{ $admin->userDetail->student_id }}</div>
                                @endif
                            </td>
                            {{--<td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 bg-gray-100 rounded text-xs font-mono text-gray-800">
                                    {{ $admin->decrypted_password }}
                                </span>
                            </td>--}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $admin?->study?->name ?? ($admin->userDetail->student_program ?? '-') }}
                                </div>
                                @if ($admin->userDetail && $admin->userDetail->student_major)
                                    <div class="text-sm text-gray-500">{{ $admin->userDetail->student_major }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($admin->userDetail && ($admin->userDetail->examSession || $admin->userDetail->examRoom || $admin->userDetail->exam_date))
                                    @if($admin->userDetail->examSession)
                                        <div class="text-sm text-gray-900 font-medium">Sesi: {{ $admin->userDetail->examSession->name }}</div>
                                    @endif
                                    @if($admin->userDetail->examRoom)
                                        <div class="text-sm text-gray-700">Ruang: {{ $admin->userDetail->examRoom->name }}</div>
                                    @endif
                                    @if($admin->userDetail->exam_date)
                                        <div class="text-xs text-gray-500">Tgl: {{ $admin->userDetail->exam_date->format('d/m/Y') }}</div>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($admin->userDetail)
                                    @php
                                        $status = $admin->userDetail->student_status ?? 'active';
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'graduate' => 'bg-blue-100 text-blue-800',
                                            'dropout' => 'bg-red-100 text-red-800',
                                            'transfer' => 'bg-yellow-100 text-yellow-800',
                                            'leave' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusLabels = [
                                            'active' => 'Aktif',
                                            'graduate' => 'Lulus',
                                            'dropout' => 'Dropout',
                                            'transfer' => 'Pindah',
                                            'leave' => 'Cuti',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            {{--<td>
                                @php
                                    $typeStudy = $admin->type_study;
                                    $isStudentColors =
                                        $typeStudy == 'mahasiswa'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-blue-100 text-blue-800';
                                    $isStudentLabels = $typeStudy == 'mahasiswa' ? 'Mahasiswa' : ((auth()->user()->company->is_pmb ?? false) ? 'PMB' : 'General');
                                @endphp

                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $isStudentColors }}">
                                    {{ $isStudentLabels }}
                                </span>
                            </td>--}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="edit('{{ $admin->id }}')" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <button
                                        class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                        wire:click="confirmDelete('{{ $admin->id }}')" title="Hapus Data">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data mahasiswa ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $admins->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $admins->lastItem() }}</span> dari <span
                        class="font-medium">{{ $admins->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $admins->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>