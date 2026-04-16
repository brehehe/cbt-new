<!-- Step 2: Password Change -->
<div wire:key="step-2" class="space-y-8 animate-fadeIn" x-data="{ showNew: false, showConfirm: false }">
    <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 text-blue-600 rounded-full mb-4">
            <i class="fas fa-shield-alt text-2xl"></i>
        </div>
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Amankan Akun Anda</h2>
        <p class="mt-3 text-gray-600">Demi keamanan data Anda, silakan ganti kata sandi default dengan yang baru.</p>
    </div>

    <div class="max-w-md mx-auto space-y-6">
        <div class="group">
            <label class="text-sm font-bold text-gray-700 group-focus-within:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-key text-xs"></i> Kata Sandi Baru
            </label>
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input :type="showNew ? 'text' : 'password'" wire:model.live="password" 
                    class="w-full pl-11 pr-12 py-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none"
                    placeholder="Minimal 8 karakter">
                <button type="button" @click="showNew = !showNew" 
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-orange-600 transition-colors">
                    <i class="fas" :class="showNew ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
            @error('password') <span class="text-red-500 text-xs font-medium mt-1 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span> @enderror
        </div>

        <div class="group">
            <label class="text-sm font-bold text-gray-700 group-focus-within:text-orange-600 transition-colors flex items-center gap-2">
                <i class="fas fa-redo text-xs"></i> Konfirmasi Kata Sandi
            </label>
            <div class="relative mt-2">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-check-double text-gray-400 group-focus-within:text-orange-500 transition-colors"></i>
                </div>
                <input :type="showConfirm ? 'text' : 'password'" wire:model.live="password_confirmation" 
                    class="w-full pl-11 pr-12 py-3.5 rounded-2xl border-2 border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-4 focus:ring-orange-100 focus:border-orange-500 transition-all outline-none"
                    placeholder="Ulangi kata sandi baru">
                <button type="button" @click="showConfirm = !showConfirm" 
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-orange-600 transition-colors">
                    <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                </button>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-5 rounded-2xl border border-blue-100 flex items-start space-x-4">
            <div class="bg-white p-2 rounded-lg shadow-sm">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            </div>
            <div class="space-y-1">
                <p class="text-xs font-bold text-blue-900">Tips Keamanan:</p>
                <p class="text-[11px] text-blue-700 leading-relaxed">
                    Gunakan kombinasi **huruf besar, huruf kecil, angka,** dan **simbol** untuk tingkat keamanan maksimal. Hindari menggunakan nama atau tanggal lahir.
                </p>
            </div>
        </div>
    </div>
</div>
