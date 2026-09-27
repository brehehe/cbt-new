@section('title', 'Buat Kuisioner Baru')
<div>
    <!-- Back & Header -->
    <div class="mb-6">
        <a href="{{ route('admin.master.kuisioner') }}" 
           class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[color:var(--primary)] mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Kembali ke Daftar Kuisioner
        </a>
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-blue-50 text-[color:var(--primary)] rounded-xl shadow-sm border border-blue-100">
                <i class="fa-solid fa-plus text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">Buat Kuisioner Baru</h1>
                <p class="text-sm text-gray-500">Tentukan judul, tautan unik (slug), serta konfigurasi pengisian kuisioner.</p>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="max-w-4xl bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form wire:submit.prevent="submit" class="p-6 md:p-8 space-y-6">
            
            <!-- Judul Kuisioner -->
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">
                    Judul Kuisioner <span class="text-red-500">*</span>
                </label>
                <input type="text" id="title" wire:model.live.debounce.400ms="title" 
                       class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)] focus:border-transparent transition"
                       placeholder="Contoh: Form Evaluasi Modul Kardiovaskular - Mahasiswa" required>
                @error('title') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Slug URL -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="slug" class="block text-sm font-semibold text-gray-700">
                        Slug Tautan Unik <span class="text-red-500">*</span>
                    </label>
                    <button type="button" wire:click="generateSlug" class="text-xs text-[color:var(--primary)] hover:underline flex items-center gap-1">
                        <i class="fa-solid fa-arrows-rotate"></i> Refresh Slug
                    </button>
                </div>
                <div class="flex rounded-xl shadow-sm border border-gray-200 overflow-hidden focus-within:ring-2 focus-within:ring-[color:var(--primary)] focus-within:border-transparent">
                    <span class="inline-flex items-center px-4 bg-gray-50 text-gray-500 text-xs sm:text-sm font-mono border-r border-gray-200 select-none">
                        {{ url('/kuisioner') }}/
                    </span>
                    <input type="text" id="slug" wire:model="slug" 
                           class="flex-1 px-4 py-2.5 text-sm border-none focus:ring-0 font-mono text-gray-800"
                           placeholder="evaluasi-modul-kardiovaskular" required>
                </div>
                <p class="text-xs text-gray-400 mt-1">Slug ini akan menjadi alamat URL pengisian kuisioner bagi mahasiswa/responden. Pastikan unik dan mudah dibaca.</p>
                @error('slug') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Periode Akademik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="academic_year" class="block text-sm font-semibold text-gray-700 mb-1">
                        Tahun Akademik
                    </label>
                    <input type="text" id="academic_year" wire:model="academic_year" 
                           class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)] focus:border-transparent"
                           placeholder="Contoh: 2025/2026">
                    @error('academic_year') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="semester" class="block text-sm font-semibold text-gray-700 mb-1">
                        Semester
                    </label>
                    <select id="semester" wire:model="semester" 
                            class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)] focus:border-transparent bg-white">
                        <option value="Ganjil">Ganjil</option>
                        <option value="Genap">Genap</option>
                        <option value="Pendek/Antara">Pendek / Antara</option>
                    </select>
                    @error('semester') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Deskripsi / Petunjuk Pengisian -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">
                    Deskripsi / Petunjuk Pengisian
                </label>
                <textarea id="description" wire:model="description" rows="3" 
                          class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-200 focus:ring-2 focus:ring-[color:var(--primary)] focus:border-transparent"
                          placeholder="Berikan penjelasan singkat mengenai tujuan evaluasi ini dan tata cara pengisian bagi mahasiswa..."></textarea>
                @error('description') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <hr class="border-gray-100">

            <!-- Template Option Box -->
            <div class="p-4 rounded-xl bg-blue-50/70 border border-blue-100 flex items-start gap-3">
                <input type="checkbox" id="use_template" wire:model="use_template" 
                       class="mt-1 h-4 w-4 rounded border-gray-300 text-[color:var(--primary)] focus:ring-[color:var(--primary)] cursor-pointer">
                <label for="use_template" class="cursor-pointer">
                    <span class="block text-sm font-bold text-gray-900">Gunakan Template Standar Evaluasi Modul FK UINAR</span>
                    <span class="block text-xs text-gray-600 mt-0.5 leading-relaxed">
                        Otomatis membuat 5 Bagian & 32 butir pertanyaan standar berdasarkan template Word resmi (Evaluasi Proses Modul, Evaluasi Tutor KKD, Evaluasi Fasil DK, Evaluasi Narasumber Kuliah, dan Saran ke Prodi/MEU/CSU/MIT). Anda dapat menyesuaikan pertanyaan ini nanti.
                    </span>
                </label>
            </div>

            <!-- Access Options -->
            <div class="space-y-3 bg-gray-50/80 p-4 rounded-xl border border-gray-100">
                <h3 class="text-xs font-bold text-gray-600 uppercase tracking-wider">Pengaturan Akses Responden</h3>
                
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="allow_guest" wire:model="allow_guest" 
                           class="h-4 w-4 rounded border-gray-300 text-[color:var(--primary)] focus:ring-[color:var(--primary)] cursor-pointer">
                    <label for="allow_guest" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Izinkan Pengisian Tanpa Login (Tamu / Guest)
                        <span class="block text-xs text-gray-400 font-normal">Jika dicentang, siapapun yang memiliki link dapat mengisi formulir dengan memasukkan nama/identitas.</span>
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="require_nim" wire:model="require_nim" 
                           class="h-4 w-4 rounded border-gray-300 text-[color:var(--primary)] focus:ring-[color:var(--primary)] cursor-pointer">
                    <label for="require_nim" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Wajibkan Mengisi NIM / Nomor Induk Mahasiswa
                    </label>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="is_active" wire:model="is_active" 
                           class="h-4 w-4 rounded border-gray-300 text-[color:var(--primary)] focus:ring-[color:var(--primary)] cursor-pointer">
                    <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
                        Publikasikan dan Buka Pengisian Sekarang (Status: Aktif)
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.master.kuisioner') }}" 
                   class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium text-sm transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl bg-[color:var(--primary)] hover:opacity-95 text-white font-medium text-sm shadow-sm transition inline-flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>Simpan & Kelola Kuisioner</span>
                </button>
            </div>
        </form>
    </div>
</div>
