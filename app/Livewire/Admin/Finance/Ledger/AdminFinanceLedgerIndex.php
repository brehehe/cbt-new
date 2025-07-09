<?php

namespace App\Livewire\Admin\Finance\Ledger;

use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class AdminFinanceLedgerIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date;
    public $end_date;
    public $account_id;

    public $accounts = [];

    public function mount()
    {
        $this->accounts = Account::where('company_id', auth()->user()->company_id)
            ->orderBy('code', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();

        $this->start_date = now()->startOfMonth()->toDateString();
        $this->end_date = now()->endOfMonth()->toDateString();
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $accountTransactions = AccountTransaction::where('company_id', auth()->user()->company_id)
            ->search($this->search)
            ->select([
                'id',
                'description',
                'date',
                'debit',
                'credit',
                'account_id',
                'journal_id',
                'journal_item_id'
            ])
            ->with(['account', 'journal', 'journalItem'])
            ->orderBy('date', 'desc');

        if ($this->start_date && $this->end_date) {
            $accountTransactions->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        if ($this->account_id) {
            $accountTransactions->where('account_id', $this->account_id);
        }

        return view('livewire.admin.finance.ledger.admin-finance-ledger-index', [
            'debits' => (clone $accountTransactions)->sum('debit'),
            'credits' => (clone $accountTransactions)->sum('credit'),
            'total' => (clone $accountTransactions)->sum('debit') - (clone $accountTransactions)->sum('credit'),
            'accountTransactions' => (clone $accountTransactions)->paginate($this->perPage),

        ])
            ->extends('layout.app')
            ->section('content');
    }
}
