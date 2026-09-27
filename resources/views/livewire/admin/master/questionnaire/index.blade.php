@section('title', 'Kuisioner & Evaluasi')
<div>
    <!-- Header -->
    <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-blue-50 text-[color:var(--primary)] rounded-xl shadow-2xs border border-blue-100">
                    <i class="fa-solid fa-clipboard-question text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-[color:var(--primary)]">Kuisioner & Evaluasi</h1>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Kelola formulir evaluasi, instrumen kepuasan modul, dan rekapitulasi data responden.</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.master.kuisioner.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[color:var(--primary)] hover:opacity-95 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-sm transition-all duration-200">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kuisioner</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-center mb-5 gap-3">
        <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
            <!-- Per Page -->
            <div class="flex items-center bg-white rounded-xl shadow-2xs border border-gray-200 px-3 py-1.5">
                <span class="text-xs text-gray-500 mr-2">Tampil</span>
                <select class="form-select text-xs border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12 cursor-pointer" wire:model.live="perPage">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-xs text-gray-500 ml-1">data</span>
            </div>

            <!-- Status Filter -->
            <div class="flex items-center bg-white rounded-xl shadow-2xs border border-gray-200 px-3 py-1.5">
                <span class="text-xs text-gray-500 mr-2"><i class="fa-solid fa-filter text-gray-400 mr-1"></i>Status:</span>
                <select class="form-select text-xs border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent cursor-pointer" wire:model.live="filter_status">
                    <option value="">Semua Status</option>
                    <option value="1">Aktif / Dibuka</option>
                    <option value="0">Tutup / Nonaktif</option>
                </select>
            </div>
        </div>

        <!-- Search Input -->
        <div class="w-full lg:w-80">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
                </div>
                <input type="text"
                    class="block w-full pl-10 pr-4 py-2 border border-gray-200 rounded-xl leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[color:var(--primary)] text-xs shadow-2xs transition"
                    placeholder="Cari judul, deskripsi, atau slug..." wire:model.live.debounce.300ms="search">
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-gray-50/75 border-b border-gray-200/80 text-[11px] font-bold text-gray-600 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5 w-12 text-center whitespace-nowrap">No</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Kuisioner & Periode</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Link Akses Formulir</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Instrumen</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Responden</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Status</th>
                        <th class="px-5 py-3.5 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($questionnaires as $index => $q)
                        <tr class="hover:bg-blue-50/20 transition-colors duration-150">
                            <td class="px-5 py-4 text-center text-gray-500 font-semibold text-xs whitespace-nowrap">
                                {{ $questionnaires->firstItem() + $index }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="space-y-1">
                                    <a href="{{ route('admin.master.kuisioner.detail', ['slug' => $q->slug]) }}" 
                                       class="font-bold text-gray-900 text-sm hover:text-[color:var(--primary)] transition leading-snug block whitespace-nowrap">
                                        {{ $q->title }}
                                    </a>
                                    <div class="flex items-center gap-1.5 flex-wrap whitespace-nowrap">
                                        @if($q->academic_year || $q->semester)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold text-[11px] border border-blue-100 whitespace-nowrap">
                                                <i class="fa-regular fa-calendar text-[10px]"></i>
                                                TA: {{ $q->academic_year ?? '-' }} ({{ $q->semester ?? '-' }})
                                            </span>
                                        @endif
                                        @if($q->allow_guest)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-medium text-[11px] border border-emerald-100 whitespace-nowrap" title="Bisa diisi tamu/tanpa login">
                                                <i class="fa-solid fa-user-check text-[10px]"></i> Tamu Diizinkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-medium text-[11px] border border-purple-100 whitespace-nowrap" title="Hanya mahasiswa/user login">
                                                <i class="fa-solid fa-lock text-[10px]"></i> Wajib Login
                                            </span>
                                        @endif
                                    </div>
                                    @if($q->description)
                                        <p class="text-[11px] text-gray-400 truncate max-w-md pt-0.5">{{ $q->description }}</p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="inline-flex items-center bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-1 text-xs transition shadow-2xs whitespace-nowrap">
                                    <span class="font-mono text-gray-600 px-2 max-w-[170px] truncate select-all" title="{{ url('/kuisioner/' . $q->slug) }}">
                                        /kuisioner/{{ $q->slug }}
                                    </span>
                                    <button type="button" 
                                            onclick="copyToClipboard('{{ url('/kuisioner/' . $q->slug) }}')" 
                                            class="p-1 px-1.5 text-gray-400 hover:text-[color:var(--primary)] hover:bg-white rounded transition cursor-pointer" 
                                            title="Salin Tautan">
                                        <i class="fa-regular fa-copy text-xs"></i>
                                    </button>
                                    <a href="{{ url('/kuisioner/' . $q->slug) }}" target="_blank" 
                                       class="p-1 px-1.5 text-gray-400 hover:text-[color:var(--primary)] hover:bg-white rounded transition cursor-pointer" 
                                       title="Buka Formulir di Tab Baru">
                                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                    </a>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="inline-flex flex-col items-center">
                                    <span class="font-bold text-gray-800 text-xs whitespace-nowrap">
                                        {{ $q->questions_count }} Butir Soal
                                    </span>
                                    <span class="text-[11px] text-gray-400 mt-0.5 whitespace-nowrap">({{ $q->sections_count }} Bagian)</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.master.kuisioner.detail', ['slug' => $q->slug, 'activeTab' => 'responses']) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold transition whitespace-nowrap {{ $q->responses_count > 0 ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}"
                                   title="Lihat Data Responden">
                                    <i class="fa-solid fa-users text-[10px]"></i>
                                    <span>{{ $q->responses_count }} Respon</span>
                                </a>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <button type="button" wire:click="toggleStatus('{{ $q->id }}')" 
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold transition-all duration-150 cursor-pointer whitespace-nowrap {{ $q->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}"
                                        title="Klik untuk mengubah status kuisioner">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $q->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                    {{ $q->is_active ? 'Aktif' : 'Tutup' }}
                                </button>
                            </td>
                            <td class="px-5 py-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1.5 whitespace-nowrap">
                                    <a href="{{ route('admin.master.kuisioner.detail', ['slug' => $q->slug]) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 font-bold rounded-lg text-xs transition shadow-2xs whitespace-nowrap"
                                       title="Lihat Detail, Analisis & Hasil">
                                        <i class="fa-solid fa-chart-pie text-xs"></i>
                                        <span>Detail & Hasil</span>
                                    </a>
                                    <button type="button" 
                                            onclick="confirmDelete('{{ $q->id }}', '{{ addslashes($q->title) }}')"
                                            class="p-1.5 px-2 text-red-500 hover:bg-red-50 hover:text-red-700 rounded-lg transition border border-transparent hover:border-red-200 cursor-pointer"
                                            title="Hapus Kuisioner">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-clipboard-question text-2xl"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">Belum ada kuisioner ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Buat kuisioner baru untuk mulai menerima evaluasi modul dari mahasiswa.</p>
                                    <div class="mt-4">
                                        <a href="{{ route('admin.master.kuisioner.create') }}" class="btn btn-primary text-xs py-2 px-3">
                                            <i class="fa-solid fa-plus mr-1"></i> Buat Kuisioner
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questionnaires->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $questionnaires->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Tautan Tersalin!',
                text: 'Link kuisioner berhasil disalin ke clipboard.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        }).catch(function() {
            prompt("Salin tautan ini secara manual:", text);
        });
    }

    function confirmDelete(id, title) {
        Swal.fire({
            title: 'Hapus Kuisioner?',
            text: `Apakah Anda yakin ingin menghapus kuisioner "${title}"? Data respon yang telah masuk akan ikut terhapus.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.deleteQuestionnaire(id);
            }
        });
    }
</script>
@endpush
