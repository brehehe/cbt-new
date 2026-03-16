<div wire:ignore.self id="modal-rating-scale-info"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-[60] transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[{{ $companyData->color_primary ?? '#2b7fff' }}] bg-opacity-10 flex items-center justify-center border border-[{{ $companyData->color_primary ?? '#2b7fff' }}] border-opacity-20 shadow-sm">
                        <i class="fa-solid fa-circle-info text-xl text-[{{ $companyData->color_primary ?? '#2b7fff' }}]"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">Panduan Penilaian</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Aturan Rentang Skor</p>
                    </div>
                </div>
                <button wire:click="closeInfoModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8">
            <div class="bg-blue-50 border-l-4 border-[{{ $companyData->color_primary ?? '#2b7fff' }}] p-6 rounded-r-2xl shadow-sm">
                <div class="flex items-start gap-5">
                    <div class="w-12 h-12 rounded-full bg-[{{ $companyData->color_primary ?? '#2b7fff' }}] bg-opacity-10 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-info text-[{{ $companyData->color_primary ?? '#2b7fff' }}] text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}] uppercase tracking-widest">Catatan Penting Penilaian</h3>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed font-medium">
                            Sistem menentukan Grade mahasiswa secara otomatis berdasarkan rentang skor yang Anda buat.
                        </p>
                        
                        <div class="mt-4 p-4 bg-white rounded-xl border border-blue-100 space-y-3">
                            <p class="text-xs font-bold text-blue-600 uppercase tracking-widest mb-2 text-center">Contoh Format Penilaian:</p>
                            <div class="grid grid-cols-1 gap-2">
                                <div class="flex justify-between items-center p-2 rounded-lg bg-blue-50/50">
                                    <span class="font-mono font-bold text-gray-700">90 - 100</span>
                                    <i class="fa-solid fa-arrow-right text-blue-400"></i>
                                    <span class="px-3 py-1 bg-white rounded-lg border border-blue-100 font-bold text-blue-600">Grade A</span>
                                </div>
                                <div class="flex justify-between items-center p-2 rounded-lg bg-blue-50/50">
                                    <span class="font-mono font-bold text-gray-700">80 - 89</span>
                                    <i class="fa-solid fa-arrow-right text-blue-400"></i>
                                    <span class="px-3 py-1 bg-white rounded-lg border border-blue-100 font-bold text-blue-600">Grade B</span>
                                </div>
                                <div class="flex justify-between items-center p-2 rounded-lg bg-blue-50/50">
                                    <span class="font-mono font-bold text-gray-700">70 - 79</span>
                                    <i class="fa-solid fa-arrow-right text-blue-400"></i>
                                    <span class="px-3 py-1 bg-white rounded-lg border border-blue-100 font-bold text-blue-600">Grade C</span>
                                </div>
                                <div class="flex justify-between items-center p-2 rounded-lg bg-blue-50/50">
                                    <span class="font-mono font-bold text-gray-700">60 - 69</span>
                                    <i class="fa-solid fa-arrow-right text-blue-400"></i>
                                    <span class="px-3 py-1 bg-white rounded-lg border border-blue-100 font-bold text-blue-600">Grade D</span>
                                </div>
                                <div class="flex justify-between items-center p-2 rounded-lg bg-blue-50/50">
                                    <span class="font-mono font-bold text-gray-700">0 - 59</span>
                                    <i class="fa-solid fa-arrow-right text-blue-400"></i>
                                    <span class="px-3 py-1 bg-white rounded-lg border border-blue-100 font-bold text-blue-600">Grade E</span>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-4 italic">
                            <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i>
                            Pastikan rentang <span class="text-blue-600 font-bold uppercase">Min</span> dan <span class="text-blue-600 font-bold uppercase">Max</span> tidak tumpang tindih untuk akurasi nilai.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-end">
            <button wire:click="closeInfoModal()"
                class="px-8 py-3 bg-[{{ $companyData->color_primary ?? '#2b7fff' }}] text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-[{{ $companyData->color_primary ?? '#2b7fff' }}]/20 hover:opacity-90 active:scale-95 transition-all cursor-pointer">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('openModalRatingScaleInfo', () => {
            const modal = document.getElementById('modal-rating-scale-info');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        });

        @this.on('closeModalRatingScaleInfo', () => {
            const modal = document.getElementById('modal-rating-scale-info');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        });
    });
</script>
