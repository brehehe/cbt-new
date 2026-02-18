@section('title', 'Laporan Hasil Ujian')
<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-2xl font-bold text-[{{ $companyData->color_primary ?? '#2b7fff' }}]">
                    Laporan Hasil Ujian</h1>
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
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-1 center">No</th>
                        <th>Nama Siswa</th>
                        <th>NIM/Username</th>
                        <th>Modul</th>
                        <th>Jadwal</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        @if($companyData->is_mark)
                            <th>Nilai</th>
                            <th>Skala Penilaian</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examResults as $index => $result)
                        @php
                            $gradeDetail = $this->getGradeDetail($result->mark);
                        @endphp
                        <tr>
                            <td class="center">{{ $examResults->firstItem() + $index }}</td>
                            <td>{{ $result->user?->name ?? '-' }}</td>
                            <td>{{ $result->user?->nim ?? ($result->user?->username ?? '-') }}</td>
                            <td>{{ $result->timetable?->module?->name ?? '-' }}</td>
                            <td>{{ $result->timetable?->name ?? '-' }}</td>
                            <td>{{ $result->timetable?->start_time?->format('d F Y H:i') ?? '-' }}</td>
                            <td>{{ $result->timetable?->end_time?->format('d F Y H:i') ?? '-' }}</td>
                            @if($companyData->is_mark)
                                <td>{{ $result->mark ?? '-' }}</td>
                                <td>
                                    <div class="flex flex-col">
                                        <span class="font-semibold">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                                        <span class="text-xs text-gray-500">{{ $gradeDetail?->description ?? '-' }}</span>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $companyData->is_mark ? 10 : 8 }}" class="no-data">Tidak ada data</td>
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
