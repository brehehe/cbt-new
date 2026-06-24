<!-- Step 1: Profile Details -->
<div wire:key="step-1" class="space-y-6 animate-fadeIn">
    <!-- Section Title -->
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <i class="fas fa-id-card text-orange-500"></i> Lengkapi Data Profil
        </h2>
        <p class="text-xs text-gray-400 mt-1">Silakan lengkapi formulir di bawah ini dengan data yang valid dan sesuai.</p>
    </div>

    <!-- Inputs Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <!-- Nama Lengkap -->
        <div class="space-y-1.5 group">
            <label class="text-xs font-bold text-gray-500 group-focus-within:text-orange-600 uppercase tracking-wider transition-colors flex items-center gap-2">
                <i class="fas fa-user text-[10px]"></i> Nama Lengkap
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-user-circle"></i>
                </div>
                <input type="text" wire:model.live="name" 
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none text-sm text-gray-800 font-medium placeholder-gray-400" 
                    placeholder="Masukkan nama sesuai KTP/Ijazah">
            </div>
            @error('name') 
                <span class="text-red-500 text-[10px] font-semibold mt-1 flex items-center gap-1 animate-pulse">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </span> 
            @enderror
        </div>

        <!-- NIM -->
        <div class="space-y-1.5 group">
            <label class="text-xs font-bold text-gray-500 group-focus-within:text-orange-600 uppercase tracking-wider transition-colors flex items-center gap-2">
                <i class="fas fa-fingerprint text-[10px]"></i> NIM / Nomor Induk
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-hashtag"></i>
                </div>
                <input type="text" wire:model.live="nim" 
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none text-sm text-gray-800 font-medium placeholder-gray-400" 
                    placeholder="Contoh: 202401001">
            </div>
            @error('nim') 
                <span class="text-red-500 text-[10px] font-semibold mt-1 flex items-center gap-1 animate-pulse">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </span> 
            @enderror
        </div>

        <!-- Phone -->
        <div class="space-y-1.5 group">
            <label class="text-xs font-bold text-gray-500 group-focus-within:text-orange-600 uppercase tracking-wider transition-colors flex items-center gap-2">
                <i class="fas fa-phone-alt text-[10px]"></i> Nomor WhatsApp
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <input type="text" wire:model.live="phone" 
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none text-sm text-gray-800 font-medium placeholder-gray-400" 
                    placeholder="Contoh: 08123456789">
            </div>
            @error('phone') 
                <span class="text-red-500 text-[10px] font-semibold mt-1 flex items-center gap-1 animate-pulse">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </span> 
            @enderror
        </div>

        <!-- Address -->
        <div class="space-y-1.5 group">
            <label class="text-xs font-bold text-gray-500 group-focus-within:text-orange-600 uppercase tracking-wider transition-colors flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-[10px]"></i> Alamat Domisili
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 pt-3 flex items-start pointer-events-none text-gray-400 group-focus-within:text-orange-500 transition-colors">
                    <i class="fas fa-home"></i>
                </div>
                <textarea wire:model.live="address" rows="1" 
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none text-sm text-gray-800 font-medium placeholder-gray-400 resize-none" 
                    placeholder="Alamat lengkap tempat tinggal saat ini..."></textarea>
            </div>
            @error('address') 
                <span class="text-red-500 text-[10px] font-semibold mt-1 flex items-center gap-1 animate-pulse">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </span> 
            @enderror
        </div>
    </div>
</div>
