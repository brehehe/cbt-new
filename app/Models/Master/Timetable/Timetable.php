<?php

namespace App\Models\Master\Timetable;

use App\Models\Company\Company;
use App\Models\User\UserTimetable;
use Illuminate\Support\Facades\Auth;
use App\Models\Master\Question\Module;
use App\Models\Timetable\TimetableAnswer;
use App\Models\Timetable\TimetableModule;
use App\Models\Timetable\TimetableQuestion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

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
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_scope', function (Builder $builder) {
            $user = Auth::user();

            if (!$user || !$user->hasRole('Anonymous')) {
                $builder->where('company_id', optional($user?->company_id)?->id)->orderBy('order', 'asc');
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
                $module = Module::find($model->module_id);
                $timetableModule = TimetableModule::updateOrCreate(
                    [
                        'timetable_id' => $model->id,
                        'module_id' => $module->id,
                    ],
                    [
                        'user_id' => $module->user_id,
                        'question_type_id' => $module->question_type_id,
                        'name' => $module->name,
                        'description' => $module->description,
                        'duration' => $module->duration,
                        'random_question' => $module->random_question,
                    ],
                );

                foreach ($module->moduleQuestions as $question) {
                    $timetableQuestion = TimetableQuestion::updateOrCreate(
                        [
                            'timetable_module_id' => $timetableModule->id,
                            'question_id' => $question->id,
                        ],
                        [
                            'user_id' => $question->question->user_id,
                            'topic_id' => $question->question->topic_id,
                            'material_category_id' => $question->question->material_category_id,
                            'material_id' => $question->question->material_id,
                            'question_type_id' => $question->question->question_type_id,
                            'question' => $question->question->question,
                            'images' => $question->question->images,
                            'description' => $question->question->description,
                            'weight_correct' => $question->question->weight_correct,
                            'weight_incorrect' => $question->question->weight_incorrect,
                        ],
                    );

                    foreach ($question->question->answers  as $key => $value) {
                        $timeTableAnswer =  TimetableAnswer::updateOrCreate(
                            [
                                'timetable_question_id' => $timetableQuestion->id,
                                'answer_id' => $value->id,
                            ],
                            [
                                'alphabet' => $value->alphabet,
                                'context' => $value->context,
                                'images' => $value->images,
                                'is_correct' => $value->is_correct,
                            ]
                        );
                    }
                }
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
}
