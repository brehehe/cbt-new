<?php

namespace App\Exports;

use App\Models\Master\Questionnaire\Questionnaire;
use App\Models\Master\Questionnaire\QuestionnaireResponse;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionnaireResponseExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithStyles
{
    protected $questionnaireId;
    protected $questions;
    private $rowNumber = 0;

    public function __construct(string $questionnaireId)
    {
        $this->questionnaireId = $questionnaireId;
        $questionnaire = Questionnaire::withoutGlobalScopes()
            ->with(['sections.questions'])
            ->findOrFail($questionnaireId);

        $this->questions = collect();
        foreach ($questionnaire->sections as $sec) {
            foreach ($sec->questions as $q) {
                $this->questions->push($q);
            }
        }
    }

    public function collection()
    {
        return QuestionnaireResponse::where('questionnaire_id', $this->questionnaireId)
            ->with(['answers'])
            ->orderBy('submitted_at', 'asc')
            ->get();
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Waktu Pengisian',
            'Nama Responden',
            'NIM / Identitas',
            'Tipe Akun',
        ];

        foreach ($this->questions as $q) {
            $headings[] = $q->question_text;
        }

        return $headings;
    }

    public function map($response): array
    {
        $this->rowNumber++;
        $answersByQuestion = $response->answers->keyBy('question_id');

        $row = [
            $this->rowNumber,
            $response->submitted_at ? Carbon::parse($response->submitted_at)->format('d/m/Y H:i') : '-',
            $response->respondent_name,
            $response->respondent_nim ?? '-',
            $response->user_id ? 'Mahasiswa Terdaftar' : 'Tamu / Umum',
        ];

        foreach ($this->questions as $q) {
            $ans = $answersByQuestion->get($q->id);
            if (!$ans) {
                $row[] = '-';
            } elseif ($ans->score !== null) {
                $row[] = $ans->score;
            } else {
                $row[] = $ans->text_answer ?? '-';
            }
        }

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'], // Navy/Blue
                ],
            ],
        ];
    }
}
