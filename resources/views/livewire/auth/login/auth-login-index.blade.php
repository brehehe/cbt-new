<div>
    @if (config('app.name_slug') == 'login_universitas')
        <div class="relative h-screen overflow-hidden bg-[#dce0f4]">
            <!-- Advanced Animated Background -->
            <div class="absolute inset-0">
                <!-- Flowing gradient mesh -->
                <div class="absolute inset-0 opacity-40">
                    <div class="absolute left-0 top-0 h-full w-full animate-pulse bg-gradient-to-br from-blue-500/20 via-indigo-500/15 to-cyan-500/20"
                        style="animation-duration: 8s;"></div>
                    <div class="absolute inset-0 animate-pulse bg-gradient-to-tl from-blue-600/15 via-transparent to-blue-400/20"
                        style="animation-duration: 12s; animation-delay: 2s;"></div>
                </div>

                <!-- Floating elements -->
                <div
                    class="absolute left-20 top-20 h-3 w-3 animate-bounce cursor-pointer rounded-full bg-blue-500/50 transition-all duration-500 hover:scale-150 hover:bg-blue-400">
                </div>
                <div
                    class="duration-400 absolute right-24 top-1/3 h-8 w-2 animate-pulse cursor-pointer bg-indigo-500/50 transition-all hover:h-16 hover:bg-indigo-400">
                </div>
                <div class="hover:scale-200 duration-600 absolute bottom-1/4 left-1/4 h-4 w-4 rotate-45 animate-spin cursor-pointer bg-cyan-500/50 transition-all"
                    style="animation-duration: 15s;"></div>
                <div
                    class="absolute bottom-20 right-20 h-1 w-12 animate-pulse cursor-pointer bg-blue-600/50 transition-all duration-500 hover:w-20">
                </div>
            </div>

            <!-- Main Container -->
            <div class="relative flex h-screen">
                <!-- Left Panel: Company Showcase - Hidden on mobile -->
                <div class="group relative hidden w-0 overflow-hidden lg:block lg:w-3/5">
                    <!-- Background with modern clip-path -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 transition-all duration-1000 hover:from-blue-500 hover:via-blue-600 hover:to-blue-800"
                        style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%); background-image: url('{{ $company->background_login ? asset('storage/'.$company->background_login) : asset('asset/img/auth-pro-cbt.webp') }}'); background-size: cover; background-position: center;">
                    </div>

                    <!-- Subtle pattern overlay -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="h-full w-full"
                            style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;">
                        </div>
                    </div>

                    <!-- Content Container -->
                    <div class="relative z-10 flex h-full flex-col p-6 text-white xl:p-12">
                        <!-- Header Section -->
                        <div class="space-y-6">
                            <!-- Company Logo & Brand -->
                            <div class="group/brand flex items-center space-x-3">
                                <div class="relative">
                                    <img alt="Logo" class="object-contain" style="width: 215px;" loading="lazy"
                                        src="{{ $company->logo ? asset('storage/'.$company->logo) : asset('asset/img/logo-procbt.png') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Login Form - Full width on mobile, partial on desktop -->
                <div class="relative flex min-h-screen w-full items-center justify-center p-4 lg:w-2/5 lg:p-8 xl:p-16">
                    <!-- Mobile Header - Only visible on mobile -->
                    <div class="absolute left-4 top-4 lg:hidden">
                        <div
                            class="inline-flex items-center space-x-3 rounded-2xl border border-gray-200/50 bg-white/90 px-4 py-2 shadow-xl backdrop-blur-xl">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-blue-600">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <div>

                            </div>
                        </div>
                    </div>

                    <!-- Login Card - Properly centered -->
                    <div class="w-full max-w-md">
                        <!-- Glassmorphism backdrop -->
                        <div class="relative">
                            <div
                                class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-cyan-500/10 opacity-60 blur-xl filter">
                            </div>

                            <!-- Main card -->
                            <div
                                class="relative rounded-3xl border border-gray-200/50 bg-white/95 p-6 shadow-2xl backdrop-blur-2xl lg:p-8">
                                <!-- Header -->
                                <div class="mb-4 text-center lg:mb-6">
                                    <div class="relative inline-block">
                                        <img src="{{ $company->logo_potrait ? asset('storage/'.$company->logo_potrait) : asset('asset/img/logo-procbt.png') }}" class="w-32 h-24 object-contain" alt="">
                                    </div>

                                    <h2 class="mb-1 text-xl font-bold text-gray-800 lg:mb-2 lg:text-1xl">{{ $company->name }}</h2>
                                    <p class="text-sm text-gray-600 lg:text-base">Masuk ke sistem CBT</p>
                                </div>

                                <!-- Active Session Error -->
                                @if ($hasActiveSession && $activeSessionInfo)
                                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">
                                                    Login Tidak Diizinkan
                                                </h3>
                                                <div class="mt-2 text-sm text-red-700">
                                                    <p>Akun <strong>{{ $activeSessionInfo['username'] }}</strong> sudah
                                                        login di perangkat lain.</p>
                                                    <p class="mt-1">Silakan logout dari perangkat lain terlebih
                                                        dahulu atau hubungi administrator untuk bantuan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Login Form -->
                                <form class="space-y-4 lg:space-y-6" wire:submit="login">
                                    <div class="space-y-4 lg:space-y-6">
                                        <x-ts-input class="text-sm lg:text-base" icon="user"
                                            label="Username / Email / NIM"
                                            placeholder="Masukkan username, email, atau NIM" required type="text"
                                            wire:model="username_or_email"
                                            wire:keyup.debounce.500ms="checkExistingSession" />

                                        <x-ts-password class="text-sm lg:text-base" icon="key" label="Password"
                                            placeholder="Masukkan password Anda" required wire:model="password" />
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <x-ts-checkbox class="text-xs lg:text-sm" id="remember" label="Ingat saya"
                                            wire:model="remember" />
                                        <!-- <a class="text-xs font-medium text-[#2b7fff] hover:text-blue-700 lg:text-sm"
                                            href="#">
                                            Lupa Password?
                                        </a> -->
                                    </div>

                                    <div class="pt-2">
                                        <x-ts-button
                                            class="w-full gap-x-2 rounded-xl text-sm font-semibold shadow-xl lg:text-base !bg-blue-500"
                                            icon="arrow-right" position="right" type="submit" loading="login">
                                            <x-slot:text>Masuk</x-slot:text>
                                        </x-ts-button>
                                    </div>

                                    @if ($is_credentials)
                                        <div class="mt-4 grid grid-cols-2 gap-2">
                                            @foreach ($credentials as $role => $val)
                                                <button type="button" wire:click="getCredentials('{{ $role }}')"
                                                    class="flex w-full items-center justify-center rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs font-medium text-gray-700 transition-colors duration-200 hover:bg-gray-100 hover:text-blue-600">
                                                    <span class="capitalize">{{ $role }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </form>
                                <!-- <div class="mt-4 text-center">
                                    <a href="{{ route('register') }}"
                                        class="text-[#2b7fff] hover:text-[#317354] text-sm">Buat Akun ?</a>
                                </div> -->
                            </div>
                        </div>

                        <!-- Status & Footer -->
                        <div class="mt-6 space-y-4 text-center">
                            <!-- Status -->
                            <div class="flex justify-center">
                                <div
                                    class="inline-flex items-center gap-3 rounded-full border border-gray-200/40 bg-white/80 px-4 py-2 shadow-lg backdrop-blur-xl">
                                    <div class="flex items-center gap-1">
                                        <div class="h-2 w-2 animate-pulse rounded-full bg-blue-500"></div>
                                        <span class="text-xs font-medium text-gray-600">Online</span>
                                    </div>
                                    <div class="h-3 w-px bg-gray-300/50"></div>
                                    <div class="flex items-center gap-1">
                                        <svg class="h-3 w-3 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Secure</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <!-- <p class="text-xs font-medium text-gray-500">
                                © {{ date('Y') }} PRO CBT
                            </p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif (config('app.name_slug') == 'medical_school')
        <div class="relative h-screen overflow-hidden bg-[#dce0f4]">
            <!-- Advanced Animated Background -->
            <div class="absolute inset-0">
                <!-- Flowing gradient mesh -->
                <div class="absolute inset-0 opacity-40">
                    <div class="absolute left-0 top-0 h-full w-full animate-pulse bg-gradient-to-br from-green-500/20 via-indigo-500/15 to-cyan-500/20"
                        style="animation-duration: 8s;"></div>
                    <div class="absolute inset-0 animate-pulse bg-gradient-to-tl from-green-600/15 via-transparent to-green-400/20"
                        style="animation-duration: 12s; animation-delay: 2s;"></div>
                </div>

                <!-- Floating elements -->
                <div
                    class="absolute left-20 top-20 h-3 w-3 animate-bounce cursor-pointer rounded-full bg-green-500/50 transition-all duration-500 hover:scale-150 hover:bg-green-400">
                </div>
                <div
                    class="duration-400 absolute right-24 top-1/3 h-8 w-2 animate-pulse cursor-pointer bg-indigo-500/50 transition-all hover:h-16 hover:bg-indigo-400">
                </div>
                <div class="hover:scale-200 duration-600 absolute bottom-1/4 left-1/4 h-4 w-4 rotate-45 animate-spin cursor-pointer bg-cyan-500/50 transition-all"
                    style="animation-duration: 15s;"></div>
                <div
                    class="absolute bottom-20 right-20 h-1 w-12 animate-pulse cursor-pointer bg-green-600/50 transition-all duration-500 hover:w-20">
                </div>
            </div>

            <!-- Main Container -->
            <div class="relative flex h-screen">
                <!-- Left Panel: Company Showcase - Hidden on mobile -->
                <div class="group relative hidden w-0 overflow-hidden lg:block lg:w-3/5">
                    <!-- Background with modern clip-path -->
                    <div class="absolute inset-0 bg-gradient-to-br from-green-600 via-green-700 to-green-900 transition-all duration-1000 hover:from-green-500 hover:via-green-600 hover:to-green-800"
                        style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%); background-image: url('{{ asset('asset/img/ikmb-dark-2.png') }}'); background-size: cover; background-position: center;">
                    </div>

                    <!-- Subtle pattern overlay -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="h-full w-full"
                            style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;">
                        </div>
                    </div>

                    <!-- Content Container -->
                    <div class="relative z-10 flex h-full flex-col p-6 text-white xl:p-12">
                        <!-- Header Section -->
                        <div class="space-y-6">
                            <!-- Company Logo & Brand -->
                            <div class="group/brand flex items-center space-x-3">
                                {{-- <div class="relative">
                                    <div
                                        class="flex h-2 w-2 cursor-pointer items-center justify-center rounded-full border border-white bg-white p-1.5 backdrop-blur-sm transition-all duration-500 hover:rotate-6 hover:scale-110 hover:bg-white">
                                        <img alt="Logo" class="w-full object-contain" loading="lazy" src="{{ asset('asset/img/logo-ikmb.png') }}">
                                    </div>
                                </div> --}}
                                <div>
                                    <h1 class="text-5xl font-extrabold tracking-tight text-gray-100">Fakultas
                                        Kedokteran</h1>
                                    {{-- <p class="text-md text-gray-800/80">insitutkmb@gmail.com</p> --}}
                                </div>
                            </div>

                            <!-- Main Value Proposition -->
                            {{-- <div class="max-w-xl space-y-4 mt-[25%]">
                                <!-- <h2 class="text-2xl font-bold leading-tight tracking-tight xl:text-3xl">
                                    <span
                                        class="block cursor-default text-white transition-colors duration-300 hover:text-green-200">Misi</span>
                                    <span
                                        class="inline-block cursor-default text-white/90 transition-all duration-300 hover:translate-x-2 hover:text-white">Bisnis
                                        &</span>
                                    <span
                                        class="inline-block cursor-default text-white/80 transition-all duration-300 hover:translate-x-4 hover:text-white">Pemeliharaan</span>
                                </h2>

                                <p
                                    class="cursor-default text-sm leading-relaxed text-white/90 transition-colors duration-300 hover:text-white xl:text-base">
                                    Perusahaan layanan fasilitas bisnis dan pemeliharaan yang profesional & berpengalaman
                                    sejak tahun 2004. Kami berkomitmen memberikan layanan terbaik untuk mengoptimalkan
                                    pertumbuhan bisnis Anda dengan berbagai jenis layanan terintegrasi.
                                </p> -->



                                <!-- Company Stats -->
                                <div class="mt-[60%] grid grid-cols-3 gap-32">

                                    <!-- Stat 1 -->
                                    <div
                                        class="group/card w-[215px] cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-green-400">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="mb-1 text-xl font-bold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                                    20+</h3>
                                                <p
                                                    class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                    Tahun Pengalaman</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stat 2 -->
                                    <div
                                        class="group/card w-[215px] cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-purple-400">
                                                <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="currentColor" class="icon icon-tabler icons-tabler-outline icon-tabler-school h-6 w-6">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="mb-1 text-xl font-bold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                                    5</h3>
                                                <p
                                                    class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                    Program Studi</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stat 3 -->
                                    <div
                                        class="group/card w-[215px] cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-green-400">
                                                <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="currentColor" class="icon icon-tabler icons-tabler-outline icon-tabler-laurel-wreath h-6 w-6">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.436 8a8.6 8.6 0 0 0 -.436 2.727c0 4.017 2.686 7.273 6 7.273s6 -3.256 6 -7.273a8.6 8.6 0 0 0 -.436 -2.727" /><path d="M14.5 21s-.682 -3 -2.5 -3s-2.5 3 -2.5 3" /><path d="M18.52 5.23c.292 1.666 -1.02 2.77 -1.02 2.77s-1.603 -.563 -1.895 -2.23c-.292 -1.666 1.02 -2.77 1.02 -2.77s1.603 .563 1.895 2.23" /><path d="M21.094 12.14c-1.281 1.266 -3.016 .76 -3.016 .76s-.454 -1.772 .828 -3.04c1.28 -1.266 3.016 -.76 3.016 -.76s.454 1.772 -.828 3.04" /><path d="M17.734 18.826c-1.5 -.575 -1.734 -2.19 -1.734 -2.19s1.267 -1.038 2.767 -.462c1.5 .575 1.733 2.19 1.733 2.19s-1.267 1.038 -2.767 .462" /><path d="M6.267 18.826c1.5 -.575 1.733 -2.19 1.733 -2.19s-1.267 -1.038 -2.767 -.462c-1.5 .575 -1.733 2.19 -1.733 2.19s1.267 1.038 2.767 .462" /><path d="M2.906 12.14c1.281 1.266 3.016 .76 3.016 .76s.454 -1.772 -.828 -3.04c-1.281 -1.265 -3.016 -.76 -3.016 -.76s-.454 1.772 .828 3.04" /><path d="M5.48 5.23c-.292 1.666 1.02 2.77 1.02 2.77s1.603 -.563 1.895 -2.23c.292 -1.666 -1.02 -2.77 -1.02 -2.77s-1.603 .563 -1.895 2.23" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="mb-1 text-xl font-bold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                                    B</h3>
                                                <p
                                                    class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                    Akreditasi Program Studi</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> --}}
                        </div>

                        <!-- Features Grid -->
                        <div class="space-y-2 mt-[40%] max-w-xl">
                            <div
                                class="group/card cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:-rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-purple-400">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 text-lg font-semibold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                            Visi
                                        </h3>
                                        <p
                                            class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                            Menjadi pusat pendidikan kedokteran yang unggul, berintegritas, dan berdaya
                                            saing global dalam mewujudkan pelayanan kesehatan yang berkualitas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group/card cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:-rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-purple-400">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 text-lg font-semibold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                            Misi
                                        </h3>
                                        <div class="space-y-2">
                                            <ul
                                                class="space-y-1 text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Menyelenggarakan pendidikan kedokteran yang inovatif, berbasis
                                                        teknologi, dan berorientasi pada kebutuhan masyarakat.</span>
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Menghasilkan lulusan dokter yang kompeten, berempati, dan
                                                        menjunjung etika profesi.</span>
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Mengembangkan penelitian yang bermanfaat bagi kemajuan ilmu
                                                        kedokteran dan kesehatan publik.</span>
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Berperan aktif dalam pengabdian kepada masyarakat melalui
                                                        program kesehatan berkelanjutan.</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Login Form - Full width on mobile, partial on desktop -->
                <div class="relative flex min-h-screen w-full items-center justify-center p-4 lg:w-2/5 lg:p-8 xl:p-16">
                    <!-- Mobile Header - Only visible on mobile -->
                    <div class="absolute left-4 top-4 lg:hidden">
                        <div
                            class="inline-flex items-center space-x-3 rounded-2xl border border-gray-200/50 bg-white/90 px-4 py-2 shadow-xl backdrop-blur-xl">
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-green-500 to-green-600">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                </svg>
                            </div>
                            <div>

                            </div>
                        </div>
                    </div>

                    <!-- Login Card - Properly centered -->
                    <div class="w-full max-w-md">
                        <!-- Glassmorphism backdrop -->
                        <div class="relative">
                            <div
                                class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-green-500/10 via-indigo-500/10 to-cyan-500/10 opacity-60 blur-xl filter">
                            </div>

                            <!-- Main card -->
                            <div
                                class="relative rounded-3xl border border-gray-200/50 bg-white/95 p-6 shadow-2xl backdrop-blur-2xl lg:p-8">
                                <!-- Header -->
                                <div class="mb-6 text-center lg:mb-8">
                                    {{-- <div class="relative mb-4 inline-block lg:mb-6">
                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full shadow-xl lg:h-20 lg:w-20">
                                            <!-- <svg class="h-8 w-8 text-white lg:h-10 lg:w-10" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                            </svg> -->
                                            <img src="{{ asset('asset/img/logo-ikmb.png') }}" alt="">
                                        </div>
                                    </div> --}}

                                    <h2 class="mb-1 text-xl font-bold text-gray-800 lg:mb-2 lg:text-2xl">Fakultas
                                        Kedokteran</h2>
                                    {{-- <p class="text-sm text-gray-600 lg:text-base">Masuk ke sistem CBT</p> --}}
                                </div>


                                <!-- Login Form -->
                                <form class="space-y-4 lg:space-y-6" wire:submit="login">
                                    <div class="space-y-4 lg:space-y-6">
                                        <x-ts-input class="text-sm lg:text-base" icon="user"
                                            label="Username / Email / NIM"
                                            placeholder="Masukkan username, email, atau NIM" required type="text"
                                            wire:model="username_or_email" />

                                        <x-ts-password class="text-sm lg:text-base" icon="key" label="Password"
                                            placeholder="Masukkan password Anda" required wire:model="password" />
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <x-ts-checkbox class="text-xs lg:text-sm" id="remember" label="Ingat saya"
                                            wire:model="remember" />
                                        <!-- <a class="text-xs font-medium text-[{{ $companyData->color_primary }}] hover:text-green-700 lg:text-sm"
                                            href="#">
                                            Lupa Password?
                                        </a> -->
                                    </div>

                                    <div class="pt-4">
                                        <x-ts-button
                                            class="w-full gap-x-2 rounded-xl text-sm font-semibold shadow-xl lg:text-base !bg-primary"
                                            icon="arrow-right" position="right" type="submit" loading="login">
                                            <x-slot:text>Masuk</x-slot:text>
                                        </x-ts-button>
                                        {{-- <button class="w-full gap-x-2 rounded-xl text-sm font-semibold shadow-xl lg:text-base text-black bg-[{{ $company->color_primary }}]">
                                            Masuk
                                        </button> --}}
                                    </div>
                                </form>
                                <div class="mt-4 text-center">
                                    <a href="{{ route('register') }}"
                                        class="text-[{{ $companyData->color_primary }}] hover:text-[#317354] text-sm">Buat
                                        Akun ?</a>
                                </div>
                            </div>
                        </div>

                        <!-- Status & Footer -->
                        <div class="mt-6 space-y-4 text-center">
                            <!-- Status -->
                            <div class="flex justify-center">
                                <div
                                    class="inline-flex items-center gap-3 rounded-full border border-gray-200/40 bg-white/80 px-4 py-2 shadow-lg backdrop-blur-xl">
                                    <div class="flex items-center gap-1">
                                        <div class="h-2 w-2 animate-pulse rounded-full bg-green-500"></div>
                                        <span class="text-xs font-medium text-gray-600">Online</span>
                                    </div>
                                    <div class="h-3 w-px bg-gray-300/50"></div>
                                    <div class="flex items-center gap-1">
                                        <svg class="h-3 w-3 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Secure</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <p class="text-xs font-medium text-gray-500">
                                © {{ date('Y') }} Fakultas Kesehatan
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif (config('app.name_slug') == 'pro-cbt')
        <div class="relative h-screen overflow-hidden bg-[#dce0f4]">
            <!-- Advanced Animated Background -->
            <div class="absolute inset-0">
                <!-- Flowing gradient mesh -->
                <div class="absolute inset-0 opacity-40">
                    <div class="absolute left-0 top-0 h-full w-full animate-pulse bg-gradient-to-br from-green-500/20 via-indigo-500/15 to-cyan-500/20"
                        style="animation-duration: 8s;"></div>
                    <div class="absolute inset-0 animate-pulse bg-gradient-to-tl from-green-600/15 via-transparent to-green-400/20"
                        style="animation-duration: 12s; animation-delay: 2s;"></div>
                </div>

                <!-- Floating elements -->
                <div
                    class="absolute left-[10%] top-[10%] h-[2vw] w-[2vw] animate-bounce cursor-pointer rounded-full bg-green-500/50 transition-all duration-500 hover:scale-150 hover:bg-green-400">
                </div>

                <div
                    class="absolute right-[12%] top-1/3 h-[5vh] w-[1vw] animate-pulse cursor-pointer bg-indigo-500/50 transition-all hover:h-[10vh] hover:bg-indigo-400">
                </div>

                <div class="absolute bottom-1/4 left-1/4 h-[2.5vw] w-[2.5vw] rotate-45 animate-spin cursor-pointer bg-cyan-500/50 transition-all hover:scale-150"
                    style="animation-duration: 15s;">
                </div>

                <div
                    class="absolute bottom-[12%] right-[8%] h-[0.6vw] w-[7vw] animate-pulse cursor-pointer bg-green-600/50 transition-all duration-500 hover:w-[10vw]">
                </div>
            </div>

            <!-- Main Container -->
            <div class="relative flex h-screen">
                <!-- Left Panel: Company Showcase - Hidden on mobile -->
                <div class="group relative hidden w-0 overflow-hidden md:block md:w-2/5 lg:w-3/5">
                    <!-- Background with modern clip-path -->
                    <div class="absolute inset-0 bg-gradient-to-br from-orange-600 via-orange-700 to-orange-800 transition-all duration-1000 hover:from-orange-500 hover:via-orange-600 hover:to-orange-800"
                        style="clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%); background-image: url('{{ asset('asset/img/auth-pro-cbt.webp') }}'); background-size: cover; background-position: center;">
                    </div>

                    <!-- Subtle pattern overlay -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="h-full w-full"
                            style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.3) 1px, transparent 0); background-size: 40px 40px;">
                        </div>
                    </div>

                    <!-- Content Container -->
                    <div class="relative z-10 flex h-full flex-col p-6 text-white xl:p-12">
                        <!-- Header Section -->
                        <div class="space-y-6">
                            <!-- Company Logo & Brand -->
                            <div class="group/brand flex items-center space-x-3">
                                {{-- <div class="relative">
                                    <div
                                        class="flex h-2 w-2 cursor-pointer items-center justify-center rounded-full border border-white bg-white p-1.5 backdrop-blur-sm transition-all duration-500 hover:rotate-6 hover:scale-110 hover:bg-white">
                                        <img alt="Logo" class="w-full object-contain" loading="lazy" src="{{ asset('asset/img/logo-ikmb.png') }}">
                                    </div>
                                </div> --}}
                                <div class="">

                                    <img src="{{ asset('asset/img/logo-procbt.png') }}" alt=""
                                        class="w-32 sm:w-40 md:w-48 lg:w-56 bg-gray-300 rounded-2xl">
                                    {{-- <h1 class="text-5xl font-extrabold tracking-tight text-gray-100">PRO CBT</h1> --}}
                                    {{-- <p class="text-md text-gray-800/80">insitutkmb@gmail.com</p> --}}
                                </div>
                            </div>

                            <!-- Main Value Proposition -->
                            {{-- <div class="max-w-xl space-y-4 mt-[25%]">
                                <!-- <h2 class="text-2xl font-bold leading-tight tracking-tight xl:text-3xl">
                                    <span
                                        class="block cursor-default text-white transition-colors duration-300 hover:text-green-200">Misi</span>
                                    <span
                                        class="inline-block cursor-default text-white/90 transition-all duration-300 hover:translate-x-2 hover:text-white">Bisnis
                                        &</span>
                                    <span
                                        class="inline-block cursor-default text-white/80 transition-all duration-300 hover:translate-x-4 hover:text-white">Pemeliharaan</span>
                                </h2>

                                <p
                                    class="cursor-default text-sm leading-relaxed text-white/90 transition-colors duration-300 hover:text-white xl:text-base">
                                    Perusahaan layanan fasilitas bisnis dan pemeliharaan yang profesional & berpengalaman
                                    sejak tahun 2004. Kami berkomitmen memberikan layanan terbaik untuk mengoptimalkan
                                    pertumbuhan bisnis Anda dengan berbagai jenis layanan terintegrasi.
                                </p> -->



                                <!-- Company Stats -->
                                <div class="mt-[60%] grid grid-cols-3 gap-32">

                                    <!-- Stat 1 -->
                                    <div
                                        class="group/card w-[215px] cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-green-400">
                                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="mb-1 text-xl font-bold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                                    20+</h3>
                                                <p
                                                    class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                    Tahun Pengalaman</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stat 2 -->
                                    <div
                                        class="group/card w-[215px] cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-purple-400">
                                                <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="currentColor" class="icon icon-tabler icons-tabler-outline icon-tabler-school h-6 w-6">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="mb-1 text-xl font-bold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                                    5</h3>
                                                <p
                                                    class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                    Program Studi</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stat 3 -->
                                    <div
                                        class="group/card w-[215px] cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:rotate-1 hover:scale-105 hover:bg-white/20 xl:p-4">
                                        <div class="flex items-start space-x-3">
                                            <div
                                                class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-green-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-green-400">
                                                <svg  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  fill="none"  stroke="currentColor" class="icon icon-tabler icons-tabler-outline icon-tabler-laurel-wreath h-6 w-6">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.436 8a8.6 8.6 0 0 0 -.436 2.727c0 4.017 2.686 7.273 6 7.273s6 -3.256 6 -7.273a8.6 8.6 0 0 0 -.436 -2.727" /><path d="M14.5 21s-.682 -3 -2.5 -3s-2.5 3 -2.5 3" /><path d="M18.52 5.23c.292 1.666 -1.02 2.77 -1.02 2.77s-1.603 -.563 -1.895 -2.23c-.292 -1.666 1.02 -2.77 1.02 -2.77s1.603 .563 1.895 2.23" /><path d="M21.094 12.14c-1.281 1.266 -3.016 .76 -3.016 .76s-.454 -1.772 .828 -3.04c1.28 -1.266 3.016 -.76 3.016 -.76s.454 1.772 -.828 3.04" /><path d="M17.734 18.826c-1.5 -.575 -1.734 -2.19 -1.734 -2.19s1.267 -1.038 2.767 -.462c1.5 .575 1.733 2.19 1.733 2.19s-1.267 1.038 -2.767 .462" /><path d="M6.267 18.826c1.5 -.575 1.733 -2.19 1.733 -2.19s-1.267 -1.038 -2.767 -.462c-1.5 .575 -1.733 2.19 -1.733 2.19s1.267 1.038 2.767 .462" /><path d="M2.906 12.14c1.281 1.266 3.016 .76 3.016 .76s.454 -1.772 -.828 -3.04c-1.281 -1.265 -3.016 -.76 -3.016 -.76s-.454 1.772 .828 3.04" /><path d="M5.48 5.23c-.292 1.666 1.02 2.77 1.02 2.77s1.603 -.563 1.895 -2.23c.292 -1.666 -1.02 -2.77 -1.02 -2.77s-1.603 .563 -1.895 2.23" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3
                                                    class="mb-1 text-xl font-bold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                                    B</h3>
                                                <p
                                                    class="text-xs text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                    Akreditasi Program Studi</p>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div> --}}
                        </div>

                        <!-- Features Grid -->
                        <div class="space-y-2 mt-[30%] max-w-xl">
                            <div
                                class="group/card cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:-rotate-1 hover:scale-105 hover:bg-gray-300 xl:p-4">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-purple-400">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 text-lg font-semibold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                            Visi
                                        </h3>
                                        <p
                                            class="text-sm text-white transition-colors duration-300 group-hover/card:text-white">
                                            Menjadi pusat pendidikan kedokteran yang unggul, berintegritas, dan berdaya
                                            saing global dalam mewujudkan pelayanan kesehatan yang berkualitas.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="group/card cursor-pointer rounded-xl border border-white/20 bg-black/20 p-3 backdrop-blur-sm transition-all duration-500 hover:-rotate-1 hover:scale-105 hover:bg-gray-300 xl:p-4">
                                <div class="flex items-start space-x-3">
                                    <div
                                        class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-purple-500 transition-all duration-300 group-hover/card:scale-110 group-hover/card:bg-purple-400">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 text-lg font-semibold text-white transition-transform duration-300 group-hover/card:translate-x-1">
                                            Misi
                                        </h3>
                                        <div class="space-y-2">
                                            <ul
                                                class="space-y-1 text-sm text-white/80 transition-colors duration-300 group-hover/card:text-white">
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Menyelenggarakan pendidikan kedokteran yang inovatif, berbasis
                                                        teknologi, dan berorientasi pada kebutuhan masyarakat.</span>
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Menghasilkan lulusan dokter yang kompeten, berempati, dan
                                                        menjunjung etika profesi.</span>
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Mengembangkan penelitian yang bermanfaat bagi kemajuan ilmu
                                                        kedokteran dan kesehatan publik.</span>
                                                </li>
                                                <li class="flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                                    <span>Berperan aktif dalam pengabdian kepada masyarakat melalui
                                                        program kesehatan berkelanjutan.</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Login Form - Full width on mobile, partial on desktop -->
                <div class="relative flex min-h-screen w-full items-center justify-center p-4 lg:w-2/5 lg:p-8 xl:p-16">

                    <!-- Login Card - Properly centered -->
                    <div class="w-full max-w-md">
                        <!-- Glassmorphism backdrop -->
                        <div class="relative">
                            <div
                                class="absolute -inset-1 rounded-3xl bg-gradient-to-r from-green-500/10 via-indigo-500/10 to-cyan-500/10 opacity-60 blur-xl filter">
                            </div>

                            <!-- Main card -->
                            <div
                                class="relative rounded-3xl border border-gray-200/50 bg-white/95 p-6 shadow-2xl backdrop-blur-2xl lg:p-8">
                                <!-- Header -->
                                <div class="mb-6 text-center lg:mb-8">
                                    {{-- <div class="relative mb-4 inline-block lg:mb-6">
                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-full shadow-xl lg:h-20 lg:w-20">
                                            <!-- <svg class="h-8 w-8 text-white lg:h-10 lg:w-10" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                                            </svg> -->
                                            <img src="{{ asset('asset/img/logo-ikmb.png') }}" alt="">
                                        </div>
                                    </div> --}}

                                    {{-- <h2 class="mb-1 text-xl font-bold text-gray-800 lg:mb-2 lg:text-2xl">PRO CBT</h2> --}}
                                    <div class="flex justify-center">
                                        <img src="{{ asset('asset/img/logo-procbt.png') }}" alt=""
                                            srcset="" class="w-48">
                                    </div>
                                    {{-- <p class="text-sm text-gray-600 lg:text-base">Masuk ke sistem CBT</p> --}}
                                </div>


                                <!-- Login Form -->
                                <form class="space-y-4 lg:space-y-6" wire:submit="login">
                                    <div class="space-y-4 lg:space-y-6">
                                        <x-ts-input class="text-sm lg:text-base" icon="user"
                                            label="Username / Email / NIM"
                                            placeholder="Masukkan username, email, atau NIM" required type="text"
                                            wire:model="username_or_email" />

                                        <x-ts-password class="text-sm lg:text-base" icon="key" label="Password"
                                            placeholder="Masukkan password Anda" required wire:model="password" />
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <x-ts-checkbox class="text-xs lg:text-sm" id="remember" label="Ingat saya"
                                            wire:model="remember" />
                                        <!-- <a class="text-xs font-medium text-orange-500 hover:text-orange-700 lg:text-sm"
                                            href="#">
                                            Lupa Password?
                                        </a> -->
                                    </div>

                                    <div class="pt-4">
                                        <x-ts-button
                                            class="w-full gap-x-2 rounded-xl text-sm font-semibold shadow-xl lg:text-base !bg-orange-500"
                                            icon="arrow-right" position="right" type="submit" loading="login">
                                            <x-slot:text>Masuk</x-slot:text>
                                        </x-ts-button>
                                        {{-- <button class="w-full gap-x-2 rounded-xl text-sm font-semibold shadow-xl lg:text-base text-black bg-[#3BA172]">
                                            Masuk
                                        </button> --}}
                                    </div>
                                </form>
                                <!-- <div class="mt-4 text-center">
                                    <a href="{{ route('register') }}"
                                        class="text-orange-500 hover:text-orange-700 text-sm">Buat Akun ?</a>
                                </div> -->
                            </div>
                        </div>

                        <!-- Status & Footer -->
                        <div class="mt-6 space-y-4 text-center">
                            <!-- Status -->
                            <div class="flex justify-center">
                                <div
                                    class="inline-flex items-center gap-3 rounded-full border border-gray-200/40 bg-white/80 px-4 py-2 shadow-lg backdrop-blur-xl">
                                    <div class="flex items-center gap-1">
                                        <div class="h-2 w-2 animate-pulse rounded-full bg-orange-500"></div>
                                        <span class="text-xs font-medium text-gray-600">Online</span>
                                    </div>
                                    <div class="h-3 w-px bg-gray-300/50"></div>
                                    <div class="flex items-center gap-1">
                                        <svg class="h-3 w-3 text-orange-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            </path>
                                        </svg>
                                        <span class="text-xs font-medium text-gray-600">Secure</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <p class="text-xs font-medium text-gray-500">
                                © {{ date('Y') }} PRO CBT
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="min-h-screen flex items-center justify-center p-4">
            <div
                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl w-full max-w-lg p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#1E3A8A] to-[#C3D4EC]"></div>

                <!-- Logo & Welcome -->
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset('asset/img/LogoPROCBT.png') }}" alt="Logo PRO CBT"
                        class="h-12 drop-shadow-md mb-4">
                    <h1
                        class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                        Selamat Datang Kembali!</h1>
                    <p class="text-gray-600 text-sm">Akses dashboard admin Anda dengan aman</p>
                </div>

                <!-- Login Form -->
                <!-- Form Login -->
                <form wire:submit.prevent="login" id="loginForm" class="space-y-4">
                    @csrf

                    <!-- Login Key -->
                    <div class="group">
                        <div class="relative">
                            <input autocomplete="off" type="text" name="code"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Login Key" wire:model='code'>
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        @error('code')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <div class="relative">
                            <input autocomplete="off" type="text" name="username_or_email"
                                wire:model='username_or_email'
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Username or Email">
                            <div class="absolute inset-y-0 right-3 flex items-center text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('username_or_email')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password dengan Toggle -->
                    <div x-data="{ showPassword: false }">
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" wire:model="password"
                                class="input-style w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="kata sandi">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors duration-200">
                                <svg class="w-5 h-5 eye-icon-show"
                                    :class="{ 'visible-password': showPassword, 'hidden-password': !showPassword }"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    <path class="eye-line" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M3 3l18 18"
                                        :style="showPassword ? 'opacity: 0' : 'opacity: 1'" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Captcha -->
                    <div>
                        <div class="flex gap-3">
                            <!-- Input Captcha -->
                            <input autocomplete="off" type="text" name="captcha" wire:model='captchaInput'
                                class="input-style flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#1E3A8A]/20 focus:border-[#1E3A8A] transition-all bg-white/50"
                                placeholder="Captcha">

                            <!-- Captcha Display with Refresh -->
                            <div class="flex items-center px-3 py-2 border border-[#1E3A8A]/30 rounded-xl bg-white shadow-sm"
                                wire:ignore>
                                @foreach (str_split($captchaCode) as $char)
                                    @php
                                        $randomColor =
                                            '#' .
                                            str_pad(dechex(rand(0, 255)), 2, '0', STR_PAD_LEFT) .
                                            str_pad(dechex(rand(0, 255)), 2, '0', STR_PAD_LEFT) .
                                            str_pad(dechex(rand(0, 255)), 2, '0', STR_PAD_LEFT);
                                    @endphp
                                    <span class="font-bold tracking-wider select-none"
                                        style="color: {{ $randomColor }};" oncontextmenu="return false"
                                        onselectstart="return false">{{ $char }}</span>
                                @endforeach
                            </div>
                        </div>
                        @error('captchaInput')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ingat Saya -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input autocomplete="off" type="checkbox" name="remember"
                                class="rounded border-gray-300 text-[{{ $companyData->color_primary }}] focus:ring-[#1E3A8A]/20"
                                wire:model='remember'>
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a href="#"
                            class="text-sm text-[{{ $companyData->color_primary }}] hover:text-[#2563EB] transition-colors">Lupa
                            kata
                            sandi?</a>
                    </div>

                    <!-- Tombol Login -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#1E3A8A] to-[#2563EB] hover:from-[#1E3A8A] hover:to-[#1E3A8A] text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl mt-2 cursor-pointer">
                        Masuk
                    </button>

                    <!-- Tombol ke Register -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('register') }}"
                                class="text-[{{ $companyData->color_primary }}] hover:underline font-semibold">Daftar
                                di
                                sini</a>
                        </p>
                    </div>
                </form>


                <!-- Footer -->
                <div class="mt-6 text-center text-xs text-gray-500">
                    <p>© 2024 PRO CBT. All rights reserved.</p>
                    <p class="mt-0.5">Secure login • Admin Portal</p>
                </div>
            </div>
        </div>
    @endif
</div>
