<?php

namespace App\Livewire\Admin\Finance\Journal;

use App\Models\Journal\Journal;
use Livewire\Component;
use Livewire\WithPagination;

class AdminFinanceJournalIndex extends Component
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

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $journals = Journal::search($this->search)
            ->where('company_id', auth()->user()->company_id)
            ->select([
                'id',
                'code',
                'date',
                'description',
            ])
            ->with(['items' => function ($query) {
                $query->select([
                    'id',
                    'journal_id',
                    'account_id'
                ]);
            }, 'items.account', 'items.accountTransaction'])
            ->orderBy('order', 'desc');

        if ($this->start_date && $this->end_date) {
            $journals->whereBetween('date', [$this->start_date, $this->end_date]);
        }

        return view('livewire.admin.finance.journal.admin-finance-journal-index', [
            'journals' => $journals->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
