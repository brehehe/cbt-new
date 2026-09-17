<!-- Modal Form Tambah / Ubah Buku Digital -->
<div wire:ignore.self id="modal"
    class="fixed inset-0 bg-overlay hidden items-center justify-center z-50 transition-opacity duration-300 ease-in-out p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col transform transition-all scale-95 duration-300 ease-out animate-fade-in overflow-hidden">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50/50">
            <div class="flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20.5C6.753 20.5 2.5 16.247 2.5 11S6.753 1.5 12 1.5 21.5 5.753 21.5 11 17.247 20.5 12 20.5z" />
                </svg>
                <h2 class="text-xl font-semibold text-gray-800">Buku Digital</h2>
            </div>
            <button wire:click="closeModal()"
                class="text-gray-500 hover:text-red-500 transition-colors text-2xl leading-none cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Body (Scrollable) -->
        <div class="px-6 py-5 text-gray-600 overflow-y-auto space-y-4 flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Judul Buku -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700">Judul Buku Digital <span class="text-red-600">*</span></label>
                    <input type="text" id="title" class="mt-1 form-control"
                        placeholder="Contoh: Modul Dasar Shorinji Kempo, Buku Panduan CBT"
                        wire:model.defer="title">
                    @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="digital_book_category_id" class="block text-sm font-medium text-gray-700">Kategori Buku Digital <span class="text-red-600">*</span></label>
                    <select id="digital_book_category_id" class="mt-1 form-control" wire:model.defer="digital_book_category_id">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('digital_book_category_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Penulis / Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-gray-700">Penulis / Penyusun</label>
                    <input type="text" id="author" class="mt-1 form-control"
                        placeholder="Nama penulis / institusi..."
                        wire:model.defer="author">
                    @error('author') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Cover Image -->
                <div class="md:col-span-2">
                    <label for="cover" class="block text-sm font-medium text-gray-700">Cover Buku (Opsional)</label>
                    <input type="file" id="cover" class="mt-1 form-control" wire:model="cover" accept="image/*">
                    @if ($cover)
                        <p class="mt-1 text-xs text-green-600"><i class="fas fa-check"></i> File cover siap diunggah</p>
                    @elseif($existing_cover)
                        <p class="mt-1 text-xs text-gray-500">Cover saat ini: <a href="{{ asset('storage/' . $existing_cover) }}" target="_blank" class="text-blue-600 underline">Lihat cover</a></p>
                    @endif
                    @error('cover') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tipe Konten Selector -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Konten Pembelajaran <span class="text-red-600">*</span></label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all {{ $content_type === 'pdf' ? 'border-indigo-600 bg-indigo-50 text-indigo-900 font-bold' : 'border-gray-200 hover:border-gray-300 text-gray-700' }}">
                        <input type="radio" name="content_type" value="pdf" class="sr-only" wire:model.live="content_type">
                        <i class="fas fa-file-pdf text-2xl mb-1 text-rose-500"></i>
                        <span class="text-xs">Upload PDF</span>
                    </label>
                    <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all {{ $content_type === 'link' ? 'border-indigo-600 bg-indigo-50 text-indigo-900 font-bold' : 'border-gray-200 hover:border-gray-300 text-gray-700' }}">
                        <input type="radio" name="content_type" value="link" class="sr-only" wire:model.live="content_type">
                        <i class="fas fa-link text-2xl mb-1 text-emerald-500"></i>
                        <span class="text-xs">Tautan Buku Web</span>
                    </label>
                    <label class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all {{ $content_type === 'video' ? 'border-indigo-600 bg-indigo-50 text-indigo-900 font-bold' : 'border-gray-200 hover:border-gray-300 text-gray-700' }}">
                        <input type="radio" name="content_type" value="video" class="sr-only" wire:model.live="content_type">
                        <i class="fas fa-play-circle text-2xl mb-1 text-sky-500"></i>
                        <span class="text-xs">Video Pembelajaran</span>
                    </label>
                </div>
            </div>

            <!-- Input File PDF -->
            @if($content_type === 'pdf')
                <div class="p-4 bg-rose-50/50 rounded-xl border border-rose-100 space-y-1">
                    <label for="file" class="block text-sm font-medium text-rose-900">Unggah Dokumen PDF <span class="text-red-600">*</span></label>
                    <input type="file" id="file" class="form-control" wire:model="file" accept="application/pdf">
                    <p class="text-xs text-rose-600">Format file harus .pdf</p>
                    @if ($file)
                        <p class="text-xs font-semibold text-green-700"><i class="fas fa-check"></i> File PDF dipilih.</p>
                    @elseif($existing_file_path)
                        <p class="text-xs text-gray-600">File PDF tersimpan: <a href="{{ asset('storage/' . $existing_file_path) }}" target="_blank" class="text-blue-600 underline font-semibold">Buka file saat ini</a></p>
                    @endif
                    @error('file') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Input Tautan / Video URL -->
            @if(in_array($content_type, ['link', 'video']))
                <div class="p-4 bg-sky-50/50 rounded-xl border border-sky-100 space-y-1">
                    <label for="external_url" class="block text-sm font-medium text-sky-900">
                        {{ $content_type === 'video' ? 'URL Video Pembelajaran (YouTube / MP4)' : 'Tautan / URL Buku Digital' }} <span class="text-red-600">*</span>
                    </label>
                    <input type="url" id="external_url" class="form-control"
                        placeholder="{{ $content_type === 'video' ? 'https://www.youtube.com/watch?v=...' : 'https://example.com/buku' }}"
                        wire:model.defer="external_url">
                    @error('external_url') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            @endif

            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi / Keterangan Buku</label>
                <textarea id="description" class="mt-1 form-control" rows="3"
                    placeholder="Sinopsis singkat atau catatan materi..."
                    wire:model.defer="description"></textarea>
                @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50">
            <button wire:click="closeModal()"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow transition cursor-pointer">
                Batal
            </button>
            <button wire:click="submit"
                class="px-4 py-2 bg-primary hover:bg-primary text-white rounded-lg shadow transition">
                Simpan
            </button>
        </div>
    </div>
</div>

<!-- Modal Preview Buku Digital (FULLSCREEN READER) -->
<div wire:ignore.self id="modal-preview-book"
    x-data="{ showDesc: false }"
    @keydown.escape.window="if(!document.getElementById('modal-preview-book')?.classList.contains('hidden')) { $wire.closePreview(); }"
    class="fixed inset-0 bg-white z-[9999] hidden flex-col w-screen h-screen overflow-hidden">
    
    <!-- Top Navigation Bar -->
    <div class="h-14 sm:h-16 px-4 sm:px-6 bg-white border-b border-slate-200 flex items-center justify-between shadow-xs shrink-0 z-20">
        <div class="flex items-center gap-3 min-w-0 pr-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg font-bold shrink-0 border border-indigo-200 shadow-xs">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h5 class="font-extrabold text-sm sm:text-base text-slate-800 truncate">
                        {{ $previewBook?->title ?? 'Preview Buku Digital' }}
                    </h5>
                    @if($previewBook?->category)
                        <span class="shrink-0 text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-indigo-100 text-indigo-800 border border-indigo-200 uppercase tracking-wider">
                            {{ $previewBook->category->name }}
                        </span>
                    @endif
                </div>
                @if($previewBook?->author)
                    <p class="text-xs text-slate-500 truncate mt-0.5">
                        <span class="text-slate-400">Penulis:</span> {{ $previewBook->author }}
                    </p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            @if($previewBook && $previewBook->content_type === 'pdf' && $previewBook->file_path)
                <a href="{{ asset('storage/' . $previewBook->file_path) }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-300 transition shadow-xs cursor-pointer">
                    <i class="fas fa-arrow-up-right-from-square text-xs"></i>
                    <span class="hidden md:inline">Buka Tab Baru</span>
                </a>
            @endif

            @if($previewBook?->description)
                <button type="button" @click="showDesc = !showDesc"
                    :class="showDesc ? 'bg-indigo-100 text-indigo-900 border-indigo-300' : 'bg-slate-100 text-slate-700 border-slate-200 hover:bg-slate-200'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border transition shadow-xs cursor-pointer">
                    <i class="fas fa-circle-info text-xs"></i>
                    <span class="hidden sm:inline">Deskripsi</span>
                </button>
            @endif

            <button type="button" wire:click="closePreview"
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-800 text-xs font-extrabold rounded-xl border border-rose-200 transition cursor-pointer shadow-xs">
                <i class="fas fa-times text-sm"></i>
                <span>Tutup</span>
            </button>
        </div>
    </div>

    <!-- Collapsible Description -->
    @if($previewBook?->description)
        <div x-show="showDesc" x-cloak
            class="px-6 py-3 bg-indigo-50/95 border-b border-indigo-200 text-xs text-slate-700 flex items-start justify-between gap-4 z-10 shrink-0 shadow-xs">
            <div class="space-y-0.5">
                <span class="font-bold text-indigo-900 uppercase tracking-wider text-[10px]">Deskripsi:</span>
                <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line">{{ $previewBook->description }}</p>
            </div>
            <button type="button" @click="showDesc = false" class="text-indigo-600 hover:text-indigo-800 p-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Content Viewer -->
    <div class="flex-1 w-full h-full relative overflow-hidden bg-slate-100 flex flex-col">
        @if($previewBook)
            @if($previewBook->content_type === 'pdf')
                <iframe src="{{ asset('storage/' . $previewBook->file_path) }}#toolbar=1&view=FitH"
                    class="w-full h-full flex-1 border-0 m-0 p-0 block bg-slate-200"
                    style="height: calc(100vh - 56px);"
                    allowfullscreen
                    frameborder="0">
                </iframe>
            @elseif($previewBook->content_type === 'video')
                @php
                    $isYoutube = str_contains($previewBook->external_url, 'youtube.com') || str_contains($previewBook->external_url, 'youtu.be');
                    $embedUrl = $previewBook->external_url;
                    if ($isYoutube) {
                        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $previewBook->external_url, $matches)) {
                            $embedUrl = 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1';
                        }
                    }
                @endphp
                <div class="flex-1 w-full h-full flex flex-col items-center justify-center p-4 sm:p-8 bg-slate-950 overflow-y-auto">
                    <div class="w-full max-w-5xl aspect-video rounded-2xl overflow-hidden shadow-2xl border border-slate-800 bg-black">
                        @if($isYoutube)
                            <iframe src="{{ $embedUrl }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                            <video controls autoplay class="w-full h-full" src="{{ $previewBook->external_url }}">
                                Browser Anda tidak mendukung tag video.
                            </video>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex-1 w-full h-full flex items-center justify-center p-6 bg-slate-50">
                    <div class="max-w-md w-full p-8 text-center bg-white rounded-3xl border border-slate-200 shadow-xl space-y-5">
                        <div class="w-20 h-20 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-3xl shadow-xs">
                            <i class="fas fa-arrow-up-right-from-square"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-lg text-slate-800">{{ $previewBook->title }}</h4>
                            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                                Materi ini disajikan melalui tautan eksternal. Silakan klik tombol di bawah untuk membuka materi di tab baru.
                            </p>
                        </div>
                        <a href="{{ $previewBook->external_url }}" target="_blank"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black rounded-xl shadow-md transition transform hover:scale-105 active:scale-95">
                            <span>Buka di Tab Baru</span>
                            <i class="fas fa-external-link-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            @endif
        @else
            <div class="flex-1 flex flex-col items-center justify-center p-8 text-center text-slate-400">
                <i class="fas fa-spinner fa-spin text-3xl mb-3 text-indigo-500"></i>
                <p class="text-xs font-bold text-slate-500">Memuat materi...</p>
            </div>
        @endif
    </div>
</div>
