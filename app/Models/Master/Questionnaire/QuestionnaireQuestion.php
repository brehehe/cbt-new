<?php

namespace App\Models\Master\Questionnaire;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireQuestion extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    protected $casts = [
        'is_required' => 'boolean',
        'order' => 'integer',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireSection::class, 'section_id', 'id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(QuestionnaireAnswer::class, 'question_id', 'id');
    }

    public function getAverageScoreAttribute(): float
    {
        return round((float) $this->answers()->whereNotNull('score')->avg('score'), 2);
    }
}
