<?php

namespace App\Jobs\Question;

use App\Models\Master\Question\Material;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
use App\Services\Answer\AnswerService;
use App\Models\Category\CategoryQuestion;
use App\Services\Question\QuestionService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class QuestionImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $study_id, $user, $collections;

    public function __construct($study_id, $user, $collections)
    {
        //
        $this->study_id    = $study_id;
        $this->user        = $user;
        $this->collections = $collections;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        try {
            foreach ($this->collections as $key => $row) {
                $value = $this->normalizeRow($row);
                if ($value === null) {
                    Log::warning('Data soal tidak bisa diproses, format baris tidak valid', [
                        'collection' => $row,
                    ]);
                    continue;
                }
                if ($key == 0) {
                    continue;
                }

                $studyName = $this->valueAt($value, 0);
                $topicName = $this->valueAt($value, 1);
                $typeName = $this->valueAt($value, 4);
                $questionText = $this->valueAt($value, 5);
                $description = $this->valueAt($value, 6);
                $categoryName = $this->valueAt($value, 13);

                if (!$studyName || !$topicName || !$typeName || !$questionText) {
                    Log::warning("Data soal tidak bisa masuk, ada field yang kosong", [
                        'collection' => $value,
                    ]);
                    continue;
                }

                $question_type = QuestionType::withoutGlobalScopes()->whereLike('name', "%{$typeName}%")->first();

                if (!$question_type) {
                    Log::warning("Data soal tidak bisa masuk, karena Tipe Soal tidak ditemukan", [
                        'collection' => $value,
                    ]);
                    continue;
                }

                for ($j = 7; $j < 11; $j++) {
                    if (!$this->valueAt($value, $j)) {
                        Log::warning("Data soal tidak bisa masuk, karena jawaban kosong ", [
                            'collection' => $value,
                        ]);
                        continue 2;
                    }
                }

                $study = Study::withoutGlobalScopes()->whereLike('name', "%{$studyName}%")->first();

                if (!$study && $studyName) {
                    $study = Study::create([
                        'company_id' => $this->user?->company?->id,
                        'name'       => $studyName
                    ]);
                }

                $topic = $study?->topics()->withoutGlobalScopes()->whereLike('name', "%{$topicName}%")->first();

                if (!$topic && $topicName) {
                    $topic = Topic::create([
                        'company_id' => $this->user?->company?->id,
                        'study_id'   => $study?->id,
                        'name'       => $topicName
                    ]);
                }

                $materialCategoryName = $this->valueAt($value, 2);
                $material_category = $topic?->materialCategories()->withoutGlobalScopes()->whereLike('name', "%{$materialCategoryName}%")->first();

                if (!$material_category && $materialCategoryName) {
                    $material_category = MaterialCategory::create([
                        'company_id' => $this->user?->company?->id,
                        'topic_id'   => $topic?->id,
                        'name'       => $materialCategoryName
                    ]);
                }

                $materialName = $this->valueAt($value, 3);
                $material = $material_category?->materials()->withoutGlobalScopes()->whereLike('name', "%{$materialName}%")->first();

                if (!$material && $materialName) {
                    Material::create([
                        'company_id'           => $this->user?->company?->id,
                        'topic_id'             => $topic?->id,
                        'material_category_id' => $material_category?->id,
                        'level'                => 1,
                        'name'                 => $materialName,
                    ]);
                }

                $categoryQuestion = null;
                if ($categoryName) {
                    $categoryQuestion = CategoryQuestion::withoutGlobalScopes()->whereLike('name', "%{$categoryName}%")->first();
                    if (!$categoryQuestion) {
                        $categoryQuestion = CategoryQuestion::create([
                            'company_id' => $this->user?->company?->id,
                            'name'       => $categoryName
                        ]);
                    }
                }

                $request_question = [
                    'user_id'              => $this->user?->id,
                    'company_id'           => $this->user?->company?->id,
                    'study_id'             => $study?->id,
                    'topic_id'             => $topic?->id,
                    'material_category_id' => $material_category?->id,
                    'material_id'          => $material?->id,
                    'question_type_id'     => $question_type?->id,
                    'category_question_id' => $categoryQuestion?->id,
                    'question'             => $questionText,
                    'images'               => null,
                    'old_images'           => null,
                    'description'          => $description,
                    'weight_correct'       => null,
                    'weight_incorrect'     => null,
                ];

                $question = app(QuestionService::class)->updateOrCreate($request_question);
                if (!$question) {
                    throw new Exception("Ada kesalahaan saat QuestionImportJob => QuestionService => updateOrCreate", 500);
                }

                $correctKey = $this->valueAt($value, 12);
                for ($i = 7; $i <= 11; $i++) {
                    $answerText = $this->valueAt($value, $i);
                    if (!$answerText) continue;
                    $request_answer = [
                        'company_id' => $this->user?->company?->id,
                        'alphabet'   => null,
                        'context'    => $answerText,
                        'images'     => null,
                        'old_images' => null,
                        'is_correct' => $i == $this->letterToValue($correctKey) ? true : false,
                    ];

                    $answer = app(AnswerService::class)->updateOrCreate($question, $request_answer);
                    if (!$answer) {
                        throw new Exception("Ada kesalahaan saat QuestionImportJob => AnswerService => updateOrCreate", 500);
                    }
                }

            }
        } catch (Exception | Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file'    => $th->getFile(),
                'line'    => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat QuestionImportJob', $error);
        }
    }

    function letterToValue(?string $ch): ?int
    {
        static $map = [
            'A'=>7,
            'B'=>8,
            'C'=>9,
            'D'=>10,
            'E'=>11
        ];
        return $map[strtoupper(trim((string)$ch))] ?? null;
    }

    private function valueAt($row, int $index): ?string
    {
        if ($row instanceof \Illuminate\Support\Collection) {
            $row = $row->toArray();
        } elseif ($row instanceof \Traversable || $row instanceof \ArrayAccess) {
            $row = collect($row)->toArray();
        }

        if (!is_array($row) || !array_key_exists($index, $row)) {
            return null;
        }

        $value = $row[$index];
        if (is_string($value)) {
            $value = trim($value);
        }

        return $value === '' ? null : $value;
    }

    private function normalizeRow($row): ?array
    {
        if ($row instanceof \Illuminate\Support\Collection) {
            $row = $row->toArray();
        } elseif ($row instanceof \Traversable || $row instanceof \ArrayAccess) {
            $row = collect($row)->toArray();
        } elseif (!is_array($row)) {
            return null;
        }

        // Jika kolom soal kosong (index 5) tapi kolom setelahnya terisi, geser ke kiri
        if (array_key_exists(5, $row) && ($row[5] === null || $row[5] === '') && array_key_exists(6, $row)) {
            array_splice($row, 5, 1);
        }

        // Pastikan minimal 14 kolom (Kategori Soal opsional di index 13)
        if (count($row) < 14) {
            $row = array_pad($row, 14, null);
        }

        return $row;
    }
}
