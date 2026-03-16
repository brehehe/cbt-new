<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[{{$companyData->color_primary}}]">Manajemen Sesi Pengguna</h1>
                <p class="text-gray-600 text-sm">Monitor dan kelola sesi aktif dari seluruh pengguna</p>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-primary" wire:click="confirmForceLogoutAll">
                    <i class="fa-solid fa-power-off mr-2"></i>
                    Force Logout Semua
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12" wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="w-full md:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-[{{ $companyData->color_primary ?? '#2b7fff' }}] focus:border-[{{ $companyData->color_primary ?? '#2b7fff' }}] sm:text-sm transition duration-150 ease-in-out" 
                    placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device / Browser</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas Terakhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($sessions as $index => $session)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $sessions->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ optional($users->get($session->user_id))->name ?? '(User Tidak Ditemukan)' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $session->ip_address }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $session->user_agent }}">
                                {{ \Illuminate\Support\Str::limit($session->user_agent, 40) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button class="btn btn-warning" wire:click="confirmForceLogout('{{ $session->user_id }}')" title="Force Logout Sesi Ini">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada sesi aktif</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            @if(count($sessions) > 0)
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $sessions->firstItem() }}</span> sampai <span class="font-medium">{{ $sessions->lastItem() }}</span> dari <span class="font-medium">{{ $sessions->total() }}</span> hasil
                </div>
                <div>
                    {{ $sessions->links('vendor.livewire.custom') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
