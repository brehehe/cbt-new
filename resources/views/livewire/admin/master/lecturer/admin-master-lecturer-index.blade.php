<div>
    @include('livewire.admin.master.lecturer.admin-master-lecturer-modal-new')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Manajemen Data Dosen</h1>
                <p class="text-gray-600">Kelola data dosen dalam sistem CBT</p>
            </div>
            <div>
                <button wire:click="openModal()" class="btn btn-primary">
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
        <div class="grid grid-cols-5 md:grid-cols-5 gap-4 items-end">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live="search" placeholder="ID Dosen, NIDN, Nama, Email..."
                    class="form-control mt-1">
            </div>

            <!-- Faculty Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fakultas</label>
                <select wire:model.live="facultyFilter" class="form-control mt-1">
                    <option value="">Semua Fakultas</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty }}">{{ $faculty }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Department Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                <select wire:model.live="departmentFilter" class="form-control mt-1">
                    <option value="">Semua Jurusan</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}">{{ $department }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Position Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                <select wire:model.live="positionFilter" class="form-control mt-1">
                    <option value="">Semua Jabatan</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position }}">{{ $position }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Per Halaman</label>
                <select wire:model.live="perPage" class="form-control mt-1">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dosen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID/NIDN
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jurusan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jabatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Spesialisasi
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
                                <div class="text-sm text-gray-500">NIDN:
                                    {{ $lecturer->userDetail->lecturer_nidn ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $lecturer->userDetail->lecturer_department ?? '-' }}</div>
                                <div class="text-sm text-gray-500">{{ $lecturer->userDetail->lecturer_faculty ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $lecturer->userDetail->lecturer_position ?? '-' }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $lecturer->userDetail->lecturer_education_level ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $lecturer->userDetail->lecturer_specialization ?? '-' }}
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
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $lecturers->links() }}
        </div>
    </div>
</div>
