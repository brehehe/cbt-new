<!-- Step 2: Password Change -->
<div wire:key="step-2" class="space-y-8 animate-fadeIn">
    <div class="text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Amankan Akun Anda</h2>
        <p class="mt-3 text-gray-600">Demi keamanan, silakan ganti kata sandi default Anda.</p>
    </div>

    <div class="max-w-md mx-auto space-y-6">
        <div class="group">
            <label class="text-sm font-semibold text-gray-700 group-focus-within:text-orange-600 transition-colors">Kata Sandi Baru</label>
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400"></i>
                </div>
                <input type="password" wire:model.live="password" class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>
            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="group">
            <label class="text-sm font-semibold text-gray-700 group-focus-within:text-orange-600 transition-colors">Konfirmasi Kata Sandi</label>
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-check-double text-gray-400"></i>
                </div>
                <input type="password" wire:model.live="password_confirmation" class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all">
            </div>
        </div>

        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex items-start space-x-3">
            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
            <span class="text-xs text-blue-700 leading-relaxed">Gunakan minimal 8 karakter dengan kombinasi huruf dan angka agar akun lebih aman.</span>
        </div>
    </div>
</div>
