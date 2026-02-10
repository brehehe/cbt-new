@section('title', 'Dashboard Mahasiswa')

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 250px;
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

        .exam-card-upcoming {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .exam-card-active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .exam-card-completed {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .progress-circle {
            transform: rotate(-90deg);
        }

        .notification-badge {
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

        .grade-excellent {
            color: #10b981;
            background-color: #d1fae5;
        }

        .grade-good {
            color: #3b82f6;
            background-color: #dbeafe;
        }

        .grade-fair {
            color: #f59e0b;
            background-color: #fef3c7;
        }

        .grade-poor {
            color: #ef4444;
            background-color: #fee2e2;
        }
    </style>
@endpush

<div>
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-3xl font-bold text-[{{ $companyData->color_primary }}]">
                    Halo, {{ Auth::user()->name ?? 'Mahasiswa' }}! 👋</h1>
                <p class="text-gray-600 mt-1">Siap untuk mengikuti ujian hari ini?</p>
                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::now()->format('l, j F Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Notifications -->
                <div class="relative">
                    <button
                        class="p-2 text-gray-600 hover:text-[{{ $companyData->color_primary }}] relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5-5 5-5h-5l-5 5z"></path>
                        </svg>
                        @if (($notifications ?? 0) > 0)
                            <span
                                class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $notifications ?? 0 }}
                            </span>
                        @endif
                    </button>
                </div>

                <!-- Connection Status -->
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-green-500 pulse-dot"></div>
                    <span class="text-sm text-gray-600">Online</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Stats for Students -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ujian Hari Ini -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ujian Hari Ini</p>
                    <h3
                        class="text-3xl font-bold text-[{{ $companyData->color_primary }}]">
                        {{ $todayExams ?? 0 }}</h3>
                    <div class="flex items-center mt-2">
                        @if (($activeExamToday ?? 0) > 0)
                            <div class="w-2 h-2 bg-red-500 rounded-full pulse-dot mr-2"></div>
                            <span class="text-xs text-red-600">{{ $activeExamToday ?? 0 }} sedang berlangsung</span>
                        @else
                            <span class="text-xs text-green-600">Semua selesai</span>
                        @endif
                    </div>
                </div>
                <div class="bg-gradient-to-br from-[#f58634]/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-[{{ $companyData->color_primary }}]"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Ujian Diikuti -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Ujian</p>
                    <h3
                        class="text-3xl font-bold text-[{{ $companyData->color_primary }}]">
                        {{ $totalExams ?? 0 }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded-full">
                            {{ $completedExams ?? 0 }} selesai
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-blue-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
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
                        class="text-3xl font-bold text-[{{ $companyData->color_primary }}]">
                        {{ number_format($averageScore ?? 0, 1) }}</h3>
                    <div class="flex items-center mt-2">
                        @php
                            $avgScore = $averageScore ?? 0;
                            if ($avgScore >= 85) {
                                $gradeClass = 'grade-excellent';
                                $gradeText = 'Excellent';
                            } elseif ($avgScore >= 75) {
                                $gradeClass = 'grade-good';
                                $gradeText = 'Good';
                            } elseif ($avgScore >= 65) {
                                $gradeClass = 'grade-fair';
                                $gradeText = 'Fair';
                            } else {
                                $gradeClass = 'grade-poor';
                                $gradeText = 'Need Improvement';
                            }
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full {{ $gradeClass }}">{{ $gradeText }}</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Peringkat Peserta -->
        <div
            class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 fade-in">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Peringkat Peserta</p>
                    <h3
                        class="text-3xl font-bold text-[{{ $companyData->color_primary }}]">
                        #{{ $classRank ?? '-' }}</h3>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-purple-600 bg-purple-50 px-2 py-1 rounded-full">
                            dari {{ $totalStudents ?? 0 }} mahasiswa
                        </span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-500/20 to-[#C3D4EC]/20 p-4 rounded-2xl">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Schedule and Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Ujian Mendatang -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ujian Mendatang</h3>
                <span class="text-sm text-blue-600 font-medium">{{ ($upcomingExams ?? collect())->count() }}
                    ujian</span>
            </div>

            @if (isset($upcomingExams) && $upcomingExams->count() > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($upcomingExams->take(3) as $exam)
                        <div class="exam-card-upcoming text-white rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold">{{ $exam->name ?? 'Ujian' }}</h4>
                                    <p class="text-sm opacity-90">{{ $exam->subject ?? 'Mata Kuliah' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm opacity-90">{{ $exam->date ?? 'TBD' }}</p>
                                    <p class="font-semibold">{{ $exam->time ?? 'TBD' }}</p>
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

        <!-- Ujian Berlangsung -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ujian Berlangsung</h3>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full pulse-dot"></div>
                    <span class="text-sm text-green-600">Live</span>
                </div>
            </div>

            @if (isset($activeExams) && $activeExams->count() > 0)
                <div class="space-y-3">
                    @foreach ($activeExams as $exam)
                        <div class="exam-card-active text-white rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h4 class="font-semibold">{{ $exam->name ?? 'Ujian Aktif' }}</h4>
                                    <p class="text-sm opacity-90">{{ $exam->subject ?? 'Mata Kuliah' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm opacity-90">Sisa waktu:</p>
                                    <p class="font-bold">{{ $exam->remaining_time ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('student.exam.take', $exam->id ?? '#') }}"
                                class="block bg-white/20 hover:bg-white/30 rounded-lg p-2 text-center text-sm font-medium transition-colors">
                                Lanjutkan Ujian
                            </a>
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
                    <p class="text-sm">Tidak ada ujian berlangsung</p>
                </div>
            @endif
        </div>

        <!-- Progress Belajar -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Progress Belajar</h3>

            @if (isset($learningProgress))
                <div class="space-y-4">
                    @foreach ($learningProgress as $subject => $progress)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $subject }}</span>
                                <span class="text-sm text-gray-600">{{ $progress['percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-[#f58634] to-[#2d8c5b] h-2 rounded-full transition-all duration-1000"
                                    style="width: {{ $progress['percentage'] ?? 0 }}%"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $progress['completed'] ?? 0 }}/{{ $progress['total'] ?? 0 }} ujian selesai</p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm">Data progress tidak tersedia</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Results and Achievements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Hasil Ujian Terbaru -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Hasil Ujian Terbaru</h3>
                <a href="{{ route('student.results') }}"
                    class="text-[{{ $companyData->color_primary }}] hover:text-[#2d8c5b] text-sm font-medium">
                    Lihat Semua →
                </a>
            </div>

            @if (isset($recentResults) && $recentResults->count() > 0)
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach ($recentResults as $result)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-800">{{ $result->exam_name ?? 'Ujian' }}</h4>
                                <p class="text-sm text-gray-600">{{ $result->subject ?? 'Mata Kuliah' }}</p>
                                <p class="text-xs text-gray-500">{{ $result->date ?? 'Tanggal' }}</p>
                            </div>
                            <div class="text-right">
                                <div
                                    class="text-2xl font-bold {{ $result->score >= 80 ? 'text-green-600' : ($result->score >= 70 ? 'text-blue-600' : ($result->score >= 60 ? 'text-yellow-600' : 'text-red-600')) }}">
                                    {{ $result->score ?? 0 }}
                                </div>
                                <p class="text-xs text-gray-500">dari 100</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-sm">Belum ada hasil ujian</p>
                </div>
            @endif
        </div>

        <!-- Performance Chart -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Grafik Performa (30 Hari)</h3>
            <div class="chart-container" wire:ignore>
                <canvas id="performanceChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Akses Cepat</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('student.exam.schedule') }}"
                class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group">
                <div
                    class="w-12 h-12 bg-blue-100 group-hover:bg-blue-200 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Jadwal Ujian</span>
            </a>

            <a href="{{ route('student.results') }}"
                class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group">
                <div
                    class="w-12 h-12 bg-green-100 group-hover:bg-green-200 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z">
                        </path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Nilai Saya</span>
            </a>

            <a href="{{ route('student.profile') }}"
                class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group">
                <div
                    class="w-12 h-12 bg-purple-100 group-hover:bg-purple-200 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Profil</span>
            </a>

            <a href="{{ route('student.help') }}"
                class="flex flex-col items-center p-4 rounded-lg transition-colors group
                    {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'bg-orange-50 hover:bg-orange-100' : 'bg-blue-50 hover:bg-blue-100' }}">
                <div
                    class="w-12 h-12 rounded-xl flex items-center justify-center mb-3
                        {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'bg-orange-100 group-hover:bg-orange-200' : 'bg-blue-100 group-hover:bg-blue-200' }}">
                    <svg class="w-6 h-6 {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'text-blue-600' : 'text-orange-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3
                            0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01
                            M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-700">Bantuan</span>
            </a>
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
                const performanceData = @json($monthlyPerformance ?? []);

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: performanceData.map(item => item.date) || Array.from({
                            length: 30
                        }, (_, i) => `Day ${i + 1}`),
                        datasets: [{
                            label: 'Nilai Ujian',
                            data: performanceData.map(item => item.score) || Array.from({
                                length: 30
                            }, () => Math.floor(Math.random() * 30) + 70),
                            borderColor: '#f58634',
                            backgroundColor: 'rgba(245, 134, 52, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#f58634',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 5
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

            // Animate progress bars
            setTimeout(() => {
                const progressBars = document.querySelectorAll('[style*="width:"]');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 100);
                });
            }, 500);
        });
    </script>
@endpush
