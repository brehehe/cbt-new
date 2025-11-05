<?php

namespace App\Jobs\Question;

use App\Models\Master\Question\QuestionType;
use App\Models\Master\Question\Topic;
use App\Models\Study\Study;
use App\Services\Answer\AnswerService;
use App\Services\Question\QuestionService;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;
use Log;
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
            foreach ($this->collections as $key => $value) {
                if ($key == 0) {
                    continue;
                }

                $question_type = QuestionType::withoutGlobalScopes()->whereLike('name', "%$value[4]%")->first();

                if (!$question_type || !$value[5]) continue;

                for ($j = 7; $j < 10; $j++) { 
                    if (!$value[$i]) continue;
                }

                $study = Study::withoutGlobalScopes()->whereLike('name', "%{$value[0]}%")->first() ??
                    Study::create([
                        'company_id' => $this->user?->company?->id,
                        'name' => $value[0]
                    ]);

                $topic = $study?->topics()->withoutGlobalScopes()->whereLike('name', "%{$value[1]}%")->first() ?? 
                    $study->topics()->create([
                        'company_id' => $this->user?->company?->id,
                        'name' => $value[1]
                    ]);

                $material_category = $topic?->materialCategories()->withoutGlobalScopes()->whereLike('name', "%$value[2]%")->first() ??
                     $topic?->materialCategories()->create([
                        'company_id' => $this->user?->company?->id,
                        'name' => $value[2]
                     ]);

                $material = $material_category?->materials()->withoutGlobalScopes()->whereLike('name', "%$value[3]%")->first() ??
                    $material_category?->materials()->create([
                        'company_id' => $this->user?->company?->id,
                        'name' => $value[3]
                    ]);

                $request_question = [
                    'user_id'              => $this->user?->id,
                    'company_id'           => $this->user?->company?->id,
                    'study_id'             => $study?->id,
                    'topic_id'             => $topic?->id,
                    'material_category_id' => $material_category?->id,
                    'material_id'          => $material?->id,
                    'question_type_id'     => $question_type?->id,
                    'question'             => $value[5],
                    'images'               => null,
                    'old_images'           => null,
                    'description'          => $value[6],
                    'weight_correct'       => null,
                    'weight_incorrect'     => null,
                ];

                $question = app(QuestionService::class)->updateOrCreate($request_question);
                if (!$question) {
                    throw new Exception("Ada kesalahaan saat QuestionImportJob => QuestionService => updateOrCreate", 500);
                }

                for ($i = 7; $i <= 11; $i++) {
                    if (!$value[$i]) continue;
                    $request_answer = [
                        'company_id' => $this->user?->company?->id,
                        'alphabet'   => null,
                        'context'    => $value[$i],
                        'images'     => null,
                        'old_images' => null,
                        'is_correct' => $i == $this->letterToValue($value[12]) ? true : false,
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
}
