<article class="analysis-card">
    <h2>Detail Soal {{ $number }}</h2>
    @if(!empty($analysis['aggregate_note']))<p class="analysis-note">{{ $analysis['aggregate_note'] }}</p>@endif
    <div class="analysis-question"><strong>Soal:</strong><div>{!! $analysis['question']->question ?? $analysis['question']->description !!}</div></div>
    <table class="analysis-columns"><tbody><tr><td>
        <h3>Statistik Utama</h3>
        <div class="analysis-metric">Total Peserta <span class="analysis-value">{{ $analysis['total_participants'] }}</span></div>
        <div class="analysis-metric analysis-green">Jawaban Benar <span class="analysis-value">{{ $analysis['correct_answers'] }}</span></div>
        <div class="analysis-metric analysis-red">Jawaban Salah / Tidak Dijawab <span class="analysis-value">{{ $analysis['incorrect_answers'] }}</span></div>
        @if(($analysis['pending_answers'] ?? 0) > 0)
        <div class="analysis-metric analysis-orange">Belum Dinilai <span class="analysis-value">{{ $analysis['pending_answers'] }}</span></div>
        @endif
    </td><td>
        <h3>Indeks Analisis</h3>
        <div class="analysis-metric analysis-blue">Tingkat Kesukaran (P) <span class="analysis-value">{{ number_format($analysis['difficulty_index'], 3) }}</span>
            <div class="analysis-bar"><span style="width:{{ max(0, min(100, $analysis['difficulty_index'] * 100)) }}%"></span></div>
            <span class="analysis-badge">{{ $analysis['difficulty_level'] }}</span>
        </div>
        <div class="analysis-metric analysis-purple">Daya Pembeda (D) <span class="analysis-value">{{ number_format($analysis['discrimination_index'], 3) }}</span>
            <div class="analysis-bar"><span style="background:#9333ea;width:{{ abs($analysis['discrimination_index']) * 100 }}%"></span></div>
            <span class="analysis-badge">{{ $analysis['discrimination_level'] }}</span>
        </div>
        <div class="analysis-metric analysis-orange">Kontribusi Reliabilitas <span class="analysis-value">{{ number_format($analysis['reliability_contribution'], 4) }}</span></div>
    </td></tr></tbody></table>
    <div class="analysis-section">
        <h3>Analisis Kelompok (27% Atas vs 27% Bawah)</h3>
        <table class="analysis-columns"><tbody><tr>
            @foreach(['upper' => 'Atas', 'lower' => 'Bawah'] as $key => $label)
                <td><div class="analysis-metric {{ $key === 'upper' ? 'analysis-green' : 'analysis-red' }}"><h3>Kelompok {{ $label }}</h3>
                    {{ $analysis[$key.'_group_correct'] }} dari {{ $analysis[$key.'_group_total'] }} peserta menjawab benar
                    ({{ number_format($analysis[$key.'_group_total'] ? 100 * $analysis[$key.'_group_correct'] / $analysis[$key.'_group_total'] : 0, 1) }}%)
                </div></td>
            @endforeach
        </tr></tbody></table>
    </div>
    <div class="analysis-section">
        <h3>Analisis Opsi Jawaban</h3>
        <table class="analysis-options"><thead><tr><th style="width:8%">Opsi</th><th style="width:38%">Jawaban</th><th style="width:12%">Dipilih</th><th style="width:25%">Persentase</th><th style="width:17%">Status</th></tr></thead><tbody>
            @forelse($analysis['option_analysis'] as $option)
                <tr class="{{ $option['is_correct'] ? 'analysis-green' : '' }}">
                    <td>{{ $option['option']->alphabet ?? chr(65 + $loop->index) }}</td>
                    <td>{!! $option['option']->context !!}</td><td>{{ $option['selected_count'] }}</td>
                    <td><div class="analysis-bar"><span style="width:{{ $option['percentage'] }}%"></span></div>{{ $option['percentage'] }}%</td>
                    <td><span class="analysis-badge">{{ $option['is_correct'] ? 'Benar' : 'Salah' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5">{{ $analysis['question']->type === 'essay' ? 'Soal esai: tidak memiliki opsi jawaban. Indeks mengikuti status hasil koreksi.' : 'Belum ada data jawaban.' }}</td></tr>
            @endforelse
        </tbody></table>
    </div>
    <p class="analysis-note">P = benar / peserta tercatat pada soal. D = proporsi benar kelompok atas − kelompok bawah; ukuran kelompok dibulatkan ke atas, tanpa tumpang tindih. Kontribusi = P × (1 − P) × D, bukan koefisien reliabilitas tes. Jawaban yang belum dinilai belum dihitung benar.</p>
</article>
