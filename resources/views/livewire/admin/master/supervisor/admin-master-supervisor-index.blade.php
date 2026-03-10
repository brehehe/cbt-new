<div>
    @include('livewire.admin.master.supervisor.admin-master-supervisor-modal-new')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Manajemen Data Pengawas</h1>
                <p class="text-gray-600">Kelola data pengawas dalam sistem CBT</p>
            </div>
            <div class="flex gap-2">
                <!-- Download Template Button -->
                <button wire:click="downloadTemplate()"
                    class="btn btn-success">
                    <i class="fa-solid fa-file-download mr-1"></i>
                    Template
                </button>

                <!-- Import Button -->
                <label class="btn btn-warning cursor-pointer">
                    <i class="fa-solid fa-file-import mr-1"></i>
                    Import
                    <input type="file" wire:model="importFile" accept=".xlsx,.xls" wire:change="import" class="hidden" />
                </label>

                <!-- Add Button -->
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pengawas
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 items-end">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live="search" placeholder="ID Pengawas, Nama, Email..."
                    class="form-control mt-1">
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pengawas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Username
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID/NIP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        @if(Auth::user()->HasRole('Admin'))
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Key
                            </th>
                        @endif
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($admins as $supervisor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($supervisor->profile)
                                            <img src="{{ asset('storage/' . $supervisor->profile) }}"
                                                alt="Foto {{ $supervisor->name }}"
                                                class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-purple-700">
                                                    {{ substr($supervisor->name, 0, 2) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $supervisor->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $supervisor->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $supervisor->username ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $supervisor->userDetail->supervisor_id ?? ($supervisor->userDetail->employee_id ?? '-') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $companyRole = $supervisor
                                        ->companyRoles()
                                        ->where('company_id', Auth::user()->company_id)
                                        ->first();
                                    $isActive = $companyRole ? $companyRole->is_active : false;
                                @endphp

                                @if ($isActive)
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            @if(Auth::user()->HasRole('Admin'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($supervisor->usrSecKey->sec_val)
                                            {{ decrypt($supervisor->usrSecKey->sec_val) }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="edit('{{ $supervisor->id }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <button
                                        class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                        wire:click="confirmDelete('{{ $supervisor->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data pengawas ditemukan.
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
