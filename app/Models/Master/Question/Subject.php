<?php

namespace App\Models\Master\Question;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            // $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    /**
     * Get the topic that owns the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id', 'id');
    }

    /**
     * Get all of the subjects for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childs(): HasMany
    {
        return $this->hasMany(Subject::class, 'subject_id', 'id');
    }

    /**
     * Get the parent that owns the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    /**
     * Get all of the subSubjects for the Subject
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subSubjects(): HasMany
    {
        return $this->hasMany(SubSubject::class, 'subject_id', 'id');
    }

}
