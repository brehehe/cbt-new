<!-- Step 1: Profile Details -->
<div wire:key="step-1" class="space-y-8 animate-fadeIn">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 text-orange-600 rounded-full mb-4">
            <i class="fas fa-user-edit text-2xl"></i>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Lengkapi Data Diri</h2>
        <p class="mt-3 text-gray-600 max-w-sm mx-auto">Pastikan informasi Anda benar untuk kelancaran administrasi dan sertifikasi ujian.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-4">
        <!-- Nama Lengkap -->
        <div class="space-y-2 group">
            <label class="text-sm font-bold text-gray-700 group-focus-within:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-id-card text-xs"></i> Nama Lengkap
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-user text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" wire:model.live="name" 
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none" 
                    placeholder="Masukkan nama sesuai KTP/Ijazah">
            </div>
            @error('name') <span class="text-red-500 text-xs font-medium mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
        </div>

        <!-- NIM -->
        <div class="space-y-2 group">
            <label class="text-sm font-bold text-gray-700 group-focus-within:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-fingerprint text-xs"></i> NIM / Nomor Induk
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-hashtag text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" wire:model.live="nim" 
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none" 
                    placeholder="Contoh: 202401001">
            </div>
            @error('nim') <span class="text-red-500 text-xs font-medium mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
        </div>

        <!-- Phone -->
        <div class="space-y-2 group">
            <label class="text-sm font-bold text-gray-700 group-focus-within:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-phone-alt text-xs"></i> Nomor WhatsApp
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fab fa-whatsapp text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input type="text" wire:model.live="phone" 
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none" 
                    placeholder="Contoh: 08123456789">
            </div>
            @error('phone') <span class="text-red-500 text-xs font-medium mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
        </div>

        <!-- Address -->
        <div class="space-y-2 group">
            <label class="text-sm font-bold text-gray-700 group-focus-within:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-xs"></i> Alamat Domisili
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 pt-4 flex items-start pointer-events-none">
                    <i class="fas fa-home text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <textarea wire:model.live="address" rows="1" 
                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none resize-none" 
                    placeholder="Alamat lengkap tempat tinggal saat ini..."></textarea>
            </div>
            @error('address') <span class="text-red-500 text-xs font-medium mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
        </div>
    </div>
</div>
