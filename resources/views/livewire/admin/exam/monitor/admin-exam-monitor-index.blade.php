<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Live Exam Monitoring</h1>
            <p class="text-sm text-gray-600">Monitor siswa yang sedang mengerjakan ujian secara real-time</p>
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
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Timetable Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jadwal Ujian</label>
                <select wire:model.live="selectedTimetable"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jadwal</option>
                    @foreach ($activeTimetables as $timetable)
                        <option value="{{ $timetable->id }}">{{ $timetable->name }} -
                            {{ $timetable->module->name ?? '' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
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
        </div>
    </div>

    <!-- Live Sessions Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Active Sessions ({{ $activeSessions->total() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Camera</th>
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
                                            {{ $session->user->name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $session->user->nim ?? ($session->user->username ?? 'N/A') }}</div>
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
                                <div class="text-sm text-gray-900">
                                    {{ $session->current_question_number }}/{{ $session->total_questions }}</div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        style="width: {{ $session->progress_percentage }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $session->progress_percentage }}% completed
                                </div>
                            </td>

                            <!-- Connection Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-green-100 text-green-800' =>
                                        $session->connection_status === 'connected',
                                    'bg-red-100 text-red-800' => $session->connection_status === 'disconnected',
                                    'bg-yellow-100 text-yellow-800' =>
                                        $session->connection_status === 'unstable',
                                    'bg-gray-100 text-gray-800' => !in_array($session->connection_status, [
                                        'connected',
                                        'disconnected',
                                        'unstable',
                                    ]),
                                ])>
                                    {{ ucfirst($session->connection_status) }}
                                </span>
                            </td>

                            <!-- Camera Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                    'bg-green-100 text-green-800' => $session->camera_status === 'active',
                                    'bg-red-100 text-red-800' => in_array($session->camera_status, [
                                        'inactive',
                                        'error',
                                    ]),
                                    'bg-yellow-100 text-yellow-800' => $session->camera_status === 'pending',
                                    'bg-gray-100 text-gray-800' => !in_array($session->camera_status, [
                                        'active',
                                        'inactive',
                                        'error',
                                        'pending',
                                    ]),
                                ])>
                                    {{ ucfirst($session->camera_status) }}
                                </span>
                            </td>

                            <!-- Alerts -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <span @class([
                                        'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                        'bg-red-100 text-red-800' => $session->risk_level === 'high',
                                        'bg-yellow-100 text-yellow-800' => $session->risk_level === 'medium',
                                        '{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'bg-orange-100 : 'bg-blue-100' }} {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'text-blue-800' : 'text-orange-800' }}' =>
                                            $session->risk_level === 'low',
                                        'bg-green-100 text-green-800' => $session->risk_level === 'none',
                                        'bg-gray-100 text-gray-800' => !in_array($session->risk_level, [
                                            'high',
                                            'medium',
                                            'low',
                                            'none',
                                        ]),
                                    ])>
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
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    @if ($session->connection_status === 'connected')
                                        <button wire:click="forceDisconnect('{{ $session->id }}')"
                                            wire:confirm="Apakah Anda yakin ingin memutuskan koneksi siswa ini?"
                                            class="text-yellow-600 hover:text-yellow-900 p-1 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636" />
                                            </svg>
                                        </button>
                                    @endif

                                    <button wire:click="terminateSession('{{ $session->id }}')"
                                        wire:confirm="Apakah Anda yakin ingin menghentikan sesi ujian ini?"
                                        class="text-red-600 hover:text-red-900 p-1 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada sesi ujian aktif ditemukan
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
</div>

@push('scripts')
    <script>
        let autoRefreshInterval;

        document.addEventListener('livewire:initialized', function() {
            // Initialize auto refresh
            if (@json($autoRefresh)) {
                startAutoRefresh();
            }

            // Listen for auto refresh toggle
            Livewire.on('autoRefreshToggled', function(enabled) {
                if (enabled) {
                    startAutoRefresh();
                } else {
                    stopAutoRefresh();
                }
            });

            // Listen for data refresh events
            Livewire.on('dataRefreshed', function() {
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
        window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });
    </script>
@endpush
