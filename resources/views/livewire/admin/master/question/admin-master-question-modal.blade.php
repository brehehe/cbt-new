<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 150vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Bank Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 80vh">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-1">
                <div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="study_id" class="block text-sm font-medium text-gray-700">Prodi <span
                                    class="text-red-600">*</span></label>
                            <select class="mt-1 form-control" wire:model.lazy='study_id'>
                                <option value="">Pilih prodi</option>
                                @foreach ($studys as $key_study => $study)
                                    <option value="{{ $key_study }}">{{ $study }}</option>
                                @endforeach
                            </select>
                            @error('study_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="category_question_id" class="block text-sm font-medium text-gray-700">Kategori
                                Soal <span class="text-red-600">*</span></label>
                            <select class="mt-1 form-control" wire:model.lazy='category_question_id'>
                                <option value="">Pilih kategori soal</option>
                                @foreach ($category_questions as $key_category_question => $category_question)
                                    <option value="{{ $category_question->id }}">{{ $category_question->name }}</option>
                                @endforeach
                            </select>
                            @error('category_question_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="topic_id" class="block text-sm font-medium text-gray-700">Topik Soal <span
                                    class="text-red-600">*</span></label>
                            <select class="mt-1 form-control" wire:model.lazy='topic_id'>
                                <option value="">Pilih topik soal</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic?->id }}">{{ $topic?->name }}</option>
                                @endforeach
                            </select>
                            @error('topic_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="material_category_id" class="block text-sm font-medium text-gray-700">Kategori
                                Materi</label>
                            <select class="mt-1 form-control" wire:model.lazy='material_category_id'>
                                <option value="">Pilih kategori materi</option>
                                @foreach ($material_categories as $material_category)
                                    <option value="{{ $material_category?->id }}">{{ $material_category?->name }}</option>
                                @endforeach
                            </select>
                            @error('material_category_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="material_id" class="block text-sm font-medium text-gray-700">Materi Soal</label>
                            <select class="mt-1 form-control" wire:model.lazy='material_id'>
                                <option value="">Pilih materi soal</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material?->id }}">{{ $material?->name }}</option>
                                @endforeach
                            </select>
                            @error('material_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe Ujian
                                <span class="text-red-600">*</span></label>
                            <select class="mt-1 form-control" wire:model.lazy='question_type_id'>
                                <option value="">Pilih Tipe Ujian</option>
                                @foreach ($question_types as $question_type)
                                    <option value="{{ $question_type?->id }}">{{ $question_type?->name }}</option>
                                @endforeach
                            </select>
                            @error('question_type_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4 md:col-span-2">
                            <label for="type" class="block text-sm font-medium text-gray-700">Jenis Soal
                                <span class="text-red-600">*</span></label>
                            <select class="mt-1 form-control" wire:model.live='type'>
                                <option value="">Pilih Jenis Soal</option>
                                <option value="single">Single Choice (Pilihan Ganda)</option>
                                <!-- <option value="multiple">Multiple Choice (Pilihan Ganda Kompleks)</option> -->
                                <option value="essay">Essay (Uraian)</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-4" wire:ignore>
                        <label for="question" class="block text-sm font-medium text-gray-700">Pertanyaan <span
                                class="text-red-600">*</span></label>
                        <textarea id="question" x-data x-init="window.initSummernote($el, 'question')" class="mt-1 form-control"></textarea>
                    </div>

                    <!-- Live Preview Box -->
                    <div class="mb-6" x-show="$wire.question && $wire.question !== '<p><br></p>'">
                        <label class="block text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-2">Pratinjau Soal:</label>
                        <div class="p-6 border-2 border-blue-50 rounded-2xl bg-blue-50/30 shadow-sm transition-all duration-300">
                            <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed font-medium">
                                {!! $question !!}
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">Gambar Soal</label>
                        
                        <!-- Grid Preview Gambar -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            @foreach ($images as $index => $img)
                                <div class="relative group border rounded-lg overflow-hidden flex items-center justify-center bg-gray-50 h-32">
                                    @php
                                        $isString = is_string($img);
                                        $isUrl = $isString && (Str::startsWith($img, 'http://') || Str::startsWith($img, 'https://'));
                                        $imgUrl = $isString ? ($isUrl ? $img : asset('storage/' . ltrim($img, '/'))) : $img->temporaryUrl();
                                        $fileName = $isString ? $img : $img->getClientOriginalName();
                                    @endphp
                                    @if(preg_match('/\.(mp4|mov|avi|wmv|webm)$/i', $fileName))
                                        <video src="{{ $imgUrl }}" class="max-h-full max-w-full object-contain" controls></video>
                                    @elseif(preg_match('/\.(mp3|wav|ogg|m4a)$/i', $fileName))
                                        <audio src="{{ $imgUrl }}" class="max-h-full max-w-full object-contain w-full" controls></audio>
                                    @elseif(preg_match('/\.(pdf)$/i', $fileName))
                                        <div class="flex flex-col items-center justify-center gap-1 p-2 text-center h-full">
                                            <i class="fa-solid fa-file-pdf text-3xl text-red-500"></i>
                                            <a href="{{ $imgUrl }}" target="_blank" class="text-[10px] text-blue-500 underline break-all font-medium">Lihat PDF</a>
                                        </div>
                                    @elseif(preg_match('/\.(docx?|xlsx?|txt|zip|rar)$/i', $fileName))
                                        <div class="flex flex-col items-center justify-center gap-1 p-2 text-center h-full">
                                            <i class="fa-solid fa-file text-3xl text-blue-500"></i>
                                            <a href="{{ $imgUrl }}" target="_blank" class="text-[10px] text-blue-500 underline break-all font-medium">Unduh Dokumen</a>
                                        </div>
                                    @else
                                        <img src="{{ $imgUrl }}" class="max-h-full max-w-full object-contain" alt="Preview Media">
                                    @endif
                                    <button type="button" wire:click="removeImage({{ $index }})" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-70 group-hover:opacity-100 transition shadow">
                                        <span class="sr-only">Hapus</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @endforeach
                            
                            <!-- Tombol Tambah Gambar (Label bound to hidden file input) -->
                            <label class="border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center h-32 hover:bg-gray-50 hover:border-gray-400 transition cursor-pointer text-gray-500 group">
                                <svg class="w-8 h-8 mb-1 text-gray-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-xs font-medium text-center px-2">Klik untuk<br>Tambah Media</span>
                                <input type="file" wire:model="new_images" multiple accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.wmv,.webm,.mp3,.wav,.ogg,.m4a,.pdf,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar" class="hidden">
                            </label>
                        </div>
                        
                        <div wire:loading wire:target="new_images" class="text-sm text-blue-600 mb-2 font-medium flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mempersiapkan gambar...
                        </div>

                        @error('images')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('new_images')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('new_images.*')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Single Choice Pilihan Jawaban Input Section -->
                    @if (($type ?? 'single') === 'single')
                        <div class="mb-6 p-4 bg-slate-50/70 rounded-2xl border border-slate-200">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 mb-3 border-b border-slate-200">
                                <div>
                                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pilihan Jawaban (Single Choice) <span class="text-red-600">*</span>
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Tandai radio button pada opsi yang merupakan <strong>KUNCI JAWABAN</strong>.</p>
                                </div>
                                <button type="button" wire:click="addAnswerOption"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg text-xs font-semibold transition shadow-2xs cursor-pointer self-start sm:self-auto">
                                    <i class="fas fa-plus text-[10px]"></i> Tambah Opsi ({{ chr(65 + count($answers_data)) }})
                                </button>
                            </div>

                            <!-- Daftar Pilihan Opsi -->
                            <div class="space-y-3">
                                @foreach ($answers_data as $index => $ans)
                                    @php
                                        $isCorrect = ($correct_answer_alphabet === $ans['alphabet']);
                                    @endphp
                                    <div class="rounded-xl border transition-all p-3.5 {{ $isCorrect ? 'border-emerald-400 bg-emerald-50/50 ring-2 ring-emerald-400/20 shadow-xs' : 'border-gray-200 bg-white hover:border-gray-300' }}">
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <!-- Selector Kunci Jawaban -->
                                            <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                                <input type="radio" wire:model.live="correct_answer_alphabet" value="{{ $ans['alphabet'] }}"
                                                    class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 cursor-pointer">
                                                <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs {{ $isCorrect ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-700' }}">
                                                    {{ $ans['alphabet'] }}
                                                </span>
                                                @if ($isCorrect)
                                                    <span class="text-xs font-bold text-emerald-700 uppercase tracking-wide bg-emerald-100/90 px-2 py-0.5 rounded-full flex items-center gap-1">
                                                        <i class="fas fa-check text-[10px]"></i> Kunci Jawaban
                                                    </span>
                                                @else
                                                    <span class="text-xs text-gray-500 hover:text-gray-700 font-medium">
                                                        Pilih sebagai Kunci Jawaban
                                                    </span>
                                                @endif
                                            </label>

                                            <!-- Aksi Opsi -->
                                            <div class="flex items-center gap-2">
                                                @if (count($answers_data) > 2)
                                                    <button type="button" wire:click="removeAnswerOption({{ $index }})"
                                                        class="text-xs text-red-500 hover:text-red-700 hover:bg-red-50 px-2 py-1 rounded transition flex items-center gap-1 cursor-pointer"
                                                        title="Hapus opsi {{ $ans['alphabet'] }}">
                                                        <i class="fas fa-trash-alt text-[10px]"></i> Hapus Opsi
                                                    </button>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Input Teks Jawaban -->
                                        <textarea wire:model.defer="answers_data.{{ $index }}.context" rows="2"
                                            placeholder="Ketik teks pilihan jawaban {{ $ans['alphabet'] }}..."
                                            class="form-control text-sm w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"></textarea>

                                        @error('answers_data.' . $index . '.context')
                                            <p class="text-xs text-red-600 mt-1 font-medium">{{ $message }}</p>
                                        @enderror

                                        <!-- Gambar Opsi Jawaban (Opsional) -->
                                        <div class="mt-2 flex flex-wrap items-center gap-3">
                                            @if (!empty($ans['images']) && count($ans['images']) > 0)
                                                @foreach ($ans['images'] as $imgIdx => $img)
                                                    @php
                                                        $isString = is_string($img);
                                                        $isUrl = $isString && (Str::startsWith($img, 'http://') || Str::startsWith($img, 'https://'));
                                                        $imgUrl = $isString ? ($isUrl ? $img : asset('storage/' . ltrim($img, '/'))) : (method_exists($img, 'temporaryUrl') ? $img->temporaryUrl() : '');
                                                    @endphp
                                                    <div class="relative group border rounded-lg overflow-hidden flex items-center justify-center bg-gray-50 h-16 w-24">
                                                        <img src="{{ $imgUrl }}" class="max-h-full max-w-full object-contain" alt="Gambar Opsi">
                                                        <button type="button" wire:click="removeAnswerOptionImage({{ $index }})"
                                                            class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-80 group-hover:opacity-100 transition shadow cursor-pointer"
                                                            title="Hapus gambar">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @else
                                                <label class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white hover:bg-gray-50 border border-gray-200 hover:border-gray-300 rounded-lg text-[11px] font-medium text-gray-600 hover:text-blue-600 transition cursor-pointer shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span>+ Gambar Opsi (Opsional)</span>
                                                    <input type="file" wire:model="answer_option_images.{{ $index }}" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                                                </label>
                                            @endif

                                            <div wire:loading wire:target="answer_option_images.{{ $index }}" class="text-xs text-blue-600 font-medium flex items-center gap-1.5">
                                                <svg class="animate-spin h-3.5 w-3.5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Mengunggah gambar opsi...
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @error('answers_data')
                                <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                            @enderror
                            @error('correct_answer_alphabet')
                                <p class="text-sm text-red-600 mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif (($type ?? '') === 'essay')
                        <div class="mb-6 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-200">
                            <h3 class="text-sm font-bold text-indigo-900 flex items-center gap-2 mb-1">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Referensi / Kunci Jawaban Essay (Opsional)
                            </h3>
                            <p class="text-xs text-indigo-700/80 mb-2.5">Tuliskan referensi jawaban atau poin-poin utama jawaban untuk soal essay ini.</p>
                            <textarea wire:model.defer="essay_answer" rows="3" placeholder="Tulis referensi jawaban essay di sini..."
                                class="form-control text-sm w-full rounded-lg border-indigo-200 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    @endif

                    <div class="mb-4" wire:ignore>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                            pertanyaan</label>
                        <textarea id="description" x-data x-init="window.initSummernote($el, 'description', { height: 150, placeholder: 'Tulis deskripsi / instruksi di sini...' })" class="mt-1 form-control"></textarea>
                    </div>
                </div>
                <div>
                    <label for="latex" class="block text-sm font-medium text-gray-700">LaTeX (Opsional)</label>
                    <textarea id="latex" wire:model.defer="latex" placeholder="Tulis LaTeX di sini..."
                        class="mt-1 form-control" data-latex-input="server" data-autoresize></textarea>
                    <p class="mt-1 text-xs text-gray-500">Disimpan sebagai source LaTeX terpisah.</p>
                    <div class="mt-2 flex items-center gap-2">
                        <button type="button" class="btn btn-primary" data-latex-render data-latex-source="#latex"
                            data-latex-target="#latexPreviewCreate" data-latex-type="question">
                            Render LaTeX
                        </button>
                        <span class="text-xs text-gray-500">Preview akan muncul di bawah.</span>
                    </div>
                    <div id="latexPreviewCreate" class="mt-2 rounded border bg-gray-50 p-3 text-sm text-gray-700"
                        wire:ignore>
                        <div class="text-xs text-gray-400">Belum ada preview.</div>
                    </div>
                    @error('latex')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                style="background-color: {{ $companyData->color_primary ?? '#f58634' }};"
                class="px-4 py-2 text-white rounded-lg shadow transition hover:opacity-90">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        function autoResize(el) {
            if (!el) return;
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        }

        function initAutoResize() {
            document.querySelectorAll('textarea[data-autoresize]').forEach(function (el) {
                if (el.dataset.autoresizeInit === '1') return;
                el.dataset.autoresizeInit = '1';
                autoResize(el);
                el.addEventListener('input', function () {
                    autoResize(el);
                });
            });
        }

        document.addEventListener('DOMContentLoaded', initAutoResize);
        document.addEventListener('livewire:navigated', initAutoResize);
        document.addEventListener('livewire:load', initAutoResize);
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', initAutoResize);
        }
    })();
</script>