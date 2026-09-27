@section('title', 'Detail & Hasil Kuisioner: ' . $questionnaire->title)
<div>
    <!-- Breadcrumb / Back Link -->
    <div class="mb-3">
        <a href="{{ route('admin.master.kuisioner') }}" 
           class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-[color:var(--primary)] transition-colors gap-1.5">
            <i class="fa-solid fa-arrow-left text-[11px]"></i>
            <span>Kembali ke Daftar Kuisioner</span>
        </a>
    </div>

    <!-- Header Card -->
    <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-100 shadow-xs mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <!-- Left: Title, Badges, & Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2.5 flex-wrap mb-1.5">
                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
                        {{ $questionnaire->title }}
                    </h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $questionnaire->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $questionnaire->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $questionnaire->is_active ? 'Aktif / Dibuka' : 'Ditutup' }}
                    </span>
                </div>
                
                <div class="flex items-center gap-2 sm:gap-3 flex-wrap text-xs text-gray-500 mb-2">
                    @if($questionnaire->academic_year || $questionnaire->semester)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold text-[11px]">
                            <i class="fa-regular fa-calendar text-[10px]"></i>
                            TA: {{ $questionnaire->academic_year ?? '-' }} ({{ $questionnaire->semester ?? '-' }})
                        </span>
                    @endif
                    @if($questionnaire->allow_guest)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-medium text-[11px]">
                            <i class="fa-solid fa-user-check text-[10px]"></i> Tamu Diizinkan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 font-medium text-[11px]">
                            <i class="fa-solid fa-lock text-[10px]"></i> Wajib Login
                        </span>
                    @endif
                </div>

                @if($questionnaire->description)
                    <p class="text-xs text-gray-500 leading-relaxed max-w-3xl">
                        {{ $questionnaire->description }}
                    </p>
                @endif
            </div>

            <!-- Right: Actions Toolbar (Link + Export Excel) -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <!-- Public URL Pill Box -->
                <div class="inline-flex items-center bg-gray-50 border border-gray-200 rounded-xl p-1 shadow-2xs">
                    <span class="px-2.5 py-1 text-xs font-mono text-gray-600 max-w-[190px] sm:max-w-[240px] truncate select-all" title="{{ url('/kuisioner/' . $questionnaire->slug) }}">
                        /kuisioner/{{ $questionnaire->slug }}
                    </span>
                    <button type="button" onclick="copyDetailLink('{{ url('/kuisioner/' . $questionnaire->slug) }}')" 
                            class="px-2.5 py-1 bg-white hover:bg-gray-100 text-gray-700 hover:text-[color:var(--primary)] text-xs font-semibold rounded-lg border border-gray-200 transition inline-flex items-center gap-1 shadow-2xs cursor-pointer"
                            title="Salin Tautan Formulir">
                        <i class="fa-regular fa-copy text-xs"></i>
                        <span>Salin</span>
                    </button>
                    <a href="{{ url('/kuisioner/' . $questionnaire->slug) }}" target="_blank" 
                       class="p-1.5 text-gray-400 hover:text-[color:var(--primary)] hover:bg-white rounded-lg transition"
                       title="Buka Formulir di Tab Baru">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </a>
                </div>

                <!-- Export Excel Button -->
                <button type="button" wire:click="exportExcel" 
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                    <i class="fa-solid fa-file-excel text-sm"></i>
                    <span>Export Excel</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-[color:var(--primary)] flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Responden</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $totalResponses }}</p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-list-ol"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Bagian & Butir</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">
                    {{ $questionnaire->sections->count() }} <span class="text-xs font-normal text-gray-400">Bagian ({{ $questionnaire->sections->sum(fn($s) => $s->questions->count()) }} Soal)</span>
                </p>
            </div>
        </div>

        @php
            $numericAnalytics = collect($analytics)->pluck('average')->filter(fn($a) => $a > 0);
            $overallAvg = $numericAnalytics->count() > 0 ? round($numericAnalytics->avg(), 2) : 0;
            $satisfactionPct = round(($overallAvg / 5) * 100, 1);
        @endphp
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Rata-rata Skor (1-5)</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">
                    {{ $overallAvg }} <span class="text-xs font-semibold text-amber-500">/ 5.0</span>
                </p>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Indeks Kepuasan</p>
                <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ $satisfactionPct }}%</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button type="button" wire:click="setTab('results')" 
                    class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2 whitespace-nowrap {{ $activeTab === 'results' ? 'border-[color:var(--primary)] text-[color:var(--primary)] bg-blue-50/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Hasil & Analisis Statistik</span>
            </button>
            <button type="button" wire:click="setTab('manage_questions')" 
                    class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2 whitespace-nowrap {{ $activeTab === 'manage_questions' ? 'border-[color:var(--primary)] text-[color:var(--primary)] bg-blue-50/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-list-check"></i>
                <span>Kelola Point & Pertanyaan</span>
            </button>
            <button type="button" wire:click="setTab('responses')" 
                    class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2 whitespace-nowrap {{ $activeTab === 'responses' ? 'border-[color:var(--primary)] text-[color:var(--primary)] bg-blue-50/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-users"></i>
                <span>Data Responden ({{ $totalResponses }})</span>
            </button>
            <button type="button" wire:click="setTab('settings')" 
                    class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2 whitespace-nowrap {{ $activeTab === 'settings' ? 'border-[color:var(--primary)] text-[color:var(--primary)] bg-blue-50/40' : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <i class="fa-solid fa-gear"></i>
                <span>Pengaturan Kuisioner</span>
            </button>
        </div>

        <!-- Tab 1: Hasil & Analisis Statistik -->
        @if($activeTab === 'results')
            <div class="p-6">
                @if($totalResponses === 0)
                    <div class="text-center py-12 text-gray-400">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-blue-50 text-[color:var(--primary)] flex items-center justify-center text-2xl">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                        <h3 class="text-base font-bold text-gray-800">Belum Ada Respon Masuk</h3>
                        <p class="text-xs text-gray-500 mt-1 max-w-md mx-auto">
                            Bagikan link kuisioner kepada mahasiswa untuk mulai mengumpulkan data evaluasi modul. Statistik dan rekap nilai akan otomatis muncul di sini.
                        </p>
                        <div class="mt-4">
                            <button type="button" onclick="copyDetailLink('{{ url('/kuisioner/' . $questionnaire->slug) }}')" 
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-[color:var(--primary)] text-white text-xs font-medium rounded-xl shadow-sm">
                                <i class="fa-regular fa-copy"></i>
                                <span>Salin Tautan Kuisioner</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="space-y-8">
                        @foreach($questionnaire->sections as $sec)
                            <div class="bg-gray-50/60 rounded-xl p-5 border border-gray-100">
                                <div class="mb-4 pb-3 border-b border-gray-200 flex items-center justify-between">
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-lg bg-[color:var(--primary)] text-white text-xs flex items-center justify-center font-bold">
                                                {{ $sec->order }}
                                            </span>
                                            {{ $sec->title }}
                                        </h3>
                                        @if($sec->description)
                                            <p class="text-xs text-gray-500 mt-0.5 ml-8">{{ $sec->description }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    @foreach($sec->questions as $qIndex => $q)
                                        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-xs">
                                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-2">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="text-xs font-bold text-gray-400">#{{ $qIndex + 1 }}</span>
                                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-gray-100 text-gray-600">
                                                            @if($q->question_type === 'rating_5')
                                                                Skala Kepuasan (1-5)
                                                            @elseif($q->question_type === 'likert_5')
                                                                Skala Likert (1-5)
                                                            @elseif($q->question_type === 'input_name')
                                                                Nama Personel
                                                            @else
                                                                Esai / Masukan Bebas
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <h4 class="text-sm font-semibold text-gray-900">{{ $q->question_text }}</h4>
                                                </div>

                                                <!-- Score Average Badge for numeric questions -->
                                                @if(in_array($q->question_type, ['rating_5', 'likert_5']))
                                                    @php
                                                        $stat = $analytics[$q->id] ?? null;
                                                        $avg = $stat['average'] ?? 0;
                                                        $count = $stat['count'] ?? 0;
                                                    @endphp
                                                    <div class="flex items-center gap-3 bg-blue-50/70 border border-blue-100 px-3 py-1.5 rounded-xl shrink-0">
                                                        <div class="text-right">
                                                            <div class="text-xs text-gray-500">Rata-rata</div>
                                                            <div class="text-base font-bold text-[color:var(--primary)]">{{ $avg }} <span class="text-xs font-normal text-gray-400">/ 5.0</span></div>
                                                        </div>
                                                        <div class="text-xl text-amber-500">
                                                            <i class="fa-solid fa-star"></i>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Distribution Bar for Rating & Likert questions -->
                                            @if(in_array($q->question_type, ['rating_5', 'likert_5']))
                                                @php
                                                    $stat = $analytics[$q->id] ?? null;
                                                    $count = $stat['count'] ?? 0;
                                                    $dist = $stat['distribution'] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                                                @endphp
                                                <div class="mt-3 pt-3 border-t border-gray-50">
                                                    <div class="grid grid-cols-5 gap-2 text-center text-xs">
                                                        @for($s = 1; $s <= 5; $s++)
                                                            @php
                                                                $valCount = $dist[$s] ?? 0;
                                                                $pct = $count > 0 ? round(($valCount / $count) * 100, 1) : 0;
                                                            @endphp
                                                            <div class="bg-gray-50 p-2 rounded-lg border border-gray-100">
                                                                <div class="font-bold text-gray-700 text-xs mb-1">Skor {{ $s }}</div>
                                                                <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden mb-1">
                                                                    <div class="bg-[color:var(--primary)] h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                                                                </div>
                                                                <div class="text-[11px] text-gray-500">{{ $valCount }} <span class="text-[10px] text-gray-400">({{ $pct }}%)</span></div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                </div>
                                            @elseif(in_array($q->question_type, ['text', 'input_name']))
                                                <!-- Free text preview -->
                                                @php
                                                    $textAnswers = $q->answers->pluck('text_answer')->filter()->values();
                                                @endphp
                                                <div class="mt-2 pt-2 border-t border-gray-50">
                                                    <p class="text-xs text-gray-500 mb-2 font-medium">
                                                        <i class="fa-regular fa-comment-dots mr-1"></i>
                                                        {{ $textAnswers->count() }} Isian Respon Masuk:
                                                    </p>
                                                    <div class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                                                        @forelse($textAnswers->take(10) as $ansText)
                                                            <div class="p-2 bg-gray-50 rounded-lg text-xs text-gray-700 border border-gray-100">
                                                                "{{ $ansText }}"
                                                            </div>
                                                        @empty
                                                            <p class="text-xs text-gray-400 italic">Belum ada isian untuk butir ini.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Tab 2: Kelola Point & Pertanyaan -->
        @if($activeTab === 'manage_questions')
            <div class="p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Struktur Bagian & Butir Pertanyaan</h3>
                        <p class="text-xs text-gray-500">Sesuaikan pertanyaan, urutan, atau tambahkan narasumber/bagian evaluasi baru.</p>
                    </div>
                    <button type="button" wire:click="openAddSectionModal" 
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[color:var(--primary)] hover:opacity-95 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Bagian / Section</span>
                    </button>
                </div>

                <div class="space-y-6">
                    @forelse($questionnaire->sections as $sec)
                        <div class="bg-gray-50 rounded-2xl border border-gray-200 overflow-hidden shadow-xs">
                            <!-- Section Header Bar -->
                            <div class="px-5 py-4 bg-white border-b border-gray-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-xl bg-[color:var(--primary)] text-white text-xs font-bold flex items-center justify-center shrink-0">
                                        {{ $sec->order }}
                                    </span>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $sec->title }}</h4>
                                        @if($sec->description)
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $sec->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 self-end sm:self-auto">
                                    <button type="button" wire:click="openAddQuestionModal('{{ $sec->id }}')" 
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-[color:var(--primary)] hover:bg-blue-100 text-xs font-semibold rounded-lg transition">
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Tambah Soal</span>
                                    </button>
                                    <button type="button" wire:click="openEditSectionModal('{{ $sec->id }}')" 
                                            class="p-1.5 text-gray-400 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition" title="Edit Bagian">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </button>
                                    <button type="button" onclick="confirmDeleteSection('{{ $sec->id }}', '{{ addslashes($sec->title) }}')" 
                                            class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Bagian">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Questions Table inside Section -->
                            <div class="p-4">
                                <div class="space-y-2">
                                    @forelse($sec->questions as $qIdx => $q)
                                        <div class="bg-white p-3.5 rounded-xl border border-gray-100 shadow-xs flex items-center justify-between gap-3 hover:border-gray-300 transition">
                                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                                <!-- Reorder Controls -->
                                                <div class="flex flex-col gap-0.5 shrink-0">
                                                    <button type="button" wire:click="moveQuestion('{{ $q->id }}', 'up')" 
                                                            class="p-1 text-gray-400 hover:text-[color:var(--primary)] transition text-[10px]" title="Pindah Naik">
                                                        <i class="fa-solid fa-chevron-up"></i>
                                                    </button>
                                                    <button type="button" wire:click="moveQuestion('{{ $q->id }}', 'down')" 
                                                            class="p-1 text-gray-400 hover:text-[color:var(--primary)] transition text-[10px]" title="Pindah Turun">
                                                        <i class="fa-solid fa-chevron-down"></i>
                                                    </button>
                                                </div>

                                                <!-- Question Content -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                                        <span class="text-xs font-bold text-gray-500">Point #{{ $qIdx + 1 }}</span>
                                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-blue-50 text-[color:var(--primary)]">
                                                            @if($q->question_type === 'rating_5')
                                                                Skala Kepuasan (1-5)
                                                            @elseif($q->question_type === 'likert_5')
                                                                Skala Likert (1-5)
                                                            @elseif($q->question_type === 'input_name')
                                                                Nama Personel
                                                            @else
                                                                Esai / Saran Bebas
                                                            @endif
                                                        </span>
                                                        @if($q->is_required)
                                                            <span class="text-[10px] text-red-500 font-semibold">* Wajib Diisi</span>
                                                        @else
                                                            <span class="text-[10px] text-gray-400">Opsional</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-sm font-medium text-gray-800 break-words">{{ $q->question_text }}</p>
                                                    @if($q->min_label || $q->max_label)
                                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                                            Label: <span class="italic">"{{ $q->min_label }}"</span> s/d <span class="italic">"{{ $q->max_label }}"</span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Question Action Buttons -->
                                            <div class="flex items-center gap-1 shrink-0">
                                                <button type="button" wire:click="openEditQuestionModal('{{ $q->id }}')" 
                                                        class="p-2 text-gray-400 hover:text-[color:var(--primary)] hover:bg-blue-50 rounded-lg transition" title="Edit Pertanyaan">
                                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                </button>
                                                <button type="button" onclick="confirmDeleteQuestion('{{ $q->id }}')" 
                                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Pertanyaan">
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
                                            <p class="text-xs">Belum ada pertanyaan pada bagian ini.</p>
                                            <button type="button" wire:click="openAddQuestionModal('{{ $sec->id }}')" 
                                                    class="mt-2 text-xs text-[color:var(--primary)] font-semibold hover:underline">
                                                + Tambah Pertanyaan Pertama
                                            </button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            <p class="text-sm">Belum ada bagian/seksi pada kuisioner ini.</p>
                            <button type="button" wire:click="openAddSectionModal" class="btn btn-primary text-xs mt-3">
                                Tambah Bagian Pertama
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Tab 3: Data Responden -->
        @if($activeTab === 'responses')
            <div class="p-6">
                <!-- Search & Filter bar -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
                    <div class="flex items-center gap-3 flex-1 max-w-md">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="responseSearch" 
                                   class="block w-full pl-10 pr-4 py-2 text-xs border border-gray-200 rounded-xl bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--primary)] shadow-2xs transition"
                                   placeholder="Cari nama atau NIM responden...">
                        </div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl shadow-2xs">
                            <i class="fa-solid fa-users text-gray-500 text-[10px]"></i>
                            <span>{{ $responses->total() }} Responden</span>
                        </span>
                        <button type="button" wire:click="exportExcel" 
                                class="inline-flex items-center gap-2 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-xs cursor-pointer">
                            <i class="fa-solid fa-file-excel text-xs"></i>
                            <span>Download Excel Lengkap</span>
                        </button>
                    </div>
                </div>

                <!-- Responses Table -->
                <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-2xs bg-white mb-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead class="bg-gray-50/80 text-gray-600 font-bold border-b border-gray-200/80 uppercase tracking-wider text-[11px]">
                                <tr>
                                    <th class="px-5 py-3.5 w-12 text-center whitespace-nowrap">No</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Waktu Submit</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">Nama Mahasiswa / Responden</th>
                                    <th class="px-5 py-3.5 whitespace-nowrap">NIM / Identitas</th>
                                    <th class="px-5 py-3.5 text-center whitespace-nowrap">Status Akun</th>
                                    <th class="px-5 py-3.5 text-center whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($responses as $rIdx => $resp)
                                    <tr class="hover:bg-blue-50/20 transition-colors duration-150">
                                        <td class="px-5 py-3.5 text-center text-gray-500 font-semibold whitespace-nowrap">
                                            {{ $responses->firstItem() + $rIdx }}
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-600 whitespace-nowrap">
                                            <span class="inline-flex items-center gap-1.5 text-gray-700 font-medium whitespace-nowrap">
                                                <i class="fa-regular fa-clock text-gray-400 text-[11px]"></i>
                                                {{ $resp->submitted_at ? $resp->submitted_at->format('d M Y, H:i') : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <div class="flex items-center gap-2.5 whitespace-nowrap">
                                                <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs shrink-0 shadow-2xs">
                                                    {{ strtoupper(substr($resp->respondent_name, 0, 1)) }}
                                                </div>
                                                <div class="font-bold text-gray-900 text-xs whitespace-nowrap">
                                                    {{ $resp->respondent_name }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3.5 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-50 border border-gray-200 font-mono text-gray-700 text-xs font-semibold whitespace-nowrap">
                                                {{ $resp->respondent_nim ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                            @if($resp->user_id)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] border border-emerald-200/80 whitespace-nowrap">
                                                    <i class="fa-solid fa-circle-check text-[9px]"></i> Login Akun
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 font-medium text-[10px] whitespace-nowrap">
                                                    <i class="fa-solid fa-user text-[9px]"></i> Tamu / Form
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                                <button type="button" wire:click="viewResponseDetail('{{ $resp->id }}')" 
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold rounded-xl text-xs transition shadow-2xs cursor-pointer whitespace-nowrap" 
                                                        title="Lihat Rincian Jawaban">
                                                    <i class="fa-regular fa-eye text-xs"></i>
                                                    <span>Lihat Jawaban</span>
                                                </button>
                                                <button type="button" onclick="confirmDeleteResponse('{{ $resp->id }}')" 
                                                        class="p-1.5 px-2 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-xl transition border border-transparent hover:border-red-200 cursor-pointer" 
                                                        title="Hapus Respon">
                                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                            <div class="max-w-xs mx-auto">
                                                <div class="w-12 h-12 mx-auto mb-2.5 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                                                    <i class="fa-solid fa-users-slash text-xl"></i>
                                                </div>
                                                <p class="font-bold text-gray-700 text-xs">Belum ada data responden yang cocok</p>
                                                <p class="text-[11px] text-gray-400 mt-0.5">Respon yang dikirimkan oleh mahasiswa akan otomatis terdata di sini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($responses->hasPages())
                    <div class="mt-4">
                        {{ $responses->links() }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Tab 4: Pengaturan Kuisioner -->
        @if($activeTab === 'settings')
            <div class="p-6 max-w-3xl">
                <form wire:submit.prevent="saveSettings" class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Judul Kuisioner <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="setting_title" 
                               class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)]">
                        @error('setting_title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                            Slug Tautan Unik <span class="text-red-500">*</span>
                        </label>
                        <div class="flex rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                            <span class="inline-flex items-center px-3 bg-gray-50 text-gray-500 text-xs font-mono border-r border-gray-200 select-none">
                                {{ url('/kuisioner') }}/
                            </span>
                            <input type="text" wire:model="setting_slug" 
                                   class="flex-1 px-3 py-2 text-xs font-mono border-none focus:ring-0 text-gray-800">
                        </div>
                        @error('setting_slug') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tahun Akademik</label>
                            <input type="text" wire:model="setting_academic_year" 
                                   class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Semester</label>
                            <select wire:model="setting_semester" 
                                    class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)] bg-white">
                                <option value="Ganjil">Ganjil</option>
                                <option value="Genap">Genap</option>
                                <option value="Pendek/Antara">Pendek / Antara</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Deskripsi / Petunjuk</label>
                        <textarea wire:model="setting_description" rows="3" 
                                  class="w-full px-3.5 py-2 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)]"></textarea>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 space-y-3">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="set_active" wire:model="setting_is_active" class="h-4 w-4 rounded text-[color:var(--primary)]">
                            <label for="set_active" class="text-xs font-semibold text-gray-800 cursor-pointer">
                                Kuisioner Aktif / Dibuka untuk Pengisian
                            </label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="set_guest" wire:model="setting_allow_guest" class="h-4 w-4 rounded text-[color:var(--primary)]">
                            <label for="set_guest" class="text-xs font-semibold text-gray-800 cursor-pointer">
                                Izinkan Pengisian Tanpa Login (Tamu / Guest)
                            </label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="set_nim" wire:model="setting_require_nim" class="h-4 w-4 rounded text-[color:var(--primary)]">
                            <label for="set_nim" class="text-xs font-semibold text-gray-800 cursor-pointer">
                                Wajibkan Mengisi NIM / Nomor Induk Mahasiswa
                            </label>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="px-5 py-2.5 bg-[color:var(--primary)] hover:opacity-95 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                            <i class="fa-solid fa-check mr-1"></i> Simpan Perubahan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <!-- MODAL 1: Tambah / Edit Section -->
    @if($showSectionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h3 class="text-base font-bold text-gray-900">
                        {{ $editingSectionId ? 'Edit Bagian Kuisioner' : 'Tambah Bagian Baru' }}
                    </h3>
                    <button type="button" wire:click="closeSectionModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveSection" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Bagian / Section <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="section_title" 
                               class="w-full px-3.5 py-2 text-xs rounded-xl border border-gray-200 focus:ring-1 focus:ring-[color:var(--primary)]"
                               placeholder="Contoh: Evaluasi Tutor KKD" required>
                        @error('section_title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Tambahan (Opsional)</label>
                        <textarea wire:model="section_description" rows="2" 
                                  class="w-full px-3.5 py-2 text-xs rounded-xl border border-gray-200 focus:ring-1 focus:ring-[color:var(--primary)]"
                                  placeholder="Keterangan singkat mengenai apa yang dinilai di bagian ini..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" wire:click="closeSectionModal" 
                                class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 rounded-xl border border-gray-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-xs font-semibold text-white bg-[color:var(--primary)] rounded-xl shadow-sm hover:opacity-95">
                            Simpan Bagian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 2: Tambah / Edit Pertanyaan -->
    @if($showQuestionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-xl border border-gray-100">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h3 class="text-base font-bold text-gray-900">
                        {{ $editingQuestionId ? 'Edit Pertanyaan / Point' : 'Tambah Pertanyaan Baru' }}
                    </h3>
                    <button type="button" wire:click="closeQuestionModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveQuestion" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tipe Pertanyaan <span class="text-red-500">*</span></label>
                        <select wire:model.live="question_type" 
                                class="w-full px-3.5 py-2 text-xs rounded-xl border border-gray-200 focus:ring-1 focus:ring-[color:var(--primary)] bg-white">
                            <option value="likert_5">Skala Likert 5 Poin (Sangat Tidak Setuju s/d Sangat Setuju)</option>
                            <option value="rating_5">Skala Kepuasan 1-5 (Sangat Buruk s/d Sangat Baik / Puas)</option>
                            <option value="input_name">Input Nama Personel (Nama Tutor / Fasil / Dosen)</option>
                            <option value="text">Esai / Masukan Bebas (Textarea)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Teks Pertanyaan / Pernyataan <span class="text-red-500">*</span></label>
                        <textarea wire:model="question_text" rows="3" 
                                  class="w-full px-3.5 py-2 text-xs rounded-xl border border-gray-200 focus:ring-1 focus:ring-[color:var(--primary)]"
                                  placeholder="Contoh: PJ modul mendukung terselenggaranya modul dengan baik" required></textarea>
                        @error('question_text') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if(in_array($question_type, ['rating_5', 'likert_5']))
                        <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div>
                                <label class="block text-[11px] font-medium text-gray-600 mb-1">Label Nilai Minimum (1)</label>
                                <input type="text" wire:model="min_label" 
                                       class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-gray-200"
                                       placeholder="Sangat tidak setuju">
                            </div>
                            <div>
                                <label class="block text-[11px] font-medium text-gray-600 mb-1">Label Nilai Maksimum (5)</label>
                                <input type="text" wire:model="max_label" 
                                       class="w-full px-2.5 py-1.5 text-xs rounded-lg border border-gray-200"
                                       placeholder="Sangat setuju">
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" id="is_req" wire:model="is_required" class="h-4 w-4 rounded text-[color:var(--primary)]">
                        <label for="is_req" class="text-xs text-gray-700 font-medium cursor-pointer">
                            Wajib Dijawab oleh Responden
                        </label>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" wire:click="closeQuestionModal" 
                                class="px-4 py-2 text-xs font-medium text-gray-600 hover:bg-gray-50 rounded-xl border border-gray-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 text-xs font-semibold text-white bg-[color:var(--primary)] rounded-xl shadow-sm hover:opacity-95">
                            Simpan Pertanyaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 3: Rincian Respon Responden Tunggal -->
    @if($selectedResponse)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-gray-100 max-h-[90vh] flex flex-col">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Rincian Jawaban Responden</h3>
                        <p class="text-xs text-gray-500">
                            {{ $selectedResponse->respondent_name }} 
                            ({{ $selectedResponse->respondent_nim ?? 'Tanpa NIM' }}) - 
                            {{ $selectedResponse->submitted_at ? $selectedResponse->submitted_at->format('d/m/Y H:i') : '' }}
                        </p>
                    </div>
                    <button type="button" wire:click="closeResponseDetail" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-4 space-y-4">
                    @foreach($selectedResponse->answers as $ans)
                        <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="text-xs font-semibold text-gray-500 mb-1">
                                {{ $ans->question?->section?->title ?? 'Umum' }}
                            </div>
                            <p class="text-xs font-bold text-gray-800 mb-2">{{ $ans->question?->question_text }}</p>
                            <div class="text-xs font-medium">
                                @if($ans->score !== null)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-blue-100 text-blue-800 font-bold">
                                        Skor: {{ $ans->score }} / 5
                                    </span>
                                @else
                                    <div class="p-2 bg-white rounded border border-gray-200 text-gray-700 italic">
                                        "{{ $ans->text_answer ?? '-' }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-gray-100 flex justify-end">
                    <button type="button" wire:click="closeResponseDetail" 
                            class="px-4 py-2 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function copyDetailLink(text) {
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Tautan Tersalin!',
                text: 'Link kuisioner berhasil disalin ke clipboard.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        }).catch(function() {
            prompt("Salin tautan ini secara manual:", text);
        });
    }

    function confirmDeleteSection(id, title) {
        Swal.fire({
            title: 'Hapus Bagian Ini?',
            text: `Apakah Anda yakin ingin menghapus "${title}"? Semua butir pertanyaan di dalamnya akan ikut terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteSection(id);
            }
        });
    }

    function confirmDeleteQuestion(id) {
        Swal.fire({
            title: 'Hapus Pertanyaan?',
            text: 'Apakah Anda yakin ingin menghapus butir pertanyaan ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteQuestion(id);
            }
        });
    }

    function confirmDeleteResponse(id) {
        Swal.fire({
            title: 'Hapus Respon?',
            text: 'Apakah Anda yakin ingin menghapus jawaban responden ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteResponse(id);
            }
        });
    }
</script>
@endpush
