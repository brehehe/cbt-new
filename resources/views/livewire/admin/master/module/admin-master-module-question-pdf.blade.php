<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Modul Soal - {{ $module->name }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 15px; line-height: 1.5; }
        
        /* Header Styling */
        .header-container {
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 12px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .module-title {
            font-size: 18px;
            font-weight: bold;
            color: #1d4ed8;
            margin-bottom: 6px;
        }
        .meta-info {
            font-size: 9px;
            color: #4b5563;
        }
        .meta-item {
            margin-right: 15px;
            display: inline-block;
        }

        /* Question Item Styling */
        .question-block {
            margin-bottom: 25px;
            page-break-inside: avoid;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 15px;
        }
        .question-header {
            font-size: 12px;
            font-weight: bold;
            color: #1f2937;
            border-bottom: 1px solid #f3f4f6;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }
        .question-body {
            margin-bottom: 12px;
            font-size: 11px;
            color: #1f2937;
        }
        .description-body {
            margin-top: 8px;
            margin-bottom: 12px;
            padding: 8px 12px;
            background-color: #f9fafb;
            border-left: 3px solid #d1d5db;
            color: #4b5563;
            font-size: 10px;
        }

        /* Answer Options */
        .options-list {
            margin-top: 10px;
            margin-bottom: 12px;
            padding-left: 5px;
        }
        .option-item {
            margin-bottom: 5px;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .option-item.correct {
            font-weight: bold;
            color: #059669;
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
        }
        
        .answer-key-section {
            margin-top: 10px;
            font-weight: bold;
            font-size: 10px;
            color: #059669;
            background-color: #ecfdf5;
            padding: 6px 12px;
            border-radius: 4px;
            display: inline-block;
        }

        .footer {
            position: fixed;
            bottom: -10px;
            left: 0;
            right: 0;
            font-size: 8px;
            color: #9ca3af;
            text-align: right;
            border-top: 1px solid #e5e7eb;
            padding-top: 4px;
        }
        .page-number:before { content: "Halaman " counter(page); }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="company-name">{{ $company->name ?? 'SISTEM CBT' }}</div>
        <div class="module-title">MODUL: {{ $module->name }}</div>
        <div class="meta-info">
            <span class="meta-item"><strong>Durasi:</strong> {{ $module->duration }} Menit</span>
            @if($module->question_type_id)
                <span class="meta-item"><strong>Tipe Ujian:</strong> {{ $module->questionType?->name ?? '-' }}</span>
            @endif
            <span class="meta-item"><strong>Dicetak pada:</strong> {{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    @foreach($questions as $index => $q)
        <div class="question-block">
            <div class="question-header">
                Soal {{ $index + 1 }}
                <span style="float: right; font-size: 9px; font-weight: normal; color: #6b7280; text-transform: uppercase;">
                    @if(($q->type ?? 'single') == 'single')
                        Pilihan Ganda
                    @elseif($q->type == 'multiple')
                        Pilihan Ganda Kompleks
                    @else
                        Essay
                    @endif
                    | {{ $q->difficulty == 'default' || !$q->difficulty ? 'Normal' : ucfirst($q->difficulty) }}
                </span>
            </div>
            
            <div class="question-body">
                {!! $q->question !!}
            </div>

            @if(!empty(trim(strip_tags($q->description))))
                <div class="description-body">
                    <strong>Deskripsi/Petunjuk:</strong><br>
                    {!! $q->description !!}
                </div>
            @endif

            @if($q->type !== 'essay' && $q->answers->isNotEmpty())
                <div class="options-list">
                    @foreach($q->answers->sortBy('alphabet') as $answer)
                        <div class="option-item {{ $answer->is_correct ? 'correct' : '' }}">
                            {{ $answer->alphabet }}. {!! $answer->context !!}
                            @if($answer->is_correct)
                                <span style="font-size: 8px; color: #059669; font-weight: normal; margin-left: 5px;">(Kunci Jawaban)</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif($q->type === 'essay')
                @php
                    $essayAnswer = $q->answers->where('is_correct', true)->first();
                @endphp
                @if($essayAnswer && !empty(trim(strip_tags($essayAnswer->context))))
                    <div class="answer-key-section">
                        <strong>Referensi Jawaban Essay:</strong><br>
                        {!! $essayAnswer->context !!}
                    </div>
                @endif
            @endif
        </div>
    @endforeach

    <div class="footer">
        <span class="page-number"></span> | Sistem CAT/CBT | Modul: {{ $module->name }}
    </div>
</body>
</html>
