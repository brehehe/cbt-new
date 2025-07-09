<?php

namespace App\Models\Transaction;

use App\Models\Company\Company;
use App\Models\HowToUse\HowToUse;
use App\Models\Master\CodeSystem\MedicationRequest\MasterMedicationRequestDosageRoute;
use App\Models\MedicineType\MedicineType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionRecipe extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function howToUse()
    {
        return $this->belongsTo(HowToUse::class, 'how_to_use_id');
    }

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_recipe_id')->orderBy('order', 'asc');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function medicineType()
    {
        return $this->belongsTo(MedicineType::class, 'medicine_type_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function routeCodingCode()
    {
        return $this->belongsTo(MasterMedicationRequestDosageRoute::class, 'route_coding_code', 'code');
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
