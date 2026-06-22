@php
    $brandColor = "text-indigo-600";
    $bgActive = "bg-indigo-50 text-indigo-700";
    $bgHover = "hover:bg-indigo-50 hover:text-indigo-600";

    $exams = [
        ['label' => 'Ujian', 'url' => '/admin/exam/timetable', 'pattern' => 'admin/exam/timetable*', 'icon' => 'fa-file-lines'],
        ['label' => 'Riwayat Ujian', 'url' => '/admin/exam/history-timetable', 'pattern' => 'admin/exam/history-timetable*', 'icon' => 'fa-clock-rotate-left'],
    ];

    $masters = [];
    if (auth()->check()) {
        if (auth()->user()->hasRole('Admin')) {
            $masters = [
                ['label' => 'Skala Penilaian', 'url' => '/admin/master/rating-scale', 'pattern' => 'admin/master/rating-scale', 'icon' => 'fa-chart-bar'],
                ['label' => 'Regulasi', 'url' => '/admin/master/regulation', 'pattern' => 'admin/master/regulation', 'icon' => 'fa-scroll'],
                ['label' => 'Ruang Ujian', 'url' => route('admin.master.exam-room'), 'pattern' => 'admin/master/exam-room*', 'icon' => 'fa-house'],
                ['label' => 'Sesi Ujian', 'url' => route('admin.master.exam-session'), 'pattern' => 'admin/master/exam-session*', 'icon' => 'fa-clock'],
                ['label' => 'Jadwal', 'url' => '/admin/master/timetable', 'pattern' => 'admin/master/timetable*', 'icon' => 'fa-calendar'],
                ['label' => 'Prodi', 'url' => route('admin.master.study'), 'pattern' => 'admin/master/study', 'icon' => 'fa-building-columns'],
                ['label' => 'Peserta', 'url' => '/admin/master/classmate', 'pattern' => 'admin/master/classmate*', 'icon' => 'fa-users'],
                ['label' => 'Admin', 'url' => '/admin/master/admin', 'pattern' => 'admin/master/admin', 'icon' => 'fa-user-shield'],
                ['label' => 'Dosen', 'url' => '/admin/master/lecturer', 'pattern' => 'admin/master/lecturer', 'icon' => 'fa-chalkboard-teacher'],
                ['label' => 'Pengawas', 'url' => '/admin/master/supervisor', 'pattern' => 'admin/master/supervisor', 'icon' => 'fa-user-tie'],
                ['label' => optional(auth()->user()->company)->is_pmb === 'all' ? 'Mahasiswa / PMB' : (optional(auth()->user()->company)->is_pmb === 'pmb' ? 'PMB' : 'Mahasiswa'), 'url' => '/admin/master/student', 'pattern' => 'admin/master/student', 'icon' => 'fa-user-graduate'],
                ['label' => 'Topik Ujian', 'url' => route('admin.master.topic'), 'pattern' => 'admin/master/topic-question', 'icon' => 'fa-tags'],
                ['label' => 'Kategori Materi', 'url' => route('admin.master.material-category'), 'pattern' => 'admin/master/material-category', 'icon' => 'fa-layer-group'],
                ['label' => 'Materi', 'url' => route('admin.master.material'), 'pattern' => 'admin/master/material', 'icon' => 'fa-book'],
                ['label' => 'Tipe Ujian', 'url' => route('admin.master.question-type'), 'pattern' => 'admin/master/question-type', 'icon' => 'fa-list-ol'],
                ['label' => 'Kategori Soal', 'url' => route('admin.master.category-question'), 'pattern' => 'admin/master/category-question', 'icon' => 'fa-list-ul'],
                ['label' => 'Modul Soal', 'url' => route('admin.master.module'), 'pattern' => 'admin/master/module*', 'icon' => 'fa-folder-open'],
                ['label' => 'Bank Soal', 'url' => route('admin.master.question'), 'pattern' => 'admin/master/question*', 'icon' => 'fa-database'],
                ['label' => 'Manajemen Sesi', 'url' => route('admin.session'), 'pattern' => 'admin/session*', 'icon' => 'fa-users-gear'],
                ['label' => 'Pengaturan', 'url' => route('admin.master.setting'), 'pattern' => 'admin/master/setting*', 'icon' => 'fa-cog'],
                ['label' => 'Log Keamanan', 'url' => route('admin.security.log.index'), 'pattern' => 'admin/master/security-log*', 'icon' => 'fa-shield-halved'],
            ];
            if (auth()->user()->username === 'procbt') {
                $masters[] = ['label' => 'Backup Database', 'url' => route('admin.master.backup'), 'pattern' => 'admin/master/backup*', 'icon' => 'fa-database'];
            }
        } elseif (auth()->user()->hasRole('Dosen')) {
            $masters = [
                ['label' => 'Topik Ujian', 'url' => route('admin.master.topic'), 'pattern' => 'admin/master/topic-question', 'icon' => 'fa-tags'],
                ['label' => 'Kategori Materi', 'url' => route('admin.master.material-category'), 'pattern' => 'admin/master/material-category', 'icon' => 'fa-layer-group'],
                ['label' => 'Materi', 'url' => route('admin.master.material'), 'pattern' => 'admin/master/material', 'icon' => 'fa-book'],
                ['label' => 'Tipe Ujian', 'url' => route('admin.master.question-type'), 'pattern' => 'admin/master/question-type', 'icon' => 'fa-list-ol'],
                ['label' => 'Kategori Soal', 'url' => route('admin.master.category-question'), 'pattern' => 'admin/master/category-question', 'icon' => 'fa-list-ul'],
                ['label' => 'Modul Soal', 'url' => route('admin.master.module'), 'pattern' => 'admin/master/module-question*', 'icon' => 'fa-folder-open'],
                ['label' => 'Bank Soal', 'url' => route('admin.master.question'), 'pattern' => 'admin/master/question*', 'icon' => 'fa-database'],
            ];
        } elseif (auth()->user()->hasRole('Pengawas')) {
            $masters = [
                ['label' => 'Ruang Ujian', 'url' => route('admin.master.exam-room'), 'pattern' => 'admin/master/exam-room*', 'icon' => 'fa-house'],
                ['label' => 'Sesi Ujian', 'url' => route('admin.master.exam-session'), 'pattern' => 'admin/master/exam-session*', 'icon' => 'fa-clock'],
                ['label' => 'Jadwal', 'url' => '/admin/master/timetable', 'pattern' => 'admin/master/timetable*', 'icon' => 'fa-calendar'],
            ];
        }
        if (optional(auth()->user()->company)->is_pmb === 'pmb') {
            $masters = array_values(array_filter($masters, function ($item) {
                return $item['label'] !== 'Dosen';
            }));
        }
    }

    $reports = [
        ['label' => 'Riwayat Jadwal Ujian', 'route' => route('admin.report.timetable'), 'match' => 'admin/report/timetable*', 'icon' => 'fa-file-alt'],
        ['label' => 'Laporan Hasil Ujian', 'route' => route('admin.report.exam-result'), 'match' => 'admin/report/exam-result', 'icon' => 'fa-clipboard-list'],
        ['label' => 'Laporan Statistik Jawaban', 'route' => route('admin.report.answer-statistics'), 'match' => 'admin/report/answer-statistics', 'icon' => 'fa-chart-pie'],
        ['label' => 'Laporan Hasil Ujian Mahasiswa', 'route' => route('admin.report.student-exam-result'), 'match' => 'admin/report/student-exam-result', 'icon' => 'fa-clipboard-list'],
        ['label' => 'Analisis Butir Soal', 'route' => route('admin.report.item-analysis'), 'match' => 'admin/report/item-analysis*', 'icon' => 'fa-chart-line'],
        ['label' => 'Analisis Butir Soal (Semua)', 'route' => route('admin.report.item-analysis-all'), 'match' => 'admin/report/item-analysis-all', 'icon' => 'fa-chart-simple'],
    ];
