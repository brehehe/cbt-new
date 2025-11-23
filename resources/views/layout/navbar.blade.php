<nav class="bg-white/80 backdrop-blur-sm border-b border-gray-100 fixed w-full z-50 shadow-sm">
    <div class="max-w-full mx-auto px-2 sm:px-4 lg:px-6">
        <div class="flex justify-between h-16">
            <!-- Left Section: Logo & Sidebar Toggle -->
            <div class="flex items-center">
                @if (config('app.name_slug') === 'ups_tegal')
                    <img src="{{ asset('asset/img/logo-ups-blue.png') }}"
                        alt="UPS Tegal Logo"
                        class="h-10 w-auto mr-2">
                @elseif (config('app.name_slug') === 'unimma')
                    <img src="{{ asset('asset/img/unimma.webp') }}"
                        alt="UNIMMA Logo"
                        class="h-10 w-auto mr-2">
                @elseif (config('app.name_slug') === 'unidayan')
                    <img src="{{ asset('asset/img/unidayan/logo-primary.png') }}"
                        alt="UNIDAYAN Logo"
                        class="h-10 w-auto mr-2">
                @else
                    <img src="{{ asset('asset/img/logo-procbt.png') }}"
                        alt="PRO CBT Logo"
                        class="h-10 w-auto mr-2">
                @endif
                <button id="toggleSidebar"
                    class="p-2 rounded-xl {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }} hover:bg-[#C3D4EC]/20 transition-all duration-200 cursor-pointer">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>

            <!-- Center Section: Company Info (Desktop Only) -->
            {{-- <div class="hidden xl:flex items-center gap-4 flex-1 justify-center">
                <div
                    class="flex items-center gap-3 px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-building text-blue-600"></i>
                        <div class="text-left">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ auth()->user()->company->name ?? 'Nama Perusahaan' }}
                            </p>
                        </div>
                    </div>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <div class="flex items-center gap-2">
                        @php
                            $expiredDate = auth()->user()->company->expired_at ?? now()->addDays(30);
                            $daysLeft = now()->diffInDays($expiredDate, false);
                            $isExpired = $daysLeft < 0;
                            $isExpiringSoon = $daysLeft <= 7 && $daysLeft >= 0;
                        @endphp

                        @if (Auth::user()->company->is_lifetime)
                            <i class="fas fa-infinity text-green-500"></i>
                            <p class="text-xs text-green-600 font-medium">Seumur Hidup</p>
                        @else
                            @if ($isExpired)
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                <div class="text-left">
                                    <p class="text-xs text-red-600 font-medium">EXPIRED {{ abs($daysLeft) }} hari yang
                                        lalu</p>
                                </div>
                            @elseif($isExpiringSoon)
                                <i class="fas fa-clock {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-blue-600' : 'text-orange-600' }}"></i>
                                <div class="text-left">
                                    <p class="text-xs {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-blue-600' : 'text-orange-600' }} font-medium">Berakhir Dalam {{ $daysLeft }}
                                        hari lagi</p>
                                </div>
                            @else
                                <i class="fas fa-calendar-check text-green-500"></i>
                                <div class="text-left">
                                    <p class="text-xs text-green-600 font-medium">Aktif Hingga
                                        {{ \Carbon\Carbon::parse($expiredDate)->format('d M Y') }}</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div> --}}

            <!-- Right Section: Actions -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Mobile Company Info Button -->

                <!-- Notifications -->
                <!-- <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="p-2 rounded-xl text-gray-500 hover:bg-[#C3D4EC]/20 hover:{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }} transition-all duration-200 relative">
                        <i class="fas fa-bell text-lg"></i>
                        <span
                            class="absolute top-1 right-1 h-4 w-4 bg-red-500 rounded-full text-[10px] text-white flex items-center justify-center">3</span>
                    </button>
                    <div x-show="open" x-transition @click.away="open = false"
                        class="absolute right-0 mt-2 w-72 sm:w-80 bg-white border border-gray-200 rounded-xl shadow-lg z-50">
                        <div class="p-4 font-semibold text-gray-700 border-b">Notifikasi</div>
                        <ul class="max-h-60 overflow-y-auto divide-y divide-gray-100">
                            <li class="p-4 hover:bg-gray-50 cursor-pointer">
                                <p class="text-sm font-medium text-gray-800">Pengguna baru mendaftar</p>
                                <span class="text-xs text-gray-500">2 menit yang lalu</span>
                            </li>
                            <li class="p-4 hover:bg-gray-50 cursor-pointer">
                                <p class="text-sm font-medium text-gray-800">Pesanan #1234 telah dikirim</p>
                                <span class="text-xs text-gray-500">10 menit yang lalu</span>
                            </li>
                            <li class="p-4 hover:bg-gray-50 cursor-pointer">
                                <p class="text-sm font-medium text-gray-800">Server sedang melakukan maintenance</p>
                                <span class="text-xs text-gray-500">1 jam yang lalu</span>
                            </li>
                        </ul>
                        <div class="text-center p-2 text-sm text-blue-600 hover:underline cursor-pointer">
                            Lihat semua notifikasi
                        </div>
                    </div>
                </div> -->

                <!-- Profile Dropdown -->
                <div x-data="{ open: false }" class="relative cursor-pointer">
                    <button @click="open = !open"
                        class="flex items-center gap-2 sm:gap-3 p-2 rounded-xl hover:bg-[#C3D4EC]/20 transition-all duration-200">
                        <!-- Profile Image -->
                        <div class="h-8 w-8 rounded-lg overflow-hidden bg-white flex items-center justify-center">
                            <img src="{{ auth()->user()->profile ?? asset('asset/img/profile.png') }}" alt="Profile"
                                class="h-full w-full object-cover">
                        </div>
                        <!-- Profile Info (Hidden on small screens) -->
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-gray-900 truncate max-w-24 lg:max-w-none">
                                {{ auth()->user()->name ?? 'Admin User' }}
                            </p>
                            <p class="text-xs text-gray-500 truncate max-w-24 lg:max-w-none">
                                {{ Auth::user()->companyRoles()->where('company_id', Auth::user()->company_id)->first()->role->name ?? 'No Role' }}
                            </p>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 hidden sm:block"></i>
                    </button>
                    <!-- Profile Dropdown Menu -->
                    <div x-show="open" x-transition @click.away="open = false"
                        class="absolute right-0 w-48 sm:w-52 mt-2 backdrop-blur-sm rounded-xl shadow-lg border bg-white border-gray-100 z-50">
                        <!-- Mobile Profile Info (Shown only on small screens) -->
                        <div class="sm:hidden p-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Admin User' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ Auth::user()->companyRoles()->where('company_id', Auth::user()->company_id)->first()->role->name ?? 'No Role' }}
                            </p>
                        </div>
                        <div class="p-2">
                            <a href="/admin/profile/profile"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#C3D4EC]/20 hover:{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }} rounded-lg transition-all duration-200">
                                <i class="fas fa-user w-4"></i>
                                <span>Profile</span>
                            </a>
                            <a href="/admin/change-password/change-password"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-[#C3D4EC]/20 hover:{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }} rounded-lg transition-all duration-200">
                                <i class="fas fa-lock w-4"></i>
                                <span>Rubah Password</span>
                            </a>
                            <hr class="my-1 border-gray-100">
                            <a href="/logout"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <i class="fas fa-sign-out-alt w-4"></i>
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
