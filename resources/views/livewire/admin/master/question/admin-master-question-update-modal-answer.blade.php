<div wire:ignore.self id="modal"
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
                <h2 class="text-xl font-semibold text-gray-800">Modal Bank Soal</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="answer_context" class="block text-sm font-medium text-gray-700">Konteks Jawaban <span
                        class="text-red-600">*</span></label>
                <textarea id="answer_context" wire:model.defer="answer_context" placeholder="" class="mt-1 form-control" data-autosize="true" rows="3" style="overflow:hidden;resize:none;" x-data x-init="$nextTick(() => { $el.style.height='auto'; $el.style.height=$el.scrollHeight+'px'; })" @focus="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'" @input="$el.style.height='auto';$el.style.height=$el.scrollHeight+'px'"></textarea>
                @error('answer_context')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-4">
                <label for="answer_correct" class="block text-sm font-medium text-gray-700">Jawaban yang benar</label>
                <div class="flex items-center mt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="answer_correct" class="sr-only peer">
                        <div
                            class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
                @error('answer_correct')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            {{-- <div class="mb-4">
                <label for="answer_description" class="block text-sm font-medium text-gray-700">Deskripsi Jawaban</label>
                <textarea id="answer_description" wire:model.defer="answer_description" placeholder="" class="mt-1 form-control"></textarea>
                @error('answer_description')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click="submitAnswer"
                class="px-4 py-2
        bg-[{{$companyData->color_primary}}] hover:bg-[{{$companyData->color_primary}}]
        text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
