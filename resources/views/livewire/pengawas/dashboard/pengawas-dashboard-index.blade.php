@section('title', 'Dashboard Pengawas')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }

        .stats-card {
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

        .monitoring-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .monitoring-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-left-color: #f58634;
        }

        .alert-high {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .alert-medium {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .alert-low {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .status-normal {
            color: #10b981;
            background-color: #d1fae5;
        }

        .status-warning {
            color: #f59e0b;
            background-color: #fef3c7;
        }

        .status-critical {
            color: #ef4444;
            background-color: #fee2e2;
        }

        .student-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }

        .student-tile {
            position: relative;
            border-radius: 0.5rem;
            padding: 1rem;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .student-tile.normal {
            background-color: #f0fdf4;
            border-color: #22c55e;
        }

        .student-tile.suspicious {
            background-color: #fffbeb;
            border-color: #f59e0b;
            animation: pulse 2s infinite;
        }

        .student-tile.violation {
            background-color: #fef2f2;
            border-color: #ef4444;
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            50% {
                opacity: 1;
            }

            51%,
            100% {
                opacity: 0.7;
            }
        }

        .live-indicator {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 50%;
        }

        .camera-preview {
            width: 100%;
            height: 80px;
            background-color: #000;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.5rem;
        }

        .quick-action-btn {
            transition: all 0.2s ease;
        }

        .quick-action-btn:hover {
            transform: scale(1.05);
        }

        .real-time-badge {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            53%,
            80%,
            100% {
                transform: translateY(0);
            }

            40%,
            43% {
                transform: translateY(-8px);
            }

            70% {
                transform: translateY(-4px);
            }
        }
    </style>
@endpush

<div>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[color:var(--primary)]">
                    Monitoring Center - {{ Auth::user()->name ?? 'Pengawas' }}
                </h1>
                <p class="text-gray-600 mt-1">Pantau ujian dan deteksi pelanggaran secara real-time</p>
                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, j F Y - H:i:s') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Emergency Alert Button -->
                <button
                    class="quick-action-btn inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    Emergency Alert
                </button>

                <!-- Auto Refresh Toggle -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="autoRefresh" class="sr-only peer" checked>
                    <div
                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#f58634]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary ">
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-600">Auto Refresh</span>
                </label>

                <!-- Real-time Status -->
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-green-500 pulse-dot"></div>
                    <span class="text-sm text-gray-600 real-time-badge">LIVE</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Monitoring Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Active Examinees -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Peserta Aktif</p>
                    <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                        {{ $activeExaminees ?? 0 }}
                    </h3>
                    <div class="flex items-center mt-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot mr-2"></div>
                        <span class="text-xs text-green-600">{{ $onlineStudents ?? 0 }} online</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-[#f58634]/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-[color:var(--primary)]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Security Alerts -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Peringatan Keamanan</p>
                    <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                        {{ $securityAlerts ?? 0 }}
                    </h3>
                    <div class="flex items-center mt-2">
                        @if (($criticalAlerts ?? 0) > 0)
                            <div class="w-2 h-2 bg-red-500 rounded-full pulse-dot mr-2"></div>
                            <span class="text-xs text-red-600">{{ $criticalAlerts ?? 0 }} kritis</span>
                        @else
                            <span class="text-xs text-green-600">Aman</span>
                        @endif
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Camera Issues -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Masalah Kamera</p>
                    <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                        {{ $cameraIssues ?? 0 }}
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                            {{ $workingCameras ?? 0 }} berfungsi
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Exam Rooms -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ruang Ujian Aktif</p>
                    <h3 class="text-3xl font-bold text-[color:var(--primary)]">
                        {{ $activeRooms ?? 0 }}
                    </h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">
                            {{ $totalRooms ?? 0 }} total ruang
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <button
            class="quick-action-btn monitoring-card bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100 text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-800">Monitor Kamera</h3>
        </button>

        <button
            class="quick-action-btn monitoring-card bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100 text-center">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                    </path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-800">Lapor Pelanggaran</h3>
        </button>

        <button
            class="quick-action-btn monitoring-card bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100 text-center">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-800">Buat Laporan</h3>
        </button>

        <button
            class="quick-action-btn monitoring-card bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100 text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-800">Pengaturan</h3>
        </button>
    </div>

    <!-- Real-time Student Monitoring Grid -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Monitoring Peserta Real-time</h3>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded"></div>
                    <span class="text-xs text-gray-600">Normal ({{ $normalStudents ?? 0 }})</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-yellow-500 rounded"></div>
                    <span class="text-xs text-gray-600">Mencurigakan ({{ $suspiciousStudents ?? 0 }})</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded"></div>
                    <span class="text-xs text-gray-600">Pelanggaran ({{ $violationStudents ?? 0 }})</span>
                </div>
            </div>
        </div>

        <div class="student-grid">
            @if (isset($monitoringStudents) && count($monitoringStudents) > 0)
                @foreach ($monitoringStudents as $student)
                    <div class="student-tile {{ $student['status'] ?? 'normal' }}">
                        <div
                            class="live-indicator {{ $student['camera_status'] === 'online' ? 'bg-green-500' : 'bg-red-500' }} pulse-dot">
                        </div>

                        <div class="camera-preview">
                            @if ($student['camera_status'] === 'online')
                                <div class="text-white text-xs text-center">
                                    <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    LIVE
                                </div>
                            @else
                                <div class="text-gray-400 text-xs text-center">
                                    <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-3-3m-12.728-12.728L3 3l3 3">
                                        </path>
                                    </svg>
                                    OFFLINE
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <h4 class="font-semibold text-sm text-gray-800">{{ $student['name'] ?? 'Student' }}</h4>
                            <p class="text-xs text-gray-600">{{ $student['nim'] ?? 'NIM' }}</p>
                            <p class="text-xs text-gray-500">{{ $student['exam'] ?? 'Ujian' }}</p>

                            <div class="mt-2 flex justify-between text-xs">
                                <span class="text-gray-600">Progress:</span>
                                <span class="font-medium">{{ $student['progress'] ?? '0' }}%</span>
                            </div>

                            <div class="mt-1 flex justify-between text-xs">
                                <span class="text-gray-600">Waktu:</span>
                                <span class="font-medium">{{ $student['remaining_time'] ?? '00:00' }}</span>
                            </div>

                            @if (isset($student['alerts']) && count($student['alerts']) > 0)
                                <div class="mt-2">
                                    @foreach ($student['alerts'] as $alert)
                                        <span class="inline-block bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full mr-1 mb-1">
                                            {{ $alert }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center text-gray-500 py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-lg font-medium">Tidak ada peserta yang sedang ujian</p>
                    <p class="text-sm">Monitoring akan dimulai saat ujian berlangsung</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Security Alerts and Incident Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Security Alerts -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Peringatan Keamanan Terbaru</h3>
                <span class="text-sm text-red-600 font-medium">{{ ($recentAlerts ?? collect())->count() }}
                    alerts</span>
            </div>

            @if (isset($recentAlerts) && $recentAlerts->count() > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($recentAlerts as $alert)
                        <div
                            class="{{ $alert->severity === 'high' ? 'alert-high' : ($alert->severity === 'medium' ? 'alert-medium' : 'alert-low') }} text-white rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="font-semibold">{{ $alert->title ?? 'Security Alert' }}</h4>
                                    <p class="text-sm opacity-90">{{ $alert->student_name ?? 'Unknown Student' }}</p>
                                    <p class="text-xs opacity-75">{{ $alert->description ?? 'No description' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs opacity-75">{{ $alert->time ?? 'Unknown time' }}</p>
                                    <span class="inline-block bg-white/20 px-2 py-1 rounded text-xs font-medium">
                                        {{ ucfirst($alert->severity ?? 'low') }}
                                    </span>
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
                    <p class="text-sm">Tidak ada peringatan keamanan</p>
                    <p class="text-xs text-gray-400">Sistem berjalan normal</p>
                </div>
            @endif
        </div>

        <!-- System Status -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Status Sistem</h3>

            @if (isset($systemStatus))
                <div class="space-y-4">
                    @foreach ($systemStatus as $component => $status)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 {{ $status['status'] === 'normal' ? 'bg-green-100' : ($status['status'] === 'warning' ? 'bg-yellow-100' : 'bg-red-100') }} rounded-lg flex items-center justify-center">
                                    @if ($status['status'] === 'normal')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @elseif($status['status'] === 'warning')
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $status['name'] ?? ucfirst($component) }}
                                    </p>
                                    <p class="text-xs text-gray-600">{{ $status['description'] ?? 'No description' }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-medium {{ $status['status'] === 'normal' ? 'status-normal' : ($status['status'] === 'warning' ? 'status-warning' : 'status-critical') }}">
                                {{ ucfirst($status['status'] ?? 'unknown') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm">Status sistem tidak tersedia</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Monitoring Statistics Chart -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Statistik Monitoring (24 Jam)</h3>
        <div class="chart-container" wire:ignore>
            <canvas id="monitoringChart" width="400" height="200"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('vendor/chartjs/chart.umd.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Monitoring Chart
            const ctx = document.getElementById('monitoringChart');
            if (ctx) {
                const monitoringData = @json($monitoringStats ?? []);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: monitoringData.map(item => item.hour) || Array.from({
                            length: 24
                        }, (_, i) => `${i}:00`),
                        datasets: [{
                            label: 'Peserta Aktif',
                            data: monitoringData.map(item => item.active_students) || Array.from({
                                length: 24
                            }, () => Math.floor(Math.random() * 50)),
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Peringatan Keamanan',
                            data: monitoringData.map(item => item.security_alerts) || Array.from({
                                length: 24
                            }, () => Math.floor(Math.random() * 10)),
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }, {
                            label: 'Masalah Teknis',
                            data: monitoringData.map(item => item.technical_issues) || Array.from({
                                length: 24
                            }, () => Math.floor(Math.random() * 5)),
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Auto refresh functionality
            const autoRefreshToggle = document.getElementById('autoRefresh');
            let refreshInterval;

            function startAutoRefresh() {
                refreshInterval = setInterval(() => {
                    // Refresh monitoring data
                    console.log('Refreshing monitoring data...');
                    // Add actual refresh logic here
                }, 10000); // Refresh every 10 seconds for real-time monitoring
            }

            function stopAutoRefresh() {
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                }
            }

            if (autoRefreshToggle) {
                autoRefreshToggle.addEventListener('change', function () {
                    if (this.checked) {
                        startAutoRefresh();
                    } else {
                        stopAutoRefresh();
                    }
                });

                // Start auto refresh if enabled by default
                if (autoRefreshToggle.checked) {
                    startAutoRefresh();
                }
            }

            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.fade-in');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Student tile click handlers
            document.querySelectorAll('.student-tile').forEach(tile => {
                tile.addEventListener('click', function () {
                    // Open detailed monitoring view for student
                    console.log('Opening detailed view for student');
                });
            });

            // Quick action button handlers
            document.querySelectorAll('.quick-action-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    // Handle quick actions
                    console.log('Quick action clicked:', this.textContent);
                });
            });
        });
    </script>
@endpush