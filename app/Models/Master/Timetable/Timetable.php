<?php

namespace App\Models\Master\Timetable;

use App\Models\Classmate\Classmate;
use App\Models\Company\Company;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Master\Question\Question;
use App\Models\Timetable\TimetableAnswer;
use App\Models\Timetable\TimetableModule;
use App\Models\Timetable\TimetableQuestion;
use App\Models\User\UserTimetable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Timetable extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id', 'id');
    }

    public function userTimetables()
    {
        return $this->hasMany(UserTimetable::class, 'timetable_id', 'id');
    }

    public function userTimetable()
    {
        return $this->hasOne(UserTimetable::class, 'timetable_id', 'id')
            ->withoutGlobalScopes()
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');
    }

    public function timetableModule()
    {
        return $this->hasOne(TimetableModule::class, 'timetable_id', 'id');
    }

    public function timetableDetails()
    {
        return $this->hasMany(TimetableDetail::class, 'timetable_id', 'id')->orderBy('order', 'asc');
    }

    public function attendances()
    {
        return $this->hasMany(TimetableAttendance::class, 'timetable_id', 'id');
    }

    protected $casts = [
        'supervisors' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'require_seb' => 'boolean',
        'is_recording' => 'boolean',
        'is_streaming' => 'boolean',
        'allow_repeat' => 'boolean',
        'require_token' => 'boolean',
    ];

    /**
     * Check if timetable is a simulation
     */
    public function isSimulation(): bool
    {
        return $this->is_simulation === 'true' || $this->is_simulation === true || $this->is_simulation === 1;
    }

    /**
     * Check if timetable allows repeat attempts
     */
    public function allowsRepeat(): bool
    {
        return $this->allow_repeat || $this->isSimulation();
    }

    /**
     * Check if timetable requires token input
     */
    public function requiresToken(): bool
    {
        return $this->require_token !== false && $this->require_token !== 0 && $this->require_token !== 'false';
    }

    /**
     * Check if timetable requires Safe Exam Browser
     */
    public function requiresSEB(): bool
    {
        return $this->require_seb || config('seb.require_for_all_exams', false);
    }



    public static function syncModuleQuestions(Timetable $timetable): int
    {
        // 1. Gather all module IDs associated with this timetable (from parent timetable or from timetable details)
        $moduleIds = collect([$timetable->module_id])
            ->merge($timetable->timetableDetails()->whereNotNull('module_id')->pluck('module_id'))
            ->filter()
            ->unique()
            ->values();

        if ($moduleIds->isEmpty()) {
            return 0;
        }

        // If parent timetable module_id is not set, set it to the first module
        if (! $timetable->module_id && $moduleIds->isNotEmpty()) {
            $timetable->update(['module_id' => $moduleIds->first()]);
        }

        $totalSyncedQuestions = 0;

        foreach ($moduleIds as $moduleId) {
            $syncedForModule = DB::transaction(function () use ($timetable, $moduleId) {
                $module = Module::withoutGlobalScopes()
                    ->select('id', 'studys', 'user_id', 'question_type_id', 'name', 'description', 'duration', 'random_question', 'question_pick_type', 'total_questions', 'company_id')
                    ->find($moduleId);

                if (! $module) {
                    return 0;
                }

                $companyId = $module->company_id ?? $timetable->company_id ?? auth()->user()?->company_id;

                $timetableModule = TimetableModule::updateOrCreate(
                    [
                        'timetable_id' => $timetable->id,
                        'module_id' => $module->id,
                    ],
                    [
                        'studys' => $module->studys,
                        'user_id' => $module->user_id,
                        'question_type_id' => $module->question_type_id,
                        'name' => $module->name,
                        'description' => $module->description,
                        'duration' => $module->duration,
                        'random_question' => $module->random_question,
                        'total_questions' => $timetable->total_questions ?? $module->total_questions,
                        'company_id' => $companyId,
                    ],
                );

                // Check if ModuleQuestion has questions
                $existingModuleQuestionsCount = ModuleQuestion::withoutGlobalScopes()
                    ->where('module_id', $module->id)
                    ->whereNull('deleted_at')
                    ->count();

                // AUTO-POPULATE MODULE: Jika module_questions masih kosong, otomatis hubungkan dari Bank Soal (Question)
                if ($existingModuleQuestionsCount === 0) {
                    $studys = is_array($module->studys) ? $module->studys : json_decode($module->studys ?? '[]', true);
                    if (empty($studys) && $timetable->studys) {
                        $studys = is_array($timetable->studys) ? $timetable->studys : json_decode($timetable->studys ?? '[]', true);
                    }

                    $bankQuestionsQuery = Question::withoutGlobalScopes()
                        ->whereNull('deleted_at');

                    if (! empty($studys)) {
                        $bankQuestionsQuery->whereIn('study_id', $studys);
                    } elseif ($timetable->study_id) {
                        $bankQuestionsQuery->where('study_id', $timetable->study_id);
                    }

                    if ($companyId) {
                        $bankQuestionsQuery->where(function ($q) use ($companyId) {
                            $q->where('company_id', $companyId)->orWhereNull('company_id');
                        });
                    }

                    $bankQuestions = $bankQuestionsQuery->orderBy('order', 'asc')->get(['id', 'study_id']);

                    if ($bankQuestions->isNotEmpty()) {
                        $now = now();
                        $lastOrder = 0;
                        $autoModuleQuestions = [];
                        foreach ($bankQuestions as $bq) {
                            $autoModuleQuestions[] = [
                                'id' => (string) Str::uuid(),
                                'module_id' => $module->id,
                                'question_id' => $bq->id,
                                'company_id' => $companyId,
                                'study_id' => $bq->study_id,
                                'question_pick_type' => 'manual',
                                'order' => ++$lastOrder,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }

                        foreach (array_chunk($autoModuleQuestions, 200) as $chunk) {
                            ModuleQuestion::insert($chunk);
                        }
                    }
                }

                $questionPickType = $module->question_pick_type ?? 'manual';

                $moduleQuestionsQuery = ModuleQuestion::withoutGlobalScopes()
                    ->where('module_id', $module->id)
                    ->whereNull('deleted_at');

                // Check if any questions match the strict questionPickType
                $hasStrictMatches = (clone $moduleQuestionsQuery)
                    ->when($questionPickType === 'manual', function ($query) {
                        $query->where(function ($q) {
                            $q->whereNull('question_pick_type')
                                ->orWhere('question_pick_type', 'manual');
                        });
                    }, function ($query) use ($questionPickType) {
                        $query->where('question_pick_type', $questionPickType);
                    })->exists();

                $allModuleQuestions = $moduleQuestionsQuery
                    ->when($hasStrictMatches, function ($query) use ($questionPickType) {
                        $query->when($questionPickType === 'manual', function ($q) {
                            $q->where(function ($sub) {
                                $sub->whereNull('question_pick_type')
                                    ->orWhere('question_pick_type', 'manual');
                            });
                        }, function ($q) use ($questionPickType) {
                            $q->where('question_pick_type', $questionPickType);
                        });
                    })
                    ->with([
                        'question' => function ($q) {
                            $q->withoutGlobalScopes()->select('id', 'study_id', 'user_id', 'topic_id', 'material_category_id', 'material_id', 'question_type_id', 'category_question_id', 'difficulty', 'order', 'question', 'images', 'description', 'latex', 'latex_preview_pdf', 'latex_preview_png', 'weight_correct', 'weight_incorrect', 'type');
                        },
                        'question.answers' => function ($q) {
                            $q->withoutGlobalScopes()->select('id', 'question_id', 'alphabet', 'context', 'images', 'latex', 'latex_preview_pdf', 'latex_preview_png', 'is_correct', 'order');
                        },
                    ])
                    ->get()
                    ->unique('question_id');

                $syncedCount = 0;

                foreach ($allModuleQuestions->chunk(200) as $chunkModuleQuestions) {
                    $now = now();
                    $questionUpserts = [];
                    $answerUpserts = [];

                    $questionIdsInChunk = $chunkModuleQuestions->pluck('question_id')->all();
                    $existingTqs = TimetableQuestion::withoutGlobalScopes()
                        ->where('timetable_module_id', $timetableModule->id)
                        ->whereIn('question_id', $questionIdsInChunk)
                        ->get(['id', 'question_id'])
                        ->keyBy('question_id');

                    foreach ($chunkModuleQuestions as $moduleQuestion) {
                        $question = $moduleQuestion->question;
                        if (! $question) {
                            continue;
                        }

                        $tqId = $existingTqs->has($question->id) ? $existingTqs->get($question->id)->id : (string) Str::uuid();

                        $questionUpserts[] = [
                            'id' => $tqId,
                            'timetable_module_id' => $timetableModule->id,
                            'question_id' => $question->id,
                            'company_id' => $companyId,
                            'study_id' => $question->study_id,
                            'user_id' => $question->user_id,
                            'topic_id' => $question->topic_id,
                            'material_category_id' => $question->material_category_id,
                            'material_id' => $question->material_id,
                            'question_type_id' => $question->question_type_id,
                            'category_question_id' => $question->category_question_id,
                            'difficulty' => $question->difficulty ?? 'default',
                            'order' => $question->order ?? 0,
                            'question' => $question->question,
                            'images' => $question->images,
                            'description' => $question->description,
                            'latex' => $question->latex,
                            'latex_preview_pdf' => $question->latex_preview_pdf,
                            'latex_preview_png' => $question->latex_preview_png,
                            'weight_correct' => $question->weight_correct,
                            'weight_incorrect' => $question->weight_incorrect,
                            'created_at' => $now,
                            'updated_at' => $now,
                            'type' => $question->type,
                        ];

                        $answers = $question->answers
                            ->sortBy([
                                ['order', 'asc'],
                                ['created_at', 'asc'],
                                ['alphabet', 'asc'],
                            ])
                            ->values();

                        foreach ($answers as $index => $answer) {
                            $answerOrder = (int) ($answer->order ?? 0);
                            if ($answerOrder <= 0) {
                                $answerOrder = $index + 1;
                            }

                            $alphabet = $answer->alphabet;
                            if ($alphabet === null || $alphabet === '') {
                                $alphabet = chr(64 + $answerOrder);
                            }

                            $answerUpserts[] = [
                                'timetable_question_id' => $tqId,
                                'answer_id' => $answer->id,
                                'company_id' => $companyId,
                                'alphabet' => $alphabet,
                                'context' => $answer->context,
                                'images' => $answer->images,
                                'latex' => $answer->latex,
                                'latex_preview_pdf' => $answer->latex_preview_pdf,
                                'latex_preview_png' => $answer->latex_preview_png,
                                'is_correct' => $answer->is_correct,
                                'order' => $answerOrder,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }

                    if (! empty($questionUpserts)) {
                        TimetableQuestion::upsert(
                            $questionUpserts,
                            ['timetable_module_id', 'question_id'],
                            [
                                'company_id',
                                'study_id',
                                'user_id',
                                'topic_id',
                                'material_category_id',
                                'material_id',
                                'question_type_id',
                                'category_question_id',
                                'difficulty',
                                'order',
                                'question',
                                'images',
                                'description',
                                'latex',
                                'latex_preview_pdf',
                                'latex_preview_png',
                                'weight_correct',
                                'weight_incorrect',
                                'updated_at',
                                'type',
                            ]
                        );

                        if (! empty($answerUpserts)) {
                            TimetableAnswer::upsert(
                                $answerUpserts,
                                ['timetable_question_id', 'answer_id'],
                                ['company_id', 'alphabet', 'context', 'images', 'latex', 'latex_preview_pdf', 'latex_preview_png', 'is_correct', 'order', 'updated_at']
                            );
                        }

                        $syncedCount += count($questionUpserts);
                    }
                }

                return $syncedCount;
            });

            $totalSyncedQuestions += $syncedForModule;
        }

        // 2. Also auto-populate any existing UserTimetables for this timetable that currently have 0 questions
        try {
            $emptyUserTimetables = UserTimetable::withoutGlobalScopes()
                ->where('timetable_id', $timetable->id)
                ->whereDoesntHave('userModuleQuestions')
                ->get();

            if ($emptyUserTimetables->isNotEmpty()) {
                $apiCtrl = app(\App\Http\Controllers\Api\Exam\ExamApiController::class);
                foreach ($emptyUserTimetables as $ut) {
                    $apiCtrl->autoPopulateUserQuestionsPublic($ut);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Auto-populating empty user timetables after sync error: ' . $e->getMessage());
        }

        return $totalSyncedQuestions;
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if ($user && ! $user->hasRole('Anonymous')) {
                $builder->where(function ($query) use ($user) {
                    $query->where('company_id', optional($user?->company)?->id)
                        ->orWhere('is_simulation', 'true')
                        ->orWhereNull('company_id');
                });
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });

        static::saved(function ($model) {
            static::syncModuleQuestions($model);
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%'.$term.'%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'start_time', 'end_time', 'description'], 'ilike', $term);
        });
    }

    public function classmate()
    {
        return $this->belongsTo(Classmate::class, 'classmate_id', 'id');
    }

    /**
     * Get the examRoom that owns the Timetable
     */
    public function examRoom(): BelongsTo
    {
        return $this->belongsTo(ExamRoom::class, 'exam_room_id', 'id');
    }

    /**
     * Get the examSession that owns the Timetable
     */
    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id', 'id');
    }
}
