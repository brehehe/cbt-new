<div>
    @php
        $primary = $company?->color_primary ?? '#008001';
        $secondary = $company?->color_secondary ?? '#008001';
    @endphp

    <style>
        :root {
            --primary: {{ $primary }};
            --secondary: {{ $secondary }};
        }
        .custom-primary-btn {
            background-color: {{ $primary }} !important;
            color: #ffffff !important;
            box-shadow: 0 4px 14px -2px {{ $primary }}60 !important;
        }
        .custom-primary-btn:hover {
            filter: brightness(0.92);
            box-shadow: 0 6px 18px -2px {{ $primary }}80 !important;
        }
        .focus-primary:focus {
            border-color: {{ $primary }} !important;
            box-shadow: 0 0 0 3px {{ $primary }}25 !important;
        }

        /* --- STYLING PILIHAN RATING (1-5) --- */
        .rating-box {
            background-color: #ffffff;
            border: 2px solid #e2e8f0;
            color: #1e293b;
            transition: all 0.15s ease-in-out;
        }
        .rating-box:hover {
            border-color: {{ $primary }}60;
            background-color: #f8fafc;
        }
        /* State Aktif / Terpilih (via CSS :checked maupun JS .is-selected) */
        .rating-option input[type="radio"]:checked + .rating-box,
        .rating-box.is-selected {
            background-color: {{ $primary }} !important;
            border-color: {{ $primary }} !important;
            color: #ffffff !important;
            box-shadow: 0 6px 18px -2px {{ $primary }}70 !important;
            transform: translateY(-2px) scale(1.03);
        }
        .rating-option input[type="radio"]:checked + .rating-box .rating-num,
        .rating-box.is-selected .rating-num {
            color: #ffffff !important;
        }
        .rating-option input[type="radio"]:checked + .rating-box .radio-circle,
        .rating-box.is-selected .radio-circle {
            border-color: #ffffff !important;
            background-color: #ffffff !important;
        }
        .rating-option input[type="radio"]:checked + .rating-box .radio-dot,
        .rating-box.is-selected .radio-dot {
            display: block !important;
            background-color: {{ $primary }} !important;
        }

        /* --- STYLING PILIHAN LIKERT (1-5) --- */
        .likert-box {
            background-color: #ffffff;
            border: 2px solid #e2e8f0;
            color: #334155;
            transition: all 0.15s ease-in-out;
        }
        .likert-box:hover {
            border-color: {{ $primary }}60;
            background-color: #f8fafc;
        }
        /* State Aktif / Terpilih (via CSS :checked maupun JS .is-selected) */
        .likert-option input[type="radio"]:checked + .likert-box,
        .likert-box.is-selected {
            background-color: {{ $primary }}12 !important;
            border-color: {{ $primary }} !important;
            color: {{ $primary }} !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 12px -2px {{ $primary }}30 !important;
            transform: translateY(-1px);
        }
        .likert-option input[type="radio"]:checked + .likert-box .likert-badge,
        .likert-box.is-selected .likert-badge {
            background-color: {{ $primary }} !important;
            color: #ffffff !important;
            border-color: {{ $primary }} !important;
            box-shadow: 0 2px 6px {{ $primary }}40 !important;
        }
    </style>

    <div class="min-h-screen bg-slate-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-full mx-auto">

            <!-- Top Institution Branding -->
            <div class="text-center mb-6">
                @if(!empty($company?->logo_portrait) && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_portrait))
                    <img src="{{ asset('storage/' . $company->logo_portrait) }}" alt="Logo" class="h-16 mx-auto mb-2.5 object-contain drop-shadow-xs">
                @elseif(!empty($company?->logo_landscape) && \Illuminate\Support\Facades\Storage::disk('public')->exists($company->logo_landscape))
                    <img src="{{ asset('storage/' . $company->logo_landscape) }}" alt="Logo" class="h-14 mx-auto mb-2.5 object-contain drop-shadow-xs">
                @endif
                <p class="text-xs sm:text-sm font-extrabold uppercase tracking-wider" style="color: {{ $primary }};">
                    PROGRAM STUDI PENDIDIKAN DOKTER FK UINAR
                </p>
                <p class="text-[11px] text-gray-500 font-medium">Sistem Evaluasi Modul & Penjaminan Mutu Pembelajaran</p>
            </div>

            @if($isSubmitted)
                <!-- Success Thank You Card -->
                <div class="bg-white rounded-3xl p-8 sm:p-12 shadow-md border border-gray-100 text-center animate-fade-in mb-10 sm:mb-14">
                    <div class="w-20 h-20 mx-auto mb-5 rounded-full flex items-center justify-center text-4xl shadow-sm"
                         style="background-color: {{ $primary }}15; color: {{ $primary }};">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mb-2">Terima Kasih!</h2>
                    <p class="text-sm sm:text-base text-gray-600 max-w-lg mx-auto leading-relaxed">
                        Evaluasi Anda terhadap <strong class="text-gray-900 font-bold">{{ $questionnaire->title }}</strong> telah berhasil tersimpan dalam sistem. Masukan Anda sangat berharga bagi peningkatan mutu pendidikan kedokteran kita.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                        @if(auth()->check())
                            <button type="button" wire:click="fillAgain" 
                                    class="w-full sm:w-auto px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold text-sm rounded-xl transition inline-flex items-center justify-center gap-2 border border-gray-200 cursor-pointer shadow-xs">
                                <i class="fa-solid fa-rotate text-gray-600"></i>
                                <span>Isi Respon Baru</span>
                            </button>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="w-full sm:w-auto px-6 py-3.5 custom-primary-btn font-semibold text-sm rounded-xl transition inline-flex items-center justify-center gap-2 shadow-xs">
                                <i class="fa-solid fa-house"></i>
                                <span>Kembali ke Dashboard</span>
                            </a>
                        @else
                            @if($questionnaire->allow_guest)
                                <button type="button" wire:click="fillAgain" 
                                        class="w-full sm:w-auto px-6 py-3.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold text-sm rounded-xl transition inline-flex items-center justify-center gap-2 border border-gray-200 cursor-pointer shadow-xs">
                                <i class="fa-solid fa-rotate text-gray-600"></i>
                                <span>Isi Respon Baru</span>
                            </button>
                            @endif
                            <a href="{{ route('login') }}" 
                               class="w-full sm:w-auto px-6 py-3.5 custom-primary-btn font-semibold text-sm rounded-xl transition inline-flex items-center justify-center gap-2 shadow-xs">
                                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                <span>Masuk ke Portal CBT</span>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <!-- Questionnaire Form Card -->
                <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden mb-10 sm:mb-14">
                    <!-- Header Banner -->
                    <div class="text-white p-6 sm:p-8 relative overflow-hidden" 
                         style="background: linear-gradient(135deg, {{ $primary }} 0%, #064e07 100%);">
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 flex-wrap mb-3">
                                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 text-white backdrop-blur-xs border border-white/25">
                                    Formulir Evaluasi Modul
                                </span>
                                @if($questionnaire->academic_year || $questionnaire->semester)
                                    <span class="px-3 py-1 rounded-full text-[11px] font-semibold bg-black/25 text-white/95 border border-white/10">
                                        TA: {{ $questionnaire->academic_year ?? '-' }} ({{ $questionnaire->semester ?? '-' }})
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-xl sm:text-2xl font-black tracking-tight text-white mb-2 leading-tight">
                                {{ $questionnaire->title }}
                            </h1>
                            @if($questionnaire->description)
                                <p class="text-xs sm:text-sm text-white/90 leading-relaxed max-w-2xl font-normal">
                                    {{ $questionnaire->description }}
                                </p>
                            @endif
                        </div>

                        <!-- Background icon ornament -->
                        <div class="absolute -right-8 -bottom-8 opacity-10 text-white pointer-events-none text-9xl">
                            <i class="fa-solid fa-file-waveform"></i>
                        </div>
                    </div>

                    @if(!$questionnaire->is_active)
                        <div class="p-8 text-center text-gray-500">
                            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-2xl">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <h3 class="text-base font-bold text-gray-800">Kuisioner Telah Ditutup</h3>
                            <p class="text-xs text-gray-500 mt-1">
                                Periode pengisian evaluasi ini saat ini sedang tidak dibuka. Silakan hubungi bagian akademik atau pengelola modul.
                            </p>
                        </div>
                    @else
                        <form wire:submit.prevent="submit" method="POST" action="javascript:void(0);" onsubmit="event.preventDefault(); return false;" id="questionnaire-form" class="p-6 sm:p-8 space-y-8">
                            @csrf
                            
                            <!-- Section: Identitas Responden -->
                            <div class="p-5 rounded-2xl bg-gray-50 border {{ ($errors->has('respondent_name') || $errors->has('respondent_nim')) ? 'border-red-400 ring-2 ring-red-400/40 bg-red-50/20 has-validation-error' : 'border-gray-200/80' }}">
                                <h3 class="text-xs font-bold uppercase tracking-wider mb-3 flex items-center gap-2" style="color: {{ $primary }};">
                                    <i class="fa-solid fa-id-card"></i>
                                    <span>Identitas Responden</span>
                                </h3>

                                @if($is_logged_in)
                                    <!-- Logged-in detected info -->
                                    <div class="flex items-center gap-3.5 p-3.5 bg-white border border-gray-200 rounded-xl shadow-xs">
                                        <div class="w-11 h-11 rounded-full text-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs"
                                             style="background-color: {{ $primary }};">
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold"
                                                      style="background-color: {{ $primary }}18; color: {{ $primary }};">
                                                    <i class="fa-solid fa-circle-check mr-1 text-[8px]"></i> Terdeteksi Login
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-600 font-mono mt-0.5">
                                                NIM / Identitas: <span class="font-bold text-gray-800">{{ auth()->user()->nim ?? auth()->user()->username ?? '-' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                @else
                                    <!-- Guest form input -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="respondent_name" class="block text-xs font-bold text-gray-700 mb-1">
                                                Nama Lengkap Mahasiswa <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" id="respondent_name" wire:model="respondent_name" 
                                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border {{ $errors->has('respondent_name') ? 'border-red-400 bg-red-50/20' : 'border-gray-300 bg-white' }} focus-primary transition"
                                                   placeholder="Masukkan nama lengkap Anda">
                                            @error('respondent_name') <span class="text-[11px] text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="respondent_nim" class="block text-xs font-bold text-gray-700 mb-1">
                                                NIM / Nomor Induk Mahasiswa @if($questionnaire->require_nim)<span class="text-red-500">*</span>@endif
                                            </label>
                                            <input type="text" id="respondent_nim" wire:model="respondent_nim" 
                                                   class="w-full px-3.5 py-2.5 text-xs rounded-xl border {{ $errors->has('respondent_nim') ? 'border-red-400 bg-red-50/20' : 'border-gray-300 bg-white' }} focus-primary transition"
                                                   placeholder="Contoh: 210101001">
                                            @error('respondent_nim') <span class="text-[11px] text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                                        <span class="text-[11px]">Sudah memiliki akun portal?</span>
                                        <a href="{{ route('login') }}" class="font-bold hover:underline" style="color: {{ $primary }};">
                                            Masuk untuk deteksi otomatis &rarr;
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Questionnaire Sections -->
                            @foreach($questionnaire->sections as $secIndex => $sec)
                                <div class="space-y-4 pt-2">
                                    <!-- Section Header -->
                                    <div class="border-b-2 pb-3 flex items-center gap-3" style="border-color: {{ $primary }}25;">
                                        <span class="w-7 h-7 rounded-xl text-white text-xs font-bold flex items-center justify-center shrink-0 shadow-xs"
                                              style="background-color: {{ $primary }};">
                                            {{ $sec->order }}
                                        </span>
                                        <div>
                                            <h2 class="text-base sm:text-lg font-extrabold text-gray-900">
                                                {{ $sec->title }}
                                            </h2>
                                            @if($sec->description)
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $sec->description }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Questions inside Section -->
                                    <div class="space-y-4">
                                        @foreach($sec->questions as $qIndex => $q)
                                            @php
                                                $safeKey = 'q_' . str_replace('-', '_', $q->id);
                                                $hasError = $errors->has("answers.{$safeKey}");
                                            @endphp
                                            <div id="q_card_{{ $safeKey }}" 
                                                 class="p-4 sm:p-5 rounded-2xl bg-white border transition-all duration-150 {{ $hasError ? 'border-red-400 ring-2 ring-red-400/40 bg-red-50/10 has-validation-error' : 'border-gray-200/90 shadow-xs hover:border-gray-300' }}">
                                                <!-- Question Text -->
                                                <div class="mb-3">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <label class="text-xs sm:text-sm font-bold text-gray-900 leading-snug">
                                                            <span class="text-gray-400 font-extrabold mr-1">{{ $qIndex + 1 }}.</span>
                                                            {{ $q->question_text }}
                                                            @if($q->is_required)
                                                                <span class="text-red-500 font-bold" title="Wajib diisi">*</span>
                                                            @endif
                                                        </label>
                                                        @if($hasError)
                                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 shrink-0 inline-flex items-center gap-1">
                                                                <i class="fa-solid fa-circle-exclamation text-[9px]"></i> Belum Diisi
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Answer Input depending on type -->
                                                @if($q->question_type === 'rating_5')
                                                    <!-- Rating Scale 1-5 (Kepuasan: Sangat Buruk / Tidak Puas s/d Sangat Baik / Puas) -->
                                                    <div>
                                                        <div class="grid grid-cols-5 gap-2 sm:gap-3 max-w-lg mx-auto my-3">
                                                            @for($val = 1; $val <= 5; $val++)
                                                                <label class="rating-option cursor-pointer block select-none">
                                                                    <input type="radio" 
                                                                           name="radio_{{ $safeKey }}" 
                                                                           value="{{ $val }}" 
                                                                           wire:model="answers.{{ $safeKey }}"
                                                                           onchange="handleRadioSelection(this)"
                                                                           @if(($answers[$safeKey] ?? null) == $val) checked @endif
                                                                           class="sr-only questionnaire-radio">
                                                                    <div class="rating-box option-card flex flex-col items-center justify-center py-3 px-2 rounded-xl text-xs font-bold @if(($answers[$safeKey] ?? null) == $val) is-selected @endif">
                                                                        <span class="rating-num text-base sm:text-lg font-black">{{ $val }}</span>
                                                                        <div class="radio-circle w-4 h-4 rounded-full border-2 border-gray-400 mt-1.5 flex items-center justify-center bg-white transition">
                                                                            <div class="radio-dot w-2 h-2 rounded-full hidden"></div>
                                                                        </div>
                                                                    </div>
                                                                </label>
                                                            @endfor
                                                        </div>
                                                        <div class="flex justify-between text-[11px] font-semibold text-gray-600 max-w-lg mx-auto px-1 mt-1">
                                                            <span class="flex items-center gap-1">
                                                                <i class="fa-solid fa-angles-left text-[9px] text-gray-400"></i>
                                                                {{ $q->min_label ?? '1 (Sangat Tidak Puas)' }}
                                                            </span>
                                                            <span class="flex items-center gap-1">
                                                                {{ $q->max_label ?? '5 (Sangat Puas)' }}
                                                                <i class="fa-solid fa-angles-right text-[9px] text-gray-400"></i>
                                                            </span>
                                                        </div>
                                                    </div>

                                                @elseif($q->question_type === 'likert_5')
                                                    <!-- Likert Scale 5 Point (Sangat Tidak Setuju s/d Sangat Setuju) -->
                                                    <div>
                                                        @php
                                                            $likertOptions = [
                                                                1 => 'Sangat Tidak Setuju / Tidak Pernah',
                                                                2 => 'Tidak Setuju / Jarang',
                                                                3 => 'Ragu-ragu / Kadang-kadang',
                                                                4 => 'Setuju / Sering',
                                                                5 => 'Sangat Setuju / Selalu',
                                                            ];
                                                        @endphp
                                                        <div class="grid grid-cols-1 sm:grid-cols-5 gap-2 my-3">
                                                            @foreach($likertOptions as $score => $label)
                                                                <label class="likert-option cursor-pointer block select-none">
                                                                    <input type="radio" 
                                                                           name="radio_{{ $safeKey }}" 
                                                                           value="{{ $score }}" 
                                                                           wire:model="answers.{{ $safeKey }}"
                                                                           onchange="handleRadioSelection(this)"
                                                                           @if(($answers[$safeKey] ?? null) == $score) checked @endif
                                                                           class="sr-only questionnaire-radio">
                                                                    <div class="likert-box option-card flex sm:flex-col items-center sm:justify-center text-left sm:text-center p-3 rounded-xl gap-2 @if(($answers[$safeKey] ?? null) == $score) is-selected @endif">
                                                                        <div class="likert-badge w-7 h-7 rounded-full border-2 border-gray-300 bg-white text-gray-700 flex items-center justify-center font-bold text-xs shrink-0 transition">
                                                                            {{ $score }}
                                                                        </div>
                                                                        <span class="text-[11px] leading-tight font-medium">{{ $label }}</span>
                                                                    </div>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                @elseif($q->question_type === 'input_name')
                                                    <!-- Input Name (Tutor/Fasil/Narasumber) -->
                                                    <div class="mt-2">
                                                        <input type="text" wire:model="answers.{{ $safeKey }}" 
                                                               class="w-full px-3.5 py-2.5 text-xs rounded-xl border {{ $hasError ? 'border-red-400 bg-red-50/20' : 'border-gray-300 bg-white' }} focus-primary transition"
                                                               placeholder="Ketikkan nama lengkap (opsional)...">
                                                    </div>

                                                @elseif($q->question_type === 'text')
                                                    <!-- Textarea (Saran / Kritik Bebas) -->
                                                    <div class="mt-2">
                                                        <textarea wire:model="answers.{{ $safeKey }}" rows="3" 
                                                                  class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-gray-300 bg-white focus-primary transition"
                                                                  placeholder="Tuliskan masukan atau saran konstruktif Anda di sini..."></textarea>
                                                    </div>
                                                @endif

                                                @error("answers.{$safeKey}")
                                                    <span class="text-[11px] text-red-500 mt-1.5 block font-bold">
                                                        <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $message }}
                                                    </span>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            <!-- Validation Summary Box -->
                            @if($errors->any())
                                <div id="validation-summary-box" class="p-4 rounded-2xl bg-red-50 border-2 border-red-300 text-red-800 text-xs shadow-sm space-y-2">
                                    <div class="flex items-center gap-2 font-bold text-sm text-red-900">
                                        <i class="fa-solid fa-triangle-exclamation text-base text-red-600"></i>
                                        <span>Terdapat {{ $errors->count() }} pertanyaan/isian wajib yang belum diisi:</span>
                                    </div>
                                    <ul class="list-disc list-inside space-y-1 text-red-700 pl-1 font-medium">
                                        @if($errors->has('respondent_name'))
                                            <li><a href="#respondent_name" class="underline hover:text-red-900">Nama lengkap mahasiswa wajib diisi</a></li>
                                        @endif
                                        @if($errors->has('respondent_nim'))
                                            <li><a href="#respondent_nim" class="underline hover:text-red-900">NIM / Nomor Induk Mahasiswa wajib diisi</a></li>
                                        @endif
                                        @foreach($questionnaire->sections as $sec)
                                            @foreach($sec->questions as $qu)
                                                @php $qk = 'q_' . str_replace('-', '_', $qu->id); @endphp
                                                @if($errors->has("answers.{$qk}"))
                                                    <li>
                                                        <a href="#q_card_{{ $qk }}" class="underline hover:text-red-900">
                                                            [{{ $sec->title }}] {{ \Illuminate\Support\Str::limit($qu->question_text, 65) }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </ul>
                                    <p class="text-[11px] text-red-600 font-semibold pt-1">
                                        💡 Silakan lengkapi butir bertanda merah di atas, lalu klik <strong>Kirim Jawaban</strong> kembali.
                                    </p>
                                </div>
                            @endif

                            @error('submit_error')
                                <div class="p-3.5 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl font-medium">
                                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}
                                </div>
                            @enderror

                            <!-- Submit Button Section -->
                            <div class="pt-5 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-3">
                                <span class="text-xs text-gray-500">
                                    <span class="text-red-500 font-bold">*</span> Wajib diisi sebelum mengirim.
                                </span>
                                <button type="submit" 
                                        wire:loading.attr="disabled"
                                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl custom-primary-btn font-semibold text-xs sm:text-sm transition-all duration-200 inline-flex items-center justify-center gap-2 cursor-pointer shadow-sm">
                                    <span wire:loading.remove>
                                        <i class="fa-solid fa-paper-plane text-xs mr-1"></i> Kirim Jawaban
                                    </span>
                                    <span wire:loading>
                                        <i class="fa-solid fa-spinner fa-spin text-xs mr-1"></i> Menyimpan...
                                    </span>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif

            <!-- Footer Note -->
            <div class="text-center text-xs text-gray-400 pt-4 pb-14 mt-4">
                &copy; {{ date('Y') }} Program Studi Pendidikan Dokter FK UINAR &bull; Powered by PROCBT Healthcare System
            </div>
        </div>
    </div>

    <!-- Script Pembantu untuk Interaktivitas Instan -->
    <script>
        function handleRadioSelection(radioEl) {
            if (!radioEl) return;
            var groupName = radioEl.name;
            document.querySelectorAll('input[name="' + groupName + '"]').forEach(function(item) {
                var card = item.nextElementSibling;
                if (card) {
                    if (item.checked) {
                        card.classList.add('is-selected');
                    } else {
                        card.classList.remove('is-selected');
                    }
                }
            });
        }

        // Sinkronisasi otomatis saat DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            initQuestionnaire();
        });
        document.addEventListener('livewire:navigated', function() {
            initQuestionnaire();
        });

        function initQuestionnaire() {
            syncSelectedCards();
            cleanAddressBar();
        }

        function cleanAddressBar() {
            // Bersihkan parameter answers[...] dari address bar browser agar URL tetap bersih
            if (window.location.search && (window.location.search.includes('answers') || window.location.search.includes('%5B') || window.location.search.includes('='))) {
                var cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }
        }

        function syncSelectedCards() {
            document.querySelectorAll('.questionnaire-radio:checked').forEach(function(radio) {
                var card = radio.nextElementSibling;
                if (card) card.classList.add('is-selected');
            });
        }

        // Event listener saat submit berhasil / isi ulang untuk bersihkan URL
        window.addEventListener('clean-url', function() {
            cleanAddressBar();
        });

        // Auto-scroll ke error pertama jika validasi gagal
        window.addEventListener('scroll-to-first-error', function() {
            setTimeout(function() {
                var firstErr = document.querySelector('.has-validation-error');
                if (firstErr) {
                    firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    var summary = document.getElementById('validation-summary-box');
                    if (summary) summary.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        });

        // Scroll ke atas saat sukses
        window.addEventListener('scroll-to-top', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>
</div>
