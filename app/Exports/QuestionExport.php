<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
        // Check if there are only essay questions
        $onlyEssay = $this->questions->isNotEmpty() && $this->questions->every(fn($q) => $q->type === 'essay');

        if ($onlyEssay) {
            return [
                'Prodi',
                'Topik Soal',
                'Kategori Materi',
                'Materi Soal',
                'Tipe Soal',
                'Kategori Soal',
                'Soal',
                'Deskripsi Soal',
                'URL Gambar Soal',
                'Jawaban Referensi',
                'URL Gambar Jawaban',
            ];
        }

        // Default PG format
        return [
            'Prodi',
            'Topik Soal',
            'Kategori Materi',
            'Materi Soal',
            'Tipe Soal',
            'Kategori Soal',
            'Soal',
            'Deskripsi Soal',
            'URL Gambar Soal',
            'A',
            'URL Gambar A',
            'B',
            'URL Gambar B',
            'C',
            'URL Gambar C',
            'D',
            'URL Gambar D',
            'E',
            'URL Gambar E',
            'Jawaban',
        ];
    }

    /**
     * @param  mixed  $question
     */
    public function map($question): array
    {
        $onlyEssay = $this->questions->isNotEmpty() && $this->questions->every(fn($q) => $q->type === 'essay');

        // Extract images array
        $images = is_string($question->images) ? json_decode($question->images, true) : $question->images;
        $questionImageUrl = '';
        if (!empty($images) && is_array($images) && count($images) > 0) {
            $firstImg = $images[0];
            $questionImageUrl = Str::startsWith($firstImg, ['http://', 'https://']) ? $firstImg : asset('storage/' . ltrim($firstImg, '/'));
        }

        $studyName = $question->study?->name ?? '';
        $topicName = $question->topic?->name ?? '';
        $materialCategoryName = $question->materialCategory?->name ?? '';
        $materialName = $question->material?->name ?? '';
        $typeName = $question->questionType?->name ?? '';
        $categoryName = $question->categoryQuestion?->name ?? '';

        if ($onlyEssay) {
            $referenceAnswer = '';
            $referenceAnswerImageUrl = '';

            $firstAnswer = $question->answers->first();
            if ($firstAnswer) {
                $referenceAnswer = $firstAnswer->context;
                $ansImages = is_string($firstAnswer->images) ? json_decode($firstAnswer->images, true) : $firstAnswer->images;
                if (!empty($ansImages) && is_array($ansImages) && count($ansImages) > 0) {
                    $firstAnsImg = $ansImages[0];
                    $referenceAnswerImageUrl = Str::startsWith($firstAnsImg, ['http://', 'https://']) ? $firstAnsImg : asset('storage/' . ltrim($firstAnsImg, '/'));
                }
            }

            return [
                $studyName,
                $topicName,
                $materialCategoryName,
                $materialName,
                $typeName,
                $categoryName,
                $question->question,
                $question->description,
                $questionImageUrl,
                $referenceAnswer,
                $referenceAnswerImageUrl,
            ];
        }

        $options = [];
        $correctKey = '';

        $alphabets = ['A', 'B', 'C', 'D', 'E'];
        foreach ($alphabets as $alphabet) {
            $answer = $question->answers->firstWhere('alphabet', $alphabet);
            $options[] = $answer ? $answer->context : '';

            $ansImageUrl = '';
            if ($answer) {
                $ansImages = is_string($answer->images) ? json_decode($answer->images, true) : $answer->images;
                if (!empty($ansImages) && is_array($ansImages) && count($ansImages) > 0) {
                    $firstAnsImg = $ansImages[0];
                    $ansImageUrl = Str::startsWith($firstAnsImg, ['http://', 'https://']) ? $firstAnsImg : asset('storage/' . ltrim($firstAnsImg, '/'));
                }
            }
            $options[] = $ansImageUrl;

            if ($answer && $answer->is_correct) {
                $correctKey = $alphabet;
            }
        }

        return array_merge([
            $studyName,
            $topicName,
            $materialCategoryName,
            $materialName,
            $typeName,
            $categoryName,
            $question->question,
            $question->description,
            $questionImageUrl,
        ], $options, [$correctKey]);
    }
}
