<div wire:ignore.self id="modal-start-exam"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[1050] p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Ujian</h2>
            </div>
            <button wire:click="closeModalStartExam()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 text-gray-600">
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700">Token <span
                        class="text-red-600">*</span></label>
                <input type="text" id="code" wire:model.defer="code" placeholder="Masukan Token Ujian"
                    class="mt-1 form-control">
                @error('code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t">
            <button wire:click="closeModalStartExam()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click='submitStartExam'
                class="px-4 py-2 bg-primary hover:bg-primary transition-colors text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<div wire:ignore.self id="modal-change-supervisor"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[1050] p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center p-6 border-b">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-user-shield text-indigo-600 text-xl"></i>
                <h2 class="text-xl font-semibold text-gray-800">Ubah Pengawas Ujian</h2>
            </div>
            <button wire:click="closeModalSupervisor()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-5 text-gray-600 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pengawas Ujian</label>
                <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-200 rounded-xl p-3 bg-gray-50">
                    @forelse (($availableSupervisors ?? $this->availableSupervisors ?? []) as $supervisorId => $supervisorName)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-white transition cursor-pointer text-sm font-medium text-gray-700">
                            <input type="checkbox"
                                wire:model="selectedSupervisors"
                                value="{{ $supervisorId }}"
                                class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span>{{ $supervisorName }}</span>
                        </label>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-3">Tidak ada data pengawas tersedia.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50 rounded-b-2xl">
            <button wire:click="closeModalSupervisor()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer font-medium text-sm">
                Batal
            </button>
            <button wire:click="saveSupervisor()"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow transition font-medium text-sm inline-flex items-center gap-2">
                <i class="fa-solid fa-check"></i>
                Simpan Pengawas
            </button>
        </div>
    </div>
</div>

@if(is_lemes())
<!-- Modal QRCODE Siswa / Mahasiswa / Kenshi -->
<div wire:ignore.self id="modal-student-qr"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[1050] p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm mx-auto flex flex-col border border-slate-200 overflow-hidden text-center">
        <!-- Header -->
        <div class="p-6 bg-gradient-to-br from-indigo-600 to-purple-600 text-white relative">
            <button type="button" wire:click="closeMyQrCode" class="absolute top-4 right-4 text-white/80 hover:text-white p-1 rounded-lg hover:bg-white/10 transition cursor-pointer">
                <i class="fas fa-times text-lg"></i>
            </button>
            <div class="w-14 h-14 mx-auto rounded-2xl bg-white/15 backdrop-blur-md border border-white/20 flex items-center justify-center text-white text-2xl mb-3 shadow-lg">
                <i class="fas fa-qrcode"></i>
            </div>
            <h4 class="font-black text-lg text-white">QRCODE Presensi</h4>
            <p class="text-xs text-indigo-100 mt-0.5">Identitas Kartu {{ student_label() }}</p>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 inline-block shadow-inner">
                @if($studentQrBase64)
                    <img src="data:image/png;base64,{{ $studentQrBase64 }}" alt="QRCODE Siswa" class="w-52 h-52 mx-auto rounded-lg">
                @else
                    <div class="w-52 h-52 flex items-center justify-center text-slate-400 text-xs">
                        Memuat QRCODE...
                    </div>
                @endif
            </div>

            <div class="space-y-1">
                <div class="font-extrabold text-base text-slate-800">
                    {{ $studentQrInfo['name'] ?? Auth::user()->name }}
                </div>
                <div class="text-xs font-mono font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-3 py-1 rounded-full inline-block">
                    {{ $studentQrInfo['username'] ?? Auth::user()->username }}
                </div>
                <p class="text-[11px] text-slate-500 mt-2">
                    Arahkan QRCODE ini ke kamera Pengawas atau Admin untuk mencatat kehadiran Anda.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50 flex justify-center">
            <button type="button" wire:click="closeMyQrCode"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition cursor-pointer shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Token Materi Digital -->
<div wire:ignore.self id="modal-material-token"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden items-center justify-center z-[1050] p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto flex flex-col border border-slate-200 overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-amber-100 bg-amber-50/80">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-600 flex items-center justify-center text-lg font-bold">
                    <i class="fas fa-key"></i>
                </div>
                <div>
                    <h5 class="font-bold text-base text-slate-800">Token Akses Materi</h5>
                    <p class="text-xs text-slate-500">Masukkan token untuk membuka materi</p>
                </div>
            </div>
            <button type="button" wire:click="closeMaterialToken" class="text-slate-400 hover:text-slate-600 p-2 rounded-xl hover:bg-slate-100 transition cursor-pointer">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-4">
            <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200">
                <div class="text-[11px] font-semibold text-slate-400 uppercase">Materi</div>
                <div class="font-bold text-slate-800 text-sm mt-0.5">
                    {{ $selectedDetail?->digitalBook?->title ?? '-' }}
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                    Token Materi <span class="text-rose-500">*</span>
                </label>
                <input type="text" wire:model="materialTokenInput"
                    placeholder="Masukkan token materi..."
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-800 font-mono text-sm tracking-wider uppercase font-bold focus:border-amber-500 focus:ring-1 focus:ring-amber-500">
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex justify-end gap-2">
            <button type="button" wire:click="closeMaterialToken"
                class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-xl transition cursor-pointer">
                Batal
            </button>
            <button type="button" wire:click="submitMaterialToken"
                class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                <i class="fas fa-unlock mr-1"></i> Buka Materi
            </button>
        </div>
    </div>
</div>

<!-- Modal Viewer Buku Digital / Materi (FULLSCREEN READER) -->
<div wire:ignore.self id="modal-view-material"
    x-data="{ showDesc: false }"
    @keydown.escape.window="if(!document.getElementById('modal-view-material')?.classList.contains('hidden')) { $wire.closeMaterial(); }"
    class="fixed inset-0 bg-white z-[9999] hidden flex-col w-screen h-screen overflow-hidden">
    
    <!-- Top Navigation Bar -->
    <div class="h-14 sm:h-16 px-4 sm:px-6 bg-white border-b border-slate-200 flex items-center justify-between shadow-xs shrink-0 z-20">
        <!-- Left: Judul & Informasi Materi -->
        <div class="flex items-center gap-3 min-w-0 pr-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-lg font-bold shrink-0 border border-amber-200/60 shadow-xs">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h5 class="font-extrabold text-sm sm:text-base text-slate-800 truncate" title="{{ $selectedDigitalBook?->title ?? 'Materi Pembelajaran' }}">
                        {{ $selectedDigitalBook?->title ?? 'Materi Pembelajaran' }}
                    </h5>
                    <span class="shrink-0 text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 border border-amber-200 uppercase tracking-wider">
                        {{ $selectedDigitalBook?->category?->name ?? 'Materi' }}
                    </span>
                </div>
                @if($selectedDigitalBook?->author)
                    <p class="text-xs text-slate-500 truncate mt-0.5">
                        <span class="text-slate-400">Penulis / Sumber:</span> {{ $selectedDigitalBook->author }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Right: Actions (Buka Tab Baru, Toggle Deskripsi, Tutup) -->
        <div class="flex items-center gap-2 shrink-0">
            @if($selectedDigitalBook)
                @php
                    $cType = $selectedDigitalBook->content_type;
                    $videoUrl = $selectedDigitalBook->external_url ?? $selectedDigitalBook->video_url ?? $selectedDigitalBook->external_link;
                    $externalUrl = $selectedDigitalBook->external_url ?? $selectedDigitalBook->external_link;
                @endphp

                @if(in_array($cType, ['pdf', 'file_pdf']) && $selectedDigitalBook->file_path)
                    <a href="{{ asset('storage/' . $selectedDigitalBook->file_path) }}" target="_blank"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 transition shadow-xs cursor-pointer"
                        title="Buka dokumen di tab baru browser">
                        <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                        <span class="hidden md:inline">Buka Tab Baru</span>
                    </a>
                @endif

                @if($selectedDigitalBook->description)
                    <button type="button" @click="showDesc = !showDesc"
                        :class="showDesc ? 'bg-amber-100 text-amber-900 border-amber-300' : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition shadow-xs cursor-pointer"
                        title="Tampilkan / Sembunyikan Deskripsi Materi">
                        <i class="fas fa-circle-info text-xs"></i>
                        <span class="hidden sm:inline">Info Materi</span>
                    </button>
                @endif
            @endif

            <button type="button" wire:click="closeMaterial"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-800 text-xs font-extrabold rounded-xl border border-rose-200 transition cursor-pointer shadow-xs"
                title="Tutup Materi (Tekan ESC)">
                <i class="fas fa-times text-sm"></i>
                <span>Tutup</span>
            </button>
        </div>
    </div>

    <!-- Collapsible Description Drawer/Banner -->
    @if($selectedDigitalBook?->description)
        <div x-show="showDesc" x-cloak
            class="px-6 py-3 bg-amber-50/95 border-b border-amber-200 text-xs text-slate-700 flex items-start justify-between gap-4 z-10 shrink-0 shadow-xs">
            <div class="space-y-0.5">
                <span class="font-bold text-amber-900 uppercase tracking-wider text-[10px]">Deskripsi Materi:</span>
                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $selectedDigitalBook->description }}</p>
            </div>
            <button type="button" @click="showDesc = false" class="text-amber-600 hover:text-amber-800 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Content Viewer (100% Fullscreen Viewport) -->
    <div class="flex-1 w-full h-full relative overflow-hidden bg-slate-100 flex flex-col">
        @if($selectedDigitalBook)
            @if(in_array($cType, ['pdf', 'file_pdf']))
                @if($selectedDigitalBook->file_path)
                    <iframe
                        src="{{ asset('storage/' . $selectedDigitalBook->file_path) }}#toolbar=1&view=FitH"
                        class="w-full h-full flex-1 border-0 m-0 p-0 block bg-slate-200"
                        style="height: calc(100vh - 56px);"
                        allowfullscreen
                        frameborder="0">
                    </iframe>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400">
                        <i class="fas fa-file-circle-xmark text-4xl mb-3 text-slate-300"></i>
                        <p class="text-sm font-semibold">File PDF materi belum diunggah atau tidak ditemukan di penyimpanan server.</p>
                    </div>
                @endif
            @elseif(in_array($cType, ['video', 'video_url']))
                @php
                    $ytId = '';
                    if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $videoUrl, $matches)) {
                        $ytId = $matches[1];
                    }
                @endphp
                <div class="flex-1 w-full h-full flex flex-col items-center justify-center p-4 sm:p-8 bg-slate-950 overflow-y-auto">
                    <div class="w-full max-w-5xl aspect-video rounded-2xl overflow-hidden shadow-2xl border border-slate-800 bg-black">
                        @if($ytId)
                            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1" frameborder="0" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        @else
                            <video controls autoplay class="w-full h-full">
                                <source src="{{ $videoUrl }}">
                                Browser Anda tidak mendukung pemutaran video.
                            </video>
                        @endif
                    </div>
                </div>
            @elseif(in_array($cType, ['link', 'external_link']))
                <div class="flex-1 w-full h-full flex items-center justify-center p-6 bg-slate-50">
                    <div class="max-w-md w-full p-8 text-center bg-white rounded-3xl border border-slate-200 shadow-xl space-y-5">
                        <div class="w-20 h-20 rounded-2xl bg-amber-500/10 text-amber-600 flex items-center justify-center mx-auto text-3xl shadow-xs">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-lg text-slate-800">{{ $selectedDigitalBook->title }}</h4>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Materi ini disajikan melalui tautan eksternal. Silakan klik tombol di bawah untuk membuka materi di tab baru.
                            </p>
                        </div>
                        <a href="{{ $externalUrl }}" target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-black rounded-xl shadow-md transition transform hover:scale-105 active:scale-95">
                            <span>Buka Materi di Tab Baru</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            @endif
        @else
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400">
                <i class="fas fa-spinner fa-spin text-3xl mb-3 text-amber-500"></i>
                <p class="text-xs font-bold text-slate-500">Memuat materi...</p>
            </div>
        @endif
    </div>
</div>
@endif
