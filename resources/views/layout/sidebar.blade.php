<!-- Sidebar Container with Flex Structure -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 flex flex-col bg-white/80 backdrop-blur-sm w-64 border-r border-gray-100 shadow-lg z-40 transition-transform duration-300 ease-in-out transform translate-x-0">
    <!-- Sidebar content -->

    <!-- Logo Section -->
    <div class="flex-shrink-0 h-16 flex items-center gap-3 px-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-[#1E3A8A]">PROCBT</h2>
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
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-light fa-house mr-2 text-lg {{ Request::is('admin') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Dashboard</span>
                        </div>
                    </a>
                </div>
                <!-- Divider: Master -->
                <div>
                    <div
                        class="w-full group flex items-center justify-between custom-padding text-xs font-bold text-[#1E3A8A] uppercase tracking-wide">
                        Master
                    </div>
                </div>
                <!-- Registration -->
                @php
                    $menuItems = [
                        ['label' => 'Skala Penilaian', 'url' => '/admin/master/rating-scale', 'icon' => 'fa-chart-bar'],
                        ['label' => 'Jadwal', 'url' => '/admin/master/timetable', 'icon' => 'fa-clock'],
                        ['label' => 'Admin', 'url' => '/admin/master/admin', 'icon' => 'fa-user-shield'],
                        ['label' => 'Dosen', 'url' => '/admin/master/lecturer', 'icon' => 'fa-chalkboard-teacher'],
                        ['label' => 'Pengawas', 'url' => '/admin/master/supervisor', 'icon' => 'fa-user-tie'],
                        ['label' => 'Mahasiswa', 'url' => '/admin/master/student', 'icon' => 'fa-user-graduate'],
                        ['label' => 'Pengaturan', 'url' => '/admin/master/setting', 'icon' => 'fa-cog'],
                    ];
                @endphp

                @foreach ($menuItems as $item)
                    <div>
                        <a href="{{ $item['url'] }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors duration-200
            {{ Request::is(ltrim($item['url'], '/')) ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }}">
                            <div class="flex items-center gap-3">
                                <i
                                    class="fa-solid {{ $item['icon'] }} text-lg mr-2
                {{ Request::is(ltrim($item['url'], '/')) ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}">
                                </i>
                                <span class="sidebar-text">{{ $item['label'] }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach

                <div>
                    <a href="{{ route('admin.master.topic') }}"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin/master/topic-question') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-tag mr-2 text-lg {{ Request::is('admin/master/topic-question') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Topik ujian</span>
                        </div>
                    </a>
                </div>
            </nav>
        </div>
    </div>
</aside>
