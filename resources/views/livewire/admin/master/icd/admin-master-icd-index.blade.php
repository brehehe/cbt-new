<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#1E3A8A]">ICD</h1>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="overflow-x-auto w-full">
            <nav class="flex w-full gap-2 " aria-label="Tabs">
                <button wire:click="changeTab('icd-10')"
                    class="flex-1 text-center px-4 py-2 text-sm font-medium transition-all duration-300 cursor-pointer rounded-2xl
                            {{ $tab === 'icd-10' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-black' }}">
                    {{ Str::title(Str::replace('-', ' ', 'icd-10')) }}
                </button>
                <button wire:click="changeTab('icd-9')"
                    class="flex-1 text-center px-4 py-2 text-sm font-medium transition-all duration-300 cursor-pointer rounded-2xl
                            {{ $tab === 'icd-9' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-black' }}">
                    {{ Str::title(Str::replace('-', ' ', 'icd-9')) }}
                </button>
            </nav>
        </div>
    </div>
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
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Code</th>
                        <th>Display</th>
                        <th>Version</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($querys as $index => $query)
                        <tr>
                            <td class="center">{{ $querys->firstItem() + $index }}</td>
                            <td>{{ $query->code }}</td>
                            <td>{{ $query->display }}</td>
                            <td>{{ $query->version }}</td>
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
                    Menampilkan <span class="font-medium">{{ $querys->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $querys->lastItem() }}</span> dari <span
                        class="font-medium">{{ $querys->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $querys->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>

    </div>
</div>
