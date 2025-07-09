<?php

namespace App\Models\Finance;

use App\Models\Company\Company;
use App\Models\MedicineType\MedicineType;
use App\Models\Product\Product;
use App\Models\Transaction\TransactionRecipe;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinanceRecipe extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function medicineType()
    {
        return $this->belongsTo(MedicineType::class, 'medicine_type_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function finance()
    {
        return $this->belongsTo(Finance::class, 'finance_id');
    }
    public function transactionRecipe()
    {
        return $this->belongsTo(TransactionRecipe::class, 'transaction_recipe_id');
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
