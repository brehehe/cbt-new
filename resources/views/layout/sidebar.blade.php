<!-- Sidebar Container with Flex Structure -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 flex flex-col bg-white/80 backdrop-blur-sm w-64 border-r border-gray-100 shadow-lg z-40 transition-transform duration-300 ease-in-out transform translate-x-0">

    <!-- Logo Section -->
    <div class="flex-shrink-0 h-16 flex items-center gap-3 px-6 border-b border-gray-100">
        <div>
            @php
                $brandColor = config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]';
            @endphp
            <h2 class="text-lg font-bold {{ $brandColor }}">PROCBT</h2>
            <p class="text-xs text-gray-500">Healthcare System</p>
        </div>
    </div>

    <!-- Scrollable Menu Section -->
    <div class="flex-1 overflow-y-auto" id="sidebar-menu">
        <div class="p-2">
            <nav class="space-y-1">
                <!-- Dashboard -->
                @php
                    $isActive = Request::is('admin');
                    $brandColor = config('app.name_slug') === 'ups_tegal' ? 'text-[#2b7fff]' : 'text-[#f58634]';
                @endphp

                <a href="/admin"
                    class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
                    {{ $isActive
                        ? "bg-[#C3D4EC]/50 $brandColor active-menu"
                        : "text-gray-600 hover:bg-[#C3D4EC]/20 hover:$brandColor" }}">
                    <div class="flex items-center gap-3">
                        <i
                            class="fa-solid fa-house mr-2 text-lg
                            {{ $isActive ? $brandColor : 'text-gray-400 group-hover:' . $brandColor }}"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </div>
                </a>

                <!-- Divider: Ujian -->
                @if (Auth::user()->hasRole(['Mahasiswa', 'Admin']))
                    <div>
                        <div
                            class="w-full group flex items-center justify-between custom-padding text-xs font-bold {{ $brandColor }} uppercase tracking-wide">
                            Ujian
                        </div>
                    </div>

                    @php
                        $exams = [
                            [
                                'label' => 'Ujian',
                                'url' => '/admin/exam/timetable',
                                'pattern' => 'admin/exam/timetable',
                                'icon' => 'fa-file-lines',
                            ],
                            [
                                'label' => 'Riwayat Ujian',
                                'url' => '/admin/exam/history-timetable',
                                'pattern' => 'admin/exam/history-timetable',
                                'icon' => 'fa-clock-rotate-left',
                            ],
                        ];
                    @endphp

                    @foreach ($exams as $exam)
                        @php
                            $active = Request::is(ltrim($exam['pattern'], '/'));
                        @endphp
                        <a href="{{ $exam['url'] }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
                            {{ $active
                                ? "bg-[#C3D4EC]/50 $brandColor active-menu"
                                : "text-gray-600 hover:bg-[#C3D4EC]/20 hover:$brandColor" }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid {{ $exam['icon'] }} text-lg mr-2
                                    {{ $active ? $brandColor : 'text-gray-400 group-hover:' . $brandColor }}"></i>
                                <span class="sidebar-text">{{ $exam['label'] }}</span>
                            </div>
                        </a>
                    @endforeach
                @endif

                <!-- Divider: Master -->
                @if (!Auth::user()->hasRole(['Mahasiswa']))
                    <div>
                        <div
                            class="w-full group flex items-center justify-between custom-padding text-xs font-bold {{ $brandColor }} uppercase tracking-wide">
                            Master
                        </div>
                    </div>
                @endif

                @php
                    $masters = [];
                    if (auth()->check()) {
                        if (auth()->user()->hasRole('Admin')) {
                            $masters = [
                                [
                                    'label' => 'Skala Penilaian',
                                    'url' => '/admin/master/rating-scale',
                                    'pattern' => 'admin/master/rating-scale',
                                    'icon' => 'fa-chart-bar',
                                ],
                                [
                                    'label' => 'Regulasi',
                                    'url' => '/admin/master/regulation',
                                    'pattern' => 'admin/master/regulation',
                                    'icon' => 'fa-scroll',
                                ],
                                [
                                    'label' => 'Ruang Ujian',
                                    'url' => route('admin.master.exam-room'),
                                    'pattern' => ['admin/master/exam-room*'],
                                    'icon' => 'fa-house',
                                ],
                                [
                                    'label' => 'Sesi Ujian',
                                    'url' => route('admin.master.exam-session'),
                                    'pattern' => ['admin/master/exam-session*'],
                                    'icon' => 'fa-clock',
                                ],
                                [
                                    'label' => 'Jadwal',
                                    'url' => '/admin/master/timetable',
                                    'pattern' => 'admin/master/timetable*',
                                    'icon' => 'fa-calendar',
                                ],
                                [
                                    'label' => 'Prodi',
                                    'url' => route('admin.master.study'),
                                    'pattern' => 'admin/master/study',
                                    'icon' => 'fa-building-columns',
                                ],
                                [
                                    'label' => 'Peserta',
                                    'url' => '/admin/master/classmate',
                                    'pattern' => 'admin/master/classmate',
                                    'icon' => 'fa-users',
                                ],
                                [
                                    'label' => 'Admin',
                                    'url' => '/admin/master/admin',
                                    'pattern' => 'admin/master/admin',
                                    'icon' => 'fa-user-shield',
                                ],
                                [
                                    'label' => 'Dosen',
                                    'url' => '/admin/master/lecturer',
                                    'pattern' => 'admin/master/lecturer',
                                    'icon' => 'fa-chalkboard-teacher',
                                ],
                                [
                                    'label' => 'Pengawas',
                                    'url' => '/admin/master/supervisor',
                                    'pattern' => 'admin/master/supervisor',
                                    'icon' => 'fa-user-tie',
                                ],
                                [
                                    'label' => 'Mahasiswa',
                                    'url' => '/admin/master/student',
                                    'pattern' => 'admin/master/student',
                                    'icon' => 'fa-user-graduate',
                                ],
                                [
                                    'label' => 'Topik Ujian',
                                    'url' => route('admin.master.topic'),
                                    'pattern' => 'admin/master/topic-question',
                                    'icon' => 'fa-tags',
                                ],
                                [
                                    'label' => 'Kategori Materi',
                                    'url' => route('admin.master.material-category'),
                                    'pattern' => 'admin/master/material-category',
                                    'icon' => 'fa-layer-group',
                                ],
                                [
                                    'label' => 'Materi',
                                    'url' => route('admin.master.material'),
                                    'pattern' => 'admin/master/material',
                                    'icon' => 'fa-book',
                                ],
                                [
                                    'label' => 'Tipe Ujian',
                                    'url' => route('admin.master.question-type'),
                                    'pattern' => 'admin/master/question-type',
                                    'icon' => 'fa-list-ol',
                                ],
                                [
                                    'label' => 'Modul Soal',
                                    'url' => route('admin.master.module'),
                                    'pattern' => ['admin/master/module-question', 'admin/master/module-question/*'],
                                    'icon' => 'fa-folder-open',
                                ],
                                [
                                    'label' => 'Bank Soal',
                                    'url' => route('admin.master.question'),
                                    'pattern' => ['admin/master/question', 'admin/master/question/*'],
                                    'icon' => 'fa-database',
                                ],
                                [
                                    'label' => 'Seting',
                                    'url' => route('admin.master.setting'),
                                    'pattern' => ['admin/master/setting', 'admin/master/setting/*'],
                                    'icon' => 'fa-cog',
                                ],
                            ];
                        }
                    }
                @endphp

                @foreach ($masters as $master)
                    @php
                        $active = Request::is($master['pattern']);
                    @endphp
                    <a href="{{ $master['url'] }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
                            {{ $active
                                ? "bg-[#C3D4EC]/50 $brandColor active-menu"
                                : "text-gray-600 hover:bg-[#C3D4EC]/20 hover:$brandColor" }}">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid {{ $master['icon'] }} text-lg mr-2
                                {{ $active ? $brandColor : 'text-gray-400 group-hover:' . $brandColor }}"></i>
                            <span class="sidebar-text">{{ $master['label'] }}</span>
                        </div>
                    </a>
                @endforeach

                <!-- Divider: Laporan -->
                @if (!Auth::user()->hasRole(['Mahasiswa', 'Pengawas']))
                    <div>
                        <div
                            class="w-full group flex items-center justify-between custom-padding text-xs font-bold {{ $brandColor }} uppercase tracking-wide">
                            Laporan
                        </div>
                    </div>

                    @php
                        $reports = [
                            [
                                'label' => 'Riwayat Jadwal Ujian',
                                'route' => route('admin.report.timetable'),
                                'match' => 'admin/report/timetable',
                                'icon' => 'fa-file-alt',
                            ],
                            [
                                'label' => 'Analisis Butir Soal',
                                'route' => route('admin.report.item-analysis'),
                                'match' => 'admin/report/item-analysis',
                                'icon' => 'fa-chart-line',
                            ],
                        ];
                    @endphp

                    @foreach ($reports as $report)
                        @php
                            $active = Request::is($report['match']);
                        @endphp
                        <a href="{{ $report['route'] }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
                                {{ $active
                                    ? "bg-[#C3D4EC]/50 $brandColor active-menu"
                                    : "text-gray-600 hover:bg-[#C3D4EC]/20 hover:$brandColor" }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid {{ $report['icon'] }} mr-2 text-lg
                                    {{ $active ? $brandColor : 'text-gray-400 group-hover:' . $brandColor }}"></i>
                                <span class="sidebar-text">{{ $report['label'] }}</span>
                            </div>
                        </a>
                    @endforeach
                @endif
            </nav>
        </div>
    </div>
</aside>
