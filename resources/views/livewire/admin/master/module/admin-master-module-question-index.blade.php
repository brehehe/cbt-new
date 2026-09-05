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
            <div class="flex items-center gap-2">
                <button wire:click="syncAllActiveTimetables()" wire:loading.attr="disabled" wire:target="syncAllActiveTimetables"
                    class="btn btn-secondary">
                    <span wire:target="syncAllActiveTimetables">
                        Sinkronkan ke Ujian Peserta
                    </span>
                </button>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe Ujian <span
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
                        Soal <span class="text-red-600">*</span></label>
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
                    <div class="md:col-span-2">
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

                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Modul Soal <span
                            class="text-red-600">*</span></label>
                    <input type="text" id="name" wire:model.defer="name" placeholder="Nama Modul Soal"
                        class="mt-1 form-control">
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- 3-Column Balanced Settings across Full Width --}}
                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-gray-50/80 border border-gray-200/80 rounded-xl">
                    <div>
                        <label for="duration" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                            Durasi Pengerjaan <span class="text-red-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="duration" wire:model.defer="duration"
                                placeholder="Durasi" class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-14" min="1">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 text-xs font-medium">
                                Menit
                            </div>
                        </div>
                        @error('duration')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_questions" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                            Jumlah Soal Peserta <span class="text-[11px] text-gray-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="total_questions" wire:model.defer="total_questions"
                                placeholder="Contoh: 100" class="w-full text-sm rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-14" min="1">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400 text-xs font-medium">
                                Soal
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-0.5">Batas soal ujian (Kosongkan = Semua).</p>
                        @error('total_questions')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="random_question" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                            Acak Soal
                        </label>
                        <div class="flex items-center gap-3 mt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model="random_question" class="sr-only peer">
                                <div
                                    class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                            <span class="text-xs text-gray-500 font-medium">{{ $random_question ? 'Acak' : 'Urut' }}</span>
                        </div>
                        @error('random_question')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Modul Soal</label>
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
            @php
                $currentPageModuleIds = ($module_questions instanceof \Illuminate\Pagination\LengthAwarePaginator || $module_questions instanceof \Illuminate\Pagination\Paginator) ? $module_questions->pluck('id')->toArray() : [];
                $isAllPageModuleSelected = !empty($currentPageModuleIds) && collect($currentPageModuleIds)->every(fn($id) => isset($selected_module_questions[$id]));
                $selectedModuleCount = count($selected_module_questions);
                $totalModuleCount = $module_questions->total() ?? 0;
            @endphp

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
                <div class="flex flex-wrap items-center w-full sm:w-auto gap-2">
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
                        @if ($selectedModuleCount > 0)
                            <button wire:click="confirmDeleteSelected"
                                wire:loading.attr="disabled"
                                class="mt-1 px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-trash-alt text-xs"></i>
                                <span>Hapus Terpilih ({{ $selectedModuleCount }})</span>
                            </button>
                        @endif
                        <button wire:click="modalModuleQuestion()" class="mt-1 px-3 py-2 btn btn-warning">
                            Tambah
                        </button>
                    @endif
                </div>
            </div>

            @if ($question_pick_type === 'manual' && $totalModuleCount > 0)
                <!-- Quick Batch Selection Controls Bar -->
                <div class="flex flex-wrap items-center justify-between gap-3 p-3 bg-slate-50 border border-slate-200/80 rounded-xl mb-4 text-xs">
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center gap-2 px-2.5 py-1.5 bg-white border border-gray-200 rounded-lg shadow-2xs font-semibold text-gray-700 cursor-pointer hover:bg-gray-50 transition select-none">
                            <input type="checkbox" 
                                class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                wire:click="toggleSelectAllModuleQuestions(@js($currentPageModuleIds))"
                                {{ $isAllPageModuleSelected ? 'checked' : '' }}>
                            <span>Pilih Semua di Halaman Ini ({{ count($currentPageModuleIds) }})</span>
                        </label>

                        @if ($totalModuleCount > count($currentPageModuleIds))
                            <button type="button" 
                                wire:click="selectAllModuleQuestionsFiltered"
                                class="flex items-center gap-1.5 px-2.5 py-1.5 bg-white border border-blue-200 text-blue-700 rounded-lg shadow-2xs font-semibold hover:bg-blue-50 transition cursor-pointer">
                                <i class="fas fa-check-double text-blue-600"></i>
                                <span>Pilih Semua Total ({{ $totalModuleCount }} Soal)</span>
                            </button>
                        @endif

                        @if ($selectedModuleCount > 0)
                            <button type="button"
                                wire:click="confirmDeleteSelected"
                                class="flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow-2xs font-semibold transition cursor-pointer">
                                <i class="fas fa-trash-alt text-xs"></i>
                                <span>Hapus Terpilih ({{ $selectedModuleCount }})</span>
                            </button>
                            <button type="button"
                                wire:click="deselectAllModuleQuestions"
                                class="flex items-center gap-1 px-2.5 py-1.5 bg-white border border-gray-200 text-gray-600 rounded-lg shadow-2xs font-medium hover:bg-gray-50 transition cursor-pointer">
                                <span>Kosongkan Pilihan</span>
                            </button>
                        @endif
                    </div>

                    <div class="text-gray-500 font-medium flex items-center gap-1.5">
                        <span>Total Soal:</span>
                        <span class="font-bold text-gray-800">{{ $totalModuleCount }}</span>
                        @if ($selectedModuleCount > 0)
                            <span>·</span>
                            <span class="text-red-600 font-bold">{{ $selectedModuleCount }} Dipilih untuk Dihapus</span>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-xs border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/80 border-b border-gray-200 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                <th class="py-3.5 px-4 w-16 text-center">
                                    @if ($question_pick_type === 'manual')
                                        <div class="flex flex-col items-center justify-center gap-1">
                                            <input type="checkbox" 
                                                class="form-checkbox h-4.5 w-4.5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
                                                wire:click="toggleSelectAllModuleQuestions(@js($currentPageModuleIds))"
                                                {{ $isAllPageModuleSelected ? 'checked' : '' }}
                                                title="Pilih / Batalkan semua soal di halaman ini">
                                            <span class="text-[10px] text-gray-400 font-normal">Semua</span>
                                        </div>
                                    @else
                                        <span>No</span>
                                    @endif
                                </th>
                                <th class="py-3.5 px-6">Pertanyaan & Pilihan Jawaban</th>
                                <th class="py-3.5 px-4 w-28 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($module_questions as $index => $result)
                                @php
                                    $q = $result?->question;
                                    $isSelectedForDelete = isset($selected_module_questions[$result->id]);
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors {{ $isSelectedForDelete ? 'bg-red-50/40' : '' }}">
                                    <!-- Number / Checkbox Column -->
                                    <td class="py-5 px-4 text-center align-top">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            @if ($question_pick_type === 'manual')
                                                <input type="checkbox" 
                                                    class="form-checkbox h-4.5 w-4.5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer transition"
                                                    wire:click="toggleSelectModuleQuestion('{{ $result->id }}')"
                                                    {{ $isSelectedForDelete ? 'checked' : '' }}
                                                    title="Pilih soal untuk dihapus">
                                            @endif
                                            <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold text-[11px] border border-slate-200">
                                                #{{ $module_questions->firstItem() + $index }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Question & Answers Column -->
                                    <td class="py-5 px-6 align-top">
                                        <div class="flex flex-col gap-3">
                                            <!-- Badges Metadata Row -->
                                            <div class="flex flex-wrap gap-1.5 items-center">
                                                @if($q?->study?->name)
                                                    <span class="px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-200/60">
                                                        Prodi: {{ $q->study->name }}
                                                    </span>
                                                @endif
                                                @if($q?->categoryQuestion?->name)
                                                    <span class="px-2.5 py-0.5 rounded-md bg-purple-50 text-purple-700 text-[10px] font-semibold border border-purple-200/60">
                                                        Kat: {{ $q->categoryQuestion->name }}
                                                    </span>
                                                @endif
                                                @if($q?->questionType?->name)
                                                    <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-semibold border border-slate-200">
                                                        Tipe: {{ $q->questionType->name }}
                                                    </span>
                                                @endif
                                                
                                                <!-- Difficulty Badge -->
                                                @if($q?->difficulty == 'easy')
                                                    <span class="px-2.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200/60 uppercase tracking-wide">Easy</span>
                                                @elseif($q?->difficulty == 'medium')
                                                    <span class="px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200/60 uppercase tracking-wide">Medium</span>
                                                @elseif($q?->difficulty == 'hard')
                                                    <span class="px-2.5 py-0.5 rounded-md bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200/60 uppercase tracking-wide">Hard</span>
                                                @else
                                                    <span class="px-2.5 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[10px] font-bold border border-gray-200 uppercase tracking-wide">Default</span>
                                                @endif

                                                <!-- Question Type Badges -->
                                                @if(($q?->type ?? 'single') == 'single')
                                                    <span class="px-2.5 py-0.5 rounded-md bg-sky-50 text-sky-700 text-[10px] font-bold border border-sky-200/60">Pilihan Ganda</span>
                                                @elseif($q?->type == 'multiple')
                                                    <span class="px-2.5 py-0.5 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200/60">Pilihan Ganda Kompleks</span>
                                                @else
                                                    <span class="px-2.5 py-0.5 rounded-md bg-violet-50 text-violet-700 text-[10px] font-bold border border-violet-200/60">Essay</span>
                                                @endif
                                            </div>

                                            <!-- Question Text -->
                                            <div class="rich-content text-[13.5px] text-gray-900 font-medium leading-relaxed">
                                                {!! $q?->question !!}
                                            </div>

                                            <!-- Question Images/Files -->
                                            @php
                                                $qImages = is_array($q?->images) ? $q->images : json_decode($q?->images ?? '[]', true);
                                            @endphp
                                            @if (!empty($qImages) && collect($qImages)->isNotEmpty())
                                                <div class="flex flex-wrap gap-2 pt-1">
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

                                            <!-- Answer Options List -->
                                            <div class="space-y-2 border-t border-gray-100 pt-3">
                                                @if($q?->type !== 'essay' && $q?->answers && $q->answers->isNotEmpty())
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                                                        @foreach($q->answers->sortBy('alphabet') as $answer)
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
                                                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs shrink-0 self-center">
                                                                        <i class="fas fa-check text-[10px]"></i>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($q?->type === 'essay')
                                                    @php
                                                        $essayAnswer = $q?->answers?->first();
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

                                            <!-- Description / Guide if available -->
                                            @if(!empty(trim(strip_tags($q?->description))))
                                                <div class="p-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-xs text-slate-600">
                                                    <span class="font-bold text-slate-700 flex items-center gap-1.5 mb-0.5">
                                                        <i class="fas fa-info-circle text-blue-500 text-[11px]"></i> Petunjuk / Deskripsi Soal:
                                                    </span>
                                                    <div class="rich-content leading-relaxed pl-4">
                                                        {!! $q->description !!}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Action Column -->
                                    <td class="py-5 px-4 text-center align-top">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <a class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center shadow-2xs"
                                                target="_blank"
                                                title="Edit Soal"
                                                href="{{ route('admin.master.question.update', $result->question_id) }}">
                                                <i class="fas fa-pen-to-square text-xs"></i>
                                            </a>
                                            @if ($question_pick_type === 'manual')
                                                <button
                                                    class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center shadow-2xs cursor-pointer"
                                                    title="Hapus dari Modul"
                                                    wire:click="confirmDelete('{{ $result->id }}')">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                            <span>Tidak ada data soal dalam modul ini</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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