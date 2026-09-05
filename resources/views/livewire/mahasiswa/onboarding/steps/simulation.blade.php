@php
    $answeredCount = collect($simAnswers)->filter(fn($a) => $a !== '')->count();
    $markedCount = collect($simMarks)->filter(fn($m) => $m)->count();
    $unansweredCount = count($simQuestions) - $answeredCount;
    $completionPercentage = count($simQuestions) > 0 ? round(($answeredCount / count($simQuestions)) * 100) : 0;
    
    $user = Auth::user();
    $nim = $user->userDetail->nim ?? $user->nim ?? '-';
    $company = \App\Models\Company\Company::first();
    $companyColor = $company->color_primary ?? '#f58634';
@endphp

<!-- Step 3: Interactive Simulation - Exact Replica of ExamContainer.jsx -->
<div wire:key="step-3" class="animate-fadeIn w-full h-screen overflow-hidden flex flex-col bg-gray-100 relative font-sans text-gray-900" 
    x-data="{ 
        showGuide: 1, 
        activeTooltip: null,
        fontSize: localStorage.getItem('exam_font_size') || 'medium',
        isNavOpen: false,
        isMonitorOpen: false,
        time: @entangle('simTimeSeconds'),
        timer: null,
        lastSavedTime: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
        init() {
            this.timer = setInterval(() => { if(this.time > 0) this.time--; }, 1000);
        },
        formatTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return [h, m, s].map(v => v < 10 ? '0' + v : v).join(':');
        },
        setFontSize(size) {
            this.fontSize = size;
            localStorage.setItem('exam_font_size', size);
        },
        updateSavedTime() {
            this.lastSavedTime = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        },
        confirmFinishOnboarding() {
            const swalInstance = window.Swal || (typeof Swal !== 'undefined' ? Swal : null);
            if (swalInstance) {
                swalInstance.fire({
                    title: 'Selesaikan Simulasi?',
                    text: 'Apakah Anda yakin ingin menyelesaikan panduan onboarding ini dan masuk ke jadwal ujian asli?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#ea580c',
                    cancelButtonColor: '#4b5563',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.$wire.finish();
                    }
                });
            } else if (window.confirm('Apakah Anda yakin ingin menyelesaikan panduan onboarding ini dan masuk ke jadwal ujian asli?')) {
                this.$wire.finish();
            }
        }
    }">

    <!-- CSS Overrides to Force Full Viewport and Hide Layout Footer -->
    <style>
        html, body {
            overflow: hidden !important;
            height: 100% !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        body > div.min-h-screen.flex.flex-col {
            min-height: 0 !important;
            height: 100vh !important;
            overflow: hidden !important;
        }
        body > div.min-h-screen.flex.flex-col > footer {
            display: none !important;
        }
        body > div.min-h-screen.flex.flex-col > main {
            height: 100vh !important;
            flex-grow: 1 !important;
            overflow: hidden !important;
        }
        [x-cloak] {
            display: none !important;
        }

        /* Tooltip behavior on mouse hover and mobile tap */
        .has-tooltip {
            position: relative;
        }
        .has-tooltip .tooltip-box {
            visibility: hidden;
            opacity: 0;
            transform: translateY(4px);
            transition: opacity 0.15s ease, transform 0.15s ease, visibility 0.15s;
            pointer-events: none;
        }
        .has-tooltip:hover .tooltip-box {
            visibility: visible !important;
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
    </style>

    <!-- ── Top Header ── -->
    <header class="flex-none flex items-center justify-between px-2.5 sm:px-4 py-2 sm:py-2.5 bg-white border-b border-gray-200 shadow-sm z-30 min-h-[52px]">
        <!-- Left: Kembali button + logo / shield + title -->
        <div class="flex items-center gap-1.5 sm:gap-3 shrink-0">
            <button wire:click="prevStep" 
                title="Kembali ke Profil Mahasiswa"
                class="flex items-center justify-center gap-1.5 px-2.5 sm:px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg sm:rounded-xl text-xs font-bold transition-all shadow-sm">
                <i class="fas fa-arrow-left text-xs"></i>
                <span class="hidden sm:inline">Kembali</span>
            </button>
            
            <!-- Shield Logo Container with Title Tooltip -->
            <div class="has-tooltip relative shrink-0" 
                 @click="activeTooltip = activeTooltip === 'title' ? null : 'title'">
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center bg-orange-600/10 cursor-pointer transition-transform hover:scale-105 active:scale-95">
                    <i class="fas fa-shield-alt text-orange-600 text-xs sm:text-sm"></i>
                </div>
                <!-- Floating Tooltip for Judul Soal / Sesi -->
                <div class="tooltip-box absolute left-0 top-full mt-2 flex flex-col z-[999] px-3 py-2 bg-gray-900 text-white text-[11px] font-medium rounded-xl shadow-2xl whitespace-nowrap border border-gray-700"
                     :class="activeTooltip === 'title' ? '!visible !opacity-100 !translate-y-0' : ''">
                    <span class="font-bold text-orange-400 flex items-center gap-1.5">
                        <i class="fas fa-shield-alt text-[10px]"></i> Simulasi Ujian
                    </span>
                    <span class="text-[10px] text-gray-300 mt-0.5">Panduan Onboarding PRO-CBT</span>
                    <div class="absolute -top-1.5 left-3 w-3 h-3 bg-gray-900 rotate-45 border-l border-t border-gray-700"></div>
                </div>
            </div>
            
            <div class="leading-tight hidden sm:block">
                <div class="font-bold text-sm text-gray-800">Simulasi Ujian</div>
                <div class="text-[10px] text-gray-400 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full inline-block animate-pulse"></span>
                    Panduan Onboarding
                </div>
            </div>
        </div>
        
        <!-- Center: Student name + Progress info (Desktop only) -->
        <div class="hidden md:flex items-center gap-2 text-sm font-medium">
            <span class="text-gray-700 font-semibold">{{ $user->name }}</span>
            <span class="text-gray-300">·</span>
            <span class="text-gray-500 text-xs">{{ $answeredCount }} / {{ count($simQuestions) }} soal dijawab</span>
        </div>
        
        <!-- Right: Info Panduan + Timer + Mobile Drawer Menu buttons -->
        <div class="flex items-center gap-1.5 sm:gap-2.5 shrink-0">
            <!-- Info / Panduan Icon Button with Tooltip -->
            <div class="has-tooltip relative shrink-0" 
                 @click="activeTooltip = activeTooltip === 'info' ? null : 'info'">
                <button @click="showGuide = (showGuide > 0 ? 0 : 1)" 
                    class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-xs font-bold transition-all shadow-sm cursor-pointer"
                    :class="showGuide > 0 ? 'bg-gray-900 text-white hover:bg-gray-800' : 'bg-orange-600 hover:bg-orange-700 text-white shadow-orange-200'">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
                <div class="tooltip-box absolute right-0 top-full mt-2 flex flex-col z-[999] px-3 py-1.5 bg-gray-900 text-white text-[11px] font-medium rounded-xl shadow-2xl whitespace-nowrap border border-gray-700"
                     :class="activeTooltip === 'info' ? '!visible !opacity-100 !translate-y-0' : ''">
                    <span class="font-bold text-orange-400 flex items-center gap-1.5">
                        <i class="fas fa-info-circle text-[10px]"></i> <span x-text="showGuide > 0 ? 'Tutup Panduan' : 'Buka Panduan'"></span>
                    </span>
                    <span class="text-[10px] text-gray-300 mt-0.5">Petunjuk interaktif fitur simulasi</span>
                    <div class="absolute -top-1.5 right-3 w-3 h-3 bg-gray-900 rotate-45 border-l border-t border-gray-700"></div>
                </div>
            </div>

            <!-- Timer Countdown with Tooltip -->
            <div class="has-tooltip relative shrink-0" 
                 @click="activeTooltip = activeTooltip === 'timer' ? null : 'timer'">
                <div class="flex items-center gap-1 sm:gap-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg border bg-orange-600/10 border-orange-600/30 cursor-pointer">
                    <i class="fas fa-clock text-green-600 text-[11px] sm:text-xs"></i>
                    <span class="font-mono font-bold text-xs sm:text-sm tracking-wide sm:tracking-widest text-orange-600" x-text="formatTime(time)"></span>
                </div>
                <div class="tooltip-box absolute right-0 top-full mt-2 flex flex-col z-[999] px-3 py-1.5 bg-gray-900 text-white text-[11px] font-medium rounded-xl shadow-2xl whitespace-nowrap border border-gray-700"
                     :class="activeTooltip === 'timer' ? '!visible !opacity-100 !translate-y-0' : ''">
                    <span class="font-bold text-green-400 flex items-center gap-1.5">
                        <i class="fas fa-hourglass-half text-[10px]"></i> Sisa Waktu Ujian
                    </span>
                    <span class="text-[10px] text-gray-300 mt-0.5">Hitung mundur otomatis</span>
                    <div class="absolute -top-1.5 right-4 w-3 h-3 bg-gray-900 rotate-45 border-l border-t border-gray-700"></div>
                </div>
            </div>

            <!-- Mobile toggle drawers (compact icon buttons on mobile) -->
            <div class="flex lg:hidden gap-1 sm:gap-1.5">
                <!-- Navigasi Soal Button with Tooltip -->
                <div class="has-tooltip relative" 
                     @click="activeTooltip = activeTooltip === 'nav' ? null : 'nav'">
                    <button @click="isNavOpen = !isNavOpen; isMonitorOpen = false" 
                        class="w-7 h-7 sm:w-auto sm:px-2.5 sm:py-1.5 rounded-lg border border-gray-200 text-gray-700 bg-gray-50 hover:bg-gray-100 flex items-center justify-center sm:gap-1 text-xs font-bold shadow-sm active:scale-95 transition-all">
                        <i class="fas fa-th text-orange-600 text-xs"></i>
                        <span class="hidden sm:inline text-[11px]">Soal</span>
                    </button>
                    <div class="tooltip-box absolute right-0 top-full mt-2 flex flex-col z-[999] px-3 py-1.5 bg-gray-900 text-white text-[11px] font-medium rounded-xl shadow-2xl whitespace-nowrap border border-gray-700"
                         :class="activeTooltip === 'nav' ? '!visible !opacity-100 !translate-y-0' : ''">
                        <span class="font-bold text-orange-400 flex items-center gap-1.5">
                            <i class="fas fa-th text-[10px]"></i> Daftar Soal
                        </span>
                        <span class="text-[10px] text-gray-300 mt-0.5">Buka navigasi nomor soal</span>
                        <div class="absolute -top-1.5 right-2.5 w-3 h-3 bg-gray-900 rotate-45 border-l border-t border-gray-700"></div>
                    </div>
                </div>

                <!-- Panel Pengawas Button with Tooltip -->
                <div class="has-tooltip relative" 
                     @click="activeTooltip = activeTooltip === 'mon' ? null : 'mon'">
                    <button @click="isMonitorOpen = !isMonitorOpen; isNavOpen = false" 
                        class="w-7 h-7 sm:w-auto sm:px-2.5 sm:py-1.5 rounded-lg border border-gray-200 text-gray-700 bg-gray-50 hover:bg-gray-100 flex items-center justify-center sm:gap-1 text-xs font-bold shadow-sm active:scale-95 transition-all">
                        <i class="fas fa-shield-alt text-orange-600 text-xs"></i>
                        <span class="hidden sm:inline text-[11px]">Pengawas</span>
                    </button>
                    <div class="tooltip-box absolute right-0 top-full mt-2 flex flex-col z-[999] px-3 py-1.5 bg-gray-900 text-white text-[11px] font-medium rounded-xl shadow-2xl whitespace-nowrap border border-gray-700"
                         :class="activeTooltip === 'mon' ? '!visible !opacity-100 !translate-y-0' : ''">
                        <span class="font-bold text-orange-400 flex items-center gap-1.5">
                            <i class="fas fa-shield-alt text-[10px]"></i> Panel Pengawas
                        </span>
                        <span class="text-[10px] text-gray-300 mt-0.5">Status proctoring kamera</span>
                        <div class="absolute -top-1.5 right-2.5 w-3 h-3 bg-gray-900 rotate-45 border-l border-t border-gray-700"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ── Main 3-Column Layout ── -->
    <div class="flex flex-1 overflow-hidden relative">

        <!-- Mobile backdrop overlay -->
        <div x-show="isNavOpen || isMonitorOpen" @click="isNavOpen = false; isMonitorOpen = false" class="lg:hidden fixed inset-0 bg-black/50 z-40" x-cloak></div>

        <!-- Left Sidebar (Navigation Sidebar) -->
        <aside 
            class="h-full flex-none flex flex-col bg-white border-r border-gray-200 fixed lg:static top-0 left-0 z-50 lg:z-0 shadow-2xl lg:shadow-none transition-transform lg:transition-none duration-300"
            :class="isNavOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            style="width: 240px;"
        >
            <!-- Header -->
            <div class="flex-none p-3 border-b border-gray-100" style="background-color: var(--primary, #f58634)">
                <div class="flex items-center justify-between">
                    <span class="text-white text-xs font-bold tracking-widest uppercase">Navigasi Soal</span>
                    <button @click="isNavOpen = false" class="lg:hidden text-white/70 hover:text-white">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
                <div class="mt-2 flex items-center justify-between text-white/80 text-[11px]">
                    <span>Soal <span x-text="(parseInt($wire.simCurrentIndex) + 1)"></span> dari {{ count($simQuestions) }}</span>
                    <span>{{ $completionPercentage }}%</span>
                </div>
                <div class="mt-1.5 h-1.5 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-green-400 rounded-full transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="flex-none grid grid-cols-3 border-b border-gray-100">
                <div class="flex flex-col items-center py-2 border-r border-gray-100">
                    <span class="font-black text-base leading-tight text-green-600">{{ $answeredCount }}</span>
                    <span class="text-[8px] font-bold text-gray-400 tracking-wider font-mono">DIJAWAB</span>
                </div>
                <div class="flex flex-col items-center py-2 border-r border-gray-100">
                    <span class="font-black text-base leading-tight text-red-500">{{ $unansweredCount }}</span>
                    <span class="text-[8px] font-bold text-gray-400 tracking-wider font-mono">BELUM</span>
                </div>
                <div class="flex flex-col items-center py-2">
                    <span class="font-black text-base leading-tight text-yellow-500">{{ $markedCount }}</span>
                    <span class="text-[8px] font-bold text-gray-400 tracking-wider font-mono">RAGU</span>
                </div>
            </div>

            <!-- Filter Tabs Mock -->
            <div class="flex-none px-2 py-2 border-b border-gray-100">
                <div class="flex gap-1 flex-wrap">
                    <button class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border bg-gray-900 border-gray-900 text-white">
                        Semua
                    </button>
                    <button class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-gray-200 text-gray-500 bg-gray-50">
                        Dijawab
                    </button>
                    <button class="text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-gray-200 text-gray-500 bg-gray-50">
                        Belum
                    </button>
                </div>
            </div>

            <!-- Legend -->
            <div class="flex-none px-2 py-1.5 border-b border-gray-100 bg-gray-50">
                <div class="flex flex-wrap gap-x-2 gap-y-1">
                    <div class="flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-blue-900"></span>
                        <span class="text-[9px] text-gray-500 font-medium">Aktif</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                        <span class="text-[9px] text-gray-500 font-medium">Isi</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-yellow-500"></span>
                        <span class="text-[9px] text-gray-500 font-medium">Ragu</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="inline-block w-2 h-2 rounded-full bg-white border border-gray-300"></span>
                        <span class="text-[9px] text-gray-500 font-medium">Belum</span>
                    </div>
                </div>
            </div>

            <!-- Question Grid -->
            <div class="flex-1 overflow-y-auto p-2">
                <div class="grid grid-cols-6 gap-1">
                    @foreach($simQuestions as $index => $q)
                        @php
                            $btnStyle = 'background-color: #fff; color: #94a3b8; border: 1.5px solid #e2e8f0;';
                            $fontWeight = 'font-medium';
                            if ($simCurrentIndex === $index) {
                                $btnStyle = 'background-color: #1e3a5f; color: #fff; border: 1.5px solid transparent; font-weight: 700;';
                            } elseif ($simMarks[$q['id']]) {
                                $btnStyle = 'background-color: #f59e0b; color: #fff; border: 1.5px solid transparent;';
                            } elseif ($simAnswers[$q['id']] !== '') {
                                $btnStyle = 'background-color: #16a34a; color: #fff; border: 1.5px solid transparent;';
                            }
                        @endphp
                        <button 
                            wire:click="setSimIndex({{ $index }}); isNavOpen = false"
                            class="relative flex items-center justify-center rounded-md text-[11px] transition-all duration-150 hover:scale-110 active:scale-95 shadow-sm font-bold"
                            style="height: 32px; {{ $btnStyle }}"
                            title="Soal {{ $index + 1 }}"
                        >
                            {{ $index + 1 }}
                            @if($simMarks[$q['id']])
                                <span class="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Bottom Action finish -->
            <div class="flex-none p-2 border-t border-gray-100">
                <button @click="confirmFinishOnboarding()" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg text-xs font-bold bg-green-600 hover:bg-green-700 text-white transition-all shadow-sm">
                    <i class="fas fa-check-circle"></i>
                    <span>Selesai Ujian</span>
                </button>
            </div>
        </aside>

        <!-- Center: Question Panel (QuestionArea Replica) -->
        <main class="flex-1 flex flex-col h-full bg-white relative overflow-hidden">
            <!-- Top Bar -->
            <div class="flex-none flex flex-wrap items-center gap-1.5 px-3 py-2 bg-white border-b border-gray-200 shadow-sm z-10">
                <div class="hidden sm:flex items-center gap-1 text-xs font-semibold text-gray-400 pr-2 border-r border-gray-200 mr-1">
                    <i class="fas fa-bars text-[10px]"></i>
                    <span>Navigasi Soal</span>
                </div>
                
                <span class="text-xs font-bold text-gray-700 mr-2">Soal {{ $simCurrentIndex + 1 }} dari {{ count($simQuestions) }}</span>
                
                <!-- Font Size Selector -->
                <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1 border border-gray-200">
                    <button
                        @click="setFontSize('small')"
                        class="px-2 py-1 rounded text-[10px] font-bold transition-all"
                        :class="fontSize === 'small' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800'"
                        title="Ukuran Huruf Kecil"
                    >
                        Kecil
                    </button>
                    <button
                        @click="setFontSize('medium')"
                        class="px-2 py-1 rounded text-[10px] font-bold transition-all"
                        :class="fontSize === 'medium' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800'"
                        title="Ukuran Huruf Sedang"
                    >
                        Sedang
                    </button>
                    <button
                        @click="setFontSize('large')"
                        class="px-2 py-1 rounded text-[10px] font-bold transition-all"
                        :class="fontSize === 'large' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800'"
                        title="Ukuran Huruf Besar"
                    >
                        Besar
                    </button>
                </div>
                
                <div class="flex-1"></div>
                
                <!-- Ragu-Ragu button -->
                <button
                    wire:click="toggleSimMark({{ $simQuestions[$simCurrentIndex]['id'] }}); updateSavedTime()"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all shadow-sm active:scale-[0.98]"
                    style="background-color: {{ $simMarks[$simQuestions[$simCurrentIndex]['id']] ? '#fef9c3' : '#f8fafc' }}; border-color: {{ $simMarks[$simQuestions[$simCurrentIndex]['id']] ? '#f59e0b' : '#e2e8f0' }}; color: {{ $simMarks[$simQuestions[$simCurrentIndex]['id']] ? '#92400e' : '#64748b' }};"
                >
                    <i class="fas fa-question-circle"></i>
                    <span>Ragu-Ragu</span>
                </button>
                
                <!-- Real-time Save status badge -->
                <div class="flex items-center gap-1.5 px-2 py-1 bg-white border border-gray-200 rounded-full text-[11px] font-semibold shadow-sm whitespace-nowrap">
                    <span class="relative flex h-2 w-2 flex-none">
                        <span class="absolute inline-flex h-full w-full rounded-full opacity-75 bg-green-500"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <span class="text-green-600">Tersimpan</span>
                    <span class="text-gray-400 font-mono text-[10px] hidden sm:inline" x-text="lastSavedTime"></span>
                </div>
            </div>

            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-10 relative">
                <div class="max-w-3xl mx-auto space-y-6">
                    <!-- Question Number label -->
                    <p class="text-xs font-semibold text-gray-400">Soal ke-{{ $simCurrentIndex + 1 }}:</p>
                    
                    <!-- Question Text -->
                    <div :class="{
                        'text-xs sm:text-sm leading-relaxed text-gray-800 font-medium': fontSize === 'small',
                        'text-sm sm:text-base leading-relaxed text-gray-800 font-medium': fontSize === 'medium',
                        'text-base sm:text-lg leading-relaxed text-gray-800 font-medium': fontSize === 'large'
                    }">
                        {{ $simQuestions[$simCurrentIndex]['question'] }}
                    </div>
                    
                    <!-- Answer Selection Area -->
                    <div class="pb-10 pt-2">
                        @if($simQuestions[$simCurrentIndex]['type'] === 'single')
                            <div class="grid grid-cols-1 gap-3.5">
                                @php $alphabets = ['a', 'b', 'c', 'd', 'e']; @endphp
                                @foreach($simQuestions[$simCurrentIndex]['options'] as $i => $option)
                                    @php $isSelected = $simAnswers[$simQuestions[$simCurrentIndex]['id']] === $option['id']; @endphp
                                    <button
                                        wire:click="selectSimAnswer({{ $simQuestions[$simCurrentIndex]['id'] }}, '{{ $option['id'] }}'); updateSavedTime()"
                                        class="w-full flex items-start gap-3 p-3.5 rounded-xl border-2 text-left transition-all duration-150 hover:shadow-sm active:scale-[0.99]"
                                        style="border-color: {{ $isSelected ? 'var(--primary, #f58634)' : '#e5e7eb' }}; background-color: {{ $isSelected ? 'rgba(245, 134, 52, 0.06)' : '#fff' }}"
                                    >
                                        <!-- Letter circle -->
                                        <div
                                            class="flex-none w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-black transition-all"
                                            style="background-color: {{ $isSelected ? 'var(--primary, #f58634)' : '#f1f5f9' }}; color: {{ $isSelected ? '#fff' : '#64748b' }}"
                                        >
                                            {{ strtoupper($option['id']) }}
                                        </div>
                                        
                                        <!-- Answer context -->
                                        <div :class="{
                                            'flex-1 text-xs text-gray-800 leading-relaxed pt-0.5 space-y-2': fontSize === 'small',
                                            'flex-1 text-sm text-gray-800 leading-relaxed pt-0.5 space-y-2': fontSize === 'medium',
                                            'flex-1 text-base text-gray-800 leading-relaxed pt-0.5 space-y-2': fontSize === 'large'
                                        }">
                                            {{ $option['text'] }}
                                        </div>
                                        
                                        <!-- Check icon -->
                                        @if($isSelected)
                                            <i class="fas fa-check-circle flex-none w-4 h-4 mt-1" style="color: var(--primary, #f58634)"></i>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <!-- Essay Area -->
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-gray-500">Jawaban Anda:</label>
                                <textarea
                                    wire:model.live="simAnswers.{{ $simQuestions[$simCurrentIndex]['id'] }}"
                                    x-on:input="updateSavedTime()"
                                    rows="7"
                                    placeholder="Ketik jawaban Anda di sini..."
                                    :class="{
                                        'w-full p-4 border-2 border-gray-200 rounded-xl text-xs text-gray-800 resize-none focus:outline-none transition-colors': fontSize === 'small',
                                        'w-full p-4 border-2 border-gray-200 rounded-xl text-sm text-gray-800 resize-none focus:outline-none transition-colors': fontSize === 'medium',
                                        'w-full p-4 border-2 border-gray-200 rounded-xl text-base text-gray-800 resize-none focus:outline-none transition-colors': fontSize === 'large'
                                    }"
                                    style="--tw-ring-color: var(--primary, #f58634)"
                                    onfocus="this.style.borderColor = 'var(--primary, #f58634)'"
                                    onblur="this.style.borderColor = '#e5e7eb'"
                                ></textarea>
                                <p class="text-xs text-gray-400 italic">
                                    Jawaban Anda akan disimpan secara otomatis saat Anda mengetik. (Loader sinkronisasi: <i class="fas fa-spinner animate-spin mx-1" wire:loading wire:target="simAnswers.{{ $simQuestions[$simCurrentIndex]['id'] }}"></i>)
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer Navigation Area -->
            <div class="flex-none border-t border-gray-200 bg-white px-3 py-2.5 flex items-center gap-2 flex-wrap sm:flex-nowrap">
                <!-- Prev Button -->
                <button
                    wire:click="setSimIndex({{ max(0, $simCurrentIndex - 1) }})"
                    @if($simCurrentIndex === 0) disabled @endif
                    class="flex items-center gap-1 px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all whitespace-nowrap"
                >
                    <i class="fas fa-chevron-left text-[10px]"></i>
                    <span>Sebelumnya</span>
                </button>

                <!-- Page indicators (middle) -->
                <div class="flex-1 flex items-center justify-center gap-1 overflow-hidden">
                    @foreach($simQuestions as $index => $q)
                        @php
                            $isCurrent = $simCurrentIndex === $index;
                        @endphp
                        <button
                            wire:click="setSimIndex({{ $index }})"
                            class="w-7 h-7 rounded-md text-xs font-bold transition-all hover:opacity-80"
                            style="background-color: {{ $isCurrent ? '#1e3a5f' : '#f1f5f9' }}; color: {{ $isCurrent ? '#fff' : '#475569' }}"
                        >
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>

                <!-- Next / Finish Button -->
                @if($simCurrentIndex < count($simQuestions) - 1)
                    <button
                        wire:click="setSimIndex({{ $simCurrentIndex + 1 }})"
                        class="flex items-center gap-1 px-4 py-2 rounded-lg text-xs font-bold text-white transition-all whitespace-nowrap"
                        style="background-color: var(--primary, #f58634)"
                    >
                        <span>Selanjutnya</span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </button>
                @else
                    <button
                        @click="confirmFinishOnboarding()"
                        class="flex items-center gap-1 px-4 py-2 rounded-lg text-xs font-bold text-white bg-green-600 hover:bg-green-700 transition-all whitespace-nowrap shadow-sm"
                    >
                        <i class="fas fa-check-circle"></i>
                        <span>Selesai</span>
                    </button>
                @endif
            </div>
        </main>

        <!-- Right Sidebar (Monitor Sidebar) -->
        <aside 
            class="h-full flex-none flex flex-col bg-white border-l border-gray-200 overflow-y-auto overflow-x-hidden fixed lg:static top-0 right-0 z-50 lg:z-0 shadow-2xl lg:shadow-none transition-transform lg:transition-none duration-300"
            :class="isMonitorOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'"
            style="width: 256px;"
        >
            <!-- Header -->
            <div class="flex-none flex items-center justify-between px-3 py-2.5 bg-white border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fas fa-shield-alt text-sm" style="color: var(--primary, #f58634)"></i>
                    <span class="text-xs font-bold tracking-wider uppercase" style="color: var(--primary, #f58634)">
                        Pengawas Aktif
                    </span>
                </div>
                <button @click="isMonitorOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Avatar Profile -->
            <div class="flex-none p-4 border-b bg-gray-50/30 flex flex-col items-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg mb-2 ring-4 ring-orange-50" style="background-color: var(--primary, #f58634)">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <h3 class="font-bold text-gray-800 text-sm text-center line-clamp-2 leading-tight">{{ $user->name }}</h3>
                <p class="text-[11px] text-orange-600 font-bold tracking-wider mt-0.5">{{ $nim }}</p>
            </div>

            <!-- Camera box mock -->
            <div class="flex-none mx-2 mt-2 rounded-xl overflow-hidden bg-gray-900 border border-gray-700 relative" style="aspect-ratio: 4/3">
                <div class="absolute inset-0 flex flex-col items-center justify-center gap-2 bg-gray-800 opacity-60">
                    <i class="fas fa-user-circle text-4xl text-gray-500 animate-pulse"></i>
                    <span class="text-gray-400 text-[10px] tracking-wider font-semibold">Webcam Simulasi</span>
                </div>
                
                <div class="absolute top-2 left-2 right-2 flex items-center justify-between pointer-events-none">
                    <div class="flex items-center gap-1 bg-black/70 px-2 py-0.5 rounded-full">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-white text-[9px] font-bold">LIVE</span>
                    </div>
                    <div class="flex items-center gap-1 bg-black/70 px-2 py-0.5 rounded-full">
                        <i class="fas fa-video text-red-400 text-[9px]"></i>
                        <span class="text-white text-[9px] font-bold">REC</span>
                    </div>
                </div>
            </div>

            <!-- Camera buttons -->
            <div class="flex-none flex gap-2 mx-2 mt-2">
                <button class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-[10px] font-semibold text-gray-600 transition-all">
                    <i class="fas fa-camera"></i>
                    Test Kamera
                </button>
                <button class="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-[10px] font-semibold text-gray-600 transition-all">
                    <i class="fas fa-sync-alt"></i>
                    Reconnect
                </button>
            </div>

            <!-- Progress Overview -->
            <div class="flex-none mx-2 mt-3 bg-orange-50/50 rounded-xl border border-orange-100/50 p-3">
                <div class="text-[10px] text-gray-400 font-bold uppercase">Progres Ujian</div>
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-orange-700">{{ $completionPercentage }}%</span>
                    <span class="text-[9px] text-gray-400 font-medium">Terselesaikan</span>
                </div>
                <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full bg-orange-600 rounded-full transition-all duration-700 ease-out" style="width: {{ $completionPercentage }}%"></div>
                </div>
            </div>

            <!-- Proctoring status -->
            <div class="flex-none mx-2 mt-2.5 bg-gray-50 rounded-xl border border-gray-100 p-3 space-y-1.5">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] text-gray-500 font-medium">Pelanggaran Terdeteksi</span>
                    <span class="text-[10px] font-black text-green-600 flex items-center gap-1">
                        0 Pelanggaran
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] text-gray-500 font-medium">Sisa Toleransi Ujian</span>
                    <span class="text-[10px] font-bold text-amber-600">5 kali</span>
                </div>
            </div>

            <!-- Logs activity -->
            <div class="flex-1 p-3">
                <h4 class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Aktivitas Sesi</h4>
                <div class="space-y-2">
                    <div class="flex gap-2">
                        <i class="fas fa-check-circle text-green-500 text-xs mt-0.5"></i>
                        <div>
                            <p class="text-[10px] font-bold text-gray-700">Status Streaming</p>
                            <p class="text-[9px] text-gray-400 leading-none">Streaming aktif dan terpantau</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <i class="fas fa-sync-alt text-orange-500 text-xs mt-0.5 animate-spin"></i>
                        <div>
                            <p class="text-[10px] font-bold text-gray-700">Auto-sync Aktif</p>
                            <p class="text-[9px] text-gray-400 leading-none">Sinkronisasi data otomatis berjalan</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- ── Interactive Guide Overlay System (No overflow clipping) ── -->
    <div class="absolute inset-0 pointer-events-none z-50 overflow-hidden">
        <!-- Overlay backdrop for step guides -->
        <div class="absolute inset-0 bg-black/30 pointer-events-auto" x-show="showGuide > 0 && showGuide <= 5" @click="showGuide = 0" x-cloak></div>

        <!-- GUIDE 1: Header Info -->
        <div class="fixed inset-x-3 sm:inset-x-auto top-14 sm:top-20 sm:left-1/2 sm:-translate-x-1/2 max-w-lg sm:w-[28rem] mx-auto bg-gray-900 border-2 border-white p-4 sm:p-5 rounded-2xl shadow-2xl text-white pointer-events-auto animate-float-subtle"
            x-show="showGuide === 1" x-cloak>
            <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-4 bg-gray-900 border-l-2 border-t-2 border-white rotate-45"></div>
            <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                <div class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">1</div>
                <span class="font-bold text-sm sm:text-base text-orange-400">Header Informasi Ujian</span>
            </div>
            <div class="text-xs sm:text-[13px] text-gray-300 leading-relaxed flex flex-col gap-2.5 sm:gap-3 font-medium">
                <p>Bagian atas halaman ujian ini memuat informasi utama sesi Anda:</p>
                <ul class="list-disc pl-4 space-y-1 sm:space-y-1.5 marker:text-orange-500 text-[11px] sm:text-xs">
                    <li><b>Nama Sesi:</b> Menunjukkan tes/mata pelajaran yang aktif.</li>
                    <li><b>Waktu Tersisa:</b> Countdown otomatis. Jika habis, jawaban tersimpan otomatis.</li>
                    <li><b>Selesai Ujian:</b> Tombol untuk mengakhiri simulasi.</li>
                </ul>
                <button @click="showGuide = 2" class="mt-1 sm:mt-2 w-full py-2.5 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-xs font-bold rounded-xl transition-colors text-center text-white shadow-md shadow-orange-600/30">
                    Lanjut ke Panel Navigasi &rarr;
                </button>
            </div>
        </div>

        <!-- GUIDE 2: Navigation Sidebar -->
        <div class="fixed inset-x-3 sm:inset-x-auto top-14 sm:top-24 sm:left-[260px] max-w-lg sm:w-[22rem] mx-auto sm:mx-0 bg-gray-900 border-2 border-white p-4 sm:p-5 rounded-2xl shadow-2xl text-white pointer-events-auto animate-float-subtle"
            x-show="showGuide === 2" x-cloak>
            <div class="hidden sm:block absolute top-8 -left-2.5 w-4 h-4 bg-gray-900 border-b-2 border-l-2 border-white rotate-45"></div>
            <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                <div class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">2</div>
                <span class="font-bold text-sm sm:text-base text-orange-400">Panel Navigasi Soal</span>
            </div>
            <div class="text-xs sm:text-[13px] text-gray-300 leading-relaxed mb-2.5 space-y-1.5 font-medium">
                <p>Panel Navigasi berfungsi sebagai peta ujian. Klik tombol <i class="fas fa-th text-orange-400"></i> <b>Soal</b> di atas untuk membuka peta nomor di HP.</p>
            </div>
            <ul class="text-[11px] sm:text-xs space-y-1.5 text-gray-300 bg-gray-800/80 p-2.5 sm:p-3 rounded-lg border border-gray-700/50 mb-3">
                <li class="flex items-start gap-2">
                    <div class="w-3.5 h-3.5 mt-0.5 bg-blue-900 rounded-sm shrink-0"></div>
                    <span><strong class="text-blue-400">Biru:</strong> Soal aktif saat ini.</span>
                </li>
                <li class="flex items-start gap-2">
                    <div class="w-3.5 h-3.5 mt-0.5 bg-green-500 rounded-sm shrink-0"></div>
                    <span><strong class="text-green-500">Hijau:</strong> Soal sudah dijawab.</span>
                </li>
                <li class="flex items-start gap-2">
                    <div class="w-3.5 h-3.5 mt-0.5 bg-yellow-500 rounded-sm shrink-0"></div>
                    <span><strong class="text-yellow-500">Kuning:</strong> Soal ditandai Ragu-Ragu.</span>
                </li>
            </ul>
            <div class="flex gap-2">
                <button @click="showGuide = 1" class="flex-1 py-2 bg-gray-800 hover:bg-gray-700 active:bg-gray-900 text-xs font-bold rounded-xl transition-colors border border-gray-700 text-center text-white">
                    &larr; Kembali
                </button>
                <button @click="showGuide = 3" class="flex-1 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-xs font-bold rounded-xl transition-colors text-center text-white shadow-md shadow-orange-600/30">
                    Lanjut &rarr;
                </button>
            </div>
        </div>

        <!-- GUIDE 3: Question Area -->
        <div class="fixed inset-x-3 sm:inset-x-auto top-14 sm:top-24 sm:left-1/2 sm:-translate-x-1/2 max-w-lg sm:w-[28rem] mx-auto bg-gray-900 border-2 border-white p-4 sm:p-5 rounded-2xl shadow-2xl text-white pointer-events-auto animate-float-subtle"
            x-show="showGuide === 3" x-cloak>
            <div class="absolute -top-2.5 left-1/2 -translate-x-1/2 w-4 h-4 bg-gray-900 border-l-2 border-t-2 border-white rotate-45"></div>
            <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                <div class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">3</div>
                <span class="font-bold text-sm sm:text-base text-orange-400">Area Soal & Ragu-Ragu</span>
            </div>
            <div class="text-xs sm:text-[13px] text-gray-300 leading-relaxed flex flex-col gap-2.5 sm:gap-3 font-medium">
                <p>Layar utama tempat Anda membaca soal dan menentukan jawaban Anda.</p>
                <ul class="list-disc pl-4 space-y-1 sm:space-y-1.5 marker:text-orange-500 text-[11px] sm:text-xs">
                    <li><b>Ukuran Teks:</b> Ubah ukuran font (Kecil, Sedang, Besar).</li>
                    <li><b>Pilihan Jawaban:</b> Sentuh/klik opsi untuk memilih.</li>
                    <li><b>Tombol Ragu-Ragu:</b> Tandai soal untuk dicek ulang nanti.</li>
                </ul>
                <div class="flex gap-2">
                    <button @click="showGuide = 2" class="flex-1 py-2 bg-gray-800 hover:bg-gray-700 active:bg-gray-900 text-xs font-bold rounded-xl transition-colors border border-gray-700 text-center text-white">
                        &larr; Kembali
                    </button>
                    <button @click="showGuide = 4" class="flex-1 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-xs font-bold rounded-xl transition-colors text-center text-white shadow-md shadow-orange-600/30">
                        Lanjut &rarr;
                    </button>
                </div>
            </div>
        </div>

        <!-- GUIDE 4: Auto-Save -->
        <div class="fixed inset-x-3 sm:inset-x-auto top-14 sm:top-1/3 sm:left-1/2 sm:-translate-x-1/2 max-w-lg sm:w-[26rem] mx-auto bg-gray-900 border-2 border-white p-4 sm:p-5 rounded-2xl shadow-2xl text-white pointer-events-auto animate-float-subtle"
            x-show="showGuide === 4" x-cloak>
            <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                <div class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">4</div>
                <span class="font-bold text-sm sm:text-base text-orange-400">Cloud Auto-Save Real-Time</span>
            </div>
            <div class="text-xs sm:text-[13px] text-gray-300 leading-relaxed mb-2 space-y-2 font-medium">
                <p>Sistem ujian menggunakan teknologi <b>Auto-Save</b> otomatis ke server.</p>
                <ul class="list-disc pl-4 space-y-1 marker:text-orange-500 text-[11px] sm:text-xs">
                    <li><b>Otomatis Tersimpan:</b> Setiap kali Anda memilih opsi atau mengetik, data langsung tersimpan.</li>
                    <li><b>Status:</b> Indikator hijau "Tersimpan" di atas menunjukkan status sinkronisasi.</li>
                </ul>
                <div class="flex gap-2 pt-1">
                    <button @click="showGuide = 3" class="flex-1 py-2 bg-gray-800 hover:bg-gray-700 active:bg-gray-900 text-xs font-bold rounded-xl transition-colors border border-gray-700 text-center text-white">
                        &larr; Kembali
                    </button>
                    <button @click="showGuide = 5" class="flex-1 py-2 bg-orange-600 hover:bg-orange-700 active:bg-orange-800 text-xs font-bold rounded-xl transition-colors text-center text-white shadow-md shadow-orange-600/30">
                        Lanjut &rarr;
                    </button>
                </div>
            </div>
        </div>

        <!-- GUIDE 5: Monitor Sidebar -->
        <div class="fixed inset-x-3 sm:inset-x-auto top-14 sm:top-24 sm:right-[276px] max-w-lg sm:w-[22rem] mx-auto sm:mx-0 bg-gray-900 border-2 border-white p-4 sm:p-5 rounded-2xl shadow-2xl text-white pointer-events-auto animate-float-subtle"
            x-show="showGuide === 5" x-cloak>
            <div class="hidden sm:block absolute top-8 -right-2.5 w-4 h-4 bg-gray-900 border-r-2 border-t-2 border-white rotate-45"></div>
            <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                <div class="w-6 h-6 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold shadow-md shadow-orange-500/50">5</div>
                <span class="font-bold text-sm sm:text-base text-orange-400">Pengawasan Kamera & Status</span>
            </div>
            <div class="text-xs sm:text-[13px] text-gray-300 leading-relaxed mb-2 space-y-1.5 font-medium">
                <p>Fitur pengawasan digital (klik tombol <i class="fas fa-shield-alt text-orange-400"></i> <b>Pengawas</b> di HP):</p>
                <ul class="list-disc pl-4 space-y-1 marker:text-orange-500 text-[11px] sm:text-xs">
                    <li><b>Kamera Live:</b> Memantau integritas peserta ujian secara berkala.</li>
                    <li><b>Toleransi Pelanggaran:</b> Mencatat peringatan jika keluar tab.</li>
                </ul>
                <div class="flex gap-2 pt-1">
                    <button @click="showGuide = 4" class="flex-1 py-2 bg-gray-800 hover:bg-gray-700 active:bg-gray-900 text-xs font-bold rounded-xl transition-colors border border-gray-700 text-center text-white">
                        &larr; Kembali
                    </button>
                    <button @click="showGuide = 0" class="flex-1 py-2 bg-green-600 hover:bg-green-700 active:bg-green-800 text-xs font-bold rounded-xl transition-colors text-center text-white shadow-md shadow-green-600/30">
                        Tutup & Selesai
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── FLOATING GUIDE CONTROLLER (Responsive glassmorphism dashboard bar) ── -->
    <div x-show="showGuide > 0" x-cloak class="fixed bottom-14 sm:bottom-16 left-1/2 transform -translate-x-1/2 z-50 bg-white/95 backdrop-blur-md p-1.5 sm:p-2.5 rounded-2xl shadow-2xl border border-gray-200 flex items-center gap-1 sm:gap-2 max-w-[95vw] overflow-x-auto">
        <span class="text-[11px] sm:text-xs font-bold text-gray-500 px-2 sm:px-2.5 border-r border-gray-200 hidden sm:inline">PANDUAN:</span>
        <button @click="showGuide = 1"
            class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold transition-all shadow-sm border shrink-0"
            :class="showGuide === 1 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">
            1. Info
        </button>
        <button @click="showGuide = 2"
            class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold transition-all shadow-sm border shrink-0"
            :class="showGuide === 2 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">
            2. Navigasi
        </button>
        <button @click="showGuide = 3"
            class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold transition-all shadow-sm border shrink-0"
            :class="showGuide === 3 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">
            3. Soal
        </button>
        <button @click="showGuide = 4"
            class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold transition-all shadow-sm border shrink-0"
            :class="showGuide === 4 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">
            4. Auto-Save
        </button>
        <button @click="showGuide = 5"
            class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold transition-all shadow-sm border shrink-0"
            :class="showGuide === 5 ? 'bg-orange-600 border-orange-600 text-white' : 'bg-white border-gray-200 text-gray-500 hover:border-orange-200'">
            5. Monitor
        </button>
        <button @click="showGuide = 0"
            class="px-2.5 sm:px-3.5 py-1.5 sm:py-2 rounded-xl text-[11px] sm:text-xs font-bold bg-gray-900 border border-gray-900 text-white hover:bg-gray-800 transition-all shadow-sm shrink-0 ml-1">
            <i class="fas fa-times sm:mr-1"></i> <span class="hidden sm:inline">Tutup</span>
        </button>
    </div>
</div>


<style>
    @keyframes float-subtle {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-4px);
        }
    }

    .animate-float-subtle {
        animation: float-subtle 2.5s infinite ease-in-out;
    }

    .animate-fadeIn {
        animation: fadeIn 0.4s ease-out forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>