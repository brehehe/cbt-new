@section('title', 'Bank Soal')
<div>
    {{-- Stop trying to control. --}}
    @include('livewire.admin.master.question.admin-master-question-index-modal-import')
    @include('livewire.admin.master.question.admin-master-question-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary }}]">
                    Data Bank Soal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                @if (config('app.import_question'))
                    <button wire:click="openModalImport()" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-file-import h-4 w-4"">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M5 13v-8a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2h-5.5m-9.5 -2h7m-3 -3l3 3l-3 3" />
                        </svg>
                        Import
                    </button>
                @endif
                <button wire:click="openModal()"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
    </div>
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
    <!-- Table Controls -->
   <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12" wire:model.live='perPage'>
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
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-[{{ $companyData->color_primary ?? '#2b7fff' }}] focus:border-[{{ $companyData->color_primary ?? '#2b7fff' }}] sm:text-sm transition duration-150 ease-in-out" 
                    placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
            </div>
        </div>
    </div>
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center gap-3">
            <label class="inline-flex items-center text-sm text-gray-700">
                <input type="checkbox" class="form-checkbox" wire:model.live="selectAll">
                <span class="ml-2">Pilih semua di halaman ini</span>
            </label>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <select class="mt-1 form-control" wire:model.live="bulkCategoryQuestionId">
                <option value="">Pilih Kategori Soal</option>
                @foreach ($category_questions as $category_question)
                    <option value="{{ $category_question->id }}">{{ $category_question->name }}</option>
                @endforeach
            </select>
            <button wire:click="applyBulkCategory" class="btn btn-primary" type="button">
                Terapkan
            </button>
        </div>
    </div>
    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="form-checkbox" wire:model.live="selectAll">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik
                            Soal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori Soal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pertanyaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Difficulty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($questions as $index => $result)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <input type="checkbox" class="form-checkbox" wire:model.live="selectedQuestions"
                                    value="{{ $result->id }}">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $questions->firstItem() + $index }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $result?->study?->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $result?->topic?->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $result?->categoryQuestion?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs" x-data="{ expanded: false }">
                                <span x-show="!expanded" class="block truncate">{{ Str::limit($result?->question, 50) }}</span>
                                <span x-show="expanded" class="block">{{ $result?->question }}</span>
                                <button type="button" class="mt-1 text-xs text-blue-600 hover:text-blue-800" @click="expanded = !expanded">
                                    <span x-show="!expanded">Show</span>
                                    <span x-show="expanded">Hide</span>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $result?->difficulty == 'default' ? '-' : ucfirst($result?->difficulty) }}
                            <td class="center">
                                <div class="flex items-center">
                                    <a class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        href="{{ route('admin.master.question.update', $result) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
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

@push('scripts')
    <script>
        (function() {
            if (window.__latexServerPreviewInit) return;
            window.__latexServerPreviewInit = true;

            async function renderLatexPreview(sourceSelector, targetSelector, meta = {}) {
                const sourceEl = document.querySelector(sourceSelector);
                const targetEl = document.querySelector(targetSelector);
                if (!sourceEl || !targetEl) return;

                const latex = sourceEl.value || '';
                if (!latex.trim()) {
                    targetEl.innerHTML = '<div class="text-xs text-gray-400">LaTeX kosong.</div>';
                    return;
                }

                targetEl.innerHTML = '<div class="text-xs text-gray-500">Rendering...</div>';

                const csrf = document.querySelector('meta[name="csrf-token"]');
                const token = csrf ? csrf.getAttribute('content') : '';

                try {
                    const response = await fetch("{{ url('/admin/latex/preview') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            latex,
                            target_type: meta.targetType || null,
                            target_id: meta.targetId || null
                        })
                    });
                    const data = await response.json();
                    if (!response.ok || !data.ok) {
                        targetEl.innerHTML = `<div class="text-xs text-red-600">${data.message || 'Render gagal.'}</div>`;
                        return;
                    }

                    const pngUrl = data.png_url || '';
                    if (pngUrl) {
                        targetEl.innerHTML = `<img src="${pngUrl}" class="w-full h-auto rounded" alt="Preview" />`;
                    } else {
                        targetEl.innerHTML = '<div class="text-xs text-gray-500">Preview gambar tidak tersedia.</div>';
                    }
                } catch (err) {
                    targetEl.innerHTML = '<div class="text-xs text-red-600">Render gagal. Coba lagi.</div>';
                }
            }

            function autoRenderLatexPreviews() {
                document.querySelectorAll('[data-latex-render]').forEach((btn) => {
                    const sourceSelector = btn.getAttribute('data-latex-source');
                    const targetSelector = btn.getAttribute('data-latex-target');
                    if (!sourceSelector || !targetSelector) return;

                    const sourceEl = document.querySelector(sourceSelector);
                    const targetEl = document.querySelector(targetSelector);
                    if (!sourceEl || !targetEl) return;

                    const latex = (sourceEl.value || '').trim();
                    if (!latex) return;

                    if (targetEl.dataset.rendered === '1') return;
                    targetEl.dataset.rendered = '1';
                    renderLatexPreview(sourceSelector, targetSelector, {
                        targetType: btn.getAttribute('data-latex-type'),
                        targetId: btn.getAttribute('data-latex-id')
                    });
                });
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-latex-render]');
                if (!btn) return;
                const sourceSelector = btn.getAttribute('data-latex-source');
                const targetSelector = btn.getAttribute('data-latex-target');
                if (!sourceSelector || !targetSelector) return;
                renderLatexPreview(sourceSelector, targetSelector, {
                    targetType: btn.getAttribute('data-latex-type'),
                    targetId: btn.getAttribute('data-latex-id')
                });
            });

            document.addEventListener('DOMContentLoaded', autoRenderLatexPreviews);
            document.addEventListener('livewire:load', autoRenderLatexPreviews);
            document.addEventListener('livewire:navigated', autoRenderLatexPreviews);
            if (typeof Livewire !== 'undefined') {
                Livewire.hook('message.processed', autoRenderLatexPreviews);
            }
        })();
    </script>
@endpush
