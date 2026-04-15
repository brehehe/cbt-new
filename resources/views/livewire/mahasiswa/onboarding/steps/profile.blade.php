<!-- Step 1: Profile Details -->
<div wire:key="step-1" class="space-y-8 animate-fadeIn">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Lengkapi Data Diri</h2>
        <p class="mt-3 text-gray-600">Pastikan informasi Anda benar untuk kelancaran administrasi ujian.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
            <input type="text" wire:model.live="name" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="John Doe">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">NIM / Nomor Induk</label>
            <input type="text" wire:model.live="nim" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="202401001">
            @error('nim') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Nomor Telepon/WhatsApp</label>
            <input type="text" wire:model.live="phone" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="08123456789">
            @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
        <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Alamat Lengkap</label>
            <textarea wire:model.live="address" rows="1" class="w-full px-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" placeholder="Alamat Anda..."></textarea>
            @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>
    </div>
</div>
