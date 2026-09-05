<div wire:ignore.self id="modal-import-question"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-2xl shadow-2xl w-full transform transition-all scale-95 duration-300 ease-out animate-fade-in"
        style="max-width: 100vh">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Import Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600 overflow-auto" style="max-height: 80vh">
            {{-- <div class="mb-4">
                <label for="study_id_import" class="block text-sm font-medium text-gray-700">Prodi <span
                        class="text-red-600">*</span></label>
                <select class="mt-1 form-control" wire:model='study_id_import'>
                    <option value="">Pilih prodi</option>
                    @foreach ($studys as $key_study => $study)
                    <option value="{{ $key_study }}">{{ $study }}</option>
                    @endforeach
                </select>
                @error('study_id_import')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Import Soal</label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="import_type" value="pg" class="form-radio text-primary">
                        <span class="ml-2 text-sm text-gray-700">Pilihan Ganda</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model.live="import_type" value="essay" class="form-radio text-primary">
                        <span class="ml-2 text-sm text-gray-700">Essay</span>
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <label for="file_import" class="block text-sm font-medium text-gray-700">File Excel</label>
                    @if ($import_type == 'pg')
                        <a href="{{ asset('import/Template soal CBT.xlsx') }}" download="Template Import Soal PG.xlsx"
                            class="text-xs font-medium text-primary hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Template PG
                        </a>
                    @else
                        <a href="{{ asset('import/Template soal CBT Essay.xlsx') }}" download="Template Import Soal Essay.xlsx"
                            class="text-xs font-medium text-primary hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Download Template Essay
                        </a>
                    @endif
                </div>
                {{-- <input type="file" id="file_import" wire:model.defer="file_import" placeholder=""
                    class="mt-1 form-control"> --}}
                <x-filepond::upload wire:model="file_import" accept="" />
                @error('file_import')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='importQuestion()'
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>