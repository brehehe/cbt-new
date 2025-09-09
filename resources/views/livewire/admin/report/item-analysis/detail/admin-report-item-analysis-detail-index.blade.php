@section('title', 'Detail Analisis Butir Soal')
@push('styles')
    <style>
        @media print {

            .btn,
            .no-print {
                display: none !important;
            }
        }

        /* Custom scrollbar untuk tabel */
        .table-container::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animation untuk modal */
        #questionModal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Highlight untuk search results */
        .highlight {
            background-color: #fef3c7;
            padding: 1px 2px;
            border-radius: 2px;
        }

        /* Sticky table header */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 10;
            background: white;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .mobile-scroll {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Kbd styling */
        kbd {
            font-family: monospace;
            font-size: 0.85em;
            font-weight: 600;
            border: 1px solid #d1d5db;
        }

        /* Loading indicator */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush

<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[#3BA172]">Detail Analisis Butir Soal</h1>
                <p class="text-gray-600 mt-2">
                    Ujian: <strong>{{ $timetable->name ?? 'Tidak diketahui' }}</strong> |
                    Modul: <strong>{{ $timetableModule->name ?? 'Tidak diketahui' }}</strong>
                </p>
                <p class="text-sm text-gray-500">
                    Periode: {{ $timetable->start_time?->format('d M Y H:i') }} -
                    {{ $timetable->end_time?->format('d M Y H:i') }}
                </p>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.report.item-analysis') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden md:block text-xs text-gray-500 mb-4">
        <div class="bg-gray-100 px-2 py-1 rounded text-gray-600">
            Shortcut: <kbd class="bg-white px-1 rounded shadow">Ctrl+F</kbd> Cari |
            <kbd class="bg-white px-1 rounded shadow">Ctrl+1</kbd> Tabel |
            <kbd class="bg-white px-1 rounded shadow">Ctrl+2</kbd> Detail |
            <kbd class="bg-white px-1 rounded shadow">Ctrl+R</kbd> Reset
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-600">Total Peserta</p>
                    <p class="text-lg font-bold text-blue-800">{{ $userTimetables->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-question-circle text-green-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-600">Total Soal</p>
                    <p class="text-lg font-bold text-green-800">{{ $timetableQuestions->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-yellow-600">Durasi</p>
                    <p class="text-lg font-bold text-yellow-800">{{ $timetableModule->duration ?? 0 }} Menit</p>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-purple-600">Rata-rata Kesulitan</p>
                    @php
                        $avgDifficulty = collect($itemAnalysisData)->avg('difficulty_index');
                    @endphp
                    <p class="text-lg font-bold text-purple-800">{{ number_format($avgDifficulty, 3) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-4 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Panduan Interpretasi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Tingkat Kesukaran (P):</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><span class="inline-block w-3 h-3 bg-red-500 rounded mr-2"></span>P < 0.3=Sukar</li>
                    <li><span class="inline-block w-3 h-3 bg-yellow-500 rounded mr-2"></span>0.3 ≤ P < 0.7=Sedang</li>
                    <li><span class="inline-block w-3 h-3 bg-green-500 rounded mr-2"></span>P ≥ 0.7 = Mudah</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-2">Daya Pembeda (D):</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li><span class="inline-block w-3 h-3 bg-red-500 rounded mr-2"></span>D < 0.2=Buruk</li>
                    <li><span class="inline-block w-3 h-3 bg-yellow-500 rounded mr-2"></span>0.2 ≤ D <
                            0.4=Cukup/Baik</li>
                    <li><span class="inline-block w-3 h-3 bg-green-500 rounded mr-2"></span>D ≥ 0.4 = Sangat Baik</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Filter dan Search -->
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4 mb-4">
            <div class="flex-1 min-w-64">
                <input type="text" id="searchQuestion" placeholder="Cari soal berdasarkan teks..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="flex gap-2">
                <select id="filterDifficulty"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Tingkat</option>
                    <option value="Mudah">Mudah (P ≥ 0.7)</option>
                    <option value="Sedang">Sedang (0.3 ≤ P < 0.7)</option>
                    <option value="Sukar">Sukar (P < 0.3)</option>
                </select>
                <select id="filterDiscrimination"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Daya Pembeda</option>
                    <option value="Sangat Baik">Sangat Baik (D ≥ 0.4)</option>
                    <option value="Baik">Baik (0.3 ≤ D < 0.4)</option>
                    <option value="Cukup">Cukup (0.2 ≤ D < 0.3)</option>
                    <option value="Buruk">Buruk (0.1 ≤ D < 0.2)</option>
                    <option value="Sangat Buruk">Sangat Buruk (D < 0.1)</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button onclick="toggleView('table')" id="btnTableView"
                    class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-table mr-2"></i>Tabel
                </button>
                <button onclick="toggleView('detail')" id="btnDetailView"
                    class="px-3 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                    <i class="fas fa-list mr-2"></i>Detail
                </button>
                <button onclick="clearFilters()"
                    class="px-3 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </button>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div class="text-center">
                <span class="block font-semibold text-gray-600"
                    id="visibleQuestions">{{ count($itemAnalysisData) }}</span>
                <span class="text-gray-500">Soal Ditampilkan</span>
            </div>
            <div class="text-center">
                <span class="block font-semibold text-blue-600" id="avgDifficulty">
                    @php
                        $avgDiff = collect($itemAnalysisData)->avg('difficulty_index');
                    @endphp
                    {{ number_format($avgDiff, 3) }}
                </span>
                <span class="text-gray-500">Rata-rata P</span>
            </div>
            <div class="text-center">
                <span class="block font-semibold text-purple-600" id="avgDiscrimination">
                    @php
                        $avgDisc = collect($itemAnalysisData)->avg('discrimination_index');
                    @endphp
                    {{ number_format($avgDisc, 3) }}
                </span>
                <span class="text-gray-500">Rata-rata D</span>
            </div>
            <div class="text-center">
                <span class="block font-semibold text-green-600" id="qualityQuestions">
                    @php
                        $quality = collect($itemAnalysisData)
                            ->filter(function ($item) {
                                return $item['difficulty_index'] >= 0.3 &&
                                    $item['difficulty_index'] <= 0.7 &&
                                    $item['discrimination_index'] >= 0.3;
                            })
                            ->count();
                    @endphp
                    {{ $quality }}
                </span>
                <span class="text-gray-500">Soal Berkualitas</span>
            </div>
        </div>
    </div>

    <!-- Tabel Ringkas -->
    <div id="tableView"
        class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
        <div class="overflow-x-auto table-container mobile-scroll" style="max-height: 70vh;">
            <table class="min-w-full">
                <thead class="bg-gray-50 sticky-header">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Soal
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                            title="Total Peserta">
                            <i class="fas fa-users mr-1"></i>Peserta
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                            title="Jawaban Benar">
                            <i class="fas fa-check mr-1"></i>Benar
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                            title="Tingkat Kesukaran (Difficulty Index)">
                            <i class="fas fa-chart-line mr-1"></i>P
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
                            title="Daya Pembeda (Discrimination Index)">
                            <i class="fas fa-balance-scale mr-1"></i>D
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tingkat</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Daya Pembeda</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($itemAnalysisData as $questionId => $analysis)
                        @php
                            $difficultyColor =
                                $analysis['difficulty_index'] >= 0.7
                                    ? 'bg-green-100 text-green-800'
                                    : ($analysis['difficulty_index'] >= 0.3
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800');
                            $discriminationColor =
                                $analysis['discrimination_index'] >= 0.4
                                    ? 'bg-green-100 text-green-800'
                                    : ($analysis['discrimination_index'] >= 0.2
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800');
                        @endphp
                        <tr class="hover:bg-gray-50 question-row"
                            data-difficulty="{{ $analysis['difficulty_level'] }}"
                            data-discrimination="{{ $analysis['discrimination_level'] }}"
                            data-question="{{ strtolower(strip_tags($analysis['question']->question ?? '')) }}">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                                <div class="truncate"
                                    title="{{ strip_tags($analysis['question']->question ?? '') }}">
                                    {!! \Str::limit(strip_tags($analysis['question']->question ?? ''), 60) !!}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">
                                {{ $analysis['total_participants'] }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">{{ $analysis['correct_answers'] }}
                            </td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">
                                {{ $analysis['difficulty_index'] }}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-900">
                                {{ $analysis['discrimination_index'] }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $difficultyColor }}">
                                    {{ $analysis['difficulty_level'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $discriminationColor }}">
                                    {{ $analysis['discrimination_level'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="showQuestionDetail({{ $loop->iteration }})"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detail View (Hidden by default) -->
    <div id="detailView" class="hidden">
        @foreach ($itemAnalysisData as $questionId => $analysis)
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Soal {{ $loop->iteration }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {!! \Str::limit(strip_tags($analysis['question']->question ?? ''), 100) !!}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="flex space-x-2">
                                @php
                                    $difficultyColor =
                                        $analysis['difficulty_index'] >= 0.7
                                            ? 'bg-green-100 text-green-800'
                                            : ($analysis['difficulty_index'] >= 0.3
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800');
                                    $discriminationColor =
                                        $analysis['discrimination_index'] >= 0.4
                                            ? 'bg-green-100 text-green-800'
                                            : ($analysis['discrimination_index'] >= 0.2
                                                ? 'bg-yellow-100 text-yellow-800'
                                                : 'bg-red-100 text-red-800');
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $difficultyColor }}">
                                    {{ $analysis['difficulty_level'] }}
                                </span>
                                <span class="px-2 py-1 text-xs font-medium rounded {{ $discriminationColor }}">
                                    {{ $analysis['discrimination_level'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Statistik Utama --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Statistik Utama</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="text-sm font-medium text-gray-600">Total Peserta</span>
                                    <span class="font-bold text-gray-800">{{ $analysis['total_participants'] }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                    <span class="text-sm font-medium text-green-600">Jawaban Benar</span>
                                    <span class="font-bold text-green-800">{{ $analysis['correct_answers'] }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                    <span class="text-sm font-medium text-red-600">Jawaban Salah</span>
                                    <span class="font-bold text-red-800">{{ $analysis['incorrect_answers'] }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Indeks Analisis --}}
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-3">Indeks Analisis</h4>
                            <div class="space-y-3">
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-blue-600">Tingkat Kesukaran (P)</span>
                                        <span
                                            class="font-bold text-blue-800">{{ $analysis['difficulty_index'] }}</span>
                                    </div>
                                    <div class="mt-1">
                                        <div class="w-full bg-blue-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                style="width: {{ $analysis['difficulty_index'] * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-purple-50 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-purple-600">Daya Pembeda (D)</span>
                                        <span
                                            class="font-bold text-purple-800">{{ $analysis['discrimination_index'] }}</span>
                                    </div>
                                    <div class="mt-1">
                                        <div class="w-full bg-purple-200 rounded-full h-2">
                                            <div class="bg-purple-600 h-2 rounded-full"
                                                style="width: {{ max(0, $analysis['discrimination_index'] * 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 bg-orange-50 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-orange-600">Kontribusi
                                            Reliabilitas</span>
                                        <span
                                            class="font-bold text-orange-800">{{ $analysis['reliability_contribution'] }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Analisis Kelompok --}}
                    @if ($analysis['upper_group_total'] > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-3">Analisis Kelompok (27% Atas vs 27% Bawah)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h5 class="font-medium text-green-800 mb-2">Kelompok Atas</h5>
                                    <p class="text-sm text-green-600">
                                        {{ $analysis['upper_group_correct'] }} dari
                                        {{ $analysis['upper_group_total'] }}
                                        peserta menjawab benar
                                        ({{ number_format(($analysis['upper_group_correct'] / $analysis['upper_group_total']) * 100, 1) }}%)
                                    </p>
                                </div>
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <h5 class="font-medium text-red-800 mb-2">Kelompok Bawah</h5>
                                    <p class="text-sm text-red-600">
                                        {{ $analysis['lower_group_correct'] }} dari
                                        {{ $analysis['lower_group_total'] }}
                                        peserta menjawab benar
                                        ({{ number_format(($analysis['lower_group_correct'] / $analysis['lower_group_total']) * 100, 1) }}%)
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Analisis Opsi Jawaban --}}
                    @if (!empty($analysis['option_analysis']))
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-semibold text-gray-800 mb-3">Analisis Opsi Jawaban</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Opsi</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Jawaban</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Dipilih</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Persentase</th>
                                            <th
                                                class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach ($analysis['option_analysis'] as $option)
                                            <tr class="{{ $option['is_correct'] ? 'bg-green-50' : '' }}">
                                                <td class="px-3 py-2 text-sm font-medium text-gray-900">
                                                    {{ $option['option']->alphabet ?? chr(65 + $loop->index) }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-gray-600">
                                                    {!! \Str::limit($option['option']->context ?? '', 50) !!}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-gray-900">
                                                    {{ $option['selected_count'] }}
                                                </td>
                                                <td class="px-3 py-2 text-sm text-gray-900">
                                                    <div class="flex items-center">
                                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                            <div class="bg-blue-600 h-2 rounded-full"
                                                                style="width: {{ $option['percentage'] }}%"></div>
                                                        </div>
                                                        {{ $option['percentage'] }}%
                                                    </div>
                                                </td>
                                                <td class="px-3 py-2 text-sm">
                                                    @if ($option['is_correct'])
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            Benar
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            Salah
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Rekomendasi --}}
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="font-semibold text-gray-800 mb-3">Rekomendasi</h4>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <ul class="text-sm text-blue-800 space-y-1">
                                @if ($analysis['difficulty_index'] < 0.3)
                                    <li>• Soal terlalu sukar, pertimbangkan untuk merevisi atau mengganti dengan soal
                                        yang
                                        lebih mudah</li>
                                @elseif($analysis['difficulty_index'] > 0.7)
                                    <li>• Soal terlalu mudah, pertimbangkan untuk membuat soal yang lebih menantang</li>
                                @else
                                    <li>• Tingkat kesukaran soal sudah baik (sedang)</li>
                                @endif

                                @if ($analysis['discrimination_index'] < 0.2)
                                    <li>• Daya pembeda rendah, soal perlu diperbaiki atau diganti</li>
                                @elseif($analysis['discrimination_index'] < 0.4)
                                    <li>• Daya pembeda cukup, masih bisa ditingkatkan</li>
                                @else
                                    <li>• Daya pembeda sangat baik, soal berkualitas tinggi</li>
                                @endif

                                @if ($analysis['reliability_contribution'] < 0.05)
                                    <li>• Kontribusi terhadap reliabilitas rendah</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Modal Detail Soal -->
    <div id="questionModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-gray-800" id="modalTitle">Detail Soal</h3>
                        <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="modalContent">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Summary dan Export --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Ringkasan Analisis</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Dari {{ count($itemAnalysisData) }} soal yang dianalisis
                </p>
            </div>
            {{-- <div class="flex space-x-3">
                <button class="btn btn-success" onclick="exportToExcel()">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div> --}}
        </div>

        @php
            $easyCount = collect($itemAnalysisData)->where('difficulty_level', 'Mudah')->count();
            $mediumCount = collect($itemAnalysisData)->where('difficulty_level', 'Sedang')->count();
            $hardCount = collect($itemAnalysisData)->where('difficulty_level', 'Sukar')->count();

            $excellentDiscrimination = collect($itemAnalysisData)
                ->filter(function ($item) {
                    return $item['discrimination_index'] >= 0.4;
                })
                ->count();
            $goodDiscrimination = collect($itemAnalysisData)
                ->filter(function ($item) {
                    return $item['discrimination_index'] >= 0.2 && $item['discrimination_index'] < 0.4;
                })
                ->count();
            $poorDiscrimination = collect($itemAnalysisData)
                ->filter(function ($item) {
                    return $item['discrimination_index'] < 0.2;
                })
                ->count();

            $totalQuestions = count($itemAnalysisData);
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mt-4">
            <div class="text-center p-3 bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-800">{{ $easyCount }}</p>
                <p class="text-sm text-green-600">Mudah</p>
                <p class="text-xs text-green-500">
                    {{ $totalQuestions > 0 ? round(($easyCount / $totalQuestions) * 100, 1) : 0 }}%</p>
            </div>
            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <p class="text-2xl font-bold text-yellow-800">{{ $mediumCount }}</p>
                <p class="text-sm text-yellow-600">Sedang</p>
                <p class="text-xs text-yellow-500">
                    {{ $totalQuestions > 0 ? round(($mediumCount / $totalQuestions) * 100, 1) : 0 }}%</p>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-lg">
                <p class="text-2xl font-bold text-red-800">{{ $hardCount }}</p>
                <p class="text-sm text-red-600">Sukar</p>
                <p class="text-xs text-red-500">
                    {{ $totalQuestions > 0 ? round(($hardCount / $totalQuestions) * 100, 1) : 0 }}%</p>
            </div>
            <div class="text-center p-3 bg-emerald-50 rounded-lg">
                <p class="text-2xl font-bold text-emerald-800">{{ $excellentDiscrimination }}</p>
                <p class="text-sm text-emerald-600">Sangat Baik</p>
                <p class="text-xs text-emerald-500">
                    {{ $totalQuestions > 0 ? round(($excellentDiscrimination / $totalQuestions) * 100, 1) : 0 }}%</p>
            </div>
            <div class="text-center p-3 bg-amber-50 rounded-lg">
                <p class="text-2xl font-bold text-amber-800">{{ $goodDiscrimination }}</p>
                <p class="text-sm text-amber-600">Cukup</p>
                <p class="text-xs text-amber-500">
                    {{ $totalQuestions > 0 ? round(($goodDiscrimination / $totalQuestions) * 100, 1) : 0 }}%</p>
            </div>
            <div class="text-center p-3 bg-rose-50 rounded-lg">
                <p class="text-2xl font-bold text-rose-800">{{ $poorDiscrimination }}</p>
                <p class="text-sm text-rose-600">Buruk</p>
                <p class="text-xs text-rose-500">
                    {{ $totalQuestions > 0 ? round(($poorDiscrimination / $totalQuestions) * 100, 1) : 0 }}%</p>
            </div>
            <div class="text-center p-3 bg-indigo-50 rounded-lg">
                <p class="text-2xl font-bold text-indigo-800">{{ $totalQuestions }}</p>
                <p class="text-sm text-indigo-600">Total Soal</p>
                <p class="text-xs text-indigo-500">100%</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Data soal untuk modal
        const questionData = @json($itemAnalysisData);

        // Toggle view antara tabel dan detail
        function toggleView(view) {
            const tableView = document.getElementById('tableView');
            const detailView = document.getElementById('detailView');
            const btnTable = document.getElementById('btnTableView');
            const btnDetail = document.getElementById('btnDetailView');

            if (view === 'table') {
                tableView.classList.remove('hidden');
                detailView.classList.add('hidden');
                btnTable.classList.remove('bg-gray-300', 'text-gray-700');
                btnTable.classList.add('bg-blue-600', 'text-white');
                btnDetail.classList.remove('bg-blue-600', 'text-white');
                btnDetail.classList.add('bg-gray-300', 'text-gray-700');
            } else {
                tableView.classList.add('hidden');
                detailView.classList.remove('hidden');
                btnDetail.classList.remove('bg-gray-300', 'text-gray-700');
                btnDetail.classList.add('bg-blue-600', 'text-white');
                btnTable.classList.remove('bg-blue-600', 'text-white');
                btnTable.classList.add('bg-gray-300', 'text-gray-700');
            }
        }

        // Search functionality
        document.getElementById('searchQuestion').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            filterTable();
        });

        // Filter functionality
        document.getElementById('filterDifficulty').addEventListener('change', filterTable);
        document.getElementById('filterDiscrimination').addEventListener('change', filterTable);

        function filterTable() {
            const searchTerm = document.getElementById('searchQuestion').value.toLowerCase();
            const difficultyFilter = document.getElementById('filterDifficulty').value;
            const discriminationFilter = document.getElementById('filterDiscrimination').value;
            const rows = document.querySelectorAll('.question-row');

            let visibleCount = 0;
            let totalDifficulty = 0;
            let totalDiscrimination = 0;
            let qualityCount = 0;

            rows.forEach(row => {
                const questionText = row.getAttribute('data-question');
                const difficulty = row.getAttribute('data-difficulty');
                const discrimination = row.getAttribute('data-discrimination');

                const matchesSearch = searchTerm === '' || questionText.includes(searchTerm);
                const matchesDifficulty = difficultyFilter === '' || difficulty === difficultyFilter;
                const matchesDiscrimination = discriminationFilter === '' || discrimination ===
                    discriminationFilter;

                if (matchesSearch && matchesDifficulty && matchesDiscrimination) {
                    row.style.display = '';
                    visibleCount++;

                    // Update quick stats
                    const difficultyValue = parseFloat(row.querySelector('td:nth-child(5)').textContent);
                    const discriminationValue = parseFloat(row.querySelector('td:nth-child(6)').textContent);

                    totalDifficulty += difficultyValue;
                    totalDiscrimination += discriminationValue;

                    if (difficultyValue >= 0.3 && difficultyValue <= 0.7 && discriminationValue >= 0.3) {
                        qualityCount++;
                    }
                } else {
                    row.style.display = 'none';
                }
            });

            // Update quick stats
            document.getElementById('visibleQuestions').textContent = visibleCount;
            document.getElementById('avgDifficulty').textContent = visibleCount > 0 ? (totalDifficulty / visibleCount)
                .toFixed(3) : '0.000';
            document.getElementById('avgDiscrimination').textContent = visibleCount > 0 ? (totalDiscrimination /
                visibleCount).toFixed(3) : '0.000';
            document.getElementById('qualityQuestions').textContent = qualityCount;
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('searchQuestion').value = '';
            document.getElementById('filterDifficulty').value = '';
            document.getElementById('filterDiscrimination').value = '';
            filterTable();
        }

        // Show question detail in modal
        function showQuestionDetail(questionNumber) {
            const questionArray = Object.values(questionData);
            const questionIndex = questionNumber - 1;
            const analysis = questionArray[questionIndex];

            if (!analysis) return;

            document.getElementById('modalTitle').textContent = `Detail Soal ${questionNumber}`;

            const difficultyColor = analysis.difficulty_index >= 0.7 ? 'bg-green-100 text-green-800' :
                (analysis.difficulty_index >= 0.3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
            const discriminationColor = analysis.discrimination_index >= 0.4 ? 'bg-green-100 text-green-800' :
                (analysis.discrimination_index >= 0.2 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');

            const modalContent = `
            <div class="space-y-6">
                <!-- Question Text -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-800 mb-2">Soal:</h4>
                    <div class="text-gray-700">${analysis.question?.question || 'Tidak ada teks soal'}</div>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">Statistik Utama</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-600">Total Peserta</span>
                                <span class="font-bold text-gray-800">${analysis.total_participants}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="text-sm font-medium text-green-600">Jawaban Benar</span>
                                <span class="font-bold text-green-800">${analysis.correct_answers}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                <span class="text-sm font-medium text-red-600">Jawaban Salah</span>
                                <span class="font-bold text-red-800">${analysis.incorrect_answers}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">Indeks Analisis</h4>
                        <div class="space-y-3">
                            <div class="p-3 bg-blue-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-blue-600">Tingkat Kesukaran (P)</span>
                                    <span class="font-bold text-blue-800">${analysis.difficulty_index}</span>
                                </div>
                                <div class="mt-1">
                                    <div class="w-full bg-blue-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: ${analysis.difficulty_index * 100}%"></div>
                                    </div>
                                </div>
                                <span class="inline-block mt-2 px-2 py-1 text-xs font-medium rounded ${difficultyColor}">
                                    ${analysis.difficulty_level}
                                </span>
                            </div>

                            <div class="p-3 bg-purple-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-purple-600">Daya Pembeda (D)</span>
                                    <span class="font-bold text-purple-800">${analysis.discrimination_index}</span>
                                </div>
                                <div class="mt-1">
                                    <div class="w-full bg-purple-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: ${Math.max(0, analysis.discrimination_index * 100)}%"></div>
                                    </div>
                                </div>
                                <span class="inline-block mt-2 px-2 py-1 text-xs font-medium rounded ${discriminationColor}">
                                    ${analysis.discrimination_level}
                                </span>
                            </div>

                            <div class="p-3 bg-orange-50 rounded-lg">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-orange-600">Kontribusi Reliabilitas</span>
                                    <span class="font-bold text-orange-800">${analysis.reliability_contribution}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group Analysis -->
                ${analysis.upper_group_total > 0 ? `
                                                                <div class="pt-6 border-t border-gray-200">
                                                                    <h4 class="font-semibold text-gray-800 mb-3">Analisis Kelompok (27% Atas vs 27% Bawah)</h4>
                                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        <div class="bg-green-50 p-4 rounded-lg">
                                                                            <h5 class="font-medium text-green-800 mb-2">Kelompok Atas</h5>
                                                                            <p class="text-sm text-green-600">
                                                                                ${analysis.upper_group_correct} dari ${analysis.upper_group_total} peserta menjawab benar
                                                                                (${((analysis.upper_group_correct / analysis.upper_group_total) * 100).toFixed(1)}%)
                                                                            </p>
                                                                        </div>
                                                                        <div class="bg-red-50 p-4 rounded-lg">
                                                                            <h5 class="font-medium text-red-800 mb-2">Kelompok Bawah</h5>
                                                                            <p class="text-sm text-red-600">
                                                                                ${analysis.lower_group_correct} dari ${analysis.lower_group_total} peserta menjawab benar
                                                                                (${((analysis.lower_group_correct / analysis.lower_group_total) * 100).toFixed(1)}%)
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                ` : ''}

                <!-- Option Analysis -->
                ${analysis.option_analysis && analysis.option_analysis.length > 0 ? `
                                                                <div class="pt-6 border-t border-gray-200">
                                                                    <h4 class="font-semibold text-gray-800 mb-3">Analisis Opsi Jawaban</h4>
                                                                    <div class="overflow-x-auto">
                                                                        <table class="min-w-full">
                                                                            <thead>
                                                                                <tr class="bg-gray-50">
                                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Opsi</th>
                                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jawaban</th>
                                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dipilih</th>
                                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Persentase</th>
                                                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="divide-y divide-gray-200">
                                                                                ${analysis.option_analysis.map((option, index) => `
                                    <tr class="${option.is_correct ? 'bg-green-50' : ''}">
                                        <td class="px-3 py-2 text-sm font-medium text-gray-900">
                                            ${option.option?.alphabet || String.fromCharCode(65 + index)}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-600">
                                            ${option.option?.context ? option.option.context.substring(0, 50) + (option.option.context.length > 50 ? '...' : '') : ''}
                                        </td>
                                        <td class="px-3 py-2 text-sm text-gray-900">${option.selected_count}</td>
                                        <td class="px-3 py-2 text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: ${option.percentage}%"></div>
                                                </div>
                                                ${option.percentage}%
                                            </div>
                                        </td>
                                        <td class="px-3 py-2 text-sm">
                                            ${option.is_correct ?
                                                '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Benar</span>' :
                                                '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Salah</span>'
                                            }
                                        </td>
                                    </tr>
                                `).join('')}
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                ` : ''}

                <!-- Recommendations -->
                <div class="pt-6 border-t border-gray-200">
                    <h4 class="font-semibold text-gray-800 mb-3">Rekomendasi</h4>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <ul class="text-sm text-blue-800 space-y-1">
                            ${analysis.difficulty_index < 0.3 ?
                                '<li>• Soal terlalu sukar, pertimbangkan untuk merevisi atau mengganti dengan soal yang lebih mudah</li>' :
                                analysis.difficulty_index > 0.7 ?
                                '<li>• Soal terlalu mudah, pertimbangkan untuk membuat soal yang lebih menantang</li>' :
                                '<li>• Tingkat kesukaran soal sudah baik (sedang)</li>'
                            }
                            ${analysis.discrimination_index < 0.2 ?
                                '<li>• Daya pembeda rendah, soal perlu diperbaiki atau diganti</li>' :
                                analysis.discrimination_index < 0.4 ?
                                '<li>• Daya pembeda cukup, masih bisa ditingkatkan</li>' :
                                '<li>• Daya pembeda sangat baik, soal berkualitas tinggi</li>'
                            }
                            ${analysis.reliability_contribution < 0.05 ?
                                '<li>• Kontribusi terhadap reliabilitas rendah</li>' : ''
                            }
                        </ul>
                    </div>
                </div>
            </div>
        `;

            document.getElementById('modalContent').innerHTML = modalContent;
            document.getElementById('questionModal').classList.remove('hidden');
        }

        // Close modal
        function closeModal() {
            document.getElementById('questionModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('questionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }

            // Ctrl+F untuk focus pada search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchQuestion').focus();
            }

            // Ctrl+1 untuk table view, Ctrl+2 untuk detail view
            if (e.ctrlKey && e.key === '1') {
                e.preventDefault();
                toggleView('table');
            }
            if (e.ctrlKey && e.key === '2') {
                e.preventDefault();
                toggleView('detail');
            }

            // Ctrl+R untuk reset filters
            if (e.ctrlKey && e.key === 'r') {
                e.preventDefault();
                clearFilters();
            }
        });

        // Debounce search untuk performance
        let searchTimeout;
        document.getElementById('searchQuestion').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                filterTable();
            }, 300);
        });

        // Add tooltips for better UX
        function initializeTooltips() {
            const tooltipElements = document.querySelectorAll('[title]');
            tooltipElements.forEach(element => {
                element.addEventListener('mouseenter', function() {
                    // Simple tooltip implementation
                    const tooltip = document.createElement('div');
                    tooltip.className =
                        'absolute z-50 px-2 py-1 text-xs text-white bg-gray-800 rounded shadow-lg';
                    tooltip.textContent = this.getAttribute('title');
                    tooltip.style.top = this.offsetTop + this.offsetHeight + 5 + 'px';
                    tooltip.style.left = this.offsetLeft + 'px';
                    this.appendChild(tooltip);

                    this.addEventListener('mouseleave', function() {
                        if (tooltip.parentNode) {
                            tooltip.parentNode.removeChild(tooltip);
                        }
                    }, {
                        once: true
                    });
                });
            });
        }

        // Initialize tooltips when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeTooltips();
        });

        function exportToExcel() {
            // Implementasi export Excel bisa ditambahkan di sini
            alert('Fitur export Excel akan segera tersedia');
        }

        // Print specific table only
        function printTable() {
            const printWindow = window.open('', '_blank');
            const tableHTML = document.getElementById('tableView').innerHTML;
            const styles = document.querySelector('style').innerHTML;

            printWindow.document.write(`
            <html>
                <head>
                    <title>Detail Analisis Butir Soal</title>
                    <style>${styles}</style>
                    <style>
                        @media print {
                            body { font-size: 12px; }
                            .no-print { display: none !important; }
                        }
                    </style>
                </head>
                <body>
                    <h2>Detail Analisis Butir Soal</h2>
                    <p>Ujian: {{ $timetable->name ?? 'Tidak diketahui' }} | Modul: {{ $timetableModule->name ?? 'Tidak diketahui' }}</p>
                    ${tableHTML}
                </body>
            </html>
        `);

            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endpush
