@section('title', 'Dashboard Dosen')

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

        .exam-status-active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .exam-status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .exam-status-completed {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .quick-action-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .quick-action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            border-left-color: #f58634;
        }
    </style>
@endpush

<div>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-3xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                    Selamat Datang, {{ Auth::user()->name ?? 'Dosen' }}!</h1>
                <p class="text-gray-600 mt-1">Kelola ujian dan monitor performa mahasiswa Anda</p>
                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, j F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Quick Actions Dropdown -->
                <div class="relative">
                    <button id="quickActionsBtn"
                        class="inline-flex items-center px-4 py-2 {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'bg-[#2b7fff]' : 'bg-[#f58634]' }} hover:bg-[#2d8c5b] text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Aksi Cepat
                    </button>
                </div>

                <!-- Real-time Status -->
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-green-500 pulse-dot"></div>
                    <span class="text-sm text-gray-600">Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stats Overview for Dosen -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Ujian Saya -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Ujian Saya</p>
                    <h3
                        class="text-3xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                        {{ $totalMyExams ?? 0 }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                            {{ $activeExams ?? 0 }} sedang berlangsung
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-[#f58634]/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Terdaftar -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Mahasiswa Terdaftar</p>
                    <h3
                        class="text-3xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                        {{ $totalStudents ?? 0 }}</h3>
                    <div class="flex items-center mt-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full pulse-dot mr-2"></div>
                        <span class="text-xs text-green-600">{{ $activeStudents ?? 0 }} sedang ujian</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bank Soal -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Bank Soal</p>
                    <h3
                        class="text-3xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                        {{ $totalQuestions ?? 0 }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">
                            {{ $questionTypes ?? 0 }} kategori
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rata-rata Nilai -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Rata-rata Nilai</p>
                    <h3
                        class="text-3xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                        {{ number_format($averageScore ?? 0, 1) }}</h3>
                    <div class="flex items-center mt-2">
                        @if (($averageScore ?? 0) >= 75)
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded-full">Sangat Baik</span>
                        @elseif(($averageScore ?? 0) >= 60)
                            <span class="text-xs text-yellow-600 bg-yellow-50 px-2 py-1 rounded-full">Baik</span>
                        @else
                            <span class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-full">Perlu Peningkatan</span>
                        @endif
                    </div>
                </div>
                <div class="bg-gradient-to-br from-indigo-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Buat Ujian Baru -->
        <a href="{{ route('admin.master.timetable') }}"
            class="quick-action-card bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 block">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-br from-blue-500/20 to-blue-600/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">Buat Ujian Baru</h3>
            <p class="text-sm text-gray-600 text-center">Siapkan ujian untuk mahasiswa Anda</p>
        </a>

        <!-- Kelola Soal -->
        <a href="{{ route('admin.master.question') }}"
            class="quick-action-card bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 block">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-br from-green-500/20 to-green-600/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">Kelola Soal</h3>
            <p class="text-sm text-gray-600 text-center">Buat dan edit bank soal ujian</p>
        </a>

        <!-- Lihat Hasil -->
        <a href="{{ route('admin.report.item-analysis') }}"
            class="quick-action-card bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 block">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-br from-purple-500/20 to-purple-600/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">Analisis Hasil</h3>
            <p class="text-sm text-gray-600 text-center">Evaluasi performa mahasiswa</p>
        </a>

        <!-- Monitor Ujian -->
        <a href="{{ route('admin.exam.monitor') }}"
            class="quick-action-card bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 block">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-gradient-to-br from-orange-500/20 to-orange-600/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-blue-600' : 'text-orange-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2 text-center">Monitor Ujian</h3>
            <p class="text-sm text-gray-600 text-center">Pantau ujian berlangsung</p>
        </a>
    </div>

    <!-- Current Exams Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Ujian Berlangsung -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ujian Berlangsung</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full pulse-dot"></div>
                    <span class="text-sm text-green-600">{{ $activeExams ?? 0 }} aktif</span>
                </div>
            </div>

            @if (!empty($currentExams))
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($currentExams as $exam)
                        <div class="exam-status-active bg-blue-100 text-gray-800 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold">{{ $exam['name'] ?? 'Ujian' }}</h4>
                                    <p class="text-sm opacity-90">{{ $exam['participants'] ?? 0 }} peserta</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm opacity-90">Sisa waktu:</p>
                                    <p class="font-semibold">{{ $exam['remaining_time'] ?? 'N/A' }}</p>
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
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm">Tidak ada ujian yang berlangsung</p>
                </div>
            @endif
        </div>

        <!-- Ujian Mendatang -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ujian Mendatang</h3>
                <a href="{{ route('admin.exam.timetable') }}"
                    class="{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }} hover:text-[#2d8c5b] text-sm font-medium">
                    Kelola →
                </a>
            </div>

            @if (!empty($upcomingExams))
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($upcomingExams as $exam)
                        <div class="exam-status-pending text-white rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold">{{ $exam['name'] ?? 'Ujian' }}</h4>
                                    <p class="text-sm opacity-90">{{ $exam['participants'] ?? 0 }} terdaftar</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm opacity-90">Mulai:</p>
                                    <p class="font-semibold">{{ $exam['start_time'] ?? 'N/A' }}</p>
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
                    <p class="text-sm">Tidak ada ujian terjadwal</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Student Performance Chart -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Performa Mahasiswa (7 Hari Terakhir)</h3>
            <div class="chart-container" wire:ignore>
                <canvas id="performanceChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Recent Student Activities -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Aktivitas Mahasiswa Terbaru</h3>

            @if (isset($recentActivities) && count($recentActivities) > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($recentActivities as $activity)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $activity['student_name'] ?? 'Mahasiswa' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ $activity['activity'] ?? 'Mengerjakan ujian' }} -
                                    {{ $activity['time'] ?? 'Baru saja' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs font-semibold {{ $activity['status'] === 'completed' ? 'text-green-600' : '{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-blue-600' : 'text-orange-600' }}' }}">
                                    {{ $activity['status'] === 'completed' ? 'Selesai' : 'Berlangsung' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm">Belum ada aktivitas terbaru</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Performance Chart
            const ctx = document.getElementById('performanceChart');
            if (ctx) {
                const performanceData = @json($weeklyPerformance ?? []);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: performanceData.map(item => item.date) || ['Mon', 'Tue', 'Wed', 'Thu',
                            'Fri', 'Sat', 'Sun'
                        ],
                        datasets: [{
                            label: 'Rata-rata Nilai',
                            data: performanceData.map(item => item.average) || [75, 78, 82, 79, 85,
                                88, 90
                            ],
                            borderColor: '#f58634',
                            backgroundColor: 'rgba(245, 134, 52, 0.1)',
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
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
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

            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.fade-in');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Quick Actions Dropdown (if needed)
            const quickActionsBtn = document.getElementById('quickActionsBtn');
            if (quickActionsBtn) {
                quickActionsBtn.addEventListener('click', function() {
                    // Add dropdown functionality here if needed
                    console.log('Quick actions clicked');
                });
            }
        });
    </script>
@endpush
