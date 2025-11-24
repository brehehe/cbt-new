<div>
    @include('livewire.admin.master.lecturer.admin-master-lecturer-modal-new')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                    Manajemen Data Dosen</h1>
                <p class="text-gray-600">Kelola data dosen dalam sistem CBT</p>
            </div>
            <div>
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'btn btn-primary' : 'btn btn-warning' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Dosen
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live="search" placeholder="ID Dosen, NIDN, Nama, Email..."
                    class="form-control mt-1">
            </div>

            <!-- Department Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Prodi</label>
                <select wire:model.live="filterStudy" class="form-control mt-1">
                    <option value="">Semua Prodi</option>
                    @foreach ($getStudys as $key_study => $getStudy)
                        <option value="{{ $key_study }}">{{ $getStudy }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Per Page -->
            <!-- <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Per Halaman</label>
                <select wire:model.live="perPage" class="form-control mt-1">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div> -->
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dosen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIDN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIP
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lecturers as $lecturer)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div
                                            class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-blue-700">
                                                {{ substr($lecturer->name, 0, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $lecturer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $lecturer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lecturer->userDetail->lecturer_id ?? '-' }}</div>

                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lecturer->userDetail->lecturer_nidn ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $lecturer->userDetail->lecturer_nip ?? '-' }}
                                </div>
                            </td>
                            <td class="center">
                                <div class="flex items-center">
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="edit('{{ $lecturer->id }}')">
                                        <i class="fa-solid fa-pen-to-square"></i> <!-- atau fa-edit (versi lama) -->
                                    </button>

                                    <!-- Tombol Delete -->
                                    <button
                                        class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                        wire:click="confirmDelete('{{ $lecturer->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data dosen ditemukan.
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
                    Menampilkan <span class="font-medium">{{ $lecturers->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $lecturers->lastItem() }}</span> dari <span
                        class="font-medium">{{ $lecturers->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $lecturers->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
