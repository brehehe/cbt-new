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
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
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
                    </div>
                    <div class="mb-4" wire:ignore>
                        <label for="question" class="block text-sm font-medium text-gray-700">Pertanyaan <span
                                class="text-red-600">*</span></label>
                        <textarea id="question" x-data x-init="$($el).summernote({
                            height: 200,
                            placeholder: 'Tulis pertanyaan di sini...',
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link', 'picture', 'video']],
                                ['view', ['fullscreen', 'codeview', 'help']]
                            ],
                            callbacks: {
                                onChange: function(contents, $editable) {
                                    @this.set('question', contents);
                                }
                            }
                        });" class="mt-1 form-control"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="images" class="block text-sm font-medium text-gray-700">Gambar</label>
                        <x-filepond::upload wire:model="images" multiple accept=".jpg,.jpeg,.png" />
                        @error('images')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4" wire:ignore>
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                            pertanyaan</label>
                        <textarea id="description" x-data x-init="$($el).summernote({
                            height: 150,
                            placeholder: 'Tulis deskripsi di sini...',
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'underline', 'clear']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['insert', ['link', 'picture']],
                                ['view', ['codeview']]
                            ],
                            callbacks: {
                                onChange: function(contents, $editable) {
                                    @this.set('description', contents);
                                }
                            }
                        });" class="mt-1 form-control"></textarea>
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
                class="px-4 py-2 bg-[{{ $companyData->color_primary ?? '#f58634' }}] hover:bg-[{{ $companyData->color_primary ?? '#f58634' }}] text-white rounded-lg shadow transition">
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