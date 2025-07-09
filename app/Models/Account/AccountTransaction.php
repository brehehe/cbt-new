<?php

namespace App\Models\Account;

use App\Models\Company\Company;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountTransaction extends Model
{
    //
    use SoftDeletes, HasUuids;
    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id');
    }

    public function journalItem()
    {
        return $this->belongsTo(JournalItem::class, 'journal_item_id');
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where('description', 'like', '%' . $search . '%')
                ->orWhere('date', 'like', '%' . $search . '%')
                ->orWhere('debit', 'like', '%' . $search . '%')
                ->orWhere('credit', 'like', '%' . $search . '%')
                ->orWhereHas('account', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('journal', function ($q) use ($search) {
                    $q->where('code', 'like', '%' . $search . '%');
                });
        }
        return $query;
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
