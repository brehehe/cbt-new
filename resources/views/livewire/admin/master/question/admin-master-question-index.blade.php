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
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center gap-3 shrink-0">
            <label class="inline-flex items-center text-sm text-gray-700 whitespace-nowrap">
                <input type="checkbox" class="form-checkbox" wire:model.live="selectAll">
                <span class="ml-2 whitespace-nowrap">Pilih semua di halaman ini</span>
            </label>
            @if (count($selectedQuestions) > 0)
                <button wire:click="bulkDelete" wire:confirm="Apakah Anda yakin ingin menghapus soal terpilih?" class="btn btn-danger flex items-center gap-2 text-sm py-1 px-3 shrink-0" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus Terpilih ({{ count($selectedQuestions) }})
                </button>
            @endif
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori Soal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Difficulty</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[1%] text-center">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($questions as $index => $result)
                        @php
                            $globalIndex = $questions->firstItem() + $index;
                            $isRestricted = config('app.limit_question_view') && $globalIndex > config('app.limit_question_count', 5);
                        @endphp
                        <tr class="hover:bg-gray-50 {{ $isRestricted ? 'bg-gray-50/50' : '' }} relative">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <input type="checkbox" class="form-checkbox" wire:model.live="selectedQuestions"
                                    value="{{ $result->id }}" {{ $isRestricted ? 'disabled' : '' }}>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $globalIndex }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }}">
                                    {{ $result?->study?->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }}">
                                    {{ $result?->topic?->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }}">
                                    {{ $result?->categoryQuestion?->name ?? '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }}">
                                    @if (($result->type ?? 'single') == 'single')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Single
                                        </span>
                                    @elseif($result->type == 'multiple')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Multiple
                                        </span>
                                    @else
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Essay
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="{{ $isRestricted ? 'blur-[3px] select-none' : '' }}">
                                    {{ $result?->difficulty == 'default' ? '-' : ucfirst($result?->difficulty) }}
                                </div>
                            </td>
                            <td class="center">
                                @if ($isRestricted)
                                    <div class="flex flex-col items-center justify-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <span
                                            class="text-[8px] text-gray-400 font-semibold uppercase leading-tight text-center">
                                            Hubungi admin jika<br>ingin melihat detail soal
                                        </span>
                                    </div>
                                @else
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
                                @endif
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