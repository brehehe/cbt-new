<div>
    @include('livewire.admin.master.rating-scale.admin-master-rating-scale-modal')
    @include('livewire.admin.master.rating-scale.admin-master-rating-scale-info-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Data Skala Penilaian</h1>
            </div>
            <div class="flex gap-3">
                <button wire:click="openInfoModal()"
                    class="btn btn-info !bg-blue-50 !text-blue-600 !border-blue-100 hover:!bg-blue-100 rounded-xl transition-all shadow-sm">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    Petunjuk
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
            <input type="text" class="form-control-search !rounded-xl !border-gray-100 !bg-white/50 backdrop-blur-sm"
                placeholder="Cari grade, skor, atau deskripsi..." wire:model.live='search'>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fas fa-search h-3 w-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Desktop Table Section -->
    <div
        class="hidden md:block bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
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
                                    <span class="font-bold text-gray-800">{{ number_format($data->min_score, 0) }}</span>
                                    <span class="text-xs text-gray-400 font-medium">:</span>
                                    <span class="text-gray-500 text-sm">{{ number_format($data->min_score, 0) }} —
                                        {{ number_format($data->max_score, 0) }}</span>
                                </div>
                            </td>
                            <td class="text-gray-600 italic text-sm">{{ $data->description }}</td>
                            <td class="center">
                                <div class="flex items-center justify-center gap-2">
                                    <button class="p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                        title="Edit" wire:click="edit('{{ $data->id }}')">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="p-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors"
                                        title="Hapus" wire:click="confirmDelete('{{ $data->id }}')">
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
                                    <p>Belum ada data. Tambahkan baris pertama di bawah.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-amber-50/60 border-t-2 border-dashed border-amber-200">
                        <!-- No -->
                        <td class="center">
                            <span class="text-amber-400">
                                <i class="fa-solid fa-plus"></i>
                            </span>
                        </td>
                        <!-- Grade Letter -->
                        <td class="py-2 pr-2">
                            <div>
                                <input id="new_grade_letter" type="text" wire:model="new_grade_letter" placeholder="A"
                                    maxlength="10"
                                    class="w-full px-3 py-1.5 text-sm font-semibold rounded-lg border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all placeholder-gray-300 uppercase"
                                    style="text-transform: uppercase;" />
                                @error('new_grade_letter')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </td>
                        <!-- Rentang Nilai -->
                        <td class="py-2 pr-2">
                            <div class="flex items-center gap-2">
                                <div class="relative">
                                    <input id="new_min_score" type="number" wire:model.live="new_min_score"
                                        placeholder="0" min="0" max="100"
                                        class="w-20 px-3 py-1.5 text-sm font-bold rounded-lg border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all placeholder-gray-300 text-center" />
                                </div>
                                <span class="text-gray-400 text-xs font-medium">:</span>
                                @if ($calculated_max_score !== null && $new_min_score !== '')
                                    <span class="text-sm text-gray-500 whitespace-nowrap">
                                        {{ (int) $new_min_score }}
                                        <span class="text-gray-300 mx-1">—</span>
                                        {{ (int) $calculated_max_score }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-300 italic">masukkan nilai</span>
                                @endif
                            </div>
                            @error('new_min_score')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </td>
                        <!-- Deskripsi -->
                        <td class="py-2 pr-2">
                            <div>
                                <input id="new_description" type="text" wire:model="new_description"
                                    placeholder="Sangat Baik, Baik, ..."
                                    class="w-full px-3 py-1.5 text-sm rounded-lg border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition-all placeholder-gray-300" />
                                @error('new_description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </td>
                        <!-- Aksi -->
                        <td class="center py-2">
                            <button wire:click="storeInline()" wire:loading.attr="disabled"
                                class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning btn-sm' : 'btn btn-primary btn-sm' }} !rounded-lg !px-3 !py-1.5 !text-xs shadow-sm"
                                title="Tambahkan">
                                <span wire:loading.remove wire:target="storeInline">
                                    <i class="fa-solid fa-check mr-1"></i> Tambah
                                </span>
                                <span wire:loading wire:target="storeInline">
                                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Menyimpan...
                                </span>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Mobile Card Section -->
    <div class="md:hidden space-y-4 mb-6">
        @forelse ($datas as $index => $data)
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex items-center justify-center w-10 h-10 rounded-lg bg-primary bg-opacity-10 text-[color:var(--primary)] font-bold text-xl">
                            {{ $data->grade_letter }}
                        </span>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Rentang Nilai</p>
                            <p class="text-lg font-bold text-gray-800">
                                {{ number_format($data->min_score, 0) }} — {{ number_format($data->max_score, 0) }}
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

        <!-- Mobile Inline Add -->
        <div class="bg-amber-50 rounded-xl border-2 border-dashed border-amber-200 p-4 space-y-3">
            <p class="text-xs font-bold text-amber-500 uppercase tracking-widest flex items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i> Tambah Grade Baru
            </p>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-gray-500 font-medium mb-1 block">Grade</label>
                    <input type="text" wire:model="new_grade_letter" placeholder="A, B+, ..." maxlength="10"
                        style="text-transform: uppercase;"
                        class="w-full px-3 py-2 text-sm font-semibold rounded-lg border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all placeholder-gray-300" />
                    @error('new_grade_letter') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs text-gray-500 font-medium mb-1 block">Nilai Min</label>
                    <input type="number" wire:model.live="new_min_score" placeholder="85" min="0" max="100"
                        class="w-full px-3 py-2 text-sm font-bold rounded-lg border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all placeholder-gray-300 text-center" />
                    @error('new_min_score') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            @if ($calculated_max_score !== null && $new_min_score !== '')
                <div class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg border border-amber-100">
                    <i class="fa-solid fa-arrow-right-arrow-left text-amber-400 text-xs"></i>
                    <span class="text-sm font-semibold text-gray-700">
                        Rentang: {{ (int) $new_min_score }} — {{ (int) $calculated_max_score }}
                    </span>
                </div>
            @endif
            <div>
                <label class="text-xs text-gray-500 font-medium mb-1 block">Deskripsi</label>
                <input type="text" wire:model="new_description" placeholder="Sangat Baik, Baik, ..."
                    class="w-full px-3 py-2 text-sm rounded-lg border border-amber-200 bg-white focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all placeholder-gray-300" />
                @error('new_description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <button wire:click="storeInline()" wire:loading.attr="disabled"
                class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }} w-full shadow-sm">
                <span wire:loading.remove wire:target="storeInline">
                    <i class="fa-solid fa-check mr-2"></i> Tambahkan
                </span>
                <span wire:loading wire:target="storeInline">
                    <i class="fa-solid fa-spinner fa-spin mr-2"></i> Menyimpan...
                </span>
            </button>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="px-6 py-4 bg-white/50 backdrop-blur-sm rounded-xl border border-gray-100 text-center">
        <p class="text-xs text-gray-400 font-medium">Total <span class="text-gray-600">{{ count($datas) }}</span> Grade
            Penilaian Terdaftar</p>
    </div>
</div>