<div>
    @include('livewire.admin.master.student.admin-master-student-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">Mahasiswa</h1>
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

    <!-- Table Controls -->
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $index => $admin)
                        <tr>
                            <td class="center">{{ $admins->firstItem() + $index }}</td>
                            <td>
                                <div class="flex items-center space-x-3">
                                    @if ($admin->profile)
                                        <img class="w-8 h-8 rounded-full object-cover"
                                            src="{{ asset('storage/' . $admin->profile) }}" alt="{{ $admin->name }}">
                                    @else
                                        <div
                                            class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $admin->name ?? '-' }}</div>
                                        @if ($admin->userDetail && $admin->userDetail->student_id)
                                            <div class="text-sm text-gray-500">ID: {{ $admin->userDetail->student_id }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $admin->email ?? '-' }}</td>
                            <td>
                                <span class="font-mono text-sm">{{ $admin->nim ?? '-' }}</span>
                            </td>
                            <td>
                                @if ($admin->userDetail)
                                    <div>{{ $admin->userDetail->student_program ?? '-' }}</div>
                                    @if ($admin->userDetail->student_faculty)
                                        <div class="text-sm text-gray-500">{{ $admin->userDetail->student_faculty }}
                                        </div>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="center">
                                @if ($admin->userDetail && $admin->userDetail->student_semester)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Semester {{ $admin->userDetail->student_semester }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="center">
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
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$status] ?? ucfirst($status) }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        -
                                    </span>
                                @endif
                            </td>
                            <td class="center">
                                <div class="flex items-center justify-center space-x-1">
                                    <!-- Tombol Edit -->
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="edit('{{ $admin->id }}')" title="Edit Data">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Tombol Delete -->
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
                            <td colspan="8" class="no-data">
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data mahasiswa</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan mahasiswa baru.</p>
                                </div>
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
