@section('title', 'Ubah Data Soal')
<div>
    {{-- In work, do what you enjoy. --}}
    @include('livewire.admin.master.question.admin-master-question-update-modal-answer')
    @include('livewire.admin.master.question.admin-master-question-update-modal-answer-images')
    @include('livewire.admin.master.question.admin-master-question-update-modal-images')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold {{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'text-[#2b7fff]' : 'text-[#f58634]' }}">
                    Ubah Data Soal</h1>
                {{-- <p class="text-gray-600">Kelola produk yang tersedia di toko Anda dengan mudah.</p> --}}
            </div>
            <div>
                <button wire:click="submitQuestion()"
                    class="{{ in_array(config('app.name_slug'), ['ups_tegal', 'unimma','unidayan']) ? 'btn btn-primary' : 'btn btn-warning' }}">
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
            <div class="mb-4">
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                        });"
                            wire:model.live="topic_id" id="topic_id">
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
                        });"
                            wire:model.live="material_category_id" id="material_category_id">
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
                        });"
                            wire:model.live="material_id" id="material_id">
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
                    <label for="question_type_id" class="block text-sm font-medium text-gray-700">Tipe Ujian<span
                            class="text-red-600">*</span></label>
                    <div wire:key="select-{{ rand() }}">
                        <select class="mt-1 form-control" x-data x-ref="input" x-init="$($refs.input).selectize({
                            dropdownParent: 'body',
                            allowClear: true,
                            plugins: ['clear_button'],
                            onChange: function(e) {
                                @this.set('question_type_id', e ? e : '');
                            }
                        });"
                            wire:model.live="question_type_id" id="question_type_id">
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
                <div class="md:col-span-2">
                    <label for="question" class="block text-sm font-medium text-gray-700">Pertanyaan<span
                            class="text-red-600">*</span></label>
                    <textarea id="question" wire:model="question" placeholder="Pertanyaan..." class="mt-1 form-control"></textarea>
                    @error('question')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="question" class="block text-sm font-medium text-gray-700">Gambar<span
                            class="text-red-600">*</span></label>
                    <button class="btn btn-warning mt-1" wire:click='modalImages()'>Gambar</button>
                </div>
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi
                        Pertanyaan</label>
                    <textarea id="description" wire:model="description" placeholder="Deskripsi pertanyaan..." class="mt-1 form-control"></textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        {{-- card --}}
        <div class="p-4 bg-white shadow rounded-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4">
                <div class="flex items-center">
                    {{-- <span class="text-sm text-gray-700 mr-2">Tampil</span>
                    <select class="mt-1 form-control" wire:model.live='perPage'>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="text-sm text-gray-700 ml-2">data</span> --}}
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
                                <td class="center">{{ chr(64 + $loop->iteration) }} </td>
                                <td>
                                    <button class="btn btn-warning mt-1"
                                        wire:click="modalAnswerImage('{{ $result?->id }}', '{{ chr(64 + $loop->iteration) }}')">Gambar</button>
                                </td>
                                <td>{{ $result?->context }}</td>
                                <td>
                                    <div class="flex items-center mt-2" wire:key="{{ rand() }}">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                wire:click="toggleAnswerCorrect('{{ $result->id }}')"
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
    <script>
        FilePond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType
        );

        const pond = FilePond.create(document.querySelector('#images'), {
            allowMultiple: true,
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/webp'],
        });

        const pond = FilePond.create(document.querySelector('#answer_images'), {
            allowMultiple: true,
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/webp'],
        });
    </script>
@endpush
