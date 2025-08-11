@section('title', 'Dashboard')
<div>
    <div class="mb-4">
        <h1 class="text-2xl font-bold text-[#3BA172]">Welcome back, {{ Auth::user()->name ?? 'Admin' }}!</h1>
        <p class="text-gray-600">Here's what's happening in your CBT system today.</p>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Users -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl p-4 shadow-lg border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <h3 class="text-2xl font-bold text-[#3BA172]">{{ number_format($totalUsers) }}</h3>
                    <p class="text-xs text-green-600">Registered users</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <h3 class="text-2xl font-bold text-[#3BA172]">{{ $todayExams }}</h3>
                    <p class="text-xs text-blue-600">Exams started today</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <h3 class="text-2xl font-bold text-[#3BA172]">{{ $activeExams }}</h3>
                    <p class="text-xs text-orange-600">Currently in progress</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <h3 class="text-2xl font-bold text-[#3BA172]">{{ $examAlerts }}</h3>
                    <p class="text-xs text-red-600">Security violations</p>
                </div>
                <div class="bg-[#C3D4EC]/20 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-[#3BA172]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
