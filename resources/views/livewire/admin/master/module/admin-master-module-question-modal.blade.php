<div wire:ignore.self id="modal-module-question"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-screen-2xl h-[90vh] mx-auto flex flex-col transform transition-all scale-95 duration-300 ease-out animate-fade-in">

        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b flex-shrink-0">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Data Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>
        <!-- Body -->
        @if ($openQuestion)
            <div class="flex-1 overflow-y-auto px-6 py-4 text-gray-600" style="max-height: 80vh;">
                <div class="space-y-6 mb-4">
                    <!-- SECTION 1: Informasi Umum Produk -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prodi</label>
                            <select wire:model.live="filterStudyId" class="mt-1 form-control">
                                <option value="">Semua Prodi</option>
                                @foreach ($get_studys as $key_get_study => $get_study)
                                    <option value="{{ $key_get_study }}">{{ $get_study }}</option>
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
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cari</label>
                            <input type="text" class="mt-1 form-control" placeholder="Cari Sesuatu..."
                                wire:model.live='search'>
                        </div>
                    </div>
                </div>

                <!-- SMART BATCH SELECTION & QUANTITY PICKER BAR -->
                @php
                    $currentPageIds = ($questions instanceof \Illuminate\Pagination\LengthAwarePaginator || $questions instanceof \Illuminate\Pagination\Paginator) ? $questions->pluck('id')->toArray() : [];
                    $isAllPageSelected = !empty($currentPageIds) && collect($currentPageIds)->every(fn($id) => isset($selected_all[$id]));
                    $selectedCount = count($selected_all);
                    $totalCount = $totalQuestionsCount ?? (is_countable($questions) ? count($questions) : 0);
                    $isAllFilteredSelected = $totalCount > 0 && $selectedCount >= $totalCount;
                @endphp

                <!-- Box 1: Input Jumlah Soal & Preset Tahap -->
                <div class="bg-gradient-to-r from-blue-50/90 to-indigo-50/90 border border-blue-200/80 rounded-xl p-3.5 mb-4 shadow-2xs">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3">
                        <!-- Custom Number Input & Picker Buttons -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-magic text-blue-600"></i>
                                <span>Pilih Jumlah Soal:</span>
                            </span>
                            <div class="flex items-center gap-1">
                                <input type="number" 
                                    min="1" 
                                    max="{{ max($totalCount, 1) }}" 
                                    wire:model.live="selectCountInput" 
                                    class="w-20 px-2 py-1 text-xs font-bold text-center bg-white border border-blue-300 rounded-lg shadow-2xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="100">
                                <span class="text-xs text-gray-500 font-medium">soal</span>
                            </div>
                            <button type="button" 
                                wire:click="selectFirstCount({{ (int)$selectCountInput ?: 100 }})" 
                                class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-2xs transition-colors cursor-pointer"
                                title="Pilih {{ (int)$selectCountInput ?: 100 }} soal pertama secara berurutan sesuai urutan nomor 1">
                                <i class="fas fa-sort-numeric-down text-[11px]"></i>
                                <span>Pilih {{ (int)$selectCountInput ?: 100 }} Soal Pertama (Urut)</span>
                            </button>
                            <button type="button" 
                                wire:click="selectRandomCount({{ (int)$selectCountInput ?: 100 }})" 
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-2xs transition-colors cursor-pointer"
                                title="Pilih {{ (int)$selectCountInput ?: 100 }} soal secara acak (random)">
                                <i class="fas fa-random text-[11px]"></i>
                                <span>Pilih {{ (int)$selectCountInput ?: 100 }} Soal Acak</span>
                            </button>
                        </div>

                        <!-- Tahap Presets Buttons -->
                        <div class="flex flex-wrap items-center gap-1.5">
                            <span class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider mr-1">Tahap:</span>
                            <button type="button" wire:click="selectRange(1, 100)" class="px-2 py-1 text-xs font-semibold rounded-md bg-white hover:bg-blue-100 text-blue-800 border border-blue-200 transition-colors cursor-pointer shadow-2xs" title="Pilih Soal 1 s/d 100">
                                Tahap 1 (1–100)
                            </button>
                            <button type="button" wire:click="selectRange(101, 200)" class="px-2 py-1 text-xs font-semibold rounded-md bg-white hover:bg-blue-100 text-blue-800 border border-blue-200 transition-colors cursor-pointer shadow-2xs" title="Pilih Soal 101 s/d 200">
                                Tahap 2 (101–200)
                            </button>
                            <button type="button" wire:click="selectRange(201, 300)" class="px-2 py-1 text-xs font-semibold rounded-md bg-white hover:bg-blue-100 text-blue-800 border border-blue-200 transition-colors cursor-pointer shadow-2xs" title="Pilih Soal 201 s/d 300">
                                Tahap 3 (201–300)
                            </button>
                            <button type="button" wire:click="selectRange(301, 400)" class="px-2 py-1 text-xs font-semibold rounded-md bg-white hover:bg-blue-100 text-blue-800 border border-blue-200 transition-colors cursor-pointer shadow-2xs" title="Pilih Soal 301 s/d 400">
                                Tahap 4 (301–400)
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Box 2: Quick Selection Actions & Status Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 p-2.5 bg-gray-50/80 border border-gray-200 rounded-xl mb-4 text-xs">
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Select All on Current Page -->
                        <label class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg shadow-2xs font-semibold text-gray-700 cursor-pointer hover:bg-gray-50 transition select-none">
                            <input type="checkbox" 
                                class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                wire:click="toggleSelectAllPage(@js($currentPageIds))"
                                {{ $isAllPageSelected ? 'checked' : '' }}>
                            <span>Pilih di Halaman Ini ({{ count($currentPageIds) }})</span>
                        </label>

                        <!-- Select All Filtered Questions across all pages -->
                        @if($totalCount > count($currentPageIds))
                            <button type="button" 
                                wire:click="toggleSelectAllFiltered"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-blue-200 text-blue-700 rounded-lg shadow-2xs font-semibold hover:bg-blue-50 transition cursor-pointer">
                                <i class="fas fa-check-double text-blue-600"></i>
                                <span>{{ $isAllFilteredSelected ? 'Batalkan Semua' : 'Pilih Semua Total (' . $totalCount . ' Soal)' }}</span>
                            </button>
                        @endif

                        <!-- Deselect button -->
                        @if($selectedCount > 0)
                            <button type="button"
                                wire:click="deselectAll"
                                class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-red-200 text-red-600 rounded-lg shadow-2xs font-semibold hover:bg-red-50 transition cursor-pointer">
                                <i class="fas fa-times"></i>
                                <span>Kosongkan Pilihan</span>
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5 text-gray-600 font-medium">
                            <span>Tampilkan:</span>
                            <select wire:model.live="perPage" class="px-2 py-1 text-xs font-semibold bg-white border border-gray-300 rounded-lg shadow-2xs">
                                <option value="8">8 / hal</option>
                                <option value="20">20 / hal</option>
                                <option value="50">50 / hal</option>
                                <option value="100">100 / hal</option>
                                <option value="200">200 / hal</option>
                            </select>
                        </div>

                        <div class="text-gray-500 font-medium flex items-center gap-1.5">
                            <span>Tersedia:</span>
                            <span class="font-bold text-gray-800">{{ $totalCount }} Soal</span>
                            <span>·</span>
                            <span class="px-2 py-0.5 rounded-md bg-blue-100 text-blue-800 font-bold">{{ $selectedCount }} Terpilih</span>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow border">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <input type="checkbox" 
                                                class="form-checkbox h-4.5 w-4.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                                wire:click="toggleSelectAllPage(@js($currentPageIds))"
                                                {{ $isAllPageSelected ? 'checked' : '' }}
                                                title="Pilih / Batalkan semua soal di halaman ini">
                                            <span class="text-[10px] text-gray-400 font-normal">Semua</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan & Pilihan Jawaban</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">Deskripsi / Petunjuk</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($questions->groupBy('topic.name') as $topicName => $topicQuestions)
                                    @php
                                        $topicIds = $topicQuestions->pluck('id')->toArray();
                                        $isTopicAllSelected = !empty($topicIds) && collect($topicIds)->every(fn($id) => isset($selected_all[$id]));
                                    @endphp
                                    <tr class="bg-gray-50">
                                        <td colspan="3"
                                            class="px-4 py-2.5 text-xs font-bold text-gray-700 uppercase tracking-wider border-y">
                                            <div class="flex items-center justify-between">
                                                <label class="flex items-center gap-2 cursor-pointer select-none">
                                                    <input type="checkbox" 
                                                        class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                                        wire:click.stop="toggleSelectTopic(@js($topicIds))"
                                                        {{ $isTopicAllSelected ? 'checked' : '' }}
                                                        title="Pilih / Batalkan semua soal pada topik ini">
                                                    <span class="flex items-center gap-1.5 text-slate-700">
                                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Topik: {{ $topicName ?? 'Tanpa Topik' }}
                                                    </span>
                                                </label>
                                                <span class="px-2 py-0.5 rounded-md bg-gray-200 text-[10px] text-gray-600 font-medium">
                                                    {{ $topicQuestions->count() }} Soal
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    @foreach($topicQuestions as $index => $result)
                                        @php
                                            $isSelected = $selected_all[$result->id] ?? false;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer {{ $isSelected ? 'bg-blue-50/60 border-l-4 border-blue-500' : '' }}"
                                            wire:click="choiceQuestion('{{ $result->id }}')">
                                            
                                            <!-- Checkbox & Number Column -->
                                            <td class="px-4 py-4 text-center align-top whitespace-nowrap">
                                                <div class="flex flex-col items-center justify-center gap-2">
                                                    <input type="checkbox" 
                                                        class="form-checkbox h-4.5 w-4.5 text-blue-600 border-gray-350 rounded focus:ring-blue-500 cursor-pointer transition"
                                                        {{ $isSelected ? 'checked' : '' }}
                                                        wire:click.stop="choiceQuestion('{{ $result->id }}')">
                                                    <span class="text-xs font-semibold text-gray-400">
                                                        #{{ $questions->firstItem() + $loop->parent->index + $index }}
                                                    </span>
                                                </div>
                                            </td>

                                            <!-- Question, Metadata & Answers Column -->
                                            <td class="px-6 py-4 align-top">
                                                <div class="flex flex-col gap-3">
                                                    
                                                    <!-- Badges Metadata Row -->
                                                    <div class="flex flex-wrap gap-1.5 items-center">
                                                        @if($result?->study?->name)
                                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold border border-slate-200/50">
                                                                Prodi: {{ $result->study->name }}
                                                            </span>
                                                        @endif
                                                        @if($result?->categoryQuestion?->name)
                                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold border border-slate-200/50">
                                                                Kat: {{ $result->categoryQuestion->name }}
                                                            </span>
                                                        @endif
                                                        @if($result?->questionType?->name)
                                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 text-[10px] font-semibold border border-slate-200/50">
                                                                Tipe: {{ $result->questionType->name }}
                                                            </span>
                                                        @endif
                                                        
                                                        <!-- Difficulty Badge -->
                                                        @if($result->difficulty == 'easy')
                                                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/60 uppercase">Easy</span>
                                                        @elseif($result->difficulty == 'medium')
                                                            <span class="px-2 py-0.5 rounded bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200/60 uppercase">Medium</span>
                                                        @elseif($result->difficulty == 'hard')
                                                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200/60 uppercase">Hard</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded bg-gray-50 text-gray-600 text-[10px] font-bold border border-gray-200/60 uppercase">Default</span>
                                                        @endif

                                                        <!-- Question Type Badges -->
                                                        @if(($result->type ?? 'single') == 'single')
                                                            <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold border border-blue-200/60">Pilihan Ganda</span>
                                                        @elseif($result->type == 'multiple')
                                                            <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 text-[10px] font-bold border border-purple-200/60">Pilihan Ganda Kompleks</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200/60">Essay</span>
                                                        @endif
                                                    </div>

                                                    <!-- Question Text -->
                                                    <div class="rich-content text-sm text-gray-800 font-medium leading-relaxed mt-1">
                                                        {!! $result?->question !!}
                                                    </div>

                                                    <!-- Question Images/Files -->
                                                    @php
                                                        $qImages = is_array($result->images) ? $result->images : json_decode($result->images, true);
                                                    @endphp
                                                    @if (!empty($qImages) && collect($qImages)->isNotEmpty())
                                                        <div class="mt-2 flex flex-wrap gap-2">
                                                            @foreach ($qImages as $image)
                                                                @php
                                                                    $isUrl = Str::startsWith($image, ['http://', 'https://']);
                                                                    $src = $isUrl ? $image : asset('storage/' . ltrim($image, '/'));
                                                                @endphp
                                                                <div class="overflow-hidden rounded-lg border border-gray-100 shadow-sm bg-white p-1">
                                                                    @if(preg_match('/\.(mp4|mov|avi|wmv|webm)$/i', $image))
                                                                        <video src="{{ $src }}" class="max-h-[140px] max-w-[200px] object-contain" controls></video>
                                                                    @elseif(preg_match('/\.(mp3|wav|ogg|m4a)$/i', $image))
                                                                        <audio src="{{ $src }}" class="w-[200px] object-contain" controls></audio>
                                                                    @elseif(preg_match('/\.(pdf)$/i', $image))
                                                                        <div class="flex flex-col items-center justify-center gap-1 p-2 text-center h-[120px] w-[160px] bg-slate-50 border rounded">
                                                                            <i class="fa-solid fa-file-pdf text-2xl text-red-500"></i>
                                                                            <a href="{{ $src }}" target="_blank" class="text-[10px] text-blue-500 underline break-all font-medium">Lihat PDF</a>
                                                                        </div>
                                                                    @elseif(preg_match('/\.(docx?|xlsx?|txt|zip|rar)$/i', $image))
                                                                        <div class="flex flex-col items-center justify-center gap-1 p-2 text-center h-[120px] w-[160px] bg-slate-50 border rounded">
                                                                            <i class="fa-solid fa-file text-2xl text-blue-500"></i>
                                                                            <a href="{{ $src }}" target="_blank" class="text-[10px] text-blue-500 underline break-all font-medium">Unduh Dokumen</a>
                                                                        </div>
                                                                    @else
                                                                        <img src="{{ $src }}" alt="Gambar soal"
                                                                            class="object-contain"
                                                                            style="max-width: 200px; max-height: 140px;">
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <!-- Answer Options List -->
                                                    <div class="mt-2 space-y-2 border-t border-dashed border-gray-100 pt-3">
                                                        @if($result->type !== 'essay' && $result->answers->isNotEmpty())
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                                @foreach($result->answers->sortBy('alphabet') as $answer)
                                                                    <div class="flex items-start gap-2.5 p-2 rounded-lg transition border {{ $answer->is_correct ? 'bg-emerald-50/80 border-emerald-200 text-emerald-900 shadow-sm font-medium' : 'bg-gray-50/50 border-gray-100 text-gray-700' }}">
                                                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full {{ $answer->is_correct ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-700' }} font-bold text-[10px] shrink-0 mt-0.5">
                                                                            {{ $answer->alphabet }}
                                                                        </span>
                                                                        <div class="rich-content text-xs leading-relaxed flex-1">
                                                                            {!! $answer->context !!}
                                                                            
                                                                            <!-- Answer Option Images -->
                                                                            @php
                                                                                $ansImages = is_array($answer->images) ? $answer->images : json_decode($answer->images, true);
                                                                            @endphp
                                                                            @if(!empty($ansImages) && collect($ansImages)->isNotEmpty())
                                                                                <div class="mt-1.5 flex flex-wrap gap-1.5">
                                                                                    @foreach($ansImages as $ansImg)
                                                                                        @php
                                                                                            $isUrl = Str::startsWith($ansImg, ['http://', 'https://']);
                                                                                            $src = $isUrl ? $ansImg : asset('storage/' . ltrim($ansImg, '/'));
                                                                                        @endphp
                                                                                        <div class="overflow-hidden rounded border border-gray-100 bg-white p-0.5">
                                                                                            @if(preg_match('/\.(mp4|mov|avi|wmv|webm)$/i', $ansImg))
                                                                                                <video src="{{ $src }}" class="max-h-[80px] max-w-[120px] object-contain" controls></video>
                                                                                            @elseif(preg_match('/\.(mp3|wav|ogg|m4a)$/i', $ansImg))
                                                                                                <audio src="{{ $src }}" class="w-[120px] object-contain" controls></audio>
                                                                                            @elseif(preg_match('/\.(pdf)$/i', $ansImg))
                                                                                                <div class="flex flex-col items-center justify-center p-1 text-center h-[70px] w-[100px] bg-slate-50 border rounded text-[8px]">
                                                                                                    <i class="fa-solid fa-file-pdf text-red-500 text-lg"></i>
                                                                                                    <a href="{{ $src }}" target="_blank" class="text-blue-500 underline break-all font-medium">PDF</a>
                                                                                                </div>
                                                                                            @elseif(preg_match('/\.(docx?|xlsx?|txt|zip|rar)$/i', $ansImg))
                                                                                                <div class="flex flex-col items-center justify-center p-1 text-center h-[70px] w-[100px] bg-slate-50 border rounded text-[8px]">
                                                                                                    <i class="fa-solid fa-file text-blue-500 text-lg"></i>
                                                                                                    <a href="{{ $src }}" target="_blank" class="text-blue-500 underline break-all font-medium">Doc</a>
                                                                                                </div>
                                                                                            @else
                                                                                                <img src="{{ $src }}" alt="Gambar opsi"
                                                                                                    class="object-contain"
                                                                                                    style="max-width: 120px; max-height: 80px;">
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                        @if($answer->is_correct)
                                                                            <span class="shrink-0 text-emerald-600 self-center">
                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                                                </svg>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @elseif($result->type === 'essay')
                                                            @php
                                                                $essayAnswer = $result->answers->where('is_correct', true)->first();
                                                            @endphp
                                                            @if($essayAnswer && !empty(trim(strip_tags($essayAnswer->context))))
                                                                <div class="text-xs text-emerald-950 bg-emerald-50/70 p-3 rounded-lg border border-emerald-100">
                                                                    <span class="font-bold flex items-center gap-1.5 text-emerald-800 mb-1">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        Referensi Jawaban Utama:
                                                                    </span>
                                                                    <div class="rich-content leading-relaxed mt-1 pl-5">
                                                                        {!! $essayAnswer->context !!}
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Description Column -->
                                            <td class="px-4 py-4 align-top text-xs text-gray-500">
                                                @if(!empty(trim(strip_tags($result?->description))))
                                                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100 max-h-40 overflow-y-auto rich-content leading-relaxed">
                                                        {!! $result?->description !!}
                                                    </div>
                                                @else
                                                    <span class="text-gray-300 italic">Tidak ada deskripsi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">Tidak ada
                                            data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                        {{ $questions->links('vendor.livewire.custom') }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-6 py-4 border-t bg-gray-50/50 flex-shrink-0">
            <!-- Info total soal terpilih (kiri) -->
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-gray-700">
                    Total soal terpilih:
                    <span class="text-blue-600 font-bold text-base">
                        {{ count($selected_all) }}
                    </span>
                    @if(isset($totalQuestionsCount) && $totalQuestionsCount > 0)
                        <span class="text-xs text-gray-400">/ {{ $totalQuestionsCount }} soal tersedia</span>
                    @endif
                </span>
                
                @if(count($selected_all) > 0)
                    <button type="button"
                        wire:click="deselectAll" 
                        class="text-xs text-red-500 hover:text-red-700 font-semibold underline transition cursor-pointer">
                        Batal Pilih Semua
                    </button>
                @endif
            </div>

            <!-- Tombol aksi (kanan) -->
            <div class="flex flex-wrap items-center gap-2">
                @if(isset($totalQuestionsCount) && $totalQuestionsCount > 0 && count($selected_all) < $totalQuestionsCount)
                    <button type="button"
                        wire:click="selectAllFiltered"
                        class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-semibold rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-check-double text-xs"></i>
                        Pilih Semua ({{ $totalQuestionsCount }} Soal)
                    </button>
                @endif

                <button wire:click="closeModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-lg shadow-sm transition cursor-pointer">
                    Batal
                </button>
                <button wire:click='submitModuleQuestion()'
                    class="px-5 py-2 bg-primary hover:bg-primary/90 text-white text-sm font-medium rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                    <i class="fas fa-save text-xs"></i>
                    Simpan ({{ count($selected_all) }})
                </button>
            </div>
        </div>
    </div>
</div>