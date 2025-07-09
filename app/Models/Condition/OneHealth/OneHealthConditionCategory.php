<?php

namespace App\Models\Condition\OneHealth;

use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Condition\OneHealth\OneHealthCondition;
use App\Models\Master\CodeSystem\Condition\MasterConditionCategory;

class OneHealthConditionCategory extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder                   = static::max('order');
            $modelCreate->order          = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->coding_display = $modelCreate?->category?->display;
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
                'coding_display' => $model?->category?->display
            ]);
        });
    }

    /**
     * Get the OHCondition that owns the OneHealthConditionCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function OHCondition(): BelongsTo
    {
        return $this->belongsTo(OneHealthCondition::class, 'one_health_condition_id', 'id');
    }

    /**
     * Get the category that owns the OneHealthConditionCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MasterConditionCategory::class, 'coding_code', 'code');
    }
}
