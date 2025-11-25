<div>
    @include('livewire.admin.master.admin.admin-master-admin-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                    Admin</h1>
            </div>
            <div>
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'btn btn-primary' : 'btn btn-warning' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Admin
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
                        <th>Username</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $index => $admin)
                        <tr>
                            <td class="center">{{ $admins->firstItem() + $index }}</td>
                            <td>{{ $admin->name ?? '-' }}</td>
                            <td>{{ $admin->email ?? '-' }}</td>
                            <td>{{ $admin->username ?? '-' }}</td>
                            {{-- <td>{{ $admin->phone ?? '-' }}</td> --}}
                            <td class="center">
                                <div class="flex items-center">
                                    <!-- Tombol Edit -->
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="edit('{{ $admin->id }}')">
                                        <i class="fa-solid fa-pen-to-square"></i> <!-- atau fa-edit (versi lama) -->
                                    </button>

                                    <!-- Tombol Delete -->
                                    <button
                                        class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                        wire:click="confirmDelete('{{ $admin->id }}')">
                                        <i class="fa-solid fa-trash"></i>
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
