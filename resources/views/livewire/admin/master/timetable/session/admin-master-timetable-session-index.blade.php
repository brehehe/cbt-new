<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#f58634]">Sesi Ujian</h1>
                <p class="text-gray-600 text-sm">{{ $timetable->name }} • {{ $timetable->module->name ?? '-' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.master.timetable.streaming', ['timetable_id' => $timetable->id]) }}" class="btn btn-primary">
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
                <option value="suspended">Suspend</option>
                <option value="disconnected">Putus</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($sessions as $session)
            <div class="bg-white rounded-lg shadow-sm border">
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $session->user->name }}</h3>
                            <p class="text-xs text-gray-500">Peer: {{ $session->peer_id ?? '-' }}</p>
                        </div>
                        <div>
                            @php
                                $statusColor = match(true) {
                                    $session->is_active => 'bg-green-100 text-green-700',
                                    $session->connection_status === 'suspended' => 'bg-yellow-100 text-yellow-700',
                                    $session->connection_status === 'disconnected' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                };
                                $statusText = $session->is_active ? 'Aktif' : ucfirst($session->connection_status ?? 'Tidak aktif');
                            @endphp
                            <span class="px-2 py-1 rounded text-xs font-medium {{ $statusColor }}">{{ $statusText }}</span>
                        </div>
                    </div>

                    <div class="mt-2 text-xs text-gray-600">
                        <div>Aktivitas terakhir: {{ $session->last_activity ?? '-' }}</div>
                        <div>Kamera: {{ $session->camera_status ?? '-' }} • Layar: {{ $session->screen_status ?? '-' }}</div>
                    </div>

                    <div class="mt-4 flex gap-2">
                        <button class="btn btn-danger" wire:click="suspendSession('{{ $session->id }}')">
                            <i class="fa-solid fa-user-slash"></i>
                            Suspend & Logout
                        </button>
                        <button class="btn btn-dark" wire:click="terminateSession('{{ $session->id }}')">
                            <i class="fa-solid fa-link-slash"></i>
                            Putus Sesi
                        </button>
                        <button class="btn btn-outline" wire:click="forceLogoutUser('{{ $session->user->id }}')">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Logout Akun
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3">
                <div class="bg-white border rounded p-8 text-center text-gray-500">Belum ada sesi untuk jadwal ini.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $sessions->links() }}
    </div>
</div>