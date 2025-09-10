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
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-[#f58634]">Welcome back, {{ Auth::user()->name ?? 'Admin' }}!</h1>
                <p class="text-gray-600 mt-1">Here's what's happening in your CBT system today.</p>
                {{-- <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, F j, Y') }}</p> --}}
            </div>
            {{-- <div class="flex items-center gap-3">
                <!-- Auto Refresh Toggle -->
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="autoRefresh" class="sr-only peer" checked>
                    <div
                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#f58634]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#f58634]">
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-600">Auto Refresh</span>
                </label>

                <!-- Refresh Button -->
                <button wire:click="refreshData"
                    class="inline-flex items-center px-4 py-2 bg-[#f58634] hover:bg-[#2d8c5b] text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Refresh
                </button>
            </div> --}}
        </div>
    </div>

    <!-- Main Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Users</p>
                    <h3 class="text-3xl font-bold text-[#f58634]">{{ number_format($totalUsers) }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">
                            @if (isset($monthlyStats['new_users_this_month']) && $monthlyStats['new_users_this_month'] > 0)
                                +{{ $monthlyStats['new_users_this_month'] }} this month
                            @else
                                Registered users
                            @endif
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-[#f58634]/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-[#f58634]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <p class="text-sm text-gray-600 mb-1">Today's Exams</p>
                    <h3 class="text-3xl font-bold text-[#f58634]">{{ $todayExams }}</h3>
                    <div class="flex items-center mt-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full pulse-dot mr-2"></div>
                        <span class="text-xs text-blue-600">Exams started today</span>
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
                    <p class="text-sm text-gray-600 mb-1">Active Exams</p>
                    <h3 class="text-3xl font-bold text-[#f58634]">{{ $activeExams }}</h3>
                    <div class="flex items-center mt-2">
                        @if ($activeExams > 0)
                            <div class="w-2 h-2 bg-orange-500 rounded-full pulse-dot mr-2"></div>
                            <span class="text-xs text-orange-600">Currently in progress</span>
                        @else
                            <span class="text-xs text-gray-500">No active exams</span>
                        @endif
                    </div>
                </div>
                <div class="bg-gradient-to-br from-orange-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <p class="text-sm text-gray-600 mb-1">Security Alerts</p>
                    <h3 class="text-3xl font-bold text-[#f58634]">{{ $examAlerts }}</h3>
                    <div class="flex items-center mt-2">
                        @if ($examAlerts > 0)
                            <div class="w-2 h-2 bg-red-500 rounded-full pulse-dot mr-2"></div>
                            <span class="text-xs text-red-600">Security violations</span>
                        @else
                            <span class="text-xs text-green-600">All secure</span>
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
            <p class="text-sm text-gray-600">Completed Exams</p>
            @if (isset($monthlyStats['completed_this_month']))
                <p class="text-xs text-green-600 mt-1">{{ $monthlyStats['completed_this_month'] }} this month</p>
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
            <p class="text-sm text-gray-600">Exam Categories</p>
            <p class="text-xs text-purple-600 mt-1">Available types</p>
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
            <p class="text-sm text-gray-600">Completion Rate</p>
            <p class="text-xs text-indigo-600 mt-1">This month average</p>
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
            <p class="text-sm text-gray-600">Live Sessions</p>
            <div class="flex items-center mt-1">
                <div class="w-2 h-2 bg-yellow-500 rounded-full pulse-dot mr-2"></div>
                <span class="text-xs text-yellow-600">Active now</span>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
        <!-- Weekly Exam Trends -->
        <div class="lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Weekly Exam Trends</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-[#f58634] rounded-full"></div>
                    <span class="text-sm text-gray-600">Exams Started</span>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="weeklyChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Exam Status Distribution -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Exam Status Distribution</h3>

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
                    @endphp

                    @foreach ($examStatistics as $status => $count)
                        @php
                            $percentage = $totalExamSessions > 0 ? round(($count / $totalExamSessions) * 100, 1) : 0;
                            $colors = $statusColors[$status] ?? ['bg-gray-500', 'text-gray-700', 'bg-gray-50'];
                        @endphp

                        <div class="flex items-center justify-between p-3 {{ $colors[2] }} rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 {{ $colors[0] }} rounded-full mr-3"></div>
                                <span
                                    class="text-sm font-medium {{ $colors[1] }} capitalize">{{ $status }}</span>
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
                        <p class="text-sm">No exam data available</p>
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
                <h3 class="text-lg font-semibold text-gray-800">Live Session Monitoring</h3>
                <a href="{{ route('admin.exam.live-stream') }}"
                    class="text-[#f58634] hover:text-[#2d8c5b] text-sm font-medium">
                    View All →
                </a>
            </div>

            @if (isset($liveSessionStats))
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $liveSessionStats['active_sessions'] ?? 0 }}
                        </div>
                        <div class="text-sm text-blue-600">Active Sessions</div>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <div class="text-2xl font-bold text-red-600">{{ $liveSessionStats['high_risk'] ?? 0 }}</div>
                        <div class="text-sm text-red-600">High Risk</div>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Camera Issues</span>
                        <span class="font-medium text-orange-600">{{ $liveSessionStats['camera_issues'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Connection Issues</span>
                        <span
                            class="font-medium text-red-600">{{ $liveSessionStats['connection_issues'] ?? 0 }}</span>
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
                    <p class="text-sm">No active sessions</p>
                </div>
            @endif
        </div>

        <!-- Critical Alerts -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Critical Alerts (24h)</h3>
                <a href="{{ route('admin.exam.monitor') }}"
                    class="text-[#f58634] hover:text-[#2d8c5b] text-sm font-medium">
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
                                    Alert Type: {{ $alert->alert_type ?? 'Security Violation' }}
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
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm">No critical alerts</p>
                    <p class="text-xs text-gray-400 mt-1">All systems secure</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Exams and Recent Results -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-8">
        <!-- Upcoming Exams -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Exams</h3>
                <a href="{{ route('admin.exam.timetable') }}"
                    class="text-[#f58634] hover:text-[#2d8c5b] text-sm font-medium">
                    Manage →
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
                    <p class="text-sm">No upcoming exams</p>
                </div>
            @endif
        </div>

        <!-- Recent Exam Results -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Recent Results</h3>
                <a href="{{ route('admin.report.item-analysis') }}"
                    class="text-[#f58634] hover:text-[#2d8c5b] text-sm font-medium">
                    View Reports →
                </a>
            </div>

            @if (isset($recentExamResults) && $recentExamResults->count() > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($recentExamResults as $result)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex-1">
                                <div class="text-sm font-medium text-green-800">{{ $result->user->name ?? 'Unknown' }}
                                </div>
                                <div class="text-xs text-green-600 mt-1">
                                    {{ $result->timetable->module->name ?? 'N/A' }}</div>
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
                    <p class="text-sm">No recent results</p>
                </div>
            @endif
        </div>
    </div>

    <!-- System Performance -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">System Performance</h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @if (isset($systemPerformance))
                <!-- Response Time -->
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 mb-2">
                        {{ $systemPerformance['avg_response_time'] ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600">Avg Response Time</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                    </div>
                </div>

                <!-- Uptime -->
                <div class="text-center relative">
                    <div class="text-2xl font-bold text-green-600 mb-2 cursor-pointer" onclick="toggleUptimeModal()"
                        title="Click for detailed uptime information">
                        {{ $systemPerformance['system_uptime'] ?? 'N/A' }}
                        <svg class="w-4 h-4 inline-block ml-1 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-sm text-gray-600">System Uptime</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        @if (isset($uptimeDetails))
                            @php
                                $uptimeValue = (float) str_replace('%', '', $systemPerformance['system_uptime']);
                                $colorClass =
                                    $uptimeValue >= 99
                                        ? 'bg-green-600'
                                        : ($uptimeValue >= 95
                                            ? 'bg-yellow-500'
                                            : 'bg-red-500');
                            @endphp
                            <div class="{{ $colorClass }} h-2 rounded-full transition-all duration-1000"
                                style="width: {{ $uptimeValue }}%"></div>
                        @else
                            <div class="bg-green-600 h-2 rounded-full" style="width: 99%"></div>
                        @endif
                    </div>

                    <!-- Real-time status indicator -->
                    @if (isset($uptimeDetails))
                        <div class="mt-2 flex items-center justify-center">
                            @php
                                $status = $uptimeDetails['status'] ?? 'Operational';
                                $statusColor =
                                    $status === 'Operational'
                                        ? 'green'
                                        : ($status === 'Minor Issues'
                                            ? 'yellow'
                                            : 'red');
                            @endphp
                            <div
                                class="w-2 h-2 rounded-full mr-2 {{ $statusColor === 'green' ? 'bg-green-500 pulse-dot' : ($statusColor === 'yellow' ? 'bg-yellow-500' : 'bg-red-500') }}">
                            </div>
                            <span class="text-xs text-{{ $statusColor }}-600">{{ $status }}</span>
                        </div>
                    @endif
                </div>

                <!-- Concurrent Users -->
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600 mb-2">
                        {{ $systemPerformance['concurrent_users'] ?? 0 }}</div>
                    <div class="text-sm text-gray-600">Concurrent Users</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: 60%"></div>
                    </div>
                </div>

                <!-- Server Load -->
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 mb-2">
                        {{ $systemPerformance['server_load'] ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600">Server Load</div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            @else
                <div class="col-span-4 text-center text-gray-500 py-8">
                    <p class="text-sm">System performance data not available</p>
                </div>
            @endif
        </div>

        <!-- Real-time Monitoring Button -->
        <div class="mt-6 text-center">
            <button onclick="openRealtimeModal()"
                class="bg-gradient-to-r from-[#f58634] to-[#2d8c5b] text-white px-6 py-3 rounded-lg hover:from-[#2d8c5b] hover:to-[#236647] transition-all duration-300 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                View Real-time Metrics
            </button>
        </div>
    </div>

    <!-- Real-time Performance Monitoring Modal -->
    <div id="realtimeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-6xl w-full max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold text-gray-800">Real-time Performance Monitoring</h3>
                        <button onclick="closeRealtimeModal()" class="text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if (isset($systemPerformance['realtime_metrics']))
                        @php $metrics = $systemPerformance['realtime_metrics']; @endphp

                        <!-- Status Overview -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg">
                                <div class="text-sm text-blue-600 mb-1">Database Response</div>
                                <div class="text-xl font-bold text-blue-800">
                                    {{ $metrics['database']['response_time'] ?? 'N/A' }}</div>
                                <div class="text-xs text-blue-600">Success:
                                    {{ $metrics['database']['success_rate'] ?? 'N/A' }}</div>
                            </div>

                            <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg">
                                <div class="text-sm text-green-600 mb-1">Throughput</div>
                                <div class="text-xl font-bold text-green-800">
                                    {{ $metrics['system_health']['throughput'] ?? 'N/A' }}</div>
                                <div class="text-xs text-green-600">Operations per minute</div>
                            </div>

                            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg">
                                <div class="text-sm text-yellow-600 mb-1">Error Rate</div>
                                <div class="text-xl font-bold text-yellow-800">
                                    {{ $metrics['system_health']['error_rate'] ?? 'N/A' }}</div>
                                <div class="text-xs text-yellow-600">Last hour</div>
                            </div>

                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-4 rounded-lg">
                                <div class="text-sm text-purple-600 mb-1">Memory Usage</div>
                                <div class="text-xl font-bold text-purple-800">
                                    {{ $metrics['system_health']['memory_usage_estimate'] ?? 'N/A' }}</div>
                                <div class="text-xs text-purple-600">Estimated</div>
                            </div>
                        </div>

                        <!-- User Activity Charts -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold mb-4">User Activity Timeline</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Last minute</span>
                                        <span
                                            class="font-semibold">{{ $metrics['user_activity']['last_minute'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Last 5 minutes</span>
                                        <span
                                            class="font-semibold">{{ $metrics['user_activity']['last_5_minutes'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Last 15 minutes</span>
                                        <span
                                            class="font-semibold">{{ $metrics['user_activity']['last_15_minutes'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t pt-2">
                                        <span class="text-sm font-medium text-gray-700">Active now</span>
                                        <span
                                            class="font-bold text-green-600">{{ $metrics['user_activity']['active_now'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-lg font-semibold mb-4">Exam Activity</h4>
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Started (1min)</span>
                                        <span
                                            class="font-semibold">{{ $metrics['exam_activity']['started_last_minute'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Completed (1min)</span>
                                        <span
                                            class="font-semibold">{{ $metrics['exam_activity']['completed_last_minute'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Alerts (1min)</span>
                                        <span
                                            class="font-semibold text-red-600">{{ $metrics['exam_activity']['alerts_last_minute'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center border-t pt-2">
                                        <span class="text-sm font-medium text-gray-700">Avg. Completion</span>
                                        <span
                                            class="font-bold">{{ $metrics['exam_activity']['average_completion_time'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Peak Performance Today -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-semibold mb-4 text-indigo-800">Peak Performance Today</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-indigo-600">
                                        {{ $metrics['peak_metrics']['peak_concurrent_today'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-indigo-600">Peak Concurrent</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">
                                        {{ $metrics['peak_metrics']['peak_response_time_today'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-purple-600">Peak Response</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-pink-600">
                                        {{ $metrics['peak_metrics']['total_requests_today'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-pink-600">Total Requests</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ $metrics['peak_metrics']['success_rate_today'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-green-600">Success Rate</div>
                                </div>
                            </div>
                        </div>

                        <!-- System Status Indicators -->
                        @if (isset($metrics['status_indicators']))
                            <div class="bg-white border border-gray-200 p-6 rounded-lg">
                                <h4 class="text-lg font-semibold mb-4">System Health Status</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="flex items-center">
                                        <div
                                            class="w-4 h-4 rounded-full mr-3 {{ $metrics['status_indicators']['response_time_status'] === 'good' ? 'bg-green-500' : ($metrics['status_indicators']['response_time_status'] === 'fair' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                        </div>
                                        <span class="text-sm">Response Time:
                                            {{ ucfirst($metrics['status_indicators']['response_time_status']) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div
                                            class="w-4 h-4 rounded-full mr-3 {{ $metrics['status_indicators']['server_load_status'] === 'good' ? 'bg-green-500' : ($metrics['status_indicators']['server_load_status'] === 'fair' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                        </div>
                                        <span class="text-sm">Server Load:
                                            {{ ucfirst($metrics['status_indicators']['server_load_status']) }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div
                                            class="w-4 h-4 rounded-full mr-3 {{ $metrics['status_indicators']['error_rate_status'] === 'good' ? 'bg-green-500' : ($metrics['status_indicators']['error_rate_status'] === 'fair' ? 'bg-yellow-500' : 'bg-red-500') }}">
                                        </div>
                                        <span class="text-sm">Error Rate:
                                            {{ ucfirst($metrics['status_indicators']['error_rate_status']) }}</span>
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                    {{ $metrics['status_indicators']['overall_status'] === 'excellent'
                                        ? 'bg-green-100 text-green-800'
                                        : ($metrics['status_indicators']['overall_status'] === 'good'
                                            ? 'bg-blue-100 text-blue-800'
                                            : ($metrics['status_indicators']['overall_status'] === 'fair'
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800')) }}">
                                        Overall Status: {{ ucfirst($metrics['status_indicators']['overall_status']) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="mt-6 text-center text-xs text-gray-500">
                            Last updated: {{ $metrics['timestamp'] ?? 'Unknown' }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Real-time metrics not available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Weekly Chart
                const ctx = document.getElementById('weeklyChart');
                if (ctx) {
                    const weeklyData = @json($weeklyExamStats ?? []);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: weeklyData.map(item => item.date),
                            datasets: [{
                                label: 'Exams Started',
                                data: weeklyData.map(item => item.count),
                                borderColor: '#f58634',
                                backgroundColor: 'rgba(59, 161, 114, 0.1)',
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
                }

                // Auto refresh functionality
                const autoRefreshToggle = document.getElementById('autoRefresh');
                let refreshInterval;

                function startAutoRefresh() {
                    refreshInterval = setInterval(() => {
                        Livewire.dispatch('refreshData');

                        // If real-time modal is open, refresh its content too
                        if (!document.getElementById('realtimeModal').classList.contains('hidden')) {
                            // Add visual indicator of refresh
                            const modal = document.getElementById('realtimeModal');
                            modal.style.opacity = '0.8';
                            setTimeout(() => {
                                modal.style.opacity = '1';
                            }, 500);
                        }
                    }, 30000); // Refresh every 30 seconds
                }

                function stopAutoRefresh() {
                    if (refreshInterval) {
                        clearInterval(refreshInterval);
                    }
                }

                if (autoRefreshToggle) {
                    autoRefreshToggle.addEventListener('change', function() {
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

                // Cleanup on page unload
                window.addEventListener('beforeunload', stopAutoRefresh);
            });

            // Real-time Modal Functions
            function openRealtimeModal() {
                document.getElementById('realtimeModal').classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeRealtimeModal() {
                document.getElementById('realtimeModal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Close modal when clicking outside
            document.getElementById('realtimeModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRealtimeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeRealtimeModal();
                }
            });
        </script>
    @endpush
