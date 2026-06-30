@section('title', 'Data Modul')
<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    @include('livewire.admin.master.module.admin-master-module-question-modal')

    {{-- Loading Screen --}}
    <div wire:loading wire:target="submitModule"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
        <div class="flex flex-col items-center justify-center space-y-4">
            <div class="animate-spin rounded-full h-16 w-16 border-4 border-gray-200 border-t-primary"></div>
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-800">Menyimpan data dan menyinkronkan soal...</p>
                <p class="text-sm text-gray-500 mt-2">Harap tunggu, jangan tutup halaman ini</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Data Modul</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="submitModule()" wire:loading.attr="disabled" wire:target="submitModule"
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}">
                    <span wire:target="submitModule">
                        Simpan Perubahan
                    </span>
                </button>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="p-5 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Detail Modul</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                <div>
                    <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe Ujian<span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            onChange: function(e) {
                                @this.set('question_type_id', e ? e : '');
                            }
                        });" wire:model.live="question_type_id" id="question_type_id" {{ !empty($module_questions) ? 'disabled' : '' }}>
                            <option value="">Pilih Topik Soal</option>
                            @foreach ($question_types as $question_type)
                                <option value="{{ $question_type?->id }}">{{ $question_type?->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('topic_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="question_pick_type" class="block text-sm font-medium text-gray-700">Tipe Pengambilan
                        Soal<span class="text-red-600">*</span></label>
                    <select id="question_pick_type" class="mt-1 form-control" wire:model.live="question_pick_type">
                        <option value="manual">Manual</option>
                        <option value="category">Kategori Soal</option>
                        <option value="topic">Topik</option>
                        <option value="material_category">Kategori Materi</option>
                    </select>
                    @error('question_pick_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    </div>
                    @if ($question_pick_type !== 'manual')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Ambil Semua Soal?</label>
                            <div class="flex items-center mt-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="is_all_questions" class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                    </div>
                                </label>
                            </div>
                            @error('is_all_questions')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                    <div class="grid grid-cols-2 gap-4">
                    <div class="mb-1">
                        <label for="duration" class="block text-sm font-medium text-gray-700">Durasi Pengerjaan <span
                                class="text-red-600">*</span></label>
                        <div class="relative mt-1">
                            <input type="number" id="duration" wire:model.defer="duration"
                                placeholder="Durasi Pengerjaan" class="mt-1 form-control" min="0">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center p-2 pointer-events-none text-gray-500 text-sm">
                                Menit</div>
                        </div>
                        @error('duration')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label for="random_question" class="block text-sm font-medium text-gray-700">Acak Soal <span
                                class="text-red-600">*</span></label>
                        <div class="flex items-center mt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="random_question" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>
                        @error('random_question')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Modul Soal <span
                            class="text-red-600">*</span></label>
                    <input type="text" id="name" wire:model.defer="name" placeholder="Nama Modul Soal"
                        class="mt-1 form-control">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Modul
                        Soal</label>
                    <textarea id="description" wire:model="description" placeholder="Deskripsi modul soal..."
                        class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mb-4">
                <label for="is_all_study" class="block text-sm font-medium text-gray-700">Semua Prodi? <span
                        class="text-red-600">*</span></label>
                <div class="flex items-center mt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input disabled type="checkbox" wire:model.live="is_all_study" class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
                @error('is_all_study')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            @if (!$is_all_study)
                <div class="mb-4">
                    <label for="studys" class="block text-sm font-medium text-gray-700">Prodi <span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select disabled class="mt-1 form-control w-full" x-data x-ref="input" x-init="$($refs.input).selectize({
                                            dropdownParent: 'body',
                                            allowClear: true,
                                            onChange: function(e) {
                                                @this.set('studys', e ? e : '');
                                            }
                                        });" wire:model.live="studys" id="studys" multiple>
                            <option value="">Pilih Prodi</option>
                            @foreach ($get_studys as $key_get_study => $get_study)
                                <option value="{{ $key_get_study }}">{{ $get_study }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('studys')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif
            @if ($question_pick_type === 'category' && !$is_all_questions)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Pengaturan Kategori Soal</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari Kategori Soal..." wire:model.live="searchCategory">
                    </div>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Kategori Soal</th>
                                    <th>Default</th>
                                    <th>Easy</th>
                                    <th>Medium</th>
                                    <th>Hard</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filteredCategoryQuestions as $category_question)
                                    @php
                                        $settings = $category_question_settings[$category_question->id] ?? ['enabled' => false];
                                        $limits = $category_question_limits[$category_question->id] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];
                                    @endphp
                                    <tr wire:key="view-cat-{{ $category_question->id }}">
                                        <td class="center">
                                            <input type="checkbox"
                                                wire:model.live="category_question_settings.{{ $category_question->id }}.enabled">
                                        </td>
                                        <td>{{ $category_question->name }}</td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="category_question_settings.{{ $category_question->id }}.default"
                                                max="{{ $limits['default'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['default'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['default'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="category_question_settings.{{ $category_question->id }}.easy"
                                                max="{{ $limits['easy'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['easy'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['easy'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="category_question_settings.{{ $category_question->id }}.medium"
                                                max="{{ $limits['medium'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['medium'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['medium'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="category_question_settings.{{ $category_question->id }}.hard"
                                                max="{{ $limits['hard'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['hard'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['hard'] ?? 0 }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="no-data">Tidak ada kategori soal</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($question_pick_type === 'topic' && !$is_all_questions)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Pengaturan Topik Soal</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari Topik Soal..." wire:model.live="searchTopic">
                    </div>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Topik</th>
                                    <th>Default</th>
                                    <th>Easy</th>
                                    <th>Medium</th>
                                    <th>Hard</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filteredTopics as $topic)
                                    @php
                                        $settings = $topic_question_settings[$topic->id] ?? ['enabled' => false];
                                        $limits = $topic_question_limits[$topic->id] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];
                                    @endphp
                                    <tr wire:key="view-topic-{{ $topic->id }}">
                                        <td class="center">
                                            <input type="checkbox"
                                                wire:model.live="topic_question_settings.{{ $topic->id }}.enabled">
                                        </td>
                                        <td>{{ $topic->name }}</td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="topic_question_settings.{{ $topic->id }}.default"
                                                max="{{ $limits['default'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['default'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['default'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="topic_question_settings.{{ $topic->id }}.easy"
                                                max="{{ $limits['easy'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['easy'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['easy'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="topic_question_settings.{{ $topic->id }}.medium"
                                                max="{{ $limits['medium'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['medium'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['medium'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="topic_question_settings.{{ $topic->id }}.hard"
                                                max="{{ $limits['hard'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['hard'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['hard'] ?? 0 }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="no-data">Tidak ada topik</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($question_pick_type === 'material_category' && !$is_all_questions)
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">Pengaturan Kategori Materi</label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                        <div>
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Kategori Materi..." wire:model.live="searchMaterialCategory">
                        </div>
                        <div>
                            <select class="form-control form-control-sm" wire:model.live="filterMaterialCategoryTopicId">
                                <option value="">Semua Topik</option>
                                @foreach ($topics as $topic)
                                    <option value="{{ $topic->id }}">{{ $topic->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pilih</th>
                                    <th>Kategori Materi</th>
                                    <th>Default</th>
                                    <th>Easy</th>
                                    <th>Medium</th>
                                    <th>Hard</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($filteredMaterialCategories as $material_category)
                                    @php
                                        $settings = $material_category_question_settings[$material_category->id] ?? ['enabled' => false];
                                        $limits = $material_category_question_limits[$material_category->id] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];
                                    @endphp
                                    <tr wire:key="view-mat-{{ $material_category->id }}">
                                        <td class="center">
                                            <input type="checkbox"
                                                wire:model.live="material_category_question_settings.{{ $material_category->id }}.enabled">
                                        </td>
                                        <td>{{ $material_category->name }}</td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="material_category_question_settings.{{ $material_category->id }}.default"
                                                max="{{ $limits['default'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['default'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['default'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="material_category_question_settings.{{ $material_category->id }}.easy"
                                                max="{{ $limits['easy'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['easy'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['easy'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="material_category_question_settings.{{ $material_category->id }}.medium"
                                                max="{{ $limits['medium'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['medium'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['medium'] ?? 0 }}</div>
                                        </td>
                                        <td class="center">
                                            <input type="number" min="0" class="mt-1 form-control w-24 text-center"
                                                wire:model.live="material_category_question_settings.{{ $material_category->id }}.hard"
                                                max="{{ $limits['hard'] ?? 0 }}" @disabled(!($settings['enabled'] ?? false) || ($limits['hard'] ?? 0) === 0)>
                                            <div class="text-xs text-gray-500 mt-1">Max: {{ $limits['hard'] ?? 0 }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="no-data">Tidak ada kategori materi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

        <div class="p-5 bg-white shadow rounded-lg mt-4">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">Data Soal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-700">Tampil</span>
                        <select class="mt-1 form-control" wire:model.live='perPageModule'>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm text-gray-700">data</span>
                    </div>
                </div>
                <div class="flex items-center w-full sm:w-auto gap-2">
                    <div class="relative w-full sm:w-64">
                        <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                            wire:model.live='search'>
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                        </div>
                    </div>
                    <button wire:click="exportPdf()" wire:loading.attr="disabled" class="mt-1 px-3 py-2 btn btn-primary flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export PDF
                    </button>
                    @if ($question_pick_type === 'manual')
                        <button wire:click="modalModuleQuestion()" class="mt-1 px-3 py-2 btn btn-warning">
                            Tambah
                        </button>
                    @endif
                </div>
            </div>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="w-1 center">No</th>
                            <th>Prodi</th>
                            <th>Tipe Ujian</th>
                            <th>Pertanyaan</th>
                            <th>Deskripsi</th>
                            <th class="w-1 center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($module_questions as $index => $result)
                            <tr>
                                <td class="center">{{ $module_questions->firstItem() + $index }}</td>
                                <td>{{ $result?->question?->study?->name }}</td>
                                <td>{{ $result?->question?->questionType?->name }}</td>
                                <td><div class="rich-content">{!! $result?->question?->question !!}</div></td>
                                <td><div class="rich-content">{!! $result?->question?->description !!}</div></td>
                                <td class="center">
                                    <div class="flex items-center">
                                        <a class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                            target="_blank"
                                            href="{{ route('admin.master.question.update', $result->question_id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if ($question_pick_type === 'manual')
                                            <button
                                                class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                                wire:click="confirmDelete('{{ $result->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
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
            <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Menampilkan <span class="font-medium">{{ $module_questions->firstItem() }}</span> sampai <span
                            class="font-medium">{{ $module_questions->lastItem() }}</span> dari <span
                            class="font-medium">{{ $module_questions->total() }}</span> hasil
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            {{ $module_questions->links('vendor.livewire.custom') }} <!-- Menampilkan pagination -->
                        </nav>
                    </div>
                </div>
            </div>
        </div>
</div>