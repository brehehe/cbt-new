<?php

namespace App\Models\Finance;

use App\Models\Account\Account;
use App\Models\Company\Company;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Transaction\TransactionPayment;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancePayment extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function finance()
    {
        return $this->belongsTo(Finance::class, 'finance_id');
    }

    public function accountPayment()
    {
        return $this->belongsTo(Account::class, 'account_payment_id');
    }

    public function accountDebt()
    {
        return $this->belongsTo(Account::class, 'account_debt_id');
    }

    public function transactionPayment()
    {
        return $this->belongsTo(TransactionPayment::class, 'transaction_payment_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
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
