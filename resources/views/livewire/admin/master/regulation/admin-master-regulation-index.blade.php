<div>
    @include('livewire.admin.master.regulation.admin-master-regulation-modal')
    @include('livewire.admin.master.regulation.admin-master-regulation-info-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Regulasi</h1>
            </div>
            <div class="flex gap-3">
                <button wire:click="openInfoModal()"
                    class="btn btn-info !bg-blue-50 !text-blue-600 !border-blue-100 hover:!bg-blue-100 rounded-xl transition-all shadow-sm">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    Petunjuk
                </button>
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }} shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Regulasi
                </button>
            </div>
        </div>
    </div>

    <!-- Table Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div class="flex items-center">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-widest px-3 py-1 bg-gray-100 rounded-lg">
                Manajemen Regulasi</h2>
        </div>

        <div class="relative w-full sm:w-80">
            <input type="text" class="form-control-search !rounded-xl !border-gray-100 !bg-white focus:!border-primary"
                placeholder="Cari regulasi..." wire:model.live='search'>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search h-3 w-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Desktop Table Section -->
    <div
        class="hidden md:block bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container shadow-none !rounded-none">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Deskripsi</th>
                        <th>Tipe</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($regulations as $index => $regulation)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="center font-medium text-gray-400">{{ $index + 1 }}</td>
                            <td class="font-medium text-gray-800 leading-relaxed">{{ $regulation->description ?? '-' }}</td>
                            <td>
                                @if($regulation->type == 'licensing')
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-600 border border-green-100 uppercase tracking-wider">Wajib</span>
                                @else
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-100 uppercase tracking-wider">Larangan</span>
                                @endif
                            </td>
                            <td class="center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        class="w-9 h-9 flex items-center justify-center rounded-xl text-blue-600 hover:bg-blue-50 transition-all active:scale-90"
                                        title="Edit" wire:click="edit('{{ $regulation->id }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <button
                                        class="w-9 h-9 flex items-center justify-center rounded-xl text-red-600 hover:bg-red-50 transition-all active:scale-90"
                                        title="Hapus" wire:click="confirmDelete('{{ $regulation->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">
                                <i class="fa-solid fa-scale-balanced text-4xl mb-3 opacity-20"></i>
                                <p class="text-sm font-medium">Belum ada data regulasi</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card Section -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse ($regulations as $index => $regulation)
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-5 space-y-4">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        @if($regulation->type == 'licensing')
                            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                                <i class="fa-solid fa-circle-check text-green-600"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                            </div>
                        @endif
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-black">Tipe Regulasi</p>
                            <p class="font-bold text-gray-800">{{ $regulation->type == 'licensing' ? 'Wajib' : 'Larangan' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="edit('{{ $regulation->id }}')"
                            class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl active:scale-90 transition-transform">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button wire:click="confirmDelete('{{ $regulation->id }}')"
                            class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-xl active:scale-90 transition-transform">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-50">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.2em] font-black mb-2">Deskripsi</p>
                    <p class="text-sm text-gray-600 leading-relaxed font-medium">{{ $regulation->description ?? '-' }}</p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-10 text-center">
                <i class="fa-solid fa-scale-balanced text-4xl text-gray-100 mb-3"></i>
                <p class="text-sm font-medium text-gray-400">Belum ada data regulasi</p>
            </div>
        @endforelse
    </div>

    <!-- Footer Info -->
    <div class="px-6 py-4 bg-white/50 backdrop-blur-sm rounded-2xl border border-gray-100 text-center">
        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total <span
                class="text-gray-800">{{ count($regulations) }}</span> Regulasi Aktif</p>
    </div>
</div>