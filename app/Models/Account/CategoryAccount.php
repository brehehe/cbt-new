<?php

namespace App\Models\Account;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryAccount extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function detailCategoryAccount()
    {
        return $this->belongsTo(DetailCategoryAccount::class, 'detail_category_account_id');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class, 'category_account_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('cash_flow', 'like', '%' . $search . '%')
            ->orWhereHas('detailCategoryAccount', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
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
}
