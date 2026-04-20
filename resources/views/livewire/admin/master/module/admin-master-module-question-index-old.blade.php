@section('title', 'Data Modul')
<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    @include('livewire.admin.master.module.admin-master-module-question-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                    Data Modul</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="submitModule()"
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
            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700">Pengaturan Kategori Soal</label>
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
                            @forelse ($category_questions as $category_question)
                                @php
                                    $settings = $category_question_settings[$category_question->id] ?? ['enabled' => false];
                                    $limits = $category_question_limits[$category_question->id] ?? ['default' => 0, 'easy' => 0, 'medium' => 0, 'hard' => 0];
                                @endphp
                                <tr>
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
        </div>
        {{-- card --}}
        <div class="p-5 bg-white shadow rounded-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <div class="flex items-center">
                    <span class="text-sm text-gray-700 mr-2">Tampil</span>
                    <select class="mt-1 form-control" wire:model.live='perPage'>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data</span>
                    <div>
                        <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                            Data Soal</h1>
                        <!-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> -->
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
                    <button wire:click="modalModuleQuestion()" class="mt-1 px-3 py-2 btn btn-warning">
                        Tambah
                    </button>
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
                                <td class="center">{{ $loop->iteration }}</td>
                                <td>{{ $result?->question?->study?->name }}</td>
                                <td>{{ $result?->question?->questionType?->name }}</td>
                                <td>{!! $result?->question?->question !!}</td>
                                <td>{!! $result?->question?->description !!}</td>
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