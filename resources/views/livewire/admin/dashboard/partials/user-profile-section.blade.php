{{-- User Profile Section for All Roles --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Profile Card --}}
    <div class="lg:col-span-1 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div
            class="bg-gradient-to-r from-[{{ $companyData->color_primary }}] to-[{{ $companyData->color_secondary }}] p-6 text-white">
            <div class="flex flex-col items-center text-center">
                {{-- Avatar --}}
                <div
                    class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-[{{ $companyData->color_primary ?? '#f58634' }}] text-3xl font-bold mb-3">
                    {{ strtoupper(substr($userProfile['user']->name ?? 'U', 0, 1)) }}
                </div>
                {{-- User Info --}}
                <h3 class="text-xl font-bold">{{ $userProfile['user']->name ?? 'Pengguna' }}</h3>
                <p class="text-blue-100 text-sm mt-1">{{ $userProfile['user']->email ?? '-' }}</p>
                <span class="mt-2 px-3 py-1 bg-white/20 rounded-full text-xs font-medium">
                    {{ $userProfile['role'] ?? 'User' }}
                </span>
            </div>
        </div>

        {{-- Profile Details --}}
        <div class="p-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-4">Informasi Dasar</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Username:</span>
                    <span class="font-medium text-gray-800">{{ $userProfile['user']->username ?? '-' }}</span>
                </div>
                @if ($userProfile['user']->userDetail && $userProfile['user']->userDetail->phone)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon:</span>
                        <span class="font-medium text-gray-800">{{ $userProfile['user']->userDetail->phone }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Terdaftar:</span>
                    <span
                        class="font-medium text-gray-800">{{ $userProfile['user']->created_at?->format('d M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile Information - Dynamic based on role --}}
    <div class="lg:col-span-2 bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">
                @if ($userProfile['show_academic_info'])
                    📚 Informasi Akademik Mahasiswa
                @else
                    👤 Informasi Profil {{ $userProfile['role'] }}
                @endif
            </h3>
        </div>

        @if ($userProfile['show_academic_info'])
            {{-- MAHASISWA: Show academic information --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-[{{ $companyData->color_primary }}]">
                        <label class="text-xs text-gray-500 uppercase">NIM</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['user']->userDetail->nim ?? '-' }}
                        </p>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <label class="text-xs text-gray-500 uppercase">Program Studi</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['user']->study->name ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                        <label class="text-xs text-gray-500 uppercase">Angkatan</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['user']->userDetail->angkatan ?? '-' }}
                        </p>
                    </div>

                    <div class="p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                        <label class="text-xs text-gray-500 uppercase">Semester</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['user']->userDetail->semester ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Message for Mahasiswa --}}
            <div
                class="mt-6 p-4 bg-blue-50 border-l-4 border-[{{ $companyData->color_primary ?? '#f58634' }}] rounded-r">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[{{ $companyData->color_primary ?? '#f58634' }}] mt-0.5 mr-3"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-[{{ $companyData->color_primary ?? '#f58634' }}]">
                            Informasi Penting</h4>
                        <p class="text-sm text-[{{ $companyData->color_primary ?? '#f58634' }}] mt-1">
                            Anda login sebagai <strong>Mahasiswa</strong>. Informasi profil pribadi Anda hanya dapat
                            dilihat oleh Anda sendiri.
                            Jika ada kesalahan data, silakan hubungi administrator.
                        </p>
                    </div>
                </div>
            </div>
        @else
            {{-- NON-MAHASISWA: Show basic information --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-[{{ $companyData->color_primary }}]">
                        <label class="text-xs text-gray-500 uppercase">Nama Lengkap</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['user']->name ?? '-' }}
                        </p>
                    </div>

                    <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <label class="text-xs text-gray-500 uppercase">Email</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['user']->email ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="p-4 bg-purple-50 rounded-lg border-l-4 border-purple-500">
                        <label class="text-xs text-gray-500 uppercase">Role</label>
                        <p class="text-lg font-semibold text-gray-800">
                            {{ $userProfile['role'] ?? '-' }}
                        </p>
                    </div>

                    @if ($userProfile['user']->userDetail && $userProfile['user']->userDetail->phone)
                        <div class="p-4 bg-orange-50 rounded-lg border-l-4 border-orange-500">
                            <label class="text-xs text-gray-500 uppercase">Telepon</label>
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $userProfile['user']->userDetail->phone ?? '-' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Message for Non-Mahasiswa --}}
            <div
                class="mt-6 p-4 bg-blue-50 border-l-4 border-[{{ $companyData->color_primary ?? '#f58634' }}] rounded-r">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-[{{ $companyData->color_primary ?? '#f58634' }}] mt-0.5 mr-3"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-[{{ $companyData->color_primary ?? '#f58634' }}]">
                            Informasi</h4>
                        <p class="text-sm text-[{{ $companyData->color_primary ?? '#f58634' }}] mt-1">
                            Anda login sebagai <strong>{{ $userProfile['role'] }}</strong>. Informasi profil Anda dapat
                            diubah melalui menu pengaturan.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Quick Actions --}}
        <!-- <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('settings.profile') }}"
                    class="inline-flex items-center px-4 py-2 bg-[{{ $companyData->color_primary }}] hover:bg-[{{ $companyData->color_secondary }}] text-white rounded-lg text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Profil
                </a>

                <a href="{{ route('settings.password') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                    Ubah Password
                </a>
            </div>
        </div> -->
    </div>
</div>
