@section('title', 'Buku Digital')
<div>
    @include('livewire.admin.master.digital-book.book.admin-master-digital-book-modal')
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Buku Digital</h1>
                {{-- <p class="text-gray-600">Kelola buku digital dan bahan ajar.</p> --}}
            </div>
            <div>
                <button wire:click="openModal()" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah
                </button>
            </div>
        </div>
    </div>

    <!-- Filter & Controls -->
    <div class="flex flex-col lg:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2">
                <span class="text-sm text-gray-600 mr-2">Tampil</span>
                <select class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12" wire:model.live='perPage'>
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-600 ml-2">data</span>
            </div>

            <!-- Kategori Filter -->
            <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2">
                <span class="text-sm text-gray-600 mr-2"><i class="fas fa-filter text-gray-400"></i> Kategori</span>
                <select class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent" wire:model.live='filter_category'>
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tipe Filter -->
            <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2">
                <span class="text-sm text-gray-600 mr-2">Tipe</span>
                <select class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent" wire:model.live='filter_type'>
                    <option value="">Semua Tipe</option>
                    <option value="pdf">Upload PDF</option>
                    <option value="link">Tautan Web</option>
                    <option value="video">Video Pembelajaran</option>
                </select>
            </div>
        </div>

        <div class="w-full lg:w-72">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out"
                    placeholder="Cari Sesuatu..." wire:model.live='search'>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Cover / Judul</th>
                        <th>Kategori</th>
                        <th class="center">Tipe Konten</th>
                        <th>Deskripsi</th>
                        <th class="w-1 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $index => $book)
                        <tr>
                            <td class="center font-medium">{{ $books->firstItem() + $index }}</td>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($book->cover_image)
                                        <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-9 h-12 object-cover rounded shadow-xs border border-gray-200" alt="Cover">
                                    @else
                                        <div class="w-9 h-12 bg-slate-100 border border-slate-200 rounded flex items-center justify-center text-slate-500 text-xs shadow-xs">
                                            @if($book->content_type === 'pdf')
                                                <i class="fas fa-file-pdf text-rose-500 text-base"></i>
                                            @elseif($book->content_type === 'video')
                                                <i class="fas fa-video text-blue-500 text-base"></i>
                                            @else
                                                <i class="fas fa-link text-emerald-500 text-base"></i>
                                            @endif
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $book->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $book->author ? 'Oleh ' . $book->author : 'Tanpa Penulis' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-md px-2 py-0.5 text-xs font-medium">
                                    {{ $book->category?->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="center">
                                @if($book->content_type === 'pdf')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </span>
                                @elseif($book->content_type === 'video')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <i class="fas fa-play-circle mr-1"></i> Video
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        <i class="fas fa-link mr-1"></i> Tautan
                                    </span>
                                @endif
                            </td>
                            <td class="text-xs text-gray-600 max-w-xs truncate" title="{{ $book->description }}">
                                {{ Str::limit($book->description ?? '-', 50) }}
                            </td>
                            <td class="center">
                                <div class="flex items-center justify-center gap-1">
                                    <button class="btn btn-icon text-emerald-600 hover:text-emerald-800 transition-colors"
                                        wire:click="openPreview('{{ $book->id }}')" title="Pratinjau / Baca">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn btn-icon text-blue-600 hover:text-blue-800 transition-colors edit-btn"
                                        wire:click="edit('{{ $book->id }}')" title="Ubah">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button
                                        class="btn btn-icon text-red-600 hover:text-red-800 transition-colors delete-btn"
                                        wire:click="confirmDelete('{{ $book->id }}')" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-data">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $books->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $books->lastItem() }}</span> dari <span
                        class="font-medium">{{ $books->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $books->links('vendor.livewire.custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
