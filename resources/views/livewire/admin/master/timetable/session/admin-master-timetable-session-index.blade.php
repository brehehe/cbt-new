<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Sesi Ujian</h1>
                {{-- <p class="text-gray-600 text-sm">{{ $timetable->name }} • {{ $timetable->module->name ?? '-' }}</p>
                --}}
            </div>
            {{-- <div class="flex gap-2">
                <a href="{{ route('admin.master.timetable') }}" class="btn btn-light">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
            </div> --}}
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-20"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="10000">Semua</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="w-full md:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Cari Sesuatu..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section (match style with admin-master-question-index) -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Peserta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Password</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Peserta</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Login / Aktif</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Soal / Terjawab</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas Terakhir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamera</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sessions as $index => $session)
                        @php
                            $user = $session->user;
                            $liveSession = $user?->examLiveSessions->first();
                            $userTimetable = $user?->userTimetables->first();

                            // Kehadiran
                            $kehadiranText = $userTimetable ? 'Hadir' : 'Tidak Hadir';
                            $kehadiranColor = $userTimetable ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';

                            // Status Login/Aktif
                            $statusText = 'Belum Login';
                            $statusColor = 'bg-gray-100 text-gray-600';
                            if ($liveSession) {
                                if ($liveSession->is_active) {
                                    $statusText = 'Aktif (Online)';
                                    $statusColor = 'bg-emerald-100 text-emerald-700';
                                } else {
                                    $statusText = $liveSession->connection_status === 'disconnected' ? 'Offline' : 'Login';
                                    $statusColor = 'bg-blue-100 text-blue-700';
                                }
                            }

                            // Jumlah Soal & Terjawab
                            $totalSoal = $userTimetable ? $userTimetable->userModuleQuestions->count() : 0;
                            $terjawab = $userTimetable ? $userTimetable->userModuleQuestions->filter(fn($q) => $q->timetable_answer_id || $q->essay_answer)->count() : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                {{ $sessions->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                {{ $user->nim ?? ($user->username ?? '-') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                {{ $user->decrypted_password ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $kehadiranColor }}">
                                    {{ $kehadiranText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold text-slate-700">
                                {{ $totalSoal }} / {{ $terjawab }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $liveSession?->last_activity ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $liveSession?->camera_status ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($userTimetable)
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($userTimetable->status === 'suspend')
                                            <button
                                                class="btn btn-icon text-green-600 hover:text-green-800 transition-colors edit-btn"
                                                wire:click="unsuspendSession('{{ $user->id }}')"
                                                wire:confirm="Apakah Anda yakin ingin mencabut suspend peserta ini?">
                                                <i class="fa-solid fa-user-check"></i>
                                            </button>
                                        @else
                                            <button
                                                class="btn btn-icon text-red-600 hover:text-red-800 transition-colors edit-btn"
                                                wire:click="suspendSession('{{ $user->id }}')"
                                                wire:confirm="Apakah Anda yakin ingin mensuspend peserta ini?">
                                                <i class="fa-solid fa-user-slash"></i>
                                            </button>
                                        @endif
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            wire:click="forceLogoutUser('{{ $user->id }}')"
                                            wire:confirm="Apakah Anda yakin ingin force logout peserta ini?">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="no-data text-center py-6 text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $sessions->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $sessions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $sessions->total() }}</span> hasil
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