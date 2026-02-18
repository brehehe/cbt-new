@section('title', 'Laporan Hasil Lengkap')
<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                    Laporan Hasil Lengkap</h1>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div>
            <label for="filter_user" class="block text-sm font-medium text-gray-700">User</label>
            <select id="filter_user" class="mt-1 form-control" wire:model.live="user_id">
                <option value="">Semua User</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name ?? '-' }}
                        @if (!empty($user->nim)) - {{ $user->nim }} @endif
                        @if (empty($user->nim) && !empty($user->username)) - {{ $user->username }} @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filter_module" class="block text-sm font-medium text-gray-700">Modul</label>
            <select id="filter_module" class="mt-1 form-control" wire:model.live="module_id">
                <option value="">Semua Modul</option>
                @foreach ($modules as $module)
                    <option value="{{ $module->id }}">{{ $module->name ?? '-' }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="filter_timetable" class="block text-sm font-medium text-gray-700">Jadwal</label>
            <select id="filter_timetable" class="mt-1 form-control" wire:model.live="timetable_id">
                <option value="">Semua Jadwal</option>
                @foreach ($timetables as $timetable)
                    <option value="{{ $timetable->id }}">
                        {{ $timetable->name ?? '-' }}
                        @if ($timetable->module)
                            - {{ $timetable->module->name }}
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table Controls -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
        <div class="flex items-center">
            <span class="text-sm text-gray-700 mr-2">Tampil</span>
            <select class="mt-1 form-control" wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-700 ml-2">data</span>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
                <input type="text" class="mt-1 form-control-search" placeholder="Cari Sesuatu..."
                    wire:model.live='search'>
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search h-3 w-3 text-gray-400"></i>
                </div>
            </div>
            <button wire:click="exportPdf" class="btn btn-primary" title="Export ke PDF">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="table-container overflow-x-auto">
            <table class="table w-full text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 center w-1">No</th>
                        <th class="px-4 py-3">Nama Peserta</th>
                        <th class="px-4 py-3">Jadwal & Modul</th>
                        <th class="px-4 py-3 center">Waktu Pelaksanaan</th>
                        <th class="px-4 py-3 center">Durasi</th>
                        <th class="px-4 py-3 center">Statistik Jawaban</th>
                        @if($companyData->is_mark)
                            <th class="px-4 py-3 center">Nilai Akhir</th>
                        @endif
                        <th class="px-4 py-3 center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examResults as $index => $result)
                        @php
                            $stats = $resultStats[$result->id] ?? ['correct' => 0, 'wrong' => 0, 'unanswered' => 0, 'total' => 0, 'answered' => 0];
                            $gradeDetail = $this->getGradeDetail($result->mark);
                            
                            // Calculate duration
                            $start = $result->start_exam ? \Carbon\Carbon::parse($result->start_exam) : null;
                            $end = $result->end_exam ? \Carbon\Carbon::parse($result->end_exam) : null;
                            $duration = ($start && $end) ? $start->diffInMinutes($end) . ' Menit' : '-';
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 center">{{ $examResults->firstItem() + $index }}</td>
                            <td class="px-4 py-3">
                                <div class="font-semibold">{{ $result->user?->name ?? '-' }}</div>
                                <div class="text-gray-500 text-xs">{{ $result->user?->nim ?? ($result->user?->username ?? '-') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $result->timetable?->name ?? '-' }}</div>
                                <div class="text-gray-500 text-xs">{{ $result->timetable?->module?->name ?? '-' }}</div>
                            </td>
                            <td class="px-4 py-3 center">
                                <div class="text-xs">
                                    <div>Mulai: {{ $result->start_exam ? \Carbon\Carbon::parse($result->start_exam)->format('d/m/y H:i') : '-' }}</div>
                                    <div>Selesai: {{ $result->end_exam ? \Carbon\Carbon::parse($result->end_exam)->format('d/m/y H:i') : '-' }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-3 center">
                                {{ $duration }}
                            </td>
                            <td class="px-4 py-3 center">
                                <div class="flex flex-col items-center justify-center text-xs space-y-1">
                                    <div class="mb-1 font-semibold text-blue-600">
                                        Terjawab: {{ $stats['answered'] }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-0.5 rounded-full bg-green-100 text-green-800" title="Benar">
                                            <i class="fas fa-check mr-1"></i>{{ $stats['correct'] }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-800" title="Salah">
                                            <i class="fas fa-times mr-1"></i>{{ $stats['wrong'] }}
                                        </span>
                                    </div>
                                    @if($stats['check'] > 0 || $stats['unanswered'] > 0)
                                    <div class="flex items-center space-x-2">
                                        @if($stats['check'] > 0)
                                        <span class="px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800" title="Perlu Cek">
                                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $stats['check'] }}
                                        </span>
                                        @endif
                                        @if($stats['unanswered'] > 0)
                                        <span class="px-2 py-0.5 rounded-full bg-gray-100 text-gray-800" title="Tidak Dijawab">
                                            <i class="fas fa-minus mr-1"></i>{{ $stats['unanswered'] }}
                                        </span>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </td>
                            @if($companyData->is_mark)
                                <td class="px-4 py-3 center">
                                    <div class="text-lg font-bold text-gray-900">{{ $result->mark ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $gradeDetail ? $gradeDetail->grade_letter : '' }}</div>
                                </td>
                            @endif
                            <td class="px-4 py-3 center">
                                <a href="{{ route('admin.master.timetable.answer', ['timetable_id' => $result->timetable_id, 'user_timetable_id' => $result->id]) }}" 
                                   class="btn btn-sm btn-primary" title="Lihat Detail Soal">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $companyData->is_mark ? 8 : 7 }}" class="px-4 py-3 center text-gray-500">Tidak ada data hasil ujian yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-5 py-4 bg-gray-50/80 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $examResults->firstItem() }}</span> sampai <span
                        class="font-medium">{{ $examResults->lastItem() }}</span> dari <span
                        class="font-medium">{{ $examResults->total() }}</span> hasil
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        {{ $examResults->links('vendor.livewire.custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
