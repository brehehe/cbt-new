<header class="p-2 text-white bg-blue-800 shadow-lg sm:p-4">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
            <h1 class="text-lg font-bold sm:text-xl">Computer Based Test</h1>
            <div class="px-2 py-1 bg-blue-700 rounded sm:px-3">
                <span class="text-xs sm:text-sm">Mata Kuliah: Pemrograman Web</span>
            </div>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
            <div class="text-center sm:text-right">
                <div class="text-xs sm:text-sm opacity-90">Waktu Tersisa</div>
                <div class="font-mono text-base font-bold text-yellow-300 sm:text-lg" id="timer">01:59:45
                </div>
            </div>
            <button
                class="px-3 py-2 text-xs font-medium transition-colors bg-red-600 rounded sm:px-4 sm:text-sm hover:bg-red-700">
                Selesai Ujian
            </button>
        </div>
    </div>
</header>
<!-- Mobile Menu Toggle Button -->
<div class="p-4 bg-white border-b border-gray-200 lg:hidden">
    <div class="flex items-center justify-between">
        <button id="toggleLeftSidebar" class="flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            Navigasi Soal
        </button>
        <button id="toggleRightSidebar" class="flex items-center text-blue-600 hover:text-blue-800">
            Profil & Camera
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </button>
    </div>
</div>
