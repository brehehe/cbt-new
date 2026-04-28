<div>
    <div class="relative min-h-screen w-full overflow-hidden bg-slate-950 selection:bg-indigo-500 selection:text-white font-sans">
        <!-- Dynamic Background Elements -->
        <div class="absolute inset-0 z-0">
            <!-- Blobs -->
            <div class="absolute top-[-10%] left-[-10%] w-96 h-96 rounded-full bg-indigo-600/40 mix-blend-screen filter blur-[100px] animate-blob"></div>
            <div class="absolute top-[20%] right-[-10%] w-96 h-96 rounded-full bg-fuchsia-600/40 mix-blend-screen filter blur-[100px] animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-[-20%] left-[20%] w-96 h-96 rounded-full bg-cyan-600/40 mix-blend-screen filter blur-[100px] animate-blob animation-delay-4000"></div>
            
            <!-- Grid Pattern Overlay -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAwIDEwIEwgNDAgMTAgTSAxMCAwIEwgMTAgNDAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgyNTUsIDI1NSwgMjU1LCAwLjA1KSIgc3Ryb2tlLXdpZHRoPSIxIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI2dyaWQpIi8+PC9zdmc+')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        </div>

        <!-- Main Content Container -->
        <div class="relative z-10 flex min-h-screen items-center justify-center p-4 sm:p-8">
            
            <div class="flex w-full max-w-6xl overflow-hidden rounded-[2.5rem] bg-white/5 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] backdrop-blur-2xl border border-white/10 ring-1 ring-white/20">
                
                <!-- Left Side: Branding / Visuals (Hidden on smaller screens) -->
                <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-indigo-900/80 via-purple-900/80 to-slate-900/80 p-12 lg:flex">
                    <!-- Custom background image if exists -->
                    @if($company->background_login)
                    <div class="absolute inset-0 -z-10 bg-cover bg-center opacity-30 mix-blend-overlay" style="background-image: url('{{ asset('storage/' . $company->background_login) }}')"></div>
                    @endif
                    
                    <div class="relative z-10">
                        <img src="{{ $company->logo ? asset('storage/' . $company->logo) : asset('asset/img/logo-procbt.png') }}" alt="Logo" class="h-16 w-auto object-contain drop-shadow-2xl transition-transform duration-500 hover:scale-105" />
                    </div>

                    <div class="relative z-10 mt-auto text-white">
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-white/10 px-4 py-2 backdrop-blur-md border border-white/20 mb-6 shadow-lg">
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <span class="text-sm font-semibold tracking-wide text-emerald-50">Sistem Sedang Online</span>
                        </div>
                        <h1 class="mb-4 text-5xl font-black leading-tight tracking-tight drop-shadow-lg">
                            Mulai<br />
                            Petualangan<br />
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-emerald-400">Belajarmu! 🚀</span>
                        </h1>
                        <p class="text-lg font-medium text-slate-300 max-w-md">
                            Platform CBT generasi terbaru untuk pengalaman ujian yang lebih asik, cepat, dan pastinya anti-ribet.
                        </p>
                        
                        <!-- Floating abstract shapes -->
                        <div class="absolute right-0 bottom-10 w-24 h-24 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-2xl rotate-12 blur-sm opacity-50 animate-pulse"></div>
                        <div class="absolute right-20 top-20 w-16 h-16 bg-gradient-to-tr from-cyan-400 to-blue-500 rounded-full blur-md opacity-40 animate-bounce" style="animation-duration: 3s;"></div>
                    </div>
                </div>

                <!-- Right Side: Login Form -->
                <div class="w-full p-8 sm:p-12 lg:w-1/2 bg-white relative">
                    
                    <!-- Mobile Logo -->
                    <div class="mb-8 flex justify-center lg:hidden">
                        <img src="{{ $company->logo_potrait ? asset('storage/' . $company->logo_potrait) : asset('asset/img/logo-procbt.png') }}" alt="Logo" class="h-20 w-auto object-contain drop-shadow-lg" />
                    </div>

                    <div class="mx-auto max-w-md xl:max-w-sm flex flex-col justify-center h-full">
                        <div class="mb-10 text-center lg:text-left">
                            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome Back 👋</h2>
                            <p class="mt-2 text-sm font-medium text-slate-500">
                                Masuk ke akun <span class="font-bold text-indigo-600">{{ $company->name }}</span> kamu untuk melanjutkan.
                            </p>
                        </div>

                        <form wire:submit="login" class="space-y-6">
                            
                            <div class="group relative">
                                <x-ts-input class="!rounded-2xl !border-slate-200 !bg-slate-50 !text-sm focus:!border-indigo-500 focus:!bg-white focus:!ring-4 focus:!ring-indigo-500/10 transition-all duration-300"
                                    icon="user" label="Username / Email / NIM"
                                    placeholder="Ketik username, email, atau NIM..." required type="text"
                                    wire:model="username_or_email"
                                    wire:keyup.debounce.500ms="checkExistingSession" />
                            </div>

                            <div class="group relative">
                                <x-ts-password class="!rounded-2xl !border-slate-200 !bg-slate-50 !text-sm focus:!border-indigo-500 focus:!bg-white focus:!ring-4 focus:!ring-indigo-500/10 transition-all duration-300"
                                    icon="key" label="Password"
                                    placeholder="Masukkan kata sandi..." required wire:model="password" />
                            </div>

                            <div class="flex items-center justify-between">
                                <x-ts-checkbox class="!rounded-md text-indigo-600 focus:ring-indigo-500" id="remember" label="Ingat saya" wire:model="remember" />
                                <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Lupa Password?</a>
                            </div>

                            <x-ts-button
                                class="w-full !rounded-2xl !bg-gradient-to-r !from-indigo-600 !to-violet-600 !py-4 !text-sm !font-bold !text-white !shadow-lg !shadow-indigo-600/30 hover:!shadow-indigo-600/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300"
                                icon="arrow-right" position="right" type="submit" loading="login">
                                <x-slot:text>Gas Masuk! 🚀</x-slot:text>
                            </x-ts-button>

                            @if ($is_credentials)
                                <div class="mt-8">
                                    <div class="relative">
                                        <div class="absolute inset-0 flex items-center">
                                            <div class="w-full border-t border-slate-200"></div>
                                        </div>
                                        <div class="relative flex justify-center text-sm">
                                            <span class="bg-white px-4 text-slate-400 font-bold uppercase tracking-wider text-xs">Atau login sebagai</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 grid grid-cols-2 gap-3">
                                        @foreach ($credentials as $role => $val)
                                            <button type="button" wire:click="getCredentials('{{ $role }}')"
                                                class="flex w-full items-center justify-center rounded-xl border-2 border-slate-100 bg-white px-4 py-2.5 text-sm font-bold text-slate-600 shadow-sm transition-all duration-300 hover:border-indigo-500 hover:bg-indigo-50 hover:text-indigo-700 hover:-translate-y-1">
                                                <span class="capitalize">{{ $role }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </form>

                        <!-- Apps Download -->
                        @if($company->app_windows || $company->app_mac || $company->app_android || $company->app_ios)
                            <div class="mt-10 pt-8 border-t border-slate-100">
                                <p class="text-center text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Unduh Aplikasi CBT</p>
                                <div class="flex flex-wrap justify-center gap-3">
                                    @if($company->app_windows)
                                        <a href="{{ Storage::url($company->app_windows) }}"
                                            class="group flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-600 transition-all duration-300 hover:bg-blue-500 hover:text-white hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span>Windows</span>
                                        </a>
                                    @endif
                                    @if($company->app_mac)
                                        <a href="{{ Storage::url($company->app_mac) }}"
                                            class="group flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-600 transition-all duration-300 hover:bg-slate-800 hover:text-white hover:shadow-lg hover:shadow-slate-800/30 hover:-translate-y-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <span>Mac OS</span>
                                        </a>
                                    @endif
                                    @if($company->app_android)
                                        <a href="{{ Storage::url($company->app_android) }}"
                                            class="group flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-600 transition-all duration-300 hover:bg-emerald-500 hover:text-white hover:shadow-lg hover:shadow-emerald-500/30 hover:-translate-y-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M16.6026 12.0253L13.7118 16.9946L16.2737 21.432C16.5959 20.9126 17.0628 20.4862 17.6186 20.1983C18.1744 19.9103 18.7951 19.7728 19.4211 19.8021C20.0471 19.8315 20.6517 20.0264 21.1628 20.3638L21.8491 20.8166C21.7259 20.5746 21.6575 20.3065 21.6526 20.0353V3.96464C21.6575 3.69345 21.7259 3.42531 21.8491 3.18337L21.1628 3.63618C20.6517 3.97354 20.0471 4.16843 19.4211 4.19782C18.7951 4.2272 18.1744 4.08972 17.6186 3.80173C17.0628 3.51374 16.5959 3.08731 16.2737 2.56793L13.7118 7.00532L16.6026 11.9746V12.0253Z" />
                                            </svg>
                                            <span>Android</span>
                                        </a>
                                    @endif
                                    @if($company->app_ios)
                                        <a href="{{ Storage::url($company->app_ios) }}"
                                            class="group flex items-center gap-2 rounded-xl bg-slate-50 px-4 py-2.5 text-sm font-bold text-slate-600 transition-all duration-300 hover:bg-slate-900 hover:text-white hover:shadow-lg hover:shadow-slate-900/30 hover:-translate-y-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.79-1.31.02-2.3-1.23-3.14-2.47-1.72-2.5-3.03-7.07-1.26-10.13 0.88-1.5 2.45-2.47 4.16-2.5 1.3 0 2.52.88 3.3.88 0.77 0 2.22-1.09 3.73-0.93 0.64.03 2.43.26 3.58 1.94-0.09.06-2.14 1.25-2.12 3.72 0.03 2.96 2.59 3.96 2.65 4-0.02.06-0.41 1.41-1.37 2.82h0ZM13 3.5c.67-.82 1.13-1.95 1.01-3.09-0.97.04-2.14.65-2.83 1.46-.61.7-1.12 1.83-0.99 3.05 1.08.08 2.18-.59 2.81-1.42h0Z" />
                                            </svg>
                                            <span>iOS</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-8 text-center">
                            <p class="text-xs font-semibold text-slate-400">
                                &copy; {{ date('Y') }} {{ $company->name }}. All rights reserved.
                            </p>
                        </div>

                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</div>