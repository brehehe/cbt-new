<div wire:ignore.self id="modal-regulation"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[{{ $companyData->color_primary ?? '#2b7fff' }}] bg-opacity-10 flex items-center justify-center border border-[{{ $companyData->color_primary ?? '#2b7fff' }}] border-opacity-20 shadow-sm">
                        <i class="fa-solid fa-book-open-reader text-xl text-[{{ $companyData->color_primary ?? '#2b7fff' }}]"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">Regulasi</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Master Data Regulasi</p>
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
                <label for="description" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Nama Regulasi <span class="text-red-500">*</span></label>
                <textarea id="description" wire:model.defer="description" rows="4" placeholder="Masukkan deskripsi regulasi..."
                    class="w-full form-control !rounded-2xl focus:ring-4 focus:ring-[{{ $companyData->color_primary ?? '#2b7fff' }}] focus:ring-opacity-10 border-gray-100 transition-all"></textarea>
                @error('description')
                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="type" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Tipe Regulasi <span class="text-red-500">*</span></label>
                <div class="relative">
                    <select id="type" wire:model.defer="type" class="w-full form-control !rounded-2xl !py-3 focus:ring-4 focus:ring-[{{ $companyData->color_primary ?? '#2b7fff' }}] focus:ring-opacity-10 border-gray-100 transition-all appearance-none">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="prohibition">Larangan</option>
                        <option value="licensing">Wajib</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>
                @error('type')
                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-end gap-3">
            <button wire:click="closeModal()"
                class="px-6 py-3 bg-white border border-gray-200 text-gray-700 text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-gray-50 hover:border-gray-300 transition-all cursor-pointer">
                Batal
            </button>
            <button wire:click='submit'
                class="px-8 py-3 bg-[{{ $companyData->color_primary ?? '#2b7fff' }}] text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-[{{ $companyData->color_primary ?? '#2b7fff' }}]/20 hover:opacity-90 active:scale-95 transition-all">
                Simpan Data
            </button>
        </div>
    </div>
</div>
