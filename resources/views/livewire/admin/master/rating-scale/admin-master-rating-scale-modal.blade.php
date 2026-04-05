<div wire:ignore.self id="modal-rating-scale"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-primary bg-opacity-10 flex items-center justify-center">
                        <i class="fa-solid fa-sliders text-xl text-primary"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Skala Penilaian</h2>
                        <p class="text-sm text-gray-500">Atur parameter grade nilai</p>
                    </div>
                </div>
                <button wire:click="closeModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8 space-y-6">
            <div class="space-y-2">
                <label for="grade_letter" class="block text-sm font-bold text-gray-700">Nama Grade Letter <span class="text-red-500">*</span></label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                        <i class="fa-solid fa-font"></i>
                    </div>
                    <input type="text" id="grade_letter" wire:model.defer="grade_letter" placeholder="Contoh : A"
                        class="!pl-12 w-full form-control focus:ring-2 focus:ring-primary focus:ring-opacity-20 border-gray-200">
                </div>
                @error('grade_letter')
                    <p class="text-xs text-red-500 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="min_score" class="block text-sm font-bold text-gray-700">Nilai Minimum <span class="text-red-500">*</span></label>
                    <input type="number" id="min_score" wire:model.defer="min_score" placeholder="0"
                        class="w-full form-control focus:ring-2 focus:ring-primary focus:ring-opacity-20 border-gray-200">
                    @error('min_score')
                        <p class="text-xs text-red-500 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="space-y-2">
                    <label for="max_score" class="block text-sm font-bold text-gray-700">Nilai Maksimum <span class="text-red-500">*</span></label>
                    <input type="number" id="max_score" wire:model.defer="max_score" placeholder="100"
                        class="w-full form-control focus:ring-2 focus:ring-primary focus:ring-opacity-20 border-gray-200">
                    @error('max_score')
                        <p class="text-xs text-red-500 font-medium ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-sm font-bold text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                <textarea id="description" wire:model.defer="description" rows="3" placeholder="Masukkan deskripsi..."
                    class="w-full form-control focus:ring-2 focus:ring-primary focus:ring-opacity-20 border-gray-200 py-3"></textarea>
                @error('description')
                    <p class="text-xs text-red-500 font-medium ml-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-end gap-3">
            <button wire:click="closeModal()"
                class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-8 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </div>
</div>
