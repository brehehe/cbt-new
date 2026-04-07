@section('title', 'Dashboard')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }

        .progress-ring {
            transform: rotate(-90deg);
        }

        .progress-ring-circle {
            transition: stroke-dasharray 0.35s;
            transform: rotate(-90deg);
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .loading-skeleton {
            animation: pulse 1.5s infinite;
            background-color: #e2e8f0;
            border-radius: 0.375rem;
        }

        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Alert animations */
        .alert-enter {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Progress bar animations */
        .progress-bar {
            transition: width 1s ease-in-out;
        }

        /* Card hover effects */
        .hover-lift {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        /* Status indicator */
        .status-online::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -10px;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .status-offline::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -10px;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border-radius: 50%;
        }

        /* Auto refresh toggle */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 20px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 2px;
            bottom: 2px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #f58634;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(20px);
        }

        /* Real-time status indicators */
        .status-indicator {
            animation: pulse 2s infinite;
        }

        .status-indicator.error {
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.3;
            }
        }

        /* Enhanced progress bars */
        .progress-bar-animated {
            transition: width 2s ease-in-out;
            position: relative;
            overflow: hidden;
        }

        .progress-bar-animated::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }

        /* Modal animations */
        .modal-enter {
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Enhanced card hover effects */
        .metric-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-left-color: #f58634;
        }

        /* Network status colors */
        .network-excellent {
            color: #10b981;
        }

        .network-good {
            color: #3b82f6;
        }

        .network-fair {
            color: #f59e0b;
        }

        .network-poor {
            color: #ef4444;
        }

        /* Loading states */
        .loading-state {
            position: relative;
            overflow: hidden;
        }

        .loading-state::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
            animation: loading-sweep 2s infinite;
        }

        @keyframes loading-sweep {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Responsive enhancements */
        @media (max-width: 640px) {
            .metric-card {
                padding: 1rem;
            }

            .real-time-controls {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .glass-effect {
                background: rgba(31, 41, 55, 0.8);
                border-color: rgba(75, 85, 99, 0.3);
            }
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .chart-container {
                height: 150px;
            }

            .stats-card {
                padding: 1rem;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-dot {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
@endpush

<div>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left w-full md:w-auto">
                <h1 class="text-2xl md:text-3xl font-bold text-[color:var(--primary)]">
                    Selamat datang kembali,
                    {{ Auth::user()->name ?? 'Admin' }}!
                </h1>
                <p class="text-gray-600 mt-1 text-sm md:text-base">Berikut aktivitas sistem CBT Anda hari ini.</p>
                {{-- <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p> --}}
            </div>
            <div class="flex items-center gap-3 w-full md:w-auto justify-center md:justify-end">
                <!-- Auto Refresh Toggle -->
                {{-- <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="autoRefresh" class="sr-only peer" checked>
                    <div
                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#f58634]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-600">Segarkan Otomatis</span>
                </label> --}}

                <!-- Real-time Status Indicator -->
                {{-- <div class="flex items-center space-x-2">
                    <div id="realtimeIndicator" class="w-3 h-3 rounded-full bg-green-500 pulse-dot"></div>
                    <span class="text-sm text-gray-600">Langsung</span>
                </div> --}}

                <!-- Refresh Button -->
                <button wire:click="refreshData" id="refreshButton"
                    class="inline-flex items-center px-4 py-2 bg-primary hover:bg-primary text-white text-sm font-medium rounded-lg transition-colors duration-200 w-full md:w-auto justify-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Segarkan
                </button>

                <!-- Last Update Time -->
                {{-- <div class="text-xs text-gray-500">
                    Pembaruan terakhir: <span id="lastUpdateTime">{{ date('H:i:s') }}</span>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Tampilkan profil untuk semua user --}}
    @if (isset($userProfile) && $userProfile && Auth::user()->hasRole('Mahasiswa'))
        {{-- User Profile Section will now appear for all roles --}}
        @include('livewire.admin.dashboard.partials.user-profile-section')
    @endif

    @if (!Auth::user()->hasRole('Mahasiswa'))
        <!-- Main Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Pengguna</p>
                        <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                            {{ number_format($totalUsers) }}
                        </h3>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                @if (isset($monthlyStats['new_users_this_month']) && $monthlyStats['new_users_this_month'] > 0)
                                    +{{ $monthlyStats['new_users_this_month'] }} bulan ini
                                @else
                                    Pengguna terdaftar
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-[#f58634]/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-[color:var(--primary)]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today's Exams -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ujian Hari Ini</p>
                        <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                            {{ $todayExams }}
                        </h3>
                        <div class="flex items-center mt-2">
                            <div class="w-2 h-2 bg-blue-500 rounded-full pulse-dot mr-2"></div>
                            <span class="text-xs text-blue-600">Ujian dimulai hari ini</span>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Exams -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Ujian Aktif</p>
                        <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                            {{ $activeExams }}
                        </h3>
                        <div class="flex items-center mt-2">
                            @if ($activeExams > 0)
                                <div class="w-2 h-2 rounded-full pulse-dot mr-2
                                                    bg-primary">
                                </div>
                                <span class="text-xs text-[color:var(--primary)]">Sedang
                                    berlangsung</span>
                            @else
                                <span class="text-xs text-gray-500">Tidak ada ujian aktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-[color:var(--primary)]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Exam Alerts -->
            <div
                class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Peringatan Keamanan</p>
                        <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                            {{ $examAlerts }}
                        </h3>
                        <div class="flex items-center mt-2">
                            @if ($examAlerts > 0)
                                <div class="w-2 h-2 bg-red-500 rounded-full pulse-dot mr-2"></div>
                                <span class="text-xs text-red-600">Pelanggaran keamanan</span>
                            @else
                                <span class="text-xs text-green-600">Semua aman</span>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-red-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Completed Exams -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-green-500/20 to-[#C3D4EC]/20 p-3 rounded-2xl">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-green-600">{{ number_format($completedExams) }}</span>
                </div>
                <p class="text-sm text-gray-600">Ujian Selesai</p>
                @if (isset($monthlyStats['completed_this_month']))
                    <p class="text-xs text-green-600 mt-1">{{ $monthlyStats['completed_this_month'] }} bulan ini</p>
                @endif
            </div>

            <!-- Total Exam Types -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-purple-500/20 to-[#C3D4EC]/20 p-3 rounded-2xl">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-purple-600">{{ number_format($totalExamTypes) }}</span>
                </div>
                <p class="text-sm text-gray-600">Kategori Ujian</p>
                <p class="text-xs text-purple-600 mt-1">Jenis tersedia</p>
            </div>

            <!-- Completion Rate -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-indigo-500/20 to-[#C3D4EC]/20 p-3 rounded-2xl">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-indigo-600">
                        @if (isset($monthlyStats['avg_completion_rate']))
                            {{ $monthlyStats['avg_completion_rate'] }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
                <p class="text-sm text-gray-600">Tingkat Penyelesaian</p>
                <p class="text-xs text-indigo-600 mt-1">Rata-rata bulan ini</p>
            </div>

            <!-- Live Sessions -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-yellow-500/20 to-[#C3D4EC]/20 p-3 rounded-2xl">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-yellow-600">
                        @if (isset($liveSessionStats['active_sessions']))
                            {{ $liveSessionStats['active_sessions'] }}
                        @else
                            0
                        @endif
                    </span>
                </div>
                <p class="text-sm text-gray-600">Sesi Langsung</p>
                <div class="flex items-center mt-1">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full pulse-dot mr-2"></div>
                    <span class="text-xs text-yellow-600">Aktif sekarang</span>
                </div>
            </div>
        </div>

        <!-- Charts and Analytics Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
            <!-- Weekly Exam Trends -->
            <div class="lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Tren Ujian Mingguan</h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-primary rounded-full">
                        </div>
                        <span class="text-sm text-gray-600">Ujian Dimulai</span>
                    </div>
                </div>

                <div class="chart-container" wire:ignore>
                    <canvas id="weeklyChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Exam Status Distribution -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Distribusi Status Ujian</h3>

                <!-- Status Items -->
                <div class="space-y-4">
                    @if (isset($examStatistics))
                        @php
                            $totalExamSessions = array_sum($examStatistics);
                            $statusColors = [
                                'done' => ['bg-green-500', 'text-green-700', 'bg-green-50'],
                                'exam' => ['bg-blue-500', 'text-blue-700', 'bg-blue-50'],
                                'warning' => ['bg-yellow-500', 'text-yellow-700', 'bg-yellow-50'],
                                'blocked' => ['bg-red-500', 'text-red-700', 'bg-red-50'],
                            ];

                            $statusLabels = [
                                'done' => 'Selesai',
                                'exam' => 'Sedang Ujian',
                                'warning' => 'Peringatan',
                                'blocked' => 'Diblokir',
                            ];
                        @endphp

                        @foreach ($examStatistics as $status => $count)
                            @php
                                $percentage =
                                    $totalExamSessions > 0 ? round(($count / $totalExamSessions) * 100, 1) : 0;
                                $colors = $statusColors[$status] ?? ['bg-gray-500', 'text-gray-700', 'bg-gray-50'];
                            @endphp

                            <div class="flex items-center justify-between p-3 {{ $colors[2] }} rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 {{ $statusLabels[$status] ?? ucfirst($status) }} rounded-full mr-3">
                                    </div>
                                    <span class="text-sm font-medium {{ $colors[1] }} capitalize">{{ $status }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold {{ $colors[1] }}">{{ $count }}</div>
                                    <div class="text-xs text-gray-500">{{ $percentage }}%</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 py-8">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <p class="text-sm">Tidak ada data ujian</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Live Monitoring and Alerts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <!-- Live Session Monitoring -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Pemantauan Sesi Langsung</h3>
                    <a href="{{ route('admin.exam.live-stream') }}"
                        class="text-[color:var(--primary)] hover:text-[#2d8c5b] text-sm font-medium">
                        Lihat Semua →
                    </a>
                </div>

                @if (isset($liveSessionStats))
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $liveSessionStats['active_sessions'] ?? 0 }}
                            </div>
                            <div class="text-sm text-blue-600">Sesi Aktif</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $liveSessionStats['high_risk'] ?? 0 }}
                            </div>
                            <div class="text-sm text-red-600">Risiko Tinggi</div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Masalah Kamera</span>
                            <span
                                class="font-medium text-[color:var(--primary)]">{{ $liveSessionStats['camera_issues'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Masalah Koneksi</span>
                            <span
                                class="font-medium text-[color:var(--primary)]">{{ $liveSessionStats['connection_issues'] ?? 0 }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm">Tidak ada sesi aktif</p>
                    </div>
                @endif
            </div>

            <!-- Critical Alerts -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Peringatan Kritis (24 Jam Terakhir)</h3>
                    <a href="{{ route('admin.exam.monitor') }}"
                        class="text-[color:var(--primary)] hover:text-[#2d8c5b] text-sm font-medium">
                        View All →
                    </a>
                </div>

                @if (isset($criticalAlerts) && $criticalAlerts->count() > 0)
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach ($criticalAlerts as $alert)
                            <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg border-l-4 border-red-500">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-red-800">
                                        {{ $alert->userTimetable->user->name ?? 'Unknown User' }}
                                    </div>
                                    <div class="text-xs text-red-600 mt-1">
                                        Jenis Peringatan: {{ $alert->alert_type ?? 'Pelanggaran Keamanan' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $alert->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-sm">Tidak ada peringatan kritis</p>
                        <p class="text-xs text-gray-400 mt-1">Semua sistem aman</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Exams and Recent Results -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
            <!-- Upcoming Exams -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Ujian Mendatang</h3>
                    <a href="{{ route('admin.exam.timetable') }}"
                        class="text-[color:var(--primary)] hover:text-[#2d8c5b] text-sm font-medium">
                        Kelola →
                    </a>
                </div>

                @if (isset($upcomingExams) && $upcomingExams->count() > 0)
                    <div class="space-y-3">
                        @foreach ($upcomingExams as $exam)
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-blue-800">{{ $exam->name }}</div>
                                    <div class="text-xs text-blue-600 mt-1">{{ $exam->module->name ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($exam->start_time)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-blue-600">
                                        {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm">Tidak ada ujian mendatang</p>
                    </div>
                @endif
            </div>

            <!-- Recent Exam Results -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Hasil Ujian Terbaru</h3>
                    <a href="{{ route('admin.report.item-analysis') }}"
                        class="text-[color:var(--primary)] hover:text-[#2d8c5b] text-sm font-medium">
                        Lihat Laporan →
                    </a>
                </div>

                @if (isset($recentExamResults) && $recentExamResults->count() > 0)
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach ($recentExamResults as $result)
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="text-sm font-medium text-green-800">
                                        {{ $result->user->name ?? 'Unknown' }}
                                    </div>
                                    <div class="text-xs text-green-600 mt-1">
                                        {{ $result->timetable->module->name ?? 'N/A' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-green-700">
                                        {{ $result->mark ?? 0 }}/100
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $result->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm">Tidak ada hasil terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <!-- Real-time Monitoring Script -->
    <script src="{{ asset('asset/js/realtime-monitor.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Global chart instance
        let weeklyChart = null;

        // Function to initialize or update chart
        function initializeChart() {
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js not loaded yet, retrying...');
                setTimeout(initializeChart, 100);
                return;
            }

            const ctx = document.getElementById('weeklyChart');
            if (ctx) {
                const weeklyData = @json($weeklyExamStats ?? []);

                // Destroy existing chart if it exists
                if (weeklyChart) {
                    weeklyChart.destroy();
                    weeklyChart = null;
                }

                try {
                    // Create new chart
                    weeklyChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: weeklyData.map(item => item.date),
                            datasets: [{
                                label: 'Exams Started',
                                data: weeklyData.map(item => item.count),
                                borderColor: '#f58634',
                                backgroundColor: 'rgba(245, 134, 52, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#f58634',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)'
                                    },
                                    ticks: {
                                        color: '#6B7280'
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#6B7280'
                                    }
                                }
                            }
                        }
                    });

                    console.log('Chart initialized successfully');
                } catch (error) {
                    console.error('Error initializing chart:', error);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Real-time Monitor
            if (typeof RealTimeMonitor !== 'undefined') {
                const monitor = new RealTimeMonitor();
                monitor.start();
            }

            // Initialize chart on page load
            initializeChart();

            // Auto refresh functionality
            const autoRefreshToggle = document.getElementById('autoRefresh');
            const refreshButton = document.getElementById('refreshButton');
            const lastUpdateTime = document.getElementById('lastUpdateTime');
            const realtimeIndicator = document.getElementById('realtimeIndicator');
            let refreshInterval;

            function updateLastRefreshTime() {
                if (lastUpdateTime) {
                    lastUpdateTime.textContent = new Date().toLocaleTimeString();
                }
            }

            function setIndicatorStatus(status) {
                if (realtimeIndicator) {
                    realtimeIndicator.className = 'w-3 h-3 rounded-full ' +
                        (status === 'active' ? 'bg-green-500 pulse-dot' :
                            status === 'error' ? 'bg-red-500' : 'bg-gray-400');
                }
            }

            function startAutoRefresh() {
                refreshInterval = setInterval(() => {
                    setIndicatorStatus('loading');

                    try {
                        // Trigger Livewire refresh
                        Livewire.dispatch('refreshData');

                        // Update timestamp
                        updateLastRefreshTime();

                        // Set success status and re-initialize chart
                        setTimeout(() => {
                            setIndicatorStatus('active');
                            initializeChart();
                            animateProgressBars();
                        }, 1000);

                        // If real-time modal is open, refresh its content too
                        if (!document.getElementById('realtimeModal').classList.contains('hidden')) {
                            const modal = document.getElementById('realtimeModal');
                            modal.style.opacity = '0.8';
                            setTimeout(() => {
                                modal.style.opacity = '1';
                            }, 500);
                        }
                    } catch (error) {
                        console.error('Auto refresh error:', error);
                        setIndicatorStatus('error');
                    }
                }, 30000); // Refresh every 30 seconds
            }

            function stopAutoRefresh() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                    setIndicatorStatus('inactive');
                }
            }

            // Manual refresh button
            if (refreshButton) {
                refreshButton.addEventListener('click', function () {
                    updateLastRefreshTime();
                    setIndicatorStatus('active');

                    // Add visual feedback
                    this.classList.add('loading-state');

                    // Re-initialize chart after manual refresh
                    setTimeout(() => {
                        initializeChart();
                        animateProgressBars();
                        this.classList.remove('loading-state');
                    }, 500);
                });
            }

            // Auto refresh toggle
            if (autoRefreshToggle) {
                autoRefreshToggle.addEventListener('change', function () {
                    if (this.checked) {
                        startAutoRefresh();
                        setIndicatorStatus('active');
                    } else {
                        stopAutoRefresh();
                    }
                });

                // Start auto refresh if enabled by default
                if (autoRefreshToggle.checked) {
                    startAutoRefresh();
                    setIndicatorStatus('active');
                }
            }

            // Update last refresh time on Livewire events
            Livewire.on('dataRefreshed', function () {
                updateLastRefreshTime();
                setIndicatorStatus('active');

                // Re-initialize chart after Livewire refresh
                setTimeout(() => {
                    initializeChart();
                    animateProgressBars();
                }, 100);
            });

            // Listen for Livewire component updates
            document.addEventListener('livewire:updated', function () {
                // Re-initialize chart after any Livewire update
                setTimeout(() => {
                    initializeChart();
                    animateProgressBars();
                }, 100);
            });

            // Listen for Livewire component loaded/mounted
            document.addEventListener('livewire:load', function () {
                initializeChart();
            });

            // Ensure chart is initialized when Livewire component navigates
            document.addEventListener('livewire:navigated', function () {
                setTimeout(() => {
                    initializeChart();
                }, 100);
            });

            // Cleanup on page unload
            window.addEventListener('beforeunload', function () {
                stopAutoRefresh();

                // Destroy chart to prevent memory leaks
                if (weeklyChart) {
                    weeklyChart.destroy();
                    weeklyChart = null;
                }
            });

            // Update progress bars with animation
            function animateProgressBars() {
                const progressBars = document.querySelectorAll('[style*="width:"]');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }

            // Animate progress bars on load
            setTimeout(() => {
                animateProgressBars();
            }, 500);

            // Re-animate on Livewire updates (removed duplicate since it's handled above)
        });

        // Real-time Modal Functions
        function openRealtimeModal() {
            document.getElementById('realtimeModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Trigger a data refresh when opening modal
            if (typeof RealTimeMonitor !== 'undefined') {
                const monitor = new RealTimeMonitor();
                monitor.fetchMetrics();
            }
        }

        function closeRealtimeModal() {
            document.getElementById('realtimeModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Uptime Modal Functions
        function toggleUptimeModal() {
            const modal = document.getElementById('uptimeModal');
            if (modal) {
                if (modal.classList.contains('hidden')) {
                    openUptimeModal();
                } else {
                    closeUptimeModal();
                }
            }
        }

        function openUptimeModal() {
            const modal = document.getElementById('uptimeModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeUptimeModal() {
            const modal = document.getElementById('uptimeModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Close modals when clicking outside
        document.addEventListener('click', function (e) {
            if (e.target.id === 'realtimeModal') {
                closeRealtimeModal();
            }
            if (e.target.id === 'uptimeModal') {
                closeUptimeModal();
            }
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeRealtimeModal();
                closeUptimeModal();
            }
        });

        // Add smooth transitions for stats cards
        function addStatsCardAnimations() {
            const statsCards = document.querySelectorAll('.stats-card, .hover-lift');
            statsCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        }

        // Initialize animations
        setTimeout(addStatsCardAnimations, 100);

        // Heartbeat animation for live indicators
        function startHeartbeat() {
            const pulseElements = document.querySelectorAll('.pulse-dot');
            pulseElements.forEach(element => {
                element.style.animation = 'pulse 2s infinite';
            });
        }

        setInterval(startHeartbeat, 1000);

        // Network status monitoring
        function monitorNetworkStatus() {
            if (navigator.onLine) {
                document.getElementById('realtimeIndicator')?.classList.remove('bg-red-500');
                document.getElementById('realtimeIndicator')?.classList.add('bg-green-500');
            } else {
                document.getElementById('realtimeIndicator')?.classList.remove('bg-green-500');
                document.getElementById('realtimeIndicator')?.classList.add('bg-red-500');
            }
        }

        window.addEventListener('online', monitorNetworkStatus);
        window.addEventListener('offline', monitorNetworkStatus);

        // Handle window resize to ensure chart responsiveness
        window.addEventListener('resize', function () {
            if (weeklyChart) {
                weeklyChart.resize();
            }
        });

        // Initialize network monitoring
        monitorNetworkStatus();

        // Performance monitoring
        if ('performance' in window) {
            const navigationTiming = performance.getEntriesByType('navigation')[0];
            if (navigationTiming) {
                const loadTime = navigationTiming.loadEventEnd - navigationTiming.loadEventStart;
                console.log(`Page load time: ${loadTime}ms`);
            }
        }

        // Add visual feedback for loading states
        function addLoadingState(element) {
            element.style.opacity = '0.6';
            element.style.pointerEvents = 'none';
        }

        function removeLoadingState(element) {
            element.style.opacity = '1';
            element.style.pointerEvents = 'auto';
        }

        // Error handling for failed requests
        window.addEventListener('unhandledrejection', function (event) {
            console.error('Unhandled promise rejection:', event.reason);
            document.getElementById('realtimeIndicator')?.classList.add('bg-red-500');
        });

        // Test Real-time API function
        async function testRealtimeAPI() {
            const button = event.target;
            const originalText = button.innerHTML;

            // Show loading state
            button.innerHTML =
                '<svg class="w-4 h-4 inline-block mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Testing...';
            button.disabled = true;

            try {
                // Test system metrics API
                const systemResponse = await fetch('/api/metrics/system');
                const systemData = await systemResponse.json();

                // Test livestream metrics API
                const streamResponse = await fetch('/api/metrics/livestream');
                const streamData = await streamResponse.json();

                // Show success notification
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50 alert-enter';
                notification.innerHTML = `
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <div class="font-medium">API Test Successful!</div>
                                    <div class="text-sm">System: ${systemData.status || 'OK'} | Streams: ${streamData.active_sessions || 0} active</div>
                                </div>
                            </div>
                        `;
                document.body.appendChild(notification);

                // Auto remove notification
                setTimeout(() => {
                    notification.remove();
                }, 5000);

                console.log('System Metrics:', systemData);
                console.log('Stream Metrics:', streamData);

            } catch (error) {
                console.error('API Test Error:', error);

                // Show error notification
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50 alert-enter';
                notification.innerHTML = `
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <div>
                                    <div class="font-medium">API Test Failed!</div>
                                    <div class="text-sm">Check console for details</div>
                                </div>
                            </div>
                        `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);
            } finally {
                // Restore button
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 1000);
            }
        }
    </script>
@endpush