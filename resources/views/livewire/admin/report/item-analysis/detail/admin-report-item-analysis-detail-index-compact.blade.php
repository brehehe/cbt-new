@section('title', 'Analisis Butir Soal')
@push('styles')
    <style>
        @media print {

            .btn,
            .no-print {
                display: none !important;
            }

            body {
                font-size: 11px;
            }
        }

        .compact-table {
            font-size: 0.8rem;
        }

        .stat-card {
            padding: 0.75rem;
        }
    </style>
@endpush

<div>
    {{-- Header Ringkas --}}
    <div class="mb-3 bg-white rounded-lg p-3 shadow-sm">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold text-[#f58634] mb-1">Analisis Butir Soal</h1>
                <div class="text-xs text-gray-600">
                    <span class="font-medium">{{ $timetable->name ?? 'N/A' }}</span> •
                    {{ $timetableModule->name ?? 'N/A' }} •
                    {{ $timetable->start_time?->format('d/m/Y') }} •
                    {{ $userTimetables->count() }} peserta •
                    {{ $timetableQuestions->count() }} soal
                </div>
            </div>
            <div class="flex gap-2 no-print">
                <button onclick="window.print()" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-print text-xs"></i>
                </button>
                <a href="{{ route('admin.report.item-analysis') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Legenda --}}
    <div class="bg-gray-50 rounded p-2 mb-3 text-xs">
        <strong>Legenda:</strong>
        <span class="text-red-600">Sukar (&lt;0.3)</span> •
        <span class="text-yellow-600">Sedang (0.3-0.7)</span> •
        <span class="text-green-600">Mudah (&gt;0.7)</span> |
        <span class="text-red-600">DB Buruk (&lt;0.2)</span> •
        <span class="text-yellow-600">DB Cukup (0.2-0.4)</span> •
        <span class="text-green-600">DB Baik (&gt;0.4)</span>
    </div>

    {{-- Tabel Analisis Ringkas --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table compact-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-12 text-center">No</th>
                        <th class="w-20">Soal</th>
                        <th class="w-16 text-center">Peserta</th>
                        <th class="w-16 text-center">Benar</th>
                        <th class="w-16 text-center">P</th>
                        <th class="w-20">Tingkat</th>
                        <th class="w-16 text-center">D</th>
                        <th class="w-20">Daya Beda</th>
                        <th class="w-20 text-center">Kelompok Atas</th>
                        <th class="w-20 text-center">Kelompok Bawah</th>
                        <th>Analisis Opsi</th>
                        <th class="w-32">Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemAnalysisData as $questionId => $analysis)
                        <tr class="border-b">
                            <td class="text-center font-medium">{{ $loop->iteration }}</td>
                            <td class="text-xs">
                                {!! \Str::limit(strip_tags($analysis['question']->question ?? ''), 30) !!}
                            </td>
                            <td class="text-center">{{ $analysis['total_participants'] }}</td>
                            <td class="text-center">{{ $analysis['correct_answers'] }}</td>
                            <td class="text-center font-bold">{{ $analysis['difficulty_index'] }}</td>
                            <td>
                                @php
                                    $diffColor =
                                        $analysis['difficulty_index'] >= 0.7
                                            ? 'text-green-600'
                                            : ($analysis['difficulty_index'] >= 0.3
                                                ? 'text-yellow-600'
                                                : 'text-red-600');
                                @endphp
                                <span class="text-xs {{ $diffColor }} font-medium">
                                    {{ $analysis['difficulty_level'] }}
                                </span>
                            </td>
                            <td class="text-center font-bold">{{ $analysis['discrimination_index'] }}</td>
                            <td>
                                @php
                                    $discColor =
                                        $analysis['discrimination_index'] >= 0.4
                                            ? 'text-green-600'
                                            : ($analysis['discrimination_index'] >= 0.2
                                                ? 'text-yellow-600'
                                                : 'text-red-600');
                                @endphp
                                <span class="text-xs {{ $discColor }} font-medium">
                                    {{ $analysis['discrimination_level'] }}
                                </span>
                            </td>
                            <td class="text-center text-xs">
                                @if ($analysis['upper_group_total'] > 0)
                                    {{ $analysis['upper_group_correct'] }}/{{ $analysis['upper_group_total'] }}
                                    <div class="text-gray-500">
                                        ({{ round(($analysis['upper_group_correct'] / $analysis['upper_group_total']) * 100, 1) }}%)
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center text-xs">
                                @if ($analysis['lower_group_total'] > 0)
                                    {{ $analysis['lower_group_correct'] }}/{{ $analysis['lower_group_total'] }}
                                    <div class="text-gray-500">
                                        ({{ round(($analysis['lower_group_correct'] / $analysis['lower_group_total']) * 100, 1) }}%)
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-xs">
                                @if (!empty($analysis['option_analysis']))
                                    @foreach ($analysis['option_analysis'] as $option)
                                        <span
                                            class="inline-block mr-1 {{ $option['is_correct'] ? 'font-bold text-green-600' : 'text-gray-600' }}">
                                            {{ $option['option']->alphabet ?? chr(65 + $loop->index) }}:{{ $option['percentage'] }}%
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-xs">
                                @if ($analysis['difficulty_index'] < 0.3)
                                    <span class="text-red-600">Perbaiki soal</span>
                                @elseif($analysis['difficulty_index'] > 0.7)
                                    <span class="text-orange-600">Persulit</span>
                                @else
                                    <span class="text-green-600">Baik</span>
                                @endif
                                <br>
                                @if ($analysis['discrimination_index'] < 0.2)
                                    <span class="text-red-600">Ganti soal</span>
                                @elseif($analysis['discrimination_index'] < 0.4)
                                    <span class="text-yellow-600">Bisa ditingkatkan</span>
                                @else
                                    <span class="text-green-600">Pertahankan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ringkasan Statistik --}}
    <div class="mt-4 bg-white rounded-lg p-3 shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-2">Ringkasan Analisis</h3>

        @php
            $easyCount = collect($itemAnalysisData)->where('difficulty_level', 'Mudah')->count();
            $mediumCount = collect($itemAnalysisData)->where('difficulty_level', 'Sedang')->count();
            $hardCount = collect($itemAnalysisData)->where('difficulty_level', 'Sukar')->count();
            $goodDiscrimination = collect($itemAnalysisData)
                ->filter(function ($item) {
                    return $item['discrimination_index'] >= 0.3;
                })
                ->count();
            $avgDifficulty = collect($itemAnalysisData)->avg('difficulty_index');
            $avgDiscrimination = collect($itemAnalysisData)->avg('discrimination_index');
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-6 gap-2 text-center text-xs">
            <div class="bg-green-50 p-2 rounded">
                <div class="font-bold text-green-800 text-lg">{{ $easyCount }}</div>
                <div class="text-green-600">Mudah</div>
            </div>
            <div class="bg-yellow-50 p-2 rounded">
                <div class="font-bold text-yellow-800 text-lg">{{ $mediumCount }}</div>
                <div class="text-yellow-600">Sedang</div>
            </div>
            <div class="bg-red-50 p-2 rounded">
                <div class="font-bold text-red-800 text-lg">{{ $hardCount }}</div>
                <div class="text-red-600">Sukar</div>
            </div>
            <div class="bg-blue-50 p-2 rounded">
                <div class="font-bold text-blue-800 text-lg">{{ $goodDiscrimination }}</div>
                <div class="text-blue-600">DB Baik</div>
            </div>
            <div class="bg-purple-50 p-2 rounded">
                <div class="font-bold text-purple-800 text-lg">{{ number_format($avgDifficulty, 2) }}</div>
                <div class="text-purple-600">Rata² P</div>
            </div>
            <div class="bg-indigo-50 p-2 rounded">
                <div class="font-bold text-indigo-800 text-lg">{{ number_format($avgDiscrimination, 2) }}</div>
                <div class="text-indigo-600">Rata² D</div>
            </div>
        </div>

        {{-- Rekomendasi Umum --}}
        <div class="mt-3 p-2 bg-gray-50 rounded text-xs">
            <strong>Rekomendasi Umum:</strong>
            @if ($avgDifficulty < 0.3)
                Ujian terlalu sukar, pertimbangkan mengganti beberapa soal dengan yang lebih mudah.
            @elseif($avgDifficulty > 0.7)
                Ujian terlalu mudah, tambahkan soal yang lebih menantang.
            @else
                Tingkat kesukaran ujian sudah seimbang.
            @endif

            @if ($goodDiscrimination < count($itemAnalysisData) * 0.5)
                Banyak soal dengan daya pembeda rendah, perlu perbaikan kualitas soal.
            @else
                Sebagian besar soal memiliki daya pembeda yang baik.
            @endif
        </div>
    </div>

    {{-- Keterangan Rumus --}}
    <div class="mt-3 text-xs text-gray-500 text-center no-print">
        <strong>Rumus:</strong> P = Benar/Total | D = (Atas-Bawah)/n | DB = Daya Beda |
        Kelompok menggunakan 27% atas dan 27% bawah berdasarkan skor total
    </div>
</div>

@push('scripts')
    <script>
        function exportToExcel() {
            alert('Fitur export Excel akan segera tersedia');
        }
    </script>
@endpush
