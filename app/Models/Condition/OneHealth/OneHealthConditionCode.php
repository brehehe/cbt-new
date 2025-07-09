<?php

namespace App\Models\Condition\OneHealth;

use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Condition\OneHealth\OneHealthCondition;
use App\Models\Icd\Icd10;
use App\Models\Master\CodeSystem\Condition\MasterConditionCodeChiefComplaint;
use App\Models\Master\CodeSystem\Condition\MasterConditionCodePreviousCondition;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OneHealthConditionCode extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->coding_display = $modelCreate?->icd10?->display ?? $modelCreate?->chiefComplaint?->display ?? $modelCreate?->previousCondition?->display;
        });

        static::saved(function ($model) {

            // Force commit any pending transactions before proceeding
            $initialTransactionLevel = DB::transactionLevel();
            if ($initialTransactionLevel > 0) {

                while (DB::transactionLevel() > 0) {
                    DB::commit();
                }
            }

            $model->updateQuietly([
                'coding_display' => $model?->icd10?->display ?? $model?->chiefComplaint?->display ?? $model?->previousCondition?->display
            ]);
        });
    }

    /**
     * Get the user that owns the OneHealthConditionCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHCondition(): BelongsTo
    {
        return $this->belongsTo(OneHealthCondition::class, 'one_health_condition_id', 'id');
    }

    /**
     * Get the icd10 that owns the OneHealthConditionCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function icd10(): BelongsTo
    {
        return $this->belongsTo(Icd10::class, 'coding_code', 'code');
    }

    /**
     * Get the chiefComplaint that owns the OneHealthConditionCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chiefComplaint(): BelongsTo
    {
        return $this->belongsTo(MasterConditionCodeChiefComplaint::class, 'coding_code', 'code');
    }

    /**
     * Get the previousCondition that owns the OneHealthConditionCode
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function previousCondition(): BelongsTo
    {
        return $this->belongsTo(MasterConditionCodePreviousCondition::class, 'coding_code', 'code');
    }
}
