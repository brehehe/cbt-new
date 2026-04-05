<div wire:ignore.self id="modal-regulation-info"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-[60] transition-opacity duration-300 ease-in-out">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-50 bg-gray-50/30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center border border-blue-100 shadow-sm">
                        <i class="fa-solid fa-circle-info text-xl text-blue-600"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">Panduan Regulasi</h2>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Contoh Pembuatan Aturan</p>
                    </div>
                </div>
                <button wire:click="closeInfoModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 text-gray-400 hover:bg-red-50 hover:text-red-500 transition-all cursor-pointer">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fa-solid fa-file-shield text-blue-600"></i>
                        </div>
                        <h2 class="font-semibold text-gray-900">Peraturan yang harus dipatuhi:</h2>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start p-4 rounded-xl bg-gray-50/50 border border-gray-100 transition-all hover:border-blue-200 hover:bg-white group">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 w-5 h-5 mr-4 mt-0.5 group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm text-gray-600 leading-relaxed font-medium">Dilarang keras membuka tab/aplikasi lain selama ujian berlangsung</span>
                        </li>
                        <li class="flex items-start p-4 rounded-xl bg-gray-50/50 border border-gray-100 transition-all hover:border-blue-200 hover:bg-white group">
                            <i class="fa-solid fa-triangle-exclamation text-red-500 w-5 h-5 mr-4 mt-0.5 group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm text-gray-600 leading-relaxed font-medium">Dilarang mengambil screenshot atau merekam layar</span>
                        </li>
                        <li class="flex items-start p-4 rounded-xl bg-gray-50/50 border border-gray-100 transition-all hover:border-blue-200 hover:bg-white group">
                            <i class="fa-solid fa-circle-check text-green-500 w-5 h-5 mr-4 mt-0.5 group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm text-gray-600 leading-relaxed font-medium">Kamera harus menyala dan wajah harus terlihat selama ujian</span>
                        </li>
                        <li class="flex items-start p-4 rounded-xl bg-gray-50/50 border border-gray-100 transition-all hover:border-blue-200 hover:bg-white group">
                            <i class="fa-solid fa-circle-check text-green-500 w-5 h-5 mr-4 mt-0.5 group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm text-gray-600 leading-relaxed font-medium">Pastikan koneksi internet stabil sebelum memulai ujian</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-50 flex justify-end">
            <button wire:click="closeInfoModal()"
                class="px-8 py-3 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-primary/20 hover:opacity-90 active:scale-95 transition-all cursor-pointer">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('openModalRegulationInfo', () => {
            const modal = document.getElementById('modal-regulation-info');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        });

        @this.on('closeModalRegulationInfo', () => {
            const modal = document.getElementById('modal-regulation-info');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        });
    });
</script>
