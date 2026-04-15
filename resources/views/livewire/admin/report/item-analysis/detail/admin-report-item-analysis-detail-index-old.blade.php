@section('title', 'Detail Analisis Butir Soal')
@push('styles')
    <style>
        @media print {

            .btn,
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
<div>
    <div class="mb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-[color:var(--primary)]">
                    Detail Analisis Butir Soal</h1>
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
                <a href="{{ route('admin.report.item-analysis') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
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

    @foreach ($itemAnalysisData as $questionId => $analysis)
        @php
            $question = $analysis['question'] ?? null;
            $questionText = strip_tags($question?->question ?? '');
            $questionType = $question?->type ?? 'single';
        @endphp
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Soal {{ $loop->iteration }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {!! \Str::limit($questionText, 100) !!}
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
                                    <span class="font-bold text-blue-800">{{ $analysis['difficulty_index'] }}</span>
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
                                    <span class="text-sm font-medium text-[color:var(--primary)]">Kontribusi
                                        Reliabilitas</span>
                                    <span
                                        class="font-bold text-[color:var(--primary)]">{{ $analysis['reliability_contribution'] }}</span>
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
                                    {{ $analysis['upper_group_correct'] }} dari {{ $analysis['upper_group_total'] }}
                                    peserta menjawab benar
                                    ({{ $upperPercent }}%)
                                </p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg">
                                <h5 class="font-medium text-red-800 mb-2">Kelompok Bawah</h5>
                                <p class="text-sm text-red-600">
                                    {{ $analysis['lower_group_correct'] }} dari {{ $analysis['lower_group_total'] }}
                                    peserta menjawab benar
                                    ({{ $lowerPercent }}%)
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
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Opsi</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Jawaban</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Dipilih</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                            Persentase</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">
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
                                <li>• Soal terlalu sukar, pertimbangkan untuk merevisi atau mengganti dengan soal yang
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

    {{-- Summary dan Export --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Ringkasan Analisis</h3>
                <p class="text-sm text-gray-600 mt-1">
                    Dari {{ count($itemAnalysisData) }} soal yang dianalisis
                </p>
            </div>
            <div class="flex space-x-3">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print mr-2"></i>Cetak Laporan
                </button>
                <button
                    class="{{ in_array(config('app.name_slug'), ['pro-cbt']) ? 'btn btn-warning' : 'btn btn-primary' }}"
                    onclick="exportToExcel()">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </div>

        @php
            $easyCount = collect($itemAnalysisData)->where('difficulty_level', 'Mudah')->count();
            $mediumCount = collect($itemAnalysisData)->where('difficulty_level', 'Sedang')->count();
            $hardCount = collect($itemAnalysisData)->where('difficulty_level', 'Sukar')->count();

            $goodDiscrimination = collect($itemAnalysisData)
                ->filter(function ($item) {
                    return $item['discrimination_index'] >= 0.3;
                })
                ->count();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="text-center p-3 bg-green-50 rounded-lg">
                <p class="text-2xl font-bold text-green-800">{{ $easyCount }}</p>
                <p class="text-sm text-green-600">Soal Mudah</p>
            </div>
            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <p class="text-2xl font-bold text-yellow-800">{{ $mediumCount }}</p>
                <p class="text-sm text-yellow-600">Soal Sedang</p>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-lg">
                <p class="text-2xl font-bold text-red-800">{{ $hardCount }}</p>
                <p class="text-sm text-red-600">Soal Sukar</p>
            </div>
            <div class="text-center p-3 bg-blue-50 rounded-lg">
                <p class="text-2xl font-bold text-blue-800">{{ $goodDiscrimination }}</p>
                <p class="text-sm text-blue-600">Daya Pembeda Baik</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function exportToExcel() {
            // Implementasi export Excel bisa ditambahkan di sini
            alert('Fitur export Excel akan segera tersedia');
        }
    </script>
@endpush
