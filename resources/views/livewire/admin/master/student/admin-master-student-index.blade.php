<div>
    @include('livewire.admin.master.student.admin-master-student-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Manajemen Data Mahasiswa</h1>
                <p class="text-gray-600">Kelola data mahasiswa dalam sistem CBT</p>
            </div>
            <div>
                <button wire:click="openModal()" class="btn btn-primary">
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
        <div class="grid grid-cols-3 md:grid-cols-3 gap-4 items-end">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" wire:model.live="search" placeholder="NIM, Nama, Email..."
                    class="form-control mt-1">
            </div>

            <!-- Program Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                <select wire:model.live="programFilter" class="form-control mt-1">
                    <option value="">Semua Program</option>
                    <option value="Informatika">Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Komputer">Teknik Komputer</option>
                    <option value="Data Science">Data Science</option>
                </select>
            </div>

            <!-- Status Filter -->
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
                            Mahasiswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NIM/ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Program Studi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Semester/Angkatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fakultas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($admins as $admin)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($admin->profile)
                                            <img src="{{ asset('storage/' . $admin->profile) }}"
                                                alt="Foto {{ $admin->name }}"
                                                class="h-10 w-10 rounded-full object-cover">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
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
                                    {{ $admin->nim ?? '-' }}
                                </div>
                                @if ($admin->userDetail && $admin->userDetail->student_id)
                                    <div class="text-sm text-gray-500">ID: {{ $admin->userDetail->student_id }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $admin->userDetail->student_program ?? '-' }}
                                </div>
                                @if ($admin->userDetail && $admin->userDetail->student_major)
                                    <div class="text-sm text-gray-500">{{ $admin->userDetail->student_major }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if ($admin->userDetail && $admin->userDetail->student_semester)
                                        Semester {{ $admin->userDetail->student_semester }}
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">
                                    Angkatan: {{ $admin->userDetail->student_batch ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $admin->userDetail->student_faculty ?? '-' }}
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
        <div class="px-6 py-3 border-t border-gray-200">
            {{ $admins->links() }}
        </div>
    </div>
</div>
