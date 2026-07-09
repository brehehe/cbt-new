<?php

namespace App\Jobs\Question;

use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Material;
use App\Models\Master\Question\MaterialCategory;
use App\Models\Master\Question\Question;
use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
use App\Services\Answer\AnswerService;
use App\Services\Question\QuestionService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Throwable;

class QuestionImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public $study_id;

    public $user;

    public $collections;

    public $import_type;

    public function __construct($study_id, $user, $collections, $import_type = 'pg')
    {
        //
        $this->study_id = $study_id;
        $this->user = $user;
        $this->collections = $collections;
        $this->import_type = $import_type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        try {
            $warnings = [];

            foreach ($this->collections as $key => $row) {
                if ($key == 0) {
                    continue;
                } // Skip header row

                // Check if the original row is completely empty
                $isEmptyRow = true;
                if ($row instanceof Collection) {
                    $rowArray = $row->toArray();
                } elseif ($row instanceof \Traversable || $row instanceof \ArrayAccess) {
                    $rowArray = collect($row)->toArray();
                } else {
                    $rowArray = (array) $row;
                }
                foreach ($rowArray as $colVal) {
                    if ($colVal !== null && trim((string)$colVal) !== '') {
                        $isEmptyRow = false;
                        break;
                    }
                }
                if ($isEmptyRow) {
                    continue; // Silently skip completely empty rows
                }

                $value = $this->normalizeRow($row);
                if ($value === null) {
                    Log::warning('Data soal tidak bisa diproses, format baris tidak valid', [
                        'collection' => $row,
                    ]);
                    $warnings[] = [
                        'row' => $key + 1,
                        'reason' => 'Format baris tidak valid.',
                    ];

                    continue;
                }

                if ($this->import_type == 'pg') {
                    $studyName = $this->valueAt($value, 0);
                    $topicName = $this->valueAt($value, 1);
                    $typeName = $this->valueAt($value, 4);
                    $categoryName = $this->valueAt($value, 5);
                    $questionText = $this->valueAt($value, 6);
                    $description = $this->valueAt($value, 7);
                    $questionImageUrl = $this->valueAt($value, 8);
                    $correctKey = $this->valueAt($value, 19);
                } else {
                    // Format Essay
                    $studyName = $this->valueAt($value, 0);
                    $topicName = $this->valueAt($value, 1);
                    $typeName = $this->valueAt($value, 4);
                    $categoryName = $this->valueAt($value, 5);
                    $questionText = $this->valueAt($value, 6);
                    $description = $this->valueAt($value, 7);
                    $questionImageUrl = $this->valueAt($value, 8);
                    $referenceAnswer = $this->valueAt($value, 9);
                    $referenceAnswerImageUrl = $this->valueAt($value, 10);
                }

                if (! $studyName || ! $topicName || ! $typeName || ! $questionText) {
                    Log::warning('Data soal tidak bisa masuk, ada field yang kosong', [
                        'collection' => $value,
                    ]);
                    $missingFields = [];
                    if (!$studyName) $missingFields[] = 'Prodi';
                    if (!$topicName) $missingFields[] = 'Topik Soal';
                    if (!$typeName) $missingFields[] = 'Tipe Soal';
                    if (!$questionText) $missingFields[] = 'Soal';

                    $warnings[] = [
                        'row' => $key + 1,
                        'reason' => 'Ada field wajib yang kosong: ' . implode(', ', $missingFields),
                    ];

                    continue;
                }

                $question_type = QuestionType::withoutGlobalScopes()
                    ->where('company_id', $this->user?->company?->id)
                    ->where('name', 'ilike', $typeName)
                    ->first();

                if (! $question_type) {
                    Log::warning('Data soal tidak bisa masuk, karena Tipe Soal tidak ditemukan', [
                        'collection' => $value,
                    ]);
                    $warnings[] = [
                        'row' => $key + 1,
                        'reason' => 'Tipe Soal "' . $typeName . '" tidak ditemukan.',
                    ];

                    continue;
                }

                // Removed choice required check to allow empty/single-quoted answers for image-only choices.

                $study = Study::withoutGlobalScopes()
                    ->where('company_id', $this->user?->company?->id)
                    ->where('name', 'ilike', $studyName)
                    ->first();

                if (! $study && $studyName) {
                    $study = Study::create([
                        'company_id' => $this->user?->company?->id,
                        'name' => $studyName,
                    ]);
                }

                $topic = $study?->topics()
                    ->withoutGlobalScopes()
                    ->where('company_id', $this->user?->company?->id)
                    ->where('name', 'ilike', $topicName)
                    ->first();

                if (! $topic && $topicName) {
                    $topic = Topic::create([
                        'company_id' => $this->user?->company?->id,
                        'study_id' => $study?->id,
                        'name' => $topicName,
                    ]);
                }

                $materialCategoryName = $this->valueAt($value, 2);
                $material_category = null;
                if ($materialCategoryName) {
                    $material_category = $topic?->materialCategories()
                        ->withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->where('name', 'ilike', $materialCategoryName)
                        ->first();

                    if (! $material_category) {
                        $material_category = MaterialCategory::create([
                            'company_id' => $this->user?->company?->id,
                            'topic_id' => $topic?->id,
                            'name' => $materialCategoryName,
                        ]);
                    }
                }

                $materialName = $this->valueAt($value, 3);
                $material = null;
                if ($materialName) {
                    if ($material_category) {
                        $material = $material_category->materials()
                            ->withoutGlobalScopes()
                            ->where('company_id', $this->user?->company?->id)
                            ->where('name', 'ilike', $materialName)
                            ->first();
                    } else {
                        $material = Material::withoutGlobalScopes()
                            ->where('company_id', $this->user?->company?->id)
                            ->where('topic_id', $topic?->id)
                            ->whereNull('material_category_id')
                            ->where('name', 'ilike', $materialName)
                            ->first();
                    }

                    if (! $material) {
                        $material = Material::create([
                            'company_id' => $this->user?->company?->id,
                            'topic_id' => $topic?->id,
                            'material_category_id' => $material_category?->id,
                            'level' => 1,
                            'name' => $materialName,
                        ]);
                    }
                }

                $categoryQuestion = null;
                if ($categoryName) {
                    $categoryQuestion = CategoryQuestion::withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->where('name', 'ilike', $categoryName)
                        ->first();
                    if (! $categoryQuestion) {
                        $categoryQuestion = CategoryQuestion::create([
                            'company_id' => $this->user?->company?->id,
                            'name' => $categoryName,
                        ]);
                    }
                }

                $request_question = [
                    'user_id' => $this->user?->id,
                    'company_id' => $this->user?->company?->id,
                    'study_id' => $study?->id,
                    'topic_id' => $topic?->id,
                    'material_category_id' => $material_category?->id,
                    'material_id' => $material?->id,
                    'question_type_id' => $question_type?->id,
                    'category_question_id' => $categoryQuestion?->id,
                    'question' => $questionText,
                    'images' => $questionImageUrl ? [$questionImageUrl] : null,
                    'old_images' => null,
                    'description' => $description,
                    'weight_correct' => null,
                    'weight_incorrect' => null,
                    'type' => ($this->import_type == 'essay') ? Question::TYPE_ESSAY : Question::TYPE_SINGLE,
                ];

                $question = app(QuestionService::class)->updateOrCreate($request_question);
                if (! $question) {
                    throw new Exception('Ada kesalahaan saat QuestionImportJob => QuestionService => updateOrCreate', 500);
                }

                if ($this->import_type == 'pg') {
                    $optionsMap = [
                        'A' => ['text' => 9, 'image' => 10],
                        'B' => ['text' => 11, 'image' => 12],
                        'C' => ['text' => 13, 'image' => 14],
                        'D' => ['text' => 15, 'image' => 16],
                        'E' => ['text' => 17, 'image' => 18],
                    ];

                    foreach ($optionsMap as $letter => $indices) {
                        $answerText = $this->valueAt($value, $indices['text']);
                        $answerImageUrl = $this->valueAt($value, $indices['image']);
                        
                        // Option E is optional; only create it if either text or image is provided.
                        // Option A, B, C, D are required; we always create them even if empty.
                        $isE = ($letter === 'E');
                        if ($isE && ! $answerText && ! $answerImageUrl) {
                            continue;
                        }

                        // If text is empty/null and there is no image, we default text to "'" as requested by the user.
                        $contextVal = $answerText;
                        if ($contextVal === null && ! $answerImageUrl) {
                            $contextVal = "'";
                        }

                        $request_answer = [
                            'company_id' => $this->user?->company?->id,
                            'alphabet' => $letter,
                            'context' => $contextVal,
                            'images' => $answerImageUrl ? [$answerImageUrl] : null,
                            'old_images' => null,
                            'is_correct' => strtoupper(trim((string) $correctKey)) === $letter,
                        ];

                        $answer = app(AnswerService::class)->updateOrCreate($question, $request_answer);
                        if (! $answer) {
                            throw new Exception('Ada kesalahaan saat QuestionImportJob => AnswerService => updateOrCreate', 500);
                        }
                    }
                } else {
                    // Create one reference answer for essay if provided
                    if (! empty($referenceAnswer)) {
                        $request_answer = [
                            'company_id' => $this->user?->company?->id,
                            'alphabet' => null,
                            'context' => $referenceAnswer,
                            'images' => $referenceAnswerImageUrl ? [$referenceAnswerImageUrl] : null,
                            'old_images' => null,
                            'is_correct' => true,
                        ];

                        app(AnswerService::class)->updateOrCreate($question, $request_answer);
                    }
                }

            }
            if ($this->user) {
                if (! empty($warnings)) {
                    \Illuminate\Support\Facades\Cache::put('import_warnings_' . $this->user->id, $warnings, 3600);
                }
                \Illuminate\Support\Facades\Cache::put('import_status_' . $this->user->id, 'success', 3600);
            }
        } catch (Exception|Throwable $th) {
            $error = [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ];
            Log::error('Ada Kesalahaan saat QuestionImportJob', $error);

            if ($this->user) {
                \Illuminate\Support\Facades\Cache::put('import_status_' . $this->user->id, 'failed:' . $th->getMessage(), 3600);
            }

            throw $th;
        }
    }

    public function letterToValue(?string $ch): ?int
    {
        static $map = [
            'A' => 9,
            'B' => 11,
            'C' => 13,
            'D' => 15,
            'E' => 17,
        ];

        return $map[strtoupper(trim((string) $ch))] ?? null;
    }

    private function valueAt($row, int $index): ?string
    {
        if ($row instanceof Collection) {
            $row = $row->toArray();
        } elseif ($row instanceof \Traversable || $row instanceof \ArrayAccess) {
            $row = collect($row)->toArray();
        }

        if (! is_array($row) || ! array_key_exists($index, $row)) {
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
        if ($row instanceof Collection) {
            $row = $row->toArray();
        } elseif ($row instanceof \Traversable || $row instanceof \ArrayAccess) {
            $row = collect($row)->toArray();
        } elseif (! is_array($row)) {
            return null;
        }

        // Pastikan minimal 20 kolom
        if (count($row) < 20) {
            $row = array_pad($row, 20, null);
        }

        return $row;
    }
}
