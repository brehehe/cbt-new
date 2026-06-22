<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 100vh;">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Modul Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600" style="max-height: 80vh; overflow-y: auto;">
            <div class="grid grid-cols-1 gap-4">
                <div class="mb-4">
                    <div class="mb-4">
                        <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe Ujian <span
                                class="text-red-600">*</span></label>
                        <select class="mt-1 form-control" wire:model='question_type_id'>
                            <option value="">Pilih Tipe Ujian</option>
                            @foreach ($question_types as $question_type)
                                <option {{ $question_type?->id == $question_type_id ? 'selected' : '' }}
                                    value="{{ $question_type?->id }}">{{ $question_type?->name }}</option>
                            @endforeach
                        </select>
                        @error('question_type_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
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
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Modul Soal <span
                                class="text-red-600">*</span></label>
                        <input type="text" id="name" wire:model.defer="name" placeholder="Nama Modul Soal"
                            class="mt-1 form-control">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="duration" class="block text-sm font-medium text-gray-700">Durasi Pengerjaan
                                <span class="text-red-600">*</span></label>
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
                        <div class="mb-4">
                            <label for="random_question" class="block text-sm font-medium text-gray-700">Acak Soal
                            </label>
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
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Modul
                            Soal</label>
                        <textarea id="description" wire:model.defer="description" placeholder=""
                            class="mt-1 form-control"></textarea>
                        @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label for="is_all_study" class="block text-sm font-medium text-gray-700">Semua Prodi? <span
                                    class="text-red-600">*</span></label>
                            <div class="flex items-center mt-2">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model.live="is_all_study" class="sr-only peer">
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
                            <div>
                                <label for="studys" class="block text-sm font-medium text-gray-700">Prodi <span
                                        class="text-red-600">*</span></label>
                                <div wire:key="select-{{ rand() }}" class="w-full">
                                    <select class="mt-1 form-control w-full" x-data x-ref="input" x-init="$($refs.input).selectize({
                                            dropdownParent: 'body',
                                            allowClear: true,
                                            plugins: ['clear_button'],
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
                    </div>
                </div>
                @if ($question_pick_type === 'category' && !$is_all_questions)
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700">Pengaturan Kategori Soal</label>
                            <span class="text-sm text-gray-600">
                                Total soal: <span class="font-semibold text-blue-600">{{ $this->totalQuestions }}</span>
                            </span>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Kategori Soal..." wire:model.live="searchCategory">
                        </div>
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Pilih</th>
                                        <th rowspan="2">Kategori Soal</th>
                                        <th colspan="4">Difficulty</th>
                                    </tr>
                                    <tr>
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
                                        <tr wire:key="modal-cat-{{ $category_question->id }}">
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
                                        <!-- <tr>
                                                    <td colspan="6" class="text-xs text-gray-500">
                                                        Soal tanpa difficulty: {{ $limits['default'] ?? 0 }}
                                                    </td>
                                                </tr> -->
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
                            <span class="text-sm text-gray-600">
                                Total soal: <span
                                    class="font-semibold text-blue-600">{{ $this->totalTopicQuestions }}</span>
                            </span>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" placeholder="Cari Topik Soal..." wire:model.live="searchTopic">
                        </div>
                        <div class="overflow-x-auto border rounded-lg">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">Pilih</th>
                                        <th rowspan="2">Topik</th>
                                        <th colspan="4">Difficulty</th>
                                    </tr>
                                    <tr>
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
                                        <tr wire:key="modal-topic-{{ $topic->id }}">
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
                                            <td colspan="6" class="no-data">Tidak ada topik soal</td>
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
                            <span class="text-sm text-gray-600">
                                Total soal: <span
                                    class="font-semibold text-blue-600">{{ $this->totalMaterialCategoryQuestions }}</span>
                            </span>
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
                                        <th rowspan="2">Pilih</th>
                                        <th rowspan="2">Kategori Materi</th>
                                        <th colspan="4">Difficulty</th>
                                    </tr>
                                    <tr>
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
                                        <tr wire:key="modal-mat-{{ $material_category->id }}">
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

        <!-- Footer -->
        <div class="flex justify-between items-center px-6 py-4 border-t">
            <div>
                @if ($data_id)
                    <button type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition flex items-center gap-2"
                        wire:click="openViewQuestionsModal('{{ $data_id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Soal
                    </button>
                @endif
            </div>
            <div class="flex gap-2">
                <button wire:click="closeModal()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                    Batal
                </button>
                <button wire:click='submit'
                    class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>