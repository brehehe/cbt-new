<?php

namespace App\Models\Master\RatingScale;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RatingScale extends Model
{
    //
    use SoftDeletes, HasUuids;
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

    public static function getGrade($mark, $companyId = null)
    {
        if ($mark === null) {
            return null;
        }

        return self::where('min_score', '<=', $mark)
            ->where('max_score', '>=', $mark)
            ->when($companyId, function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->orderBy('order')
            ->first();
    }
}
