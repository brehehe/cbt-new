<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuestionExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    protected $questions;

    public function __construct(Collection $questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->questions;
    }

    public function headings(): array
    {
        return [
            'No',
            'Prodi',
            'Topik',
            'Kategori',
            'Jenis',
            'Kesukaran',
            'Pertanyaan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Pilihan E',
            'Kunci Jawaban',
        ];
    }

    /**
     * @param  mixed  $question
     */
    public function map($question): array
    {
        static $no = 0;
        $no++;

        $typeLabel = match ($question->type) {
            'single' => 'Single (PG)',
            'multiple' => 'Multiple',
            'essay' => 'Essay',
            default => 'Single (PG)'
        };

        $options = [];
        $correctKey = '-';

        $alphabets = ['A', 'B', 'C', 'D', 'E'];
        foreach ($alphabets as $alphabet) {
            $answer = $question->answers->firstWhere('alphabet', $alphabet);
            $options[] = $answer ? strip_tags($answer->context) : '';
            if ($answer && $answer->is_correct) {
                $correctKey = $alphabet;
            }
        }

        return array_merge([
            $no,
            $question->study?->name ?? '-',
            $question->topic?->name ?? '-',
            $question->categoryQuestion?->name ?? '-',
            $typeLabel,
            $question->difficulty == 'default' ? 'Unknown' : ucfirst($question->difficulty),
            strip_tags($question->question),
        ], $options, [$correctKey]);
    }
}
