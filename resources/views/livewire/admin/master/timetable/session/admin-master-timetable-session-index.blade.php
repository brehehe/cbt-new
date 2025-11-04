<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold {{ config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                    Sesi Ujian</h1>
                <p class="text-gray-600 text-sm">{{ $timetable->name }} • {{ $timetable->module->name ?? '-' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.master.timetable.streaming', ['timetable_id' => $timetable->id]) }}"
                    class="btn btn-primary">
                    <i class="fa-solid fa-camera"></i>
                    Streaming
                </a>
                <a href="{{ route('admin.master.timetable') }}" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center">
            <span class="text-sm text-gray-700 mr-2">Tampil</span>
            <select class="mt-1 form-control" wire:model.live='perPage'>
                <option value="6">6</option>
                <option value="12">12</option>
                <option value="24">24</option>
            </select>
        </div>

        <div class="flex items-center gap-3">
            <input type="text" class="form-control w-64" placeholder="Cari nama user..." wire:model.live="search">
            <select class="form-control w-48" wire:model.live="filterStatus">
                <option value="all">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="disconnected">Putus</option>
            </select>
        </div>
    </div>

    <!-- Table Section (match style with admin-master-question-index) -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peer ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas Terakhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamera</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Layar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($sessions as $index => $session)
                        @php
                            $statusColor = match (true) {
                                $session->is_active => 'bg-green-100 text-green-700',
                                $session->connection_status === 'disconnected' => 'bg-red-100 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };
                            $statusText = $session->is_active
                                ? 'Aktif'
                                : ucfirst($session->connection_status ?? 'Tidak aktif');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $sessions->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->peer_id ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $statusColor }}">{{ $statusText }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->last_activity ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->camera_status ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $session->screen_status ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <button class="btn btn-danger" wire:click="suspendSession('{{ $session->id }}')">
                                        <i class="fa-solid fa-user-slash"></i>
                                    </button>
                                    <button class="btn btn-outline" wire:click="forceLogoutUser('{{ $session->user->id }}')">
                                        <i class="fa-solid fa-right-from-bracket"></i>
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
                    Menampilkan <span class="font-medium">{{ $sessions->firstItem() }}</span> sampai <span class="font-medium">{{ $sessions->lastItem() }}</span> dari <span class="font-medium">{{ $sessions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $sessions->links('vendor.livewire.custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
