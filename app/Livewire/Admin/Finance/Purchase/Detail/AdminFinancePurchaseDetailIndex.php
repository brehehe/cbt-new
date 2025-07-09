<?php

namespace App\Livewire\Admin\Finance\Purchase\Detail;

use App\Helpers\AlertHelper;
use App\Models\Account\Account;
use App\Models\Company\Company;
use App\Models\Finance\Finance;
use App\Models\Finance\FinancePayment;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Livewire\Component;
use Carbon\Carbon;
use DB;
use Log;

class AdminFinancePurchaseDetailIndex extends Component
{
    public $financeId;
    public $code, $description, $date, $type, $grand_total, $details = [], $account_id, $accounts = [], $status;

    public function mount()
    {
        $this->financeId = session('finance_purchase_id', null);

        if ($this->financeId === null) {
            return redirect()->route('user.finance.purchase');
        } else {
            $finance = Finance::find($this->financeId);
            if ($finance === null) {
                return redirect()->route('user.finance.purchase');
            }

            $this->code = $finance->code;
            $this->description = $finance->description;
            $this->date = $finance->date ? Carbon::parse($finance->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $this->type = $finance->type;
            $this->account_id = $finance->payments()->first()->account_payment_id;
            $this->grand_total = number_format($finance->grand_total, 0, ',', '.');
            $this->status = $finance->status;

            $this->getDetails();
        }

        $this->accounts = Account::where('company_id', auth()->user()->company_id)
            ->where('is_cash', true)
            ->orderBy('code', 'asc')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getDetails()
    {
        $finance = Finance::find($this->financeId);
        if ($finance) {
            $this->details = $finance->items()->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_name' => $item->product ? $item->product->name : 'Unknown Product',
                    'quantity' => $item->quantity,
                    'price' => number_format($item->price, 0, ',', '.'),
                    'tax' => number_format($item->tax, 0, ',', '.'),
                    'sub_total' => number_format($item->sub_total, 0, ',', '.'),
                ];
            })->toArray();
        }
    }

    public function confirmSubmit()
    {
        $this->validate([
            'account_id' => 'required|exists:accounts,id',
        ]);

        return AlertHelper::confirmSave('submit', 'Apakah Anda yakin ingin menyimpan perubahan ini?');
    }

    public function submit()
    {
        try {
            DB::beginTransaction();
            $finance = Finance::find($this->financeId);
            $journal = Journal::where('finance_id', $this->financeId)->first();
            if ($finance) {
                $finance->update([
                    'account_id' => $this->account_id,
                    'status' => 'confirmed',
                ]);

                $company = Company::find(auth()->user()->company_id);

                $financePayment = FinancePayment::where('finance_id', $this->financeId)->first();
                if ($financePayment) {
                    $financePayment->update([
                        'account_payment_id' => $this->account_id,
                    ]);

                    $journalItem = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePayment->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                            'account_id' => $financePayment->account_debt_id,
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $financePayment->account_debt_id,
                        'Hutang Usaha - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $finance->number,
                        $finance->grand_total,
                        Carbon::now(),
                        $journal,
                        $journalItem
                    );

                    $journalItemCredit = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePayment->id,
                            'type' => 'credit', // Assuming this is a credit transaction
                            'account_id' => $financePayment->account_payment_id,
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $financePayment->account_payment_id,
                        'Hutang Usaha - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $finance->number,
                        $finance->grand_total,
                        Carbon::now(),
                        $journal,
                        $journalItemCredit
                    );
                }

                $this->status = $finance->status;
                return AlertHelper::success('Berhasil', 'Pembelian berhasil diperbarui.');
            } else {
                return AlertHelper::error('Gagal', 'Pembelian tidak ditemukan.');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating purchase: ', [
                'error' => $e->getMessage(),
                'finance_id' => $this->financeId,
                'user_id' => auth()->id(),
                'company_id' => auth()->user()->company_id,
                'data' => [
                    'account_id' => $this->account_id,
                    'status' => $this->status,
                ],
                'trace' => $e->getTraceAsString(),
            ]);
            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat memperbarui pembelian: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.finance.purchase.detail.admin-finance-purchase-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
