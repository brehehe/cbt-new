<?php

namespace App\Models\Master\RatingScale;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingScale extends Model
{
    //
    use \App\Traits\LogsSystemActivity, HasUuids, SoftDeletes;

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($modelCreate) {
            $lastOrder = static::max('order');
            $modelCreate->order = $lastOrder ? $lastOrder + 1 : 1;
            $modelCreate->company_id = $modelCreate->company_id ?? auth()->user()->company_id;
        });
    }

    private static $cachedScales = null;

    public static function getGrade($mark, $companyId = null)
    {
        if ($mark === null) {
            return null;
        }

        if (self::$cachedScales === null) {
            self::$cachedScales = self::orderBy('order')->get();
        }

        $targetCompanyId = $companyId ?? (auth()->check() ? auth()->user()->company_id : null);

        return self::$cachedScales->first(function ($scale) use ($mark, $targetCompanyId) {
            $companyMatch = true;
            if ($targetCompanyId && $scale->company_id) {
                $companyMatch = ($scale->company_id === $targetCompanyId);
            }
            return $companyMatch && $scale->min_score <= $mark && $scale->max_score >= $mark;
        });
    }
}
