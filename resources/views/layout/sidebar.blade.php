<!-- Sidebar Container with Flex Structure -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 flex flex-col bg-white/80 backdrop-blur-sm w-64 border-r border-gray-100 shadow-lg z-40 transition-transform duration-300 ease-in-out transform translate-x-0">
    <!-- Sidebar content -->

    <!-- Logo Section -->
    <div class="flex-shrink-0 h-16 flex items-center gap-3 px-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-[#3BA172]">PROCBT</h2>
            <p class="text-xs text-gray-500">Healthcare System</p>
        </div>
    </div>

    <!-- Scrollable Menu Section -->
    <div class="flex-1 overflow-y-auto" id="sidebar-menu">
        <div class="p-2">
            <nav class="space-y-1">
                <!-- Dashboard -->
                <div>
                    <a href="/admin"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin') ? 'bg-[#C3D4EC]/50 text-[#3BA172] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#3BA172]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-house mr-2 text-lg {{ Request::is('admin') ? 'text-[#3BA172]' : 'text-gray-400 group-hover:text-[#3BA172]' }}"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </div>
                    </a>
                </div>
                <!-- Divider: Ujian -->
                @if(Auth::user()->hasRole(['Mahasiswa']))
                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#3BA172] uppercase tracking-wide">
                        Ujian
                    </div>
                </div>
                @php
                    $exams = [
                        ['label' => 'Ujian', 'url' => '/admin/exam/timetable', 'icon' => 'fa-file-lines']
                    ];
                @endphp
                @foreach ($exams as $exam)
                    <div>
                        <a href="{{ $exam['url'] }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
         {{ Request::is(ltrim($exam['url'], '/')) ? 'bg-[#C3D4EC]/50 text-[#3BA172] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#3BA172]' }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid {{ $exam['icon'] }} text-lg mr-2
             {{ Request::is(ltrim($exam['url'], '/')) ? 'text-[#3BA172]' : 'text-gray-400 group-hover:text-[#3BA172]' }}">
                                </i>
                                <span class="sidebar-text">{{ $exam['label'] }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
                @endif
                <!-- Divider: Master -->
                @if(!Auth::user()->hasRole(['Mahasiswa']))
                      <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#3BA172] uppercase tracking-wide">
                        Master
                    </div>
                </div>
                @endif
                @php
                    // Jika belum login, kosongkan menu
                    if (!auth()->check()) {
                        $masters = [];
                    } else {
                        // Hanya Admin yang boleh lihat menu "Master"
                        if (auth()->user()->hasRole('Admin')) {
                            $masters = [
                                [
                                    'label'   => 'Skala Penilaian',
                                    'url'     => '/admin/master/rating-scale',
                                    'pattern' => 'admin/master/rating-scale*',
                                    'icon'    => 'fa-chart-bar',
                                ],
                                [
                                    'label'   => 'Regulasi',
                                    'url'     => '/admin/master/regulation',
                                    'pattern' => 'admin/master/regulation*',
                                    'icon'    => 'fa-scroll',
                                ],
                                [
                                    'label'   => 'Jadwal',
                                    'url'     => '/admin/master/timetable',
                                    'pattern' => 'admin/master/timetable*',
                                    'icon'    => 'fa-clock',
                                ],
                                [
                                    'label'   => 'Admin',
                                    'url'     => '/admin/master/admin',
                                    'pattern' => 'admin/master/admin*',
                                    'icon'    => 'fa-user-shield',
                                ],
                                [
                                    'label'   => 'Dosen',
                                    'url'     => '/admin/master/lecturer',
                                    'pattern' => 'admin/master/lecturer*',
                                    'icon'    => 'fa-chalkboard-teacher',
                                ],
                                [
                                    'label'   => 'Pengawas',
                                    'url'     => '/admin/master/supervisor',
                                    'pattern' => 'admin/master/supervisor*',
                                    'icon'    => 'fa-user-tie',
                                ],
                                [
                                    'label'   => 'Mahasiswa',
                                    'url'     => '/admin/master/student',
                                    'pattern' => 'admin/master/student*',
                                    'icon'    => 'fa-user-graduate',
                                ],

                                // —— Materi & Soal ——
                                [
                                    'label'   => 'Topik Ujian',
                                    'url'     => route('admin.master.topic'),
                                    'pattern' => 'admin/master/topic*', // dirapikan dari topic-question*
                                    'icon'    => 'fa-tags',
                                ],
                                [
                                    'label'   => 'Kategori Materi',
                                    'url'     => route('admin.master.material-category'),
                                    'pattern' => 'admin/master/material-category',
                                    'icon'    => 'fa-layer-group',
                                ],
                                [
                                    'label'   => 'Materi',
                                    'url'     => route('admin.master.material'),
                                    'pattern' => 'admin/master/material',
                                    'icon'    => 'fa-book',
                                ],
                                [
                                    'label'   => 'Tipe Soal',
                                    'url'     => route('admin.master.question-type'),
                                    'pattern' => 'admin/master/question-type*',
                                    'icon'    => 'fa-list-ol',
                                ],
                                [
                                    'label'   => 'Modul Soal',
                                    'url'     => route('admin.master.module'),
                                    'pattern' => 'admin/master/module*',
                                    'icon'    => 'fa-folder-open',
                                ],
                                [
                                    'label'   => 'Bank Soal',
                                    'url'     => route('admin.master.question'),
                                    'pattern' => ['admin/master/question', 'admin/master/question/*'],
                                    'icon'    => 'fa-database',
                                ],
                            ];
                        } elseif(Auth::user()->hasRole(['Pengawas'])) {
                            $masters = [
                                [
                                    'label'   => 'Regulasi',
                                    'url'     => '/admin/master/regulation',
                                    'pattern' => 'admin/master/regulation*',
                                    'icon'    => 'fa-scroll',
                                ],
                                [
                                    'label'   => 'Jadwal',
                                    'url'     => '/admin/master/timetable',
                                    'pattern' => 'admin/master/timetable*',
                                    'icon'    => 'fa-clock',
                                ]
                            ];
                        } elseif(Auth::user()->hasRole('Dosen')) {
                            $masters = [
                                [
                                    'label'   => 'Topik Ujian',
                                    'url'     => route('admin.master.topic'),
                                    'pattern' => 'admin/master/topic*', // dirapikan dari topic-question*
                                    'icon'    => 'fa-tags',
                                ],
                                [
                                    'label'   => 'Kategori Materi',
                                    'url'     => route('admin.master.material-category'),
                                    'pattern' => 'admin/master/material-category*',
                                    'icon'    => 'fa-layer-group',
                                ],
                                [
                                    'label'   => 'Materi',
                                    'url'     => route('admin.master.material'),
                                    'pattern' => 'admin/master/material*',
                                    'icon'    => 'fa-book',
                                ],
                                [
                                    'label'   => 'Tipe Soal',
                                    'url'     => route('admin.master.question-type'),
                                    'pattern' => 'admin/master/question-type*',
                                    'icon'    => 'fa-list-ol',
                                ],
                                [
                                    'label'   => 'Modul Soal',
                                    'url'     => route('admin.master.module'),
                                    'pattern' => 'admin/master/module*',
                                    'icon'    => 'fa-folder-open',
                                ],
                                [
                                    'label'   => 'Bank Soal',
                                    'url'     => route('admin.master.question'),
                                    'pattern' => ['admin/master/question', 'admin/master/question/*'],
                                    'icon'    => 'fa-database',
                                ],
                            ];
                        } else {
                            $masters = [];
                        }
                    }
                @endphp

                @foreach ($masters as $master)
                    <div>
                        <a href="{{ $master['url'] }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
               {{ Request::is($master['pattern'])
                   ? 'bg-[#C3D4EC]/50 text-[#3BA172] active-menu'
                   : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#3BA172]' }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid {{ $master['icon'] }} text-lg mr-2
                       {{ Request::is($master['pattern']) ? 'text-[#3BA172]' : 'text-gray-400 group-hover:text-[#3BA172]' }}"></i>
                                <span class="sidebar-text">{{ $master['label'] }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach

                @if(!Auth::user()->hasRole(['Mahasiswa','Pengawas']))

                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#3BA172] uppercase tracking-wide">
                        Laporan
                    </div>
                </div>

                @php
                    $reports = [
                        [
                            'label' => 'Riwayat Jadwal Ujian',
                            'route' => route('admin.report.timetable'),
                            'icon' => 'fa-file-alt',
                            'match' => 'admin/report/timetable*',
                        ],
                        //[
                        //  'label' => 'Analisis Soal',
                        //'route' => route('admin.report.question'),
                        //'icon' => 'fa-chart-pie',
                        //'match' => 'admin/report/question*',
                        //],
                    ];
                @endphp


                @foreach ($reports as $report)
                    <div>
                        <a href="{{ $report['route'] }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
            {{ Request::is($report['match']) ? 'bg-[#C3D4EC]/50 text-[#3BA172] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#3BA172]' }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid {{ $report['icon'] }} mr-2 text-lg
                    {{ Request::is($report['match']) ? 'text-[#3BA172]' : 'text-gray-400 group-hover:text-[#3BA172]' }}">
                                </i>
                                <span class="sidebar-text">{{ $report['label'] }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
                @endif
            </nav>
        </div>
    </div>
</aside>
