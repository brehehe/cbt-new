@section('title', 'Laporan Hasil Ujian')
<div>
    <div class="mb-4">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Laporan Hasil Ujian</h1>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="exportExcel" class="btn btn-success flex items-center gap-2 shadow-sm font-semibold text-xs py-2 px-3">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button wire:click="exportPdf" class="btn btn-danger flex items-center gap-2 shadow-sm font-semibold text-xs py-2 px-3">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>
    </div>

    <!-- CSS styling helper -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Filter Controls -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div>
            <label for="filter_user" class="block text-sm font-medium text-gray-700">Mahasiswa</label>
            <select id="filter_user" class="mt-1 form-control" wire:model.live="user_id">
                <option value="">Semua Mahasiswa</option>
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
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-200 px-3 py-2 w-full md:w-auto">
            <span class="text-sm text-gray-600 mr-2">Tampil</span>
            <select
                class="form-select text-sm border-none focus:ring-0 p-0 text-gray-700 font-semibold bg-transparent w-12"
                wire:model.live='perPage'>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <span class="text-sm text-gray-600 ml-2">data</span>
        </div>

        <div class="w-full md:w-72">
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
        <div class="table-container overflow-x-auto w-full">
            <table class="table whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="w-1 center">Detail</th>
                        <th class="w-1 center">No</th>
                        <th>Nama Mahasiswa</th>
                        <th>NIM/Username</th>
                        <th>Modul</th>
                        <th>Jadwal</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th class="center">Soal</th>
                        <th class="center text-green-600">Benar</th>
                        <th class="center text-red-500">Salah</th>
                        <th class="center text-yellow-600">TJ</th>
                        <th>Nilai</th>
                        <th>Skala Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($examResults as $index => $result)
                        @php
                            $gradeDetail = $this->getGradeDetail($result->mark);
                            $totalQ = $result->userModuleQuestions->count();
                            $correct = $result->userModuleQuestions->where('status', 'correct')->count();
                            $wrong = $result->userModuleQuestions->where('status', 'wrong')->count();
                            $unanswered = $result->userModuleQuestions->whereNull('timetable_answer_id')->count();
                        @endphp
                        <tr x-data="{ open: false }" wire:key="row-{{ $result->id }}">
                            <td class="center">
                                <button @click="open = !open" class="text-gray-400 hover:text-gray-700 focus:outline-none transition-colors">
                                    <i class="fas" :class="open ? 'fa-chevron-down' : 'fa-chevron-right'"></i>
                                </button>
                            </td>
                            <td class="center">{{ $examResults->firstItem() + $index }}</td>
                            <td>
                                <div class="font-semibold text-gray-800">{{ $result->user?->name ?? '-' }}</div>
                            </td>
                            <td>{{ $result->user?->nim ?? ($result->user?->username ?? '-') }}</td>
                            <td>{{ $result->timetable?->module?->name ?? '-' }}</td>
                            <td>{{ $result->timetable?->name ?? '-' }}</td>
                            <td>{{ $result->timetable?->start_time?->format('d F Y H:i') ?? '-' }}</td>
                            <td>{{ $result->timetable?->end_time?->format('d F Y H:i') ?? '-' }}</td>
                            <td class="center font-semibold text-gray-700">{{ $totalQ }}</td>
                            <td class="center font-semibold text-green-600 bg-green-50/50">{{ $correct }}</td>
                            <td class="center font-semibold text-red-500 bg-red-50/50">{{ $wrong }}</td>
                            <td class="center font-semibold text-yellow-600 bg-yellow-50/50">{{ $unanswered }}</td>
                            <td class="font-bold text-gray-900">{{ $result->mark ?? '-' }}</td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-primary">{{ $gradeDetail?->grade_letter ?? '-' }}</span>
                                    <span class="text-xs text-gray-500">{{ $gradeDetail?->description ?? '-' }}</span>
                                </div>
                            </td>
                        </tr>

                        <!-- Collapsible topic breakdown details row -->
                        @php
                            $topicStats = $this->getTopicStats($result);
                        @endphp
                        <tr x-show="open" x-cloak wire:key="details-{{ $result->id }}">
                            <td colspan="14" class="bg-gray-50/50 p-4 border-b border-gray-100 whitespace-normal">
                                <div class="max-w-5xl mx-auto">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="fas fa-chart-pie text-gray-500 text-sm"></i>
                                        <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Detail Analisis Hasil per Topik Ujian</span>
                                    </div>
                                    @if (!empty($topicStats))
                                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                                            @foreach ($topicStats as $topicId => $topic)
                                                <div class="bg-white p-3.5 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                                                    <div class="font-bold text-gray-800 text-xs truncate mb-2 pb-1 border-b border-gray-100" title="{{ $topic['name'] }}">
                                                        {{ $topic['name'] }}
                                                    </div>
                                                    <div class="grid grid-cols-4 gap-1 text-center">
                                                        <div>
                                                            <div class="text-[9px] text-gray-400 font-bold uppercase">Soal</div>
                                                            <div class="text-xs font-black text-gray-700 mt-0.5">{{ $topic['total'] }}</div>
                                                        </div>
                                                        <div>
                                                            <div class="text-[9px] text-green-500 font-bold uppercase">B</div>
                                                            <div class="text-xs font-black text-green-600 mt-0.5">{{ $topic['correct'] }}</div>
                                                        </div>
                                                        <div>
                                                            <div class="text-[9px] text-red-400 font-bold uppercase">S</div>
                                                            <div class="text-xs font-black text-red-500 mt-0.5">{{ $topic['wrong'] }}</div>
                                                        </div>
                                                        <div>
                                                            <div class="text-[9px] text-yellow-500 font-bold uppercase">TJ</div>
                                                            <div class="text-xs font-black text-yellow-600 mt-0.5">{{ $topic['unanswered'] }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400 italic">Tidak ada pembagian topik pada modul ujian ini.</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="no-data">Tidak ada data</td>
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