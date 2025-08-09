@section('title', 'Dashboard')
<div>
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-[#1E3A8A]">Welcome back, {{ Auth::user()->name ?? 'Admin' }}!</h1>
        <p class="text-gray-600">Here's what's happening in your CBT system today.</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Users -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <h3 class="text-2xl font-bold text-[#1E3A8A]">{{ number_format($totalUsers) }}</h3>
                    <p class="text-xs text-green-600">Registered users</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Exams -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Today's Exams</p>
                    <h3 class="text-2xl font-bold text-[#1E3A8A]">{{ $todayExams }}</h3>
                    <p class="text-xs text-blue-600">Exams started today</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Exams -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Active Exams</p>
                    <h3 class="text-2xl font-bold text-[#1E3A8A]">{{ $activeExams }}</h3>
                    <p class="text-xs text-orange-600">Currently in progress</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Exam Alerts -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Exam Alerts</p>
                    <h3 class="text-2xl font-bold text-[#1E3A8A]">{{ $examAlerts }}</h3>
                    <p class="text-xs text-red-600">Security violations</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#1E3A8A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Exam Statistics -->
        <div class="lg:col-span-2">
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="mb-4 text-lg font-semibold text-[#1E3A8A]">Exam Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Completed</p>
                                <p class="text-xl font-bold text-green-600">{{ $completedExams }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C20.168 18.477 18.582 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Total Exams</p>
                                <p class="text-xl font-bold text-blue-600">{{ $totalExams }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">Exam Types</p>
                                <p class="text-xl font-bold text-purple-600">{{ $totalExamTypes }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-yellow-100 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-gray-600">In Progress</p>
                                <p class="text-xl font-bold text-yellow-600">{{ $activeExams }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Exam Results -->
        <div class="lg:col-span-1">
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="mb-4 text-lg font-semibold text-[#1E3A8A]">Recent Exam Results</h3>
                <div class="space-y-3">
                    @forelse($recentExamResults as $result)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="bg-[#C3D4EC] rounded-full p-2">
                                    <svg class="w-4 h-4 text-[#1E3A8A]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $result->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-500">{{ $result->timetable->module->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-[#1E3A8A]">
                                    {{ number_format($result->mark ?? 0, 1) }}%</p>
                                <p class="text-xs text-gray-500">
                                    {{ $result->updated_at ? $result->updated_at->format('M d, H:i') : 'N/A' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-4">
                            <p>No recent exam results</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="mt-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Quick Actions -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="mb-4 text-lg font-semibold text-[#1E3A8A]">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="/admin/master/exam-type"
                        class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                        <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm font-medium text-blue-600">Manage Exam Types</span>
                    </a>

                    <a href="/admin/exam/timetable"
                        class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                        <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-green-600">View Exams</span>
                    </a>

                    <a href="/admin/report/question"
                        class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                        <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="text-sm font-medium text-purple-600">View Reports</span>
                    </a>

                    <button
                        class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors"
                        wire:click="refreshData">
                        <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-sm font-medium text-orange-600">Refresh Data</span>
                    </button>
                </div>
            </div>

            <!-- System Info -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
                <h3 class="mb-4 text-lg font-semibold text-[#1E3A8A]">System Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">System Version</span>
                        <span class="font-medium text-gray-800">CBT v1.0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Laravel Version</span>
                        <span class="font-medium text-gray-800">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">PHP Version</span>
                        <span class="font-medium text-gray-800">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Last Updated</span>
                        <span class="font-medium text-gray-800">{{ now()->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
