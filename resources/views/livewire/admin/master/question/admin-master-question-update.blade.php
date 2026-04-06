<div>
    @section('title', 'Ubah Data Soal')
    @push('styles')
        @include('partials.admin-latex-styles')
    @endpush
    {{-- In work, do what you enjoy. --}}
    @include('livewire.admin.master.question.admin-master-question-update-modal-answer')
    @include('livewire.admin.master.question.admin-master-question-update-modal-answer-images')
    @include('livewire.admin.master.question.admin-master-question-update-modal-images')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-primary">
                    Ubah Data Soal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="submitQuestion()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="p-4 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Detail Soal</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-4">
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                Soal</label>
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
                        <div>
                            <label for="topic_id" class="block text-sm font-medium text-gray-700">Topik Soal<span
                                    class="text-red-600">*</span></label>
                            <div wire:key="select-{{ rand() }}">
                                <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('topic_id', e ? e : '');
                            }
                        });" wire:model.live="topic_id" id="topic_id">
                                    <option value="">Pilih Topik Soal</option>
                                    @foreach ($topics as $topic)
                                        <option value="{{ $topic?->id }}">{{ $topic?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('topic_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="material_category_id" class="block text-sm font-medium text-gray-700">Kategori
                                Materi</label>
                            <div wire:key="select-{{ rand() }}">
                                <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('material_category_id', e ? e : '');
                            }
                        });" wire:model.live="material_category_id" id="material_category_id">
                                    <option value="">Pilih Kategori Materi</option>
                                    @foreach ($material_categories as $material_cateory)
                                        <option value="{{ $material_cateory?->id }}">{{ $material_cateory?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('material_category_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="material_id" class="block text-sm font-medium text-gray-700">Materi</label>
                            <div wire:key="select-{{ rand() }}">
                                <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('material_id', e ? e : '');
                            }
                        });" wire:model.live="material_id" id="material_id">
                                    <option value="">Pilih Materi</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material?->id }}">{{ $material?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('material_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe
                                Ujian<span class="text-red-600">*</span></label>
                            <div wire:key="select-{{ rand() }}">
                                <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('question_type_id', e ? e : '');
                            }
                        });" wire:model.live="question_type_id" id="question_type_id">
                                    <option value="">Pilih Tipe Ujian</option>
                                    @foreach ($question_types as $question_type)
                                        <option value="{{ $question_type?->id }}">{{ $question_type?->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('question_type_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2" wire:ignore>
                            <label for="question" class="block text-sm font-medium text-gray-700">Pertanyaan<span
                                    class="text-red-600">*</span></label>
                            <textarea id="question" x-data
                                x-init="window.initSummernote($el, 'question', { height: 300, initialCode: $wire.question })"
                                class="mt-1 form-control"></textarea>

                            <!-- Live Preview for Question -->
                            <div class="mt-4" x-show="$wire.question && $wire.question !== '<p><br></p>'">
                                <label
                                    class="block text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">Pratinjau
                                    Pertanyaan:</label>
                                <div class="p-4 border rounded-xl bg-blue-50/50 shadow-sm transition-all duration-300">
                                    <div class="prose prose-sm max-w-none text-gray-800 leading-relaxed">
                                        {!! $question !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label for="question" class="block text-sm font-medium text-gray-700">Gambar<span
                                    class="text-red-600">*</span></label>
                            <button class="bg-primary text-white px-2 py-1 rounded"
                                wire:click='modalImages()'>Gambar</button>
                        </div>
                        <div class="md:col-span-2" wire:ignore>
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                                Pertanyaan</label>
                            <textarea id="description" x-data
                                x-init="window.initSummernote($el, 'description', { height: 200, placeholder: 'Deskripsi pertanyaan...', initialCode: $wire.description })"
                                class="mt-1 form-control"></textarea>

                            <!-- Live Preview for Description -->
                            <div class="mt-4" x-show="$wire.description && $wire.description !== '<p><br></p>'">
                                <label
                                    class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Pratinjau
                                    Deskripsi:</label>
                                <div
                                    class="p-4 border border-dashed rounded-xl bg-gray-50/50 transition-all duration-300">
                                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed italic">
                                        {!! $description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="latex" class="block text-sm font-medium text-gray-700">LaTeX (Opsional)</label>
                    <textarea id="latex" wire:model="latex" placeholder="Tulis LaTeX di sini..."
                        class="mt-1 form-control" data-autosize="true" style="overflow:hidden;resize:none;" x-data
                        x-init="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"
                        @input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"
                        data-latex-input="server"></textarea>
                    <p class="mt-1 text-xs text-gray-500">Disimpan sebagai source LaTeX terpisah.</p>
                    <div class="mt-2 flex items-center gap-2">
                        <button type="button" class="btn btn-primary" data-latex-render data-latex-source="#latex"
                            data-latex-target="#latexPreviewUpdate" data-latex-type="question"
                            data-latex-id="{{ $data_id }}">
                            Render LaTeX
                        </button>
                        <span class="text-xs text-gray-500">Preview akan muncul di bawah.</span>
                    </div>
                    <div id="latexPreviewUpdate" class="mt-2 rounded border bg-gray-50 p-3 text-sm text-gray-700"
                        wire:ignore>
                        <div class="text-xs text-gray-400">Belum ada preview.</div>
                    </div>
                    @error('latex')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        {{-- card --}}
        <div class="p-4 bg-white shadow rounded-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <div class="flex items-center">
                </div>
                <div class="flex items-center w-full sm:w-auto gap-2">
                    <div class="relative w-full sm:w-64">
                        <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                            wire:model.live='search'>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button wire:click="openModal()" class="mt-1 px-3 py-2 btn btn-warning">
                        Tambah
                    </button>
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-2 center">Alpabet</th>
                            <th class="w-2">Gambar</th>
                            <th>Konteks Jawaban</th>
                            <th class="w-2">Jawaban</th>
                            <th class="w-2 center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($answers as $index => $result)
                            <tr>
                                <td class="center">{{ $result?->alphabet ?? chr(64 + $loop->iteration) }} </td>
                                <td>
                                    <button class="bg-primary text-white px-2 py-1 rounded"
                                        wire:click="modalAnswerImage('{{ $result?->id }}', '{{ $result?->alphabet ?? chr(64 + $loop->iteration) }}')">Gambar</button>
                                </td>
                                <td>{{ $result?->context }}</td>
                                <td>
                                    <div class="flex items-center mt-2" wire:key="{{ rand() }}">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" wire:click="toggleAnswerCorrect('{{ $result->id }}')"
                                                class="sr-only peer" {{ $result->is_correct ? 'checked' : '' }}>
                                            <div
                                                class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td class="center">
                                    <div class="flex items-center">
                                        <button
                                            class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            wire:click="edit('{{ $result->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button
                                            class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                            wire:click="confirmDelete('{{ $result->id }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="no-data">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    @include('partials.admin-latex-scripts')
    <script>
        function initTextareaAutosize() {
            const textareas = document.querySelectorAll('textarea[data-autosize="true"]');
            textareas.forEach((textarea) => {
                const autoResize = () => {
                    textarea.style.height = 'auto';
                    textarea.style.height = `${textarea.scrollHeight}px`;
                };
                textarea.addEventListener('input', autoResize);
                autoResize();
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initTextareaAutosize();
        });
        document.addEventListener('livewire:load', function () {
            initTextareaAutosize();
        });
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', () => {
                initTextareaAutosize();
            });
        }

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

        document.addEventListener('reset-answer-latex-preview', function () {
            const targetEl = document.querySelector('#answerLatexPreviewModal');
            if (!targetEl) return;
            targetEl.dataset.rendered = '0';
            targetEl.innerHTML = '<div class="text-xs text-gray-400">Belum ada preview.</div>';
        });

        document.addEventListener('sync-answer-latex-preview', function () {
            const sourceEl = document.querySelector('#answer_latex');
            const targetEl = document.querySelector('#answerLatexPreviewModal');
            if (!sourceEl || !targetEl) return;

            const latex = (sourceEl.value || '').trim();
            if (!latex) {
                targetEl.dataset.rendered = '0';
                targetEl.innerHTML = '<div class="text-xs text-gray-400">Belum ada preview.</div>';
                return;
            }
            
            targetEl.dataset.rendered = '0';
            window.renderLatexPreview('#answer_latex', '#answerLatexPreviewModal', {
                targetType: 'answer' // Usually answer in this context
            });
        });
    </script>
@endpush