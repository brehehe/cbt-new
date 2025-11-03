<div wire:ignore.self id="modal-start-exam"
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
                <h2 class="text-xl font-semibold text-gray-800">Modal Ujian</h2>
            </div>
            <button wire:click="closeModalStartExam()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700">Token <span
                        class="text-red-600">*</span></label>
                <input type="text" id="code" wire:model.defer="code" placeholder="Masukan Token Ujian"
                    class="mt-1 form-control">
                @error('code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalStartExam()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitStartExam'
                class="px-4 py-2 {{ config('app.name_slug') === 'ups_tegal' ? 'bg-[#2b7fff]' : 'bg-[#f58634]' }} hover:{{ config('app.name_slug') === 'ups_tegal' ? 'bg-[#2b7fff]' : 'bg-[#f58634]' }} text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>