@endphp

<!-- Fixed Top Navbar -->
<nav
    class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur-lg border-b border-slate-200 shadow-sm transition-all duration-300">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo & Brand -->
            <div class="flex items-center gap-4">
                <a href="/admin" class="flex items-center gap-3 group">
                    <img src="{{ $companyData?->logo ? asset('storage/' . $companyData->logo) : asset('asset/img/logo-procbt.png') }}"
                        alt="Logo" class="h-8 w-auto transform transition-transform group-hover:scale-105">
                    <div class="hidden md:block">
                        <h2 class="text-xl font-black text-slate-800 tracking-tight">PROCBT <span
                                class="text-indigo-600">App</span></h2>
                    </div>
                </a>
            </div>

            <!-- Desktop Horizontal Menu -->
            <div class="hidden lg:flex items-center gap-1">
                <!-- Dashboard -->
                <a href="/admin"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ Request::is('admin') ? $bgActive : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Ujian Dropdown -->
                @if (Auth::user()->hasRole(['Mahasiswa', 'Admin']))
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all duration-200">
                            <i class="fa-solid fa-laptop-code text-indigo-500"></i>
                            <span>Ujian</span>
                            <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <!-- Dropdown Content -->
                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute top-full left-0 mt-2 w-56 rounded-2xl bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 p-2 z-50">
                            @foreach ($exams as $exam)
                                <a href="{{ $exam['url'] }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is($exam['pattern']) ? $bgActive : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-lg {{ Request::is($exam['pattern']) ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500' }}">
                                        <i class="fa-solid {{ $exam['icon'] }}"></i>
                                    </div>
                                    <span>{{ $exam['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Master Dropdown (Mega Menu Style for many items) -->
                @if (!Auth::user()->hasRole(['Mahasiswa']) && count($masters) > 0)
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all duration-200">
                            <i class="fa-solid fa-database text-rose-500"></i>
                            <span>Data Master</span>
                            <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <!-- Mega Menu Content -->
                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-[600px] rounded-3xl bg-white shadow-[0_20px_50px_-10px_rgba(0,0,0,0.1)] border border-slate-100 p-4 z-50">
                            <div class="grid grid-cols-2 gap-x-6 gap-y-1">
                                @foreach ($masters as $master)
                                    <a href="{{ $master['url'] }}"
                                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is($master['pattern']) ? $bgActive : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                        <div
                                            class="flex items-center justify-center w-8 h-8 rounded-lg {{ Request::is($master['pattern']) ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500' }}">
                                            <i class="fa-solid {{ $master['icon'] }}"></i>
                                        </div>
                                        <span>{{ $master['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Laporan Dropdown -->
                @if (!Auth::user()->hasRole(['Mahasiswa', 'Pengawas']))
                    <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative">
                        <button
                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all duration-200">
                            <i class="fa-solid fa-chart-pie text-emerald-500"></i>
                            <span>Laporan</span>
                            <i class="fa-solid fa-chevron-down text-[10px] ml-1 transition-transform duration-200"
                                :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <!-- Dropdown Content -->
                        <div x-show="open" x-transition.opacity.duration.200ms
                            class="absolute top-full right-0 mt-2 w-72 rounded-2xl bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 p-2 z-50">
                            @foreach ($reports as $report)
                                <a href="{{ $report['route'] }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ Request::is($report['match']) ? $bgActive : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-lg {{ Request::is($report['match']) ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-500' }}">
                                        <i class="fa-solid {{ $report['icon'] }}"></i>
                                    </div>
                                    <span class="line-clamp-1">{{ $report['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Actions (Profile) -->
            <div class="flex items-center gap-3">

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-3 p-1 pr-3 rounded-full bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        <img src="{{ auth()->user()->profile ? asset('storage/' . auth()->user()->profile) : asset('asset/img/profile.png') }}"
                            alt="Profile" class="h-8 w-8 rounded-full object-cover shadow-sm">
                        <div class="hidden sm:block text-left mr-1">
                            <p class="text-xs font-bold text-slate-800 leading-tight">
                                {{ auth()->user()->name ?? 'Admin User' }}</p>
                            <p class="text-[10px] text-slate-500 uppercase tracking-wide">
                                {{ Auth::user()->companyRoles()->where('company_id', Auth::user()->company_id)->first()->role->name ?? 'No Role' }}
                            </p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>

                    <div x-show="open" x-transition.origin.top.right
                        class="absolute right-0 mt-3 w-56 rounded-2xl bg-white shadow-xl border border-slate-100 overflow-hidden z-50">
                        <div class="p-4 border-b border-slate-50 bg-slate-50/50">
                            <p class="text-sm font-bold text-slate-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email ?? 'user@example.com' }}
                            </p>
                        </div>
                        <div class="p-2">
                            <a href="/admin/change-password/change-password"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i class="fa-solid fa-lock w-4 text-center"></i>
                                Ubah Password
                            </a>
                            <div class="h-px bg-slate-100 my-1 mx-2"></div>
                            <a href="/logout"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-rose-600 hover:bg-rose-50 transition-colors">
                                <i class="fa-solid fa-power-off w-4 text-center"></i>
                                Sign Out
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button & Drawer -->
                <div x-data="{ mobileMenuOpen: false }" class="lg:hidden">
                    <button @click="mobileMenuOpen = true" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Mobile Drawer Overlay -->
                    <div x-show="mobileMenuOpen" x-transition.opacity
                        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[60]"></div>

                    <!-- Mobile Drawer Content -->
                    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full" @click.away="mobileMenuOpen = false"
                        class="fixed inset-y-0 right-0 w-80 bg-white shadow-2xl z-[70] overflow-y-auto flex flex-col">

                        <div class="flex items-center justify-between p-4 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Menu Navigasi</h3>
                            <button @click="mobileMenuOpen = false"
                                class="p-2 rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="flex-1 p-4 space-y-6">
                            <!-- Mobile Dashboard Link -->
                            <a href="/admin"
                                class="flex items-center gap-3 px-4 py-3 rounded-xl {{ Request::is('admin') ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600' }} font-bold">
                                <i class="fa-solid fa-house"></i> Dashboard
                            </a>

                            <!-- Mobile Menu Sections -->
                            @if (Auth::user()->hasRole(['Mahasiswa', 'Admin']))
                                <div>
                                    <h4 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ujian
                                    </h4>
                                    <div class="space-y-1">
                                        @foreach ($exams as $exam)
                                            <a href="{{ $exam['url'] }}"
                                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ Request::is($exam['pattern']) ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }} font-medium text-sm">
                                                <i class="fa-solid {{ $exam['icon'] }} w-5"></i> {{ $exam['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!Auth::user()->hasRole(['Mahasiswa']) && count($masters) > 0)
                                <div>
                                    <h4 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Data
                                        Master</h4>
                                    <div class="space-y-1">
                                        @foreach ($masters as $master)
                                            <a href="{{ $master['url'] }}"
                                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ Request::is($master['pattern']) ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }} font-medium text-sm">
                                                <i class="fa-solid {{ $master['icon'] }} w-5"></i> {{ $master['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if (!Auth::user()->hasRole(['Mahasiswa', 'Pengawas']))
                                <div>
                                    <h4 class="px-4 text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Laporan
                                    </h4>
                                    <div class="space-y-1">
                                        @foreach ($reports as $report)
                                            <a href="{{ $report['route'] }}"
                                                class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ Request::is($report['match']) ? 'bg-indigo-50 text-indigo-600' : 'text-slate-600 hover:bg-slate-50' }} font-medium text-sm">
                                                <i class="fa-solid {{ $report['icon'] }} w-5"></i> <span
                                                    class="line-clamp-1">{{ $report['label'] }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</nav>