<!-- Sidebar Container with Flex Structure -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 flex flex-col bg-white/80 backdrop-blur-sm w-64 border-r border-gray-100 shadow-lg z-40 transition-transform duration-300 ease-in-out transform translate-x-0">
    <!-- Sidebar content -->

    <!-- Logo Section -->
    <div class="flex-shrink-0 h-16 flex items-center gap-3 px-6 border-b border-gray-100">
        <div>
            <h2 class="text-lg font-bold text-[#1E3A8A]">Mediction</h2>
            <p class="text-xs text-gray-500">Healthcare System</p>
        </div>
    </div>

    <!-- Scrollable Menu Section -->
    <div class="flex-1 overflow-y-auto" id="sidebar-menu">
        <div class="p-2">
            <nav class="space-y-1">
                <!-- Dashboard -->
                <div>
                    <a href="/user"
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
                <div>
                    <a href="/admin/master/rating-scale"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin/master/rating-scale') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-tag mr-2 text-lg {{ Request::is('admin/master/rating-scale') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Skala Penilaian</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/admin/master/user"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin/master/user') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-users-medical mr-2 text-lg {{ Request::is('admin/master/user') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">User</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/admin/master/role"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin/master/role') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <i
                                class="fa-solid fa-tag mr-2 text-lg {{ Request::is('admin/master/role') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }}"></i>
                            <span class="sidebar-text">Role</span>
                        </div>
                    </a>
                </div>
                <div>
                    <a href="/admin/master/setting"
                        class="group flex items-center px-4 py-3 text-sm font-medium rounded-lg {{ Request::is('admin/master/setting') ? 'bg-[#C3D4EC]/50 text-[#1E3A8A] active-menu' : 'text-gray-600 hover:bg-[#C3D4EC]/20 hover:text-[#1E3A8A]' }} transition-colors duration-200">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 mr-2 {{ Request::is('admin/master/setting') ? 'text-[#1E3A8A]' : 'text-gray-400 group-hover:text-[#1E3A8A]' }} shrink-0"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="3" />
                                <path
                                    d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                            </svg>

                            <span class="sidebar-text">Pengaturan</span>
                        </div>
                    </a>
                </div>
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
