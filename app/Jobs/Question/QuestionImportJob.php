<?php

namespace App\Jobs\Question;

use App\Models\Category\CategoryQuestion;
use App\Models\Master\Question\Answer;
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
        try {
            $warnings = [];
            $headerMap = [];
            $orderIndex = 1;

            foreach ($this->collections as $key => $row) {
                // Convert row to array
                if ($row instanceof Collection) {
                    $rowArray = $row->toArray();
                } elseif ($row instanceof \Traversable || $row instanceof \ArrayAccess) {
                    $rowArray = collect($row)->toArray();
                } else {
                    $rowArray = (array) $row;
                }

                // Check if the original row is completely empty
                $nonEmptyCells = array_filter($rowArray, fn ($colVal) => $colVal !== null && trim((string) $colVal) !== '');
                if (empty($nonEmptyCells)) {
                    continue;
                }

                // Detect if this is a Header Row (e.g. contains 'prodi')
                $firstCell = strtolower(trim((string) ($rowArray[0] ?? '')));
                if ($firstCell === 'prodi' || $this->rowContainsProdiHeader($rowArray)) {
                    $headerMap = $this->buildHeaderMap($rowArray);
                    continue;
                }

                // Detect if this is a Section Title Row (e.g. 'TAHAP 1 (Soal 1-100)')
                if (count($nonEmptyCells) <= 1) {
                    continue;
                }

                // If we haven't found any header yet, skip
                if (empty($headerMap)) {
                    continue;
                }

                // Detect if this row has an extra column shifted before Tipe Soal (e.g. 14 columns where index 5 is 'Pilihan Ganda')
                $cell5 = strtolower(trim((string) ($rowArray[5] ?? '')));
                $isShiftedRow = ($cell5 === 'pilihan ganda' || $cell5 === 'essay' || $cell5 === 'single' || $cell5 === 'multiple');

                if ($isShiftedRow && count($rowArray) >= 13) {
                    $studyName = trim((string) ($rowArray[0] ?? ''));
                    $topicName = trim((string) ($rowArray[1] ?? ''));
                    $materialCategoryName = trim((string) ($rowArray[2] ?? ''));
                    $extraSubMateri = trim((string) ($rowArray[4] ?? ''));
                    $materialName = trim((string) ($rowArray[3] ?? '')) . ($extraSubMateri !== '' ? ' - ' . $extraSubMateri : '');
                    $typeName = trim((string) ($rowArray[5] ?? ''));
                    $categoryName = trim((string) ($rowArray[6] ?? ''));
                    $questionText = trim((string) ($rowArray[7] ?? ''));
                    $description = null;
                    $questionImageUrl = null;
                    $correctKey = trim((string) ($rowArray[13] ?? ''));
                    $referenceAnswer = null;
                    $referenceAnswerImageUrl = null;
                } else {
                    $studyName = $this->getMappedValue($rowArray, $headerMap, 'study');
                    $topicName = $this->getMappedValue($rowArray, $headerMap, 'topic');
                    $typeName = $this->getMappedValue($rowArray, $headerMap, 'type');
                    $categoryName = $this->getMappedValue($rowArray, $headerMap, 'category');
                    $materialCategoryName = $this->getMappedValue($rowArray, $headerMap, 'material_category');
                    $materialName = $this->getMappedValue($rowArray, $headerMap, 'material');
                    $questionText = $this->getMappedValue($rowArray, $headerMap, 'question');
                    $description = $this->getMappedValue($rowArray, $headerMap, 'description');
                    $questionImageUrl = $this->getMappedValue($rowArray, $headerMap, 'question_image');
                    $correctKey = $this->getMappedValue($rowArray, $headerMap, 'answer_key');
                    $referenceAnswer = $this->getMappedValue($rowArray, $headerMap, 'reference_answer');
                    $referenceAnswerImageUrl = $this->getMappedValue($rowArray, $headerMap, 'reference_answer_image');
                }

                if (! $questionText) {
                    continue;
                }

                // Fallback for study name if not present in row
                if (! $studyName && $this->study_id) {
                    $selectedStudy = Study::withoutGlobalScopes()->find($this->study_id);
                    $studyName = $selectedStudy?->name;
                }

                if (! $studyName || ! $topicName || ! $questionText) {
                    $missingFields = [];
                    if (! $studyName) $missingFields[] = 'Prodi';
                    if (! $topicName) $missingFields[] = 'Topik Soal';
                    if (! $questionText) $missingFields[] = 'Soal';

                    $warnings[] = [
                        'row' => $key + 1,
                        'reason' => 'Ada field wajib yang kosong: ' . implode(', ', $missingFields),
                    ];

                    continue;
                }

                // Find or create QuestionType
                $question_type = null;
                if ($typeName) {
                    $question_type = QuestionType::withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->where('name', 'ilike', $typeName)
                        ->first();

                    if (! $question_type) {
                        $question_type = QuestionType::create([
                            'company_id' => $this->user?->company?->id,
                            'name' => $typeName,
                        ]);
                    }
                } else {
                    $question_type = QuestionType::withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->first();

                    if (! $question_type) {
                        $question_type = QuestionType::create([
                            'company_id' => $this->user?->company?->id,
                            'name' => 'Ujian',
                        ]);
                    }
                }

                // Find or create Study
                $study = null;
                if ($studyName) {
                    $study = Study::withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->where('name', 'ilike', $studyName)
                        ->first();

                    if (! $study) {
                        $study = Study::create([
                            'company_id' => $this->user?->company?->id,
                            'name' => $studyName,
                        ]);
                    }
                }

                // Find or create Topic
                $topic = null;
                if ($study && $topicName) {
                    $topic = $study->topics()
                        ->withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->where('name', 'ilike', $topicName)
                        ->first();

                    if (! $topic) {
                        $topic = Topic::create([
                            'company_id' => $this->user?->company?->id,
                            'study_id' => $study->id,
                            'name' => $topicName,
                        ]);
                    }
                }

                // Find or create MaterialCategory
                $material_category = null;
                if ($topic && $materialCategoryName) {
                    $material_category = $topic->materialCategories()
                        ->withoutGlobalScopes()
                        ->where('company_id', $this->user?->company?->id)
                        ->where('name', 'ilike', $materialCategoryName)
                        ->first();

                    if (! $material_category) {
                        $material_category = MaterialCategory::create([
                            'company_id' => $this->user?->company?->id,
                            'topic_id' => $topic->id,
                            'name' => $materialCategoryName,
                        ]);
                    }
                }

                // Find or create Material
                $material = null;
                if ($topic && $materialName) {
                    if ($material_category) {
                        $material = $material_category->materials()
                            ->withoutGlobalScopes()
                            ->where('company_id', $this->user?->company?->id)
                            ->where('name', 'ilike', $materialName)
                            ->first();
                    } else {
                        $material = Material::withoutGlobalScopes()
                            ->where('company_id', $this->user?->company?->id)
                            ->where('topic_id', $topic->id)
                            ->whereNull('material_category_id')
                            ->where('name', 'ilike', $materialName)
                            ->first();
                    }

                    if (! $material) {
                        $material = Material::create([
                            'company_id' => $this->user?->company?->id,
                            'topic_id' => $topic->id,
                            'material_category_id' => $material_category?->id,
                            'level' => 1,
                            'name' => $materialName,
                        ]);
                    }
                }

                // Find or create CategoryQuestion
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

                // Prevent duplicates: search for existing question by company, study, topic, material, and question text
                $existingQuestion = Question::withoutGlobalScopes()
                    ->where('company_id', $this->user?->company?->id)
                    ->where('study_id', $study?->id)
                    ->where('topic_id', $topic?->id)
                    ->where('material_id', $material?->id)
                    ->where('question', $questionText)
                    ->first();

                $request_question = [
                    'id' => $existingQuestion?->id,
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
                    'order' => $orderIndex++,
                    'type' => ($this->import_type == 'essay') ? Question::TYPE_ESSAY : Question::TYPE_SINGLE,
                ];

                $question = app(QuestionService::class)->updateOrCreate($request_question);
                if (! $question) {
                    throw new Exception('Ada kesalahan saat QuestionImportJob => QuestionService => updateOrCreate', 500);
                }

                if ($this->import_type == 'pg') {
                    $letters = ['A', 'B', 'C', 'D', 'E'];
                    foreach ($letters as $lIdx => $letter) {
                        if ($isShiftedRow) {
                            $answerText = trim((string) ($rowArray[8 + $lIdx] ?? ''));
                            $answerImageUrl = null;
                        } else {
                            $keyText = 'opt_' . strtolower($letter);
                            $keyImg = 'opt_img_' . strtolower($letter);

                            $answerText = $this->getMappedValue($rowArray, $headerMap, $keyText);
                            $answerImageUrl = $this->getMappedValue($rowArray, $headerMap, $keyImg);
                        }

                        $isE = ($letter === 'E');
                        if ($isE && ($answerText === null || $answerText === '') && $answerImageUrl === null) {
                            continue;
                        }

                        $contextVal = $answerText;
                        if (($contextVal === null || $contextVal === '') && ! $answerImageUrl) {
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
                            throw new Exception('Ada kesalahan saat QuestionImportJob => AnswerService => updateOrCreate', 500);
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
            Log::error('Ada Kesalahan saat QuestionImportJob', $error);

            if ($this->user) {
                \Illuminate\Support\Facades\Cache::put('import_status_' . $this->user->id, 'failed:' . $th->getMessage(), 3600);
            }

            throw $th;
        }
    }

    private function rowContainsProdiHeader(array $row): bool
    {
        foreach ($row as $cell) {
            if (is_string($cell) && strtolower(trim($cell)) === 'prodi') {
                return true;
            }
        }

        return false;
    }

    private function buildHeaderMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $index => $colName) {
            if ($colName === null) {
                continue;
            }
            $name = strtolower(trim((string) $colName));
            if ($name === '') {
                continue;
            }

            if ($name === 'prodi') {
                $map['study'] = $index;
            } elseif (in_array($name, ['topik soal', 'topik'])) {
                $map['topic'] = $index;
            } elseif (in_array($name, ['kategori materi', 'kategori materi soal'])) {
                $map['material_category'] = $index;
            } elseif (in_array($name, ['materi soal', 'materi'])) {
                $map['material'] = $index;
            } elseif (in_array($name, ['tipe soal', 'tipe ujian', 'tipe'])) {
                $map['type'] = $index;
            } elseif (in_array($name, ['kategori soal', 'kategori'])) {
                $map['category'] = $index;
            } elseif (in_array($name, ['soal', 'pertanyaan'])) {
                $map['question'] = $index;
            } elseif (in_array($name, ['deskripsi soal', 'deskripsi', 'petunjuk'])) {
                $map['description'] = $index;
            } elseif (in_array($name, ['url gambar soal', 'gambar soal', 'gambar'])) {
                $map['question_image'] = $index;
            } elseif ($name === 'a') {
                $map['opt_a'] = $index;
            } elseif (in_array($name, ['url gambar a', 'gambar a'])) {
                $map['opt_img_a'] = $index;
            } elseif ($name === 'b') {
                $map['opt_b'] = $index;
            } elseif (in_array($name, ['url gambar b', 'gambar b'])) {
                $map['opt_img_b'] = $index;
            } elseif ($name === 'c') {
                $map['opt_c'] = $index;
            } elseif (in_array($name, ['url gambar c', 'gambar c'])) {
                $map['opt_img_c'] = $index;
            } elseif ($name === 'd') {
                $map['opt_d'] = $index;
            } elseif (in_array($name, ['url gambar d', 'gambar d'])) {
                $map['opt_img_d'] = $index;
            } elseif ($name === 'e') {
                $map['opt_e'] = $index;
            } elseif (in_array($name, ['url gambar e', 'gambar e'])) {
                $map['opt_img_e'] = $index;
            } elseif (in_array($name, ['jawaban', 'kunci jawaban', 'jawaban benar', 'kunci'])) {
                $map['answer_key'] = $index;
            } elseif (in_array($name, ['jawaban referensi', 'referensi jawaban', 'kunci essay'])) {
                $map['reference_answer'] = $index;
            } elseif (in_array($name, ['url gambar jawaban', 'gambar jawaban'])) {
                $map['reference_answer_image'] = $index;
            }
        }

        return $map;
    }

    private function getMappedValue(array $row, array $headerMap, string $key): ?string
    {
        if (! isset($headerMap[$key])) {
            return null;
        }

        $index = $headerMap[$key];
        if (! array_key_exists($index, $row)) {
            return null;
        }

        $val = $row[$index];
        if (is_string($val)) {
            $val = trim($val);
        }

        return $val === '' || $val === null ? null : (string) $val;
    }
}
