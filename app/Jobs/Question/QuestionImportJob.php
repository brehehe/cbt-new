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

                $topic = Topic::withoutGlobalScopes()->where('name', 'ilike', "%{$value[0]}%")->first();

                $material_category = $topic?->materialCategories()->withoutGlobalScopes()->whereLike('name', "%$value[1]%")->first();

                $material = $material_category?->materials()->withoutGlobalScopes()->whereLike('name', "%$value[2]%")->first();

                $question_type = QuestionType::withoutGlobalScopes()->whereLike('name', "%$value[3]%")->first();

                if (!$topic || !$question_type) continue;

                $request_question = [
                    'user_id'              => $this->user?->id,
                    'company_id'           => $this->user?->company?->id,
                    'topic_id'             => $topic?->id,
                    'study_id'             => $this->study_id,
                    'material_category_id' => $material_category?->id,
                    'material_id'          => $material?->id,
                    'question_type_id'     => $question_type?->id,
                    'question'             => $value[4],
                    'images'               => null,
                    'old_images'           => null,
                    'description'          => $value[5],
                    'weight_correct'       => null,
                    'weight_incorrect'     => null,
                ];

                $question = app(QuestionService::class)->updateOrCreate($request_question);
                if (!$question) {
                    throw new Exception("Ada kesalahaan saat QuestionImportJob => QuestionService => updateOrCreate", 500);
                }

                for ($i = 6; $i <= 10; $i++) {
                    $request_answer = [
                        'company_id' => $this->user?->company?->id,
                        'alphabet'   => null,
                        'context'    => $value[$i],
                        'images'     => null,
                        'old_images' => null,
                        'is_correct' => $i == $this->letterToValue($value[11]) ? true : false,
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
            'A'=>6,
            'B'=>7,
            'C'=>8,
            'D'=>9,
            'E'=>10
        ];
        return $map[strtoupper(trim((string)$ch))] ?? null;
    }
}
