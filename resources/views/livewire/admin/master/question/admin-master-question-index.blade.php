@section('title', 'Bank Soal')
<div>
    {{-- Stop trying to control. --}}
    <!-- Global Loading Overlay for Deletion -->
    <div wire:loading wire:target="delete, bulkDelete">
        @include('layout.loading')
    </div>

    @include('livewire.admin.master.question.admin-master-question-index-modal-import')
    @include('livewire.admin.master.question.admin-master-question-modal')

    <!-- Modal for Import Warnings -->
    <div wire:ignore.self id="modal-import-warnings"
        class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
        <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
            style="max-width: 600px">
            <!-- Header -->
            <div class="flex justify-between items-center p-6 border-b">
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h2 class="text-xl font-semibold text-gray-800">Detail Catatan Import Soal</h2>
                </div>
                <button wire:click="closeModalImportWarnings()"
                    class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 60vh">
                <div class="space-y-3">
                    @foreach ($importWarnings as $warn)
                        <div class="flex items-start gap-3 p-3 bg-amber-50/50 rounded-xl border border-amber-100">
                            <span class="font-bold text-amber-600 whitespace-nowrap bg-amber-100 px-2 py-0.5 rounded-full text-xs">Baris {{ $warn['row'] }}</span>
                            <span class="flex-grow text-gray-700 leading-relaxed text-xs">{{ $warn['reason'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 px-6 py-4 border-t">
                <button wire:click="closeModalImportWarnings()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Data Bank Soal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="$refresh" wire:loading.attr="disabled" class="btn btn-secondary flex items-center gap-2" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4" wire:loading.class="animate-spin" wire:target="$refresh">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Refresh
                </button>
                @if (config('app.import_question'))
                    <button wire:click="openModalImport()" class="btn btn-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-import h-4 w-4">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                        </svg>
                        Import
                    </button>
                @endif
                @if (config('app.export_question'))
                    <button wire:click="exportExcel" class="btn btn-success flex items-center gap-2">
                        <i class="fa-solid fa-file-excel text-sm"></i>
                        Excel
                    </button>
                    <button wire:click="exportPdf" class="btn btn-danger flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf text-sm"></i>
                        PDF
                    </button>
                @endif
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }} flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
    </div>

    @if (!empty($importWarnings))
        <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-2xl shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in transition-all">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-amber-100 text-amber-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
                <div>
                    <h3 class="text-sm font-bold text-amber-800">Import Selesai dengan Catatan</h3>
                    <p class="text-xs text-amber-600 mt-0.5">Beberapa baris soal dilewati karena data tidak lengkap.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 self-end sm:self-center">
                <button wire:click="openModalImportWarnings()" class="px-3.5 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all hover:scale-[1.02] active:scale-95 cursor-pointer">
                    Lihat Detail
                </button>
                <button wire:click="$set('importWarnings', [])" class="text-amber-400 hover:text-amber-600 transition-colors p-1 rounded-lg hover:bg-amber-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="space-y-6 mb-6">
        <!-- SECTION 1: Informasi Umum Produk -->
        <div class="p-6 bg-white shadow rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prodi</label>
                    <select wire:model.live="filterStudyId" class="mt-1 form-control">
                        <option value="">Semua Prodi</option>
                        @foreach ($studys as $key_study => $study)
                            <option value="{{ $key_study }}">{{ $study }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe Ujian</label>
                    <select wire:model.live="filterQuestionTypeId" class="mt-1 form-control">
                        <option value="">Semua Tipe Ujian</option>
                        @foreach ($question_types as $key_question_type => $question_type)
                            <option value="{{ $question_type->id }}">{{ $question_type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Topik</label>
                    <select wire:model.live="filterTopicId" class="mt-1 form-control">
                        <option value="">Semua Topik</option>
                        @foreach ($topics as $key_topic => $topic)
                            <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Difficulty</label>
                    <select wire:model.live="filterDifficulty" class="mt-1 form-control">
                        <option value="">Semua Difficulty</option>
                        <option value="default">Unknown</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori Soal</label>
                    <select wire:model.live="filterCategoryQuestionId" class="mt-1 form-control">
                        <option value="">Semua Kategori Soal</option>
                        @foreach ($category_questions as $key_category_question => $category_question)
                            <option value="{{ $category_question->id }}">{{ $category_question->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    {{-- Remove global alert --}}

    <!-- Table Controls -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="w-full md:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Cari Sesuatu..." wire:model.live='search'>
            </div>
        </div>
    </div>
    <div x-data="{ openAll: false, openRows: {} }">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <label class="inline-flex items-center text-sm text-gray-700 whitespace-nowrap cursor-pointer">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded" wire:model.live="selectAll">
                    <span class="ml-2 whitespace-nowrap font-medium">Pilih semua di halaman ini</span>
                </label>

                @if ($questions->total() > count($selectedQuestions))
                    <button type="button" wire:click="selectAllFiltered" class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 transition-colors cursor-pointer">
                        Pilih Semua {{ number_format($questions->total(), 0, ',', '.') }} Soal Terfilter
                    </button>
                @endif

                @if (count($selectedQuestions) > 0)
                    <button type="button" wire:click="deselectAll" class="text-xs text-gray-500 hover:text-gray-700 underline cursor-pointer">
                        Batalkan Pilihan
                    </button>

                    <button wire:click="bulkDelete" wire:confirm="Apakah Anda yakin ingin menghapus {{ count($selectedQuestions) }} soal terpilih?" class="btn btn-danger flex items-center gap-2 text-sm py-1 px-3 shrink-0" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Terpilih ({{ count($selectedQuestions) }})
                    </button>
                @endif

                <!-- Expand / Collapse All Accordion Button -->
                <button type="button"
                    @click="openAll = !openAll; if(!openAll) openRows = {}"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-semibold rounded-lg shadow-2xs transition-all cursor-pointer">
                    <i class="fas" :class="openAll ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    <span x-text="openAll ? 'Tutup Semua Accordion' : 'Buka Semua Accordion'"></span>
                </button>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select class="form-control" wire:model.live="bulkCategoryQuestionId">
                    <option value="">Pilih Kategori Soal</option>
                    @foreach ($category_questions as $category_question)
                        <option value="{{ $category_question->id }}">{{ $category_question->name }}</option>
                    @endforeach
                </select>
                <button wire:click="applyBulkCategory" class="btn btn-primary whitespace-nowrap" type="button">
                    Terapkan
                </button>
            </div>
        </div>

        @if (count($selectedQuestions) > 0)
            <div class="mb-4 px-4 py-2.5 bg-blue-50/80 border border-blue-200 rounded-xl flex items-center justify-between gap-3 text-xs text-blue-900 font-medium animate-fade-in">
                <div class="flex items-center gap-2">
                    <i class="fas fa-check-circle text-blue-600 text-sm"></i>
                    <span><strong>{{ count($selectedQuestions) }} soal</strong> telah dipilih.</span>
                </div>
                @if (count($selectedQuestions) < $questions->total())
                    <button type="button" wire:click="selectAllFiltered" class="font-bold text-blue-700 hover:text-blue-900 underline cursor-pointer">
                        Pilih semua {{ number_format($questions->total(), 0, ',', '.') }} soal dalam hasil pencarian ini
                    </button>
                @endif
            </div>
        @endif

        <!-- Table Section with Accordion -->
        <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/80 border-b border-gray-200 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">
                                <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded cursor-pointer" wire:model.live="selectAll">
                            </th>
                            <th class="px-3 py-3.5 text-center w-14">No</th>
                            <th class="px-5 py-3.5 text-left">Pertanyaan & Pilihan Jawaban</th>
                            <th class="px-4 py-3.5 text-left w-36">Prodi & Topik</th>
                            <th class="px-4 py-3.5 text-center w-28">Difficulty</th>
                            <th class="px-4 py-3.5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($questions as $index => $result)
                            @php
                                $globalIndex = $questions->firstItem() + $index;
                                $isRestricted = config('app.limit_question_view') && $globalIndex > config('app.limit_question_count', 5);
                                $qImages = is_array($result?->images) ? $result->images : json_decode($result?->images ?? '[]', true);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <td class="px-4 py-4 text-center align-top whitespace-nowrap">
                                    <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 rounded cursor-pointer"
                                        wire:model.live="selectedQuestions"
                                        value="{{ $result->id }}" {{ $isRestricted ? 'disabled' : '' }}>
                                </td>
                                <td class="px-3 py-4 text-center align-top whitespace-nowrap">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-slate-100 text-slate-700 font-bold text-xs border border-slate-200">
                                            #{{ $globalIndex }}
                                        </span>
                                        @if (!$isRestricted)
                                            <button type="button"
                                                @click="openRows['{{ $result->id }}'] = !(openRows['{{ $result->id }}'] ?? openAll)"
                                                class="w-6 h-6 rounded-full bg-slate-50 hover:bg-blue-100 text-slate-500 hover:text-blue-600 transition flex items-center justify-center cursor-pointer text-[10px]"
                                                title="Buka / Tutup Detail">
                                                <i class="fas transition-transform duration-200"
                                                    :class="(openRows['{{ $result->id }}'] ?? openAll) ? 'fa-chevron-up text-blue-600' : 'fa-chevron-down'"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4 align-top">
                                    <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }} flex flex-col gap-2.5">
                                        <!-- Badges Row -->
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @if($result?->study?->name)
                                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-200/60">
                                                    Prodi: {{ $result->study->name }}
                                                </span>
                                            @endif
                                            @if($result?->topic?->name)
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold border border-slate-200">
                                                    Topik: {{ $result->topic->name }}
                                                </span>
                                            @endif
                                            @if($result?->categoryQuestion?->name)
                                                <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 text-[10px] font-semibold border border-purple-200/60">
                                                    Kat: {{ $result->categoryQuestion->name }}
                                                </span>
                                            @endif
                                            
                                            @if($result?->difficulty == 'easy')
                                                <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/60 uppercase">Easy</span>
                                            @elseif($result?->difficulty == 'medium')
                                                <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200/60 uppercase">Medium</span>
                                            @elseif($result?->difficulty == 'hard')
                                                <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200/60 uppercase">Hard</span>
                                            @endif

                                            @if(($result->type ?? 'single') == 'single')
                                                <span class="px-2 py-0.5 rounded bg-sky-50 text-sky-700 text-[10px] font-bold border border-sky-200/60">Pilihan Ganda</span>
                                            @elseif($result->type == 'multiple')
                                                <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200/60">Pilihan Ganda Kompleks</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded bg-violet-50 text-violet-700 text-[10px] font-bold border border-violet-200/60">Essay</span>
                                            @endif
                                        </div>

                                        <!-- Question Context -->
                                        <div class="rich-content text-[13.5px] text-gray-900 font-medium leading-relaxed cursor-pointer"
                                            @click="openRows['{{ $result->id }}'] = !(openRows['{{ $result->id }}'] ?? openAll)">
                                            {!! $result?->question !!}
                                        </div>

                                        <!-- Expandable Accordion Body -->
                                        <div x-show="openRows['{{ $result->id }}'] ?? openAll"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            class="pt-3 mt-2 border-t border-dashed border-gray-200 space-y-3">
                                            
                                            <!-- Media Attachments -->
                                            @if (!empty($qImages) && collect($qImages)->isNotEmpty())
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach ($qImages as $image)
                                                        @php
                                                            $isUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']);
                                                            $src = $isUrl ? $image : asset('storage/' . ltrim($image, '/'));
                                                        @endphp
                                                        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white p-1.5 shadow-2xs">
                                                            @if(preg_match('/\.(mp4|mov|avi|wmv|webm)$/i', $image))
                                                                <video src="{{ $src }}" class="max-h-[160px] max-w-[240px] rounded-lg object-contain" controls></video>
                                                            @elseif(preg_match('/\.(mp3|wav|ogg|m4a)$/i', $image))
                                                                <audio src="{{ $src }}" class="w-[240px] object-contain" controls></audio>
                                                            @elseif(preg_match('/\.(pdf)$/i', $image))
                                                                <div class="flex flex-col items-center justify-center gap-1.5 p-3 text-center h-[120px] w-[160px] bg-slate-50 rounded-lg border border-slate-200">
                                                                    <i class="fa-solid fa-file-pdf text-3xl text-red-500"></i>
                                                                    <a href="{{ $src }}" target="_blank" class="text-[11px] text-blue-600 underline font-semibold break-all">Lihat PDF</a>
                                                                </div>
                                                            @elseif(preg_match('/\.(docx?|xlsx?|txt|zip|rar)$/i', $image))
                                                                <div class="flex flex-col items-center justify-center gap-1.5 p-3 text-center h-[120px] w-[160px] bg-slate-50 rounded-lg border border-slate-200">
                                                                    <i class="fa-solid fa-file text-3xl text-blue-500"></i>
                                                                    <a href="{{ $src }}" target="_blank" class="text-[11px] text-blue-600 underline font-semibold break-all">Unduh Dokumen</a>
                                                                </div>
                                                            @else
                                                                <img src="{{ $src }}" alt="Gambar soal"
                                                                    class="rounded-lg object-contain max-h-[160px] max-w-[240px]">
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Choices and Answers -->
                                            <div class="space-y-2">
                                                @if($result?->type !== 'essay' && $result?->answers && $result->answers->isNotEmpty())
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                                        @foreach($result->answers->sortBy('alphabet') as $answer)
                                                            <div class="flex items-start gap-2.5 p-2.5 rounded-xl transition border {{ $answer->is_correct ? 'bg-emerald-50/80 border-emerald-300 text-emerald-950 font-medium shadow-2xs' : 'bg-gray-50/60 border-gray-200/80 text-gray-700 hover:bg-gray-50' }}">
                                                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full {{ $answer->is_correct ? 'bg-emerald-600 text-white shadow-2xs' : 'bg-gray-200 text-gray-700' }} font-bold text-[10px] shrink-0 mt-0.5">
                                                                    {{ $answer->alphabet }}
                                                                </span>
                                                                <div class="rich-content text-xs leading-relaxed flex-1">
                                                                    {!! $answer->context !!}
                                                                    
                                                                    @php
                                                                        $ansImages = is_array($answer->images) ? $answer->images : json_decode($answer->images ?? '[]', true);
                                                                    @endphp
                                                                    @if (!empty($ansImages) && collect($ansImages)->isNotEmpty())
                                                                        <div class="mt-2 flex flex-wrap gap-1.5">
                                                                            @foreach ($ansImages as $image)
                                                                                @php
                                                                                    $isUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://']);
                                                                                    $src = $isUrl ? $image : asset('storage/' . ltrim($image, '/'));
                                                                                @endphp
                                                                                <div class="overflow-hidden rounded-lg border border-gray-200 bg-white p-1 shadow-2xs">
                                                                                    <img src="{{ $src }}" alt="Gambar opsi" class="object-contain max-h-[90px] max-w-[140px] rounded">
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                @if($answer->is_correct)
                                                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs shrink-0 self-center" title="Kunci Jawaban">
                                                                        <i class="fas fa-check text-[10px]"></i>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($result?->type === 'essay')
                                                    @php
                                                        $essayAnswer = $result?->answers?->first();
                                                    @endphp
                                                    @if($essayAnswer && !empty(trim(strip_tags($essayAnswer->context))))
                                                        <div class="bg-indigo-50/60 p-3 rounded-xl border border-indigo-200/70 text-xs text-indigo-950">
                                                            <span class="font-bold flex items-center gap-1.5 text-indigo-700 mb-1">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Referensi Jawaban Utama:
                                                            </span>
                                                            <div class="rich-content leading-relaxed pl-5 font-normal">
                                                                {!! $essayAnswer->context !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>

                                            <!-- Description / Petunjuk -->
                                            @if(!empty(trim(strip_tags($result?->description))))
                                                <div class="p-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-xs text-slate-600">
                                                    <span class="font-bold text-slate-700 flex items-center gap-1.5 mb-0.5">
                                                        <i class="fas fa-info-circle text-blue-500 text-[11px]"></i> Petunjuk / Deskripsi Soal:
                                                    </span>
                                                    <div class="rich-content leading-relaxed pl-4">
                                                        {!! $result->description !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-xs text-gray-700">
                                    <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }} flex flex-col gap-1">
                                        <span class="font-bold text-gray-800">{{ $result?->study?->name ?? '-' }}</span>
                                        <span class="text-gray-500">{{ $result?->topic?->name ?? '-' }}</span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 align-top text-center">
                                    <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }} flex flex-col items-center gap-1">
                                        <span class="text-xs font-semibold text-gray-600">
                                            {{ $result?->difficulty == 'default' || empty($result?->difficulty) ? '-' : ucfirst($result?->difficulty) }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-center align-top">
                                    @if ($isRestricted)
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <i class="fas fa-lock text-gray-400"></i>
                                            <span class="text-[8px] text-gray-400 font-semibold uppercase leading-tight text-center">Terkunci</span>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center shadow-2xs"
                                                title="Edit Soal"
                                                href="{{ route('admin.master.question.update', $result) }}">
                                                <i class="fas fa-pen-to-square text-xs"></i>
                                            </a>
                                            <button class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center shadow-2xs cursor-pointer"
                                                title="Hapus Soal"
                                                wire:click="confirmDelete('{{ $result->id }}')">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                        <span>Tidak ada data soal</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $questions->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $questions->lastItem() }}</span> dari <span
                        class="font-medium">{{ $questions->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $questions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @include('partials.admin-latex-styles')
@endpush

@push('scripts')
    @include('partials.admin-latex-scripts')
    <script>
        (function () {
            if (window.__latexServerPreviewInitIndex) return;
            window.__latexServerPreviewInitIndex = true;

            // Clicking the render button triggers the shared helper
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('[data-latex-render]');
                if (!btn) return;
                const sourceSelector = btn.getAttribute('data-latex-source');
                const targetSelector = btn.getAttribute('data-latex-target');
                if (!sourceSelector || !targetSelector) return;

                window.renderLatexPreview(sourceSelector, targetSelector, {
                    targetType: btn.getAttribute('data-latex-type'),
                    targetId: btn.getAttribute('data-latex-id')
                });
            });
        })();
    </script>
@endpush