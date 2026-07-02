<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Live Exam Monitoring</h1>
            <p class="text-sm text-gray-600">Monitor Mahasiswa yang sedang mengerjakan ujian secara real-time</p>
        </div>

        <div class="flex flex-col gap-2 lg:flex-row lg:items-center">
            <!-- Auto Refresh Toggle -->
            <div class="flex items-center gap-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model.live="autoRefresh" class="sr-only peer">
                    <div
                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Auto Refresh</span>
                </label>
            </div>

            <!-- Manual Refresh -->
            <button wire:click="refreshData"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh
            </button>

            <!-- Download Excel -->
            <button wire:click="exportExcel" wire:loading.attr="disabled"
                class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-60">
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <span wire:loading wire:target="exportExcel">Memproses...</span>
                <span wire:loading.remove wire:target="exportExcel">Excel</span>
            </button>

            <!-- Download PDF -->
            <button wire:click="exportPdf" wire:loading.attr="disabled"
                class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:opacity-60">
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span wire:loading wire:target="exportPdf">Memproses...</span>
                <span wire:loading.remove wire:target="exportPdf">PDF</span>
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Total Active Sessions -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Sessions</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalActiveSessions }}</p>
                </div>
            </div>
        </div>

        <!-- Online Students -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Online Students</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOnlineStudents }}</p>
                </div>
            </div>
        </div>

        <!-- High Risk Students -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L3.316 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">High Risk</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $highRiskStudents }}</p>
                </div>
            </div>
        </div>

        <!-- Total Alerts Today -->
        <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-5 5v-5zM9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Alerts Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAlerts }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="p-4 mb-6 bg-white border border-gray-200 rounded-lg shadow-sm">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <!-- Timetable Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Ujian</label>
                <select wire:model.live="selectedTimetable"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jadwal</option>
                    @foreach ($activeTimetables as $timetable)
                        <option value="{{ $timetable->id }}">{{ $timetable->name }} -
                            {{ $timetable->module->name ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Mahasiswa</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nama, NIM, Username..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Koneksi</label>
                <select wire:model.live="statusFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="connected">Terhubung</option>
                    <option value="disconnected">Terputus</option>
                    <option value="unstable">Tidak Stabil</option>
                </select>
            </div>

            <!-- Risk Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat Risiko</label>
                <select wire:model.live="riskFilter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Risiko</option>
                    <option value="high">Tinggi</option>
                    <option value="medium">Sedang</option>
                    <option value="low">Rendah</option>
                    <option value="none">Aman</option>
                </select>
            </div>

            <!-- Session Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Sesi</label>
                <select wire:model.live="sessionType"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="active">Sesi Aktif</option>
                    <option value="history">Riwayat Ujian (Selesai)</option>
                    <option value="all">Semua Sesi</option>
                </select>
            </div>

            <!-- Status Ujian Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Ujian</label>
                <select wire:model.live="utStatus"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="exam">Sedang Ujian</option>
                    <option value="done">Selesai</option>
                    <option value="warning">Peringatan</option>
                    <option value="blocked">Diblokir</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Live Sessions Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                @if($sessionType === 'active')
                    Sesi Aktif ({{ $activeSessions->total() }})
                @elseif($sessionType === 'history')
                    Riwayat Ujian ({{ $activeSessions->total() }})
                @else
                    Semua Sesi Ujian ({{ $activeSessions->total() }})
                @endif
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mahasiswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Alerts</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                            Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activeSessions as $session)
                                    <tr class="hover:bg-gray-50">
                                        <!-- Student Info -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-medium">
                                                        {{ strtoupper(substr($session->user->name ?? 'U', 0, 2)) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $session->user->name ?? 'Unknown' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $session->user->nim ?? ($session->user->username ?? 'N/A') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Exam Info -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $session->timetable->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $session->timetable->module->name ?? 'N/A' }}
                                            </div>
                                        </td>

                                        <!-- Progress -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $stats = $session->db_question_stats;
                                            @endphp
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $stats['answered'] }}/{{ $stats['total'] }} Soal
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                <div class="bg-blue-600 h-2 rounded-full"
                                                    style="width: {{ $stats['percentage'] }}%"></div>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1 mb-2">{{ $stats['percentage'] }}% selesai</div>
                                            
                                            <!-- Detail Jawaban -->
                                            <div class="flex items-center gap-1 text-[10px]">
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded font-semibold bg-green-50 text-green-700 border border-green-200" title="Benar">
                                                    ✓ {{ $stats['correct'] }}
                                                </span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded font-semibold bg-red-50 text-red-700 border border-red-200" title="Salah">
                                                    ✗ {{ $stats['wrong'] }}
                                                </span>
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded font-semibold bg-gray-50 text-gray-700 border border-gray-200" title="Belum Dijawab">
                                                    ? {{ $stats['unanswered'] }}
                                                </span>
                                            </div>
                                        </td>
                                        
                                        <!-- Alerts -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-2">
                                                <span @class([
                                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                                    'bg-red-100 text-red-800' => $session->risk_level === 'high',
                                                    'bg-yellow-100 text-yellow-800' => $session->risk_level === 'medium',
                                                    'text-white border' => $session->risk_level === 'low',
                                                    'bg-green-100 text-green-800' => $session->risk_level === 'none',
                                                    'bg-gray-100 text-gray-800' => !in_array($session->risk_level, ['high', 'medium', 'low', 'none']),
                                                ])
                                                    style="{{ $session->risk_level === 'low'
                        ? 'background-color:' . ($companyData->color_primary ?? '#2b7fff') .
                        ';border-color:' . ($companyData->color_primary ?? '#2b7fff')
                        : '' }}">
                                                    {{ $session->alert_count }} alerts
                                                </span>

                                            </div>
                                        </td>

                                        <!-- Last Activity -->
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $session->last_activity ? $session->last_activity->diffForHumans() : 'N/A' }}
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button wire:click="viewSessionDetail('{{ $session->id }}')"
                                                    class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>

                                                @if ($session->connection_status === 'connected')
                                                    <button wire:click="forceDisconnect('{{ $session->id }}')"
                                                        wire:confirm="Apakah Anda yakin ingin memutuskan koneksi Mahasiswa ini?"
                                                        class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                 @if($session->is_active)
                                                    <button wire:click="terminateSession('{{ $session->id }}')"
                                                        wire:confirm="Apakah Anda yakin ingin menghentikan sesi ujian ini?"
                                                        class="text-red-600 hover:text-red-900 p-1 rounded" title="Putus Sesi">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                @endif

                                                {{-- Force Finish: always shown when UserTimetable is not yet done --}}
                                                @if($session->userTimetable && !in_array($session->userTimetable->status, ['done', 'suspend']))
                                                    <button wire:click="openFinishModal('{{ $session->id }}')"
                                                        class="text-emerald-600 hover:text-emerald-900 p-1 rounded" title="Selesaikan Ujian">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                @if($sessionType === 'active')
                                    Tidak ada sesi ujian aktif ditemukan
                                @elseif($sessionType === 'history')
                                    Tidak ada riwayat sesi ujian ditemukan
                                @else
                                    Tidak ada data sesi ujian ditemukan
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $activeSessions->links() }}
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         Force Finish Modal
    ════════════════════════════════════════════════════════════════════ --}}
    @if($confirmFinishModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data
         @keydown.escape.window="$wire.closeFinishModal()">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
             wire:click="closeFinishModal"></div>

        {{-- Panel --}}
        <div class="relative z-10 bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

            {{-- Header --}}
            <div class="bg-emerald-600 px-6 py-4 flex items-center gap-3">
                <div class="flex-shrink-0 bg-white/20 rounded-full p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg">Selesaikan Ujian</h3>
                <button wire:click="closeFinishModal" class="ml-auto text-white/70 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5">
                    <div class="flex gap-2">
                        <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-amber-800 font-medium">
                            Tindakan ini akan <strong>menghitung nilai secara otomatis</strong> berdasarkan jawaban yang sudah diisi dan menandai ujian sebagai <strong>Selesai</strong>. Tindakan ini tidak dapat dibatalkan.
                        </p>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Nama Peserta</span>
                        <span class="font-bold text-gray-900">{{ $finishTargetInfo['student_name'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">NIM / No. Peserta</span>
                        <span class="font-bold text-gray-700">{{ $finishTargetInfo['nim'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Jadwal Ujian</span>
                        <span class="font-bold text-gray-700">{{ $finishTargetInfo['timetable_name'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Modul</span>
                        <span class="font-bold text-gray-700">{{ $finishTargetInfo['module_name'] ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 font-medium">Status Saat Ini</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $finishTargetInfo['ut_status'] ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button wire:click="closeFinishModal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button wire:click="confirmForceFinish" wire:loading.attr="disabled"
                    class="px-5 py-2 text-sm font-bold text-white bg-emerald-600 rounded-lg hover:emerald-700 disabled:opacity-60 flex items-center gap-2">
                    <svg wire:loading wire:target="confirmForceFinish" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="confirmForceFinish">Ya, Selesaikan Ujian</span>
                    <span wire:loading wire:target="confirmForceFinish">Memproses...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
    <script>
        let autoRefreshInterval;

        document.addEventListener('livewire:initialized', function () {
            // Initialize auto refresh
            if (@json($autoRefresh)) {
                startAutoRefresh();
            }

            // Listen for auto refresh toggle
            Livewire.on('autoRefreshToggled', function (enabled) {
                if (enabled) {
                    startAutoRefresh();
                } else {
                    stopAutoRefresh();
                }
            });

            // Listen for data refresh events
            Livewire.on('dataRefreshed', function () {
                console.log('Data refreshed at', new Date().toLocaleTimeString());
            });
        });

        function startAutoRefresh() {
            stopAutoRefresh(); // Clear any existing interval
            autoRefreshInterval = setInterval(() => {
                Livewire.dispatch('refreshData');
            }, @json($refreshInterval) * 1000);
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }

        // Clean up when page is unloaded
        window.addEventListener('beforeunload', function () {
            stopAutoRefresh();
        });
    </script>
@endpush