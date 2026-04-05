<div>
    @include('livewire.admin.master.rating-scale.admin-master-rating-scale-modal')
    @include('livewire.admin.master.rating-scale.admin-master-rating-scale-info-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-primary">
                    Data Skala Penilaian</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
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
                    Tambah Skala Penilaian
                </button>
            </div>
        </div>
    </div>

    <!-- Table Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Daftar Skala</h2>
        </div>

        <div class="relative w-full sm:w-80">
            <input type="text" class="form-control-search !rounded-xl !border-gray-100 !bg-white/50 backdrop-blur-sm" placeholder="Cari grade, skor, atau deskripsi..."
                wire:model.live='search'>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search h-3 w-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Desktop Table Section -->
    <div class="hidden md:block bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Grade Letter</th>
                        <th>Rentang Nilai</th>
                        <th>Deskripsi</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($datas as $index => $data)
                        <tr>
                            <td class="center">{{ $index + 1 }}</td>
                            <td>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-primary text-white">
                                    {{ $data->grade_letter }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-700">{{ number_format($data->min_score, 0) }}</span>
                                    <span class="text-gray-400">—</span>
                                    <span class="font-medium text-gray-700">{{ number_format($data->max_score, 0) }}</span>
                                </div>
                            </td>
                            <td class="text-gray-600 italic text-sm">{{ $data->description }}</td>
                            <td class="center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Edit"
                                        wire:click="edit('{{ $data->id }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <button
                                        class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                        title="Hapus"
                                        wire:click="confirmDelete('{{ $data->id }}')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <i class="fa-solid fa-layer-group text-4xl"></i>
                                    <p>Tidak ada data skala penilaian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card Section -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse ($datas as $index => $data)
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary bg-opacity-10 text-primary font-bold text-xl">
                            {{ $data->grade_letter }}
                        </span>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Rentang Nilai</p>
                            <p class="text-lg font-bold text-gray-800">
                                {{ number_format($data->min_score, 0) }} - {{ number_format($data->max_score, 0) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="edit('{{ $data->id }}')" class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button wire:click="confirmDelete('{{ $data->id }}')" class="p-2 bg-red-50 text-red-600 rounded-lg">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="pt-3 border-t border-gray-50">
                    <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-600 italic">{{ $data->description }}</p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 text-center text-gray-400">
                <i class="fa-solid fa-layer-group text-4xl mb-2"></i>
                <p>Tidak ada data skala penilaian</p>
            </div>
        @endforelse
    </div>

    <!-- Footer Info -->
    <div class="px-6 py-4 bg-white/50 backdrop-blur-sm rounded-xl border border-gray-100 text-center">
        <p class="text-xs text-gray-400 font-medium">Total <span class="text-gray-600">{{ count($datas) }}</span> Grade Penilaian Terdaftar</p>
    </div>
</div>
