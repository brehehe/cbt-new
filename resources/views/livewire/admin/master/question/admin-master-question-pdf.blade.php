<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bank Soal</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 10px; color: #1f2937; margin: 0; padding: 20px; }
        
        /* Header Styling */
        .header-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #374151;
            padding-bottom: 15px;
        }
        .header-content {
            display: table-cell;
            vertical-align: middle;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .report-title {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
        }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; vertical-align: top; }
        th { 
            background-color: #f3f4f6; 
            color: #374151; 
            font-weight: bold; 
            font-size: 9px; 
            text-transform: uppercase;
        }
        tr:nth-child(even) { background-color: #f9fafb; }
        .col-no { width: 30px; text-align: center; }
        .col-type { width: 60px; }
        .col-diff { width: 60px; }
        
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .badge-purple { background-color: #f3e8ff; color: #6b21a8; }
        .badge-green { background-color: #dcfce7; color: #166534; }

        .footer {
            position: fixed;
            bottom: 0px;
            width: 100%;
            font-size: 8px;
            color: #9ca3af;
            text-align: right;
            border-top: 1px solid #e5e7eb;
            padding-top: 5px;
        }
        .page-number:before { content: "Halaman " counter(page); }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header-content">
            <div class="company-name">{{ $company->name ?? 'SISTEM CBT' }}</div>
            <div class="report-title">LAPORAN DATA BANK SOAL</div>
            <div style="font-size: 9px; color: #6b7280; margin-top: 3px;">
                Dicetak pada: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-no">No</th>
                <th style="width: 150px;">Metadata (Prodi/Topik)</th>
                <th class="col-type">Jenis</th>
                <th class="col-diff">Difficulty</th>
                <th>Pertanyaan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($questions as $index => $q)
                <tr>
                    <td class="col-no">{{ $index + 1 }}</td>
                    <td>
                        <div style="font-weight: bold;">{{ $q->study?->name ?? '-' }}</div>
                        <div style="color: #6b7280; font-size: 8px;">{{ $q->topic?->name ?? '-' }}</div>
                        <div style="color: #6b7280; font-size: 8px;">{{ $q->categoryQuestion?->name ?? '-' }}</div>
                    </td>
                    <td>
                        @if(($q->type ?? 'single') == 'single')
                            <span class="badge badge-blue">PG</span>
                        @elseif($q->type == 'multiple')
                            <span class="badge badge-purple">Multiple</span>
                        @else
                            <span class="badge badge-green">Essay</span>
                        @endif
                    </td>
                    <td>{{ $q->difficulty == 'default' ? '-' : ucfirst($q->difficulty) }}</td>
                    <td>
                        <div style="line-height: 1.4; margin-bottom: 8px;">
                            {!! $q->question !!}
                        </div>
                        
                        @if($q->answers->isNotEmpty() && $q->type !== 'essay')
                            <div style="margin-top: 5px; border-top: 1px dashed #e5e7eb; padding-top: 5px;">
                                @foreach($q->answers->sortBy('alphabet') as $answer)
                                    <div style="margin-bottom: 3px; {{ $answer->is_correct ? 'font-weight: bold; color: #059669;' : '' }}">
                                        {{ $answer->alphabet }}. {!! $answer->context !!}
                                        @if($answer->is_correct)
                                            <span style="font-size: 8px;">(Kunci)</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #9ca3af;">Tidak ada data soal.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span class="page-number"></span> | Sistem CAT/CBT
    </div>
</body>
</html>
