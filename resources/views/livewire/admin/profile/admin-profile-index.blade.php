@section('title', 'Profil Pengguna')

<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-[{{ $companyData->color_primary }}]">
            Profil Pengguna
        </h1>
        <p class="text-gray-600 mt-1">
            @if(Auth::user()->hasRole('Mahasiswa'))
                Informasi profil pribadi Anda
            @else
                Kelola informasi profil Anda
            @endif
        </p>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r bg-[{{$$companyData->color_primary}] p-6 text-white">
            <div class="flex items-center space-x-4">
                <!-- Avatar -->
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-[{{ $companyData->color_primary }}] text-3xl font-bold">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                </div>
                <!-- User Info -->
                <div>
                    <h2 class="text-2xl font-bold">{{ $user->name ?? 'Pengguna' }}</h2>
                    <p class="text-blue-100">{{ $user->email ?? '-' }}</p>
                    @if($user->userDetail)
                        <p class="text-sm text-blue-100 mt-1">
                            @if(Auth::user()->hasRole('Mahasiswa'))
                                Mahasiswa
                            @elseif(Auth::user()->hasRole('Admin'))
                                Administrator
                            @elseif(Auth::user()->hasRole('Dosen'))
                                Dosen
                            @elseif(Auth::user()->hasRole('Pengawas'))
                                Pengawas
                            @else
                                Pengguna
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[{{ $companyData->color_primary }}]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Dasar
                    </h3>

                    <div class="space-y-3">
                        <div class="flex flex-col">
                            <label class="text-sm text-gray-500">Nama Lengkap</label>
                            <p class="text-gray-800 font-medium">{{ $user->name ?? '-' }}</p>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm text-gray-500">Username</label>
                            <p class="text-gray-800 font-medium">{{ $user->username ?? '-' }}</p>
                        </div>

                        <div class="flex flex-col">
                            <label class="text-sm text-gray-500">Email</label>
                            <p class="text-gray-800 font-medium">{{ $user->email ?? '-' }}</p>
                        </div>

                        @if($user->userDetail)
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">Nomor Telepon</label>
                                <p class="text-gray-800 font-medium">{{ $user->userDetail->phone ?? '-' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[{{ $companyData->color_primary }}]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi Tambahan
                    </h3>

                    <div class="space-y-3">
                        @if(Auth::user()->hasRole('Mahasiswa') && $user->userDetail)
                            <!-- Informasi khusus Mahasiswa -->
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">NIM</label>
                                <p class="text-gray-800 font-medium">{{ $user->userDetail->nim ?? '-' }}</p>
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">Program Studi</label>
                                <p class="text-gray-800 font-medium">{{ $user->userDetail->study?->name ?? '-' }}</p>
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">Angkatan</label>
                                <p class="text-gray-800 font-medium">{{ $user->userDetail->angkatan ?? '-' }}</p>
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">Semester</label>
                                <p class="text-gray-800 font-medium">{{ $user->userDetail->semester ?? '-' }}</p>
                            </div>
                        @else
                            <!-- Informasi untuk role lain -->
                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">Status Akun</label>
                                <p class="text-gray-800 font-medium">
                                    <span class="px-3 py-1 rounded-full text-sm {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </p>
                            </div>

                            <div class="flex flex-col">
                                <label class="text-sm text-gray-500">Terdaftar Sejak</label>
                                <p class="text-gray-800 font-medium">{{ $user->created_at?->format('d F Y') ?? '-' }}</p>
                            </div>

                            @if($user->userDetail)
                                <div class="flex flex-col">
                                    <label class="text-sm text-gray-500">Alamat</label>
                                    <p class="text-gray-800 font-medium">{{ $user->userDetail->address ?? '-' }}</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Only shown if user can edit) -->
            @if($canEdit)
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-wrap gap-3">
                        <!-- Link to settings or edit profile -->
                        <a href="{{ route('settings.profile') }}"
                           class="inline-flex items-center px-4 py-2 {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'bg-[#2b7fff] hover:bg-blue-700' : 'bg-[#f58634] hover:bg-orange-700' }} text-white rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Profil
                        </a>

                        <a href="{{ route('settings.password') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Ubah Password
                        </a>
                    </div>
                </div>
            @endif

            <!-- Info Message for Mahasiswa -->
            @if(Auth::user()->hasRole('Mahasiswa'))
                <div class="mt-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-r">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800">Informasi</h4>
                            <p class="text-sm text-blue-700 mt-1">
                                Anda hanya dapat melihat dan mengedit profil pribadi Anda sendiri.
                                Jika ada kesalahan data, silakan hubungi administrator.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
