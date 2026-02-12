<?php

namespace App\Models\Master\Timetable;

use App\Models\Classmate\Classmate;
use App\Models\Company\Company;
use App\Models\Master\Exam\ExamRoom;
use App\Models\Master\Exam\ExamSession;
use App\Models\User\UserTimetable;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Module;
use App\Models\Master\Question\ModuleQuestion;
use App\Models\Timetable\TimetableAnswer;
use App\Models\Timetable\TimetableModule;
use App\Models\Timetable\TimetableQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Timetable extends Model
{
    //
    use SoftDeletes, HasUuids;
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

    public function timetableModule()
    {
        return $this->hasOne(TimetableModule::class, 'timetable_id', 'id');
    }

    protected $casts = [
        'supervisors' => 'array',
        'start_time'  => 'datetime',
        'end_time'    => 'datetime',
        'require_seb' => 'boolean',
        'is_recording' => 'boolean',
        'is_streaming' => 'boolean',
    ];

    /**
     * Check if timetable requires Safe Exam Browser
     */
    public function requiresSEB(): bool
    {
        return $this->require_seb || config('seb.require_for_all_exams', false);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !$user->hasRole('Anonymous')) {
                $builder->where('company_id', optional($user?->company)?->id)->orderBy('order', 'asc');
            }

            $builder->orderBy('order', 'asc');
        });

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });

        static::saved(function ($model) {
            if ($model->module_id) {
                DB::transaction(function () use ($model) {
                    $module = Module::find($model->module_id);
                    if (!$module) {
                        return;
                    }

                    $timetableModule = TimetableModule::updateOrCreate(
                        [
                            'timetable_id' => $model->id,
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
                        ],
                    );

                    ModuleQuestion::where('module_id', $module->id)
                        ->with(['question.answers'])
                        ->chunkById(200, function ($moduleQuestions) use ($timetableModule) {
                            $now = now();
                            $questionUpserts = [];

                            $companyId = $timetableModule->company_id ?? auth()->user()?->company_id;

                            foreach ($moduleQuestions as $moduleQuestion) {
                                $question = $moduleQuestion->question;
                                if (!$question) {
                                    continue;
                                }

                                $questionUpserts[] = [
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
                                ];
                            }

                            if (!empty($questionUpserts)) {
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
                                    ]
                                );

                                $questionIds = collect($questionUpserts)->pluck('question_id')->all();
                                $timetableQuestions = TimetableQuestion::where('timetable_module_id', $timetableModule->id)
                                    ->whereIn('question_id', $questionIds)
                                    ->get(['id', 'question_id'])
                                    ->keyBy('question_id');

                                $answerUpserts = [];
                                foreach ($moduleQuestions as $moduleQuestion) {

                                    $question = $moduleQuestion->question;
                                    if (!$question) {
                                        continue;
                                    }

                                    $timetableQuestion = $timetableQuestions->get($question->id);
                                    if (!$timetableQuestion) {
                                        continue;
                                    }

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
                                            'timetable_question_id' => $timetableQuestion->id,
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

                                if (!empty($answerUpserts)) {
                                    TimetableAnswer::upsert(
                                        $answerUpserts,
                                        ['timetable_question_id', 'answer_id'],
                                        ['company_id', 'alphabet', 'context', 'images', 'latex', 'latex_preview_pdf', 'latex_preview_png', 'is_correct', 'order', 'updated_at']
                                    );
                                }
                            }
                        });
                });
            }
        });
    }

    public function scopeSearch(Builder $query, $term): void
    {
        $term = '%' . $term . '%';

        $query->where(function ($query) use ($term) {
            $query->whereAny(['company_id', 'name', 'start_time', 'end_time', 'description'], 'ILIKE', $term);
        });
    }

    public function classmate()
    {
        return $this->belongsTo(Classmate::class, 'classmate_id', 'id');
    }

    /**
     * Get the examRoom that owns the Timetable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examRoom(): BelongsTo
    {
        return $this->belongsTo(ExamRoom::class, 'exam_room_id', 'id');
    }

    /**
     * Get the examSession that owns the Timetable
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class, 'exam_session_id', 'id');
    }
}
