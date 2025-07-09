<?php

namespace App\Livewire\Admin\Finance\Finance;

use App\Helpers\AlertHelper;
use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinancePayment;
use App\Models\Journal\Journal;
use Livewire\Component;
use Session;
use Livewire\WithPagination;

class AdminFinanceFinanceIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $queryString = [
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;

    public function mount()
    {
        Session::forget('finance_finance_id');
    }

    public function createFinance()
    {
        return redirect()->route('user.finance.finance.detail');
    }

    public function editFinance($financeId)
    {
        Session::put('finance_finance_id', $financeId);
        return redirect()->route('user.finance.finance.detail');
    }

    public function confirmDelete($financeId)
    {
        return AlertHelper::confirmDelete('delete', 'Apakah Anda yakin ingin menghapus data ini?', $financeId);
    }

    public function delete($id)
    {
        $finance = Finance::findOrFail($id[0]);

        $journal = Journal::where('finance_id', $finance->id)->first();
        if ($journal) {
            $journalItems = $journal->items;
            foreach ($journalItems as $journalItem) {
                $journalItem->delete();
            }
            $journal->delete();
        }

        $financePayments = FinancePayment::where('finance_id', $finance->id)->get();
        foreach ($financePayments as $financePayment) {
            $financePayment->delete();
        }

        $financeItems = FinanceItem::where('finance_id', $finance->id)->get();
        foreach ($financeItems as $financeItem) {
            $financeItem->delete();
        }

        $accountTransactions = AccountTransaction::where('finance_id', $finance->id)->get();
        foreach ($accountTransactions as $accountTransaction) {
            $accountTransaction->delete();
        }

        $finance->delete();

        return AlertHelper::success('Data berhasil dihapus.');
    }

    public function render()
    {
        $finance = Finance::search($this->search)
            ->select('id', 'code', 'date', 'type', 'description', 'sub_total', 'discount', 'tax', 'grand_total', 'company_id')
            ->where('company_id', auth()->user()->company_id)
            ->whereIn('type', ['expenditure', 'reception', 'fund-transfer'])
            ->orderBy('order', 'desc');

        return view('livewire.admin.finance.finance.admin-finance-finance-index', [
            'finances' => $finance->paginate($this->perPage)
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
