<?php

namespace App\Livewire\Admin\Finance\Sale\Detail;

use App\Helpers\AlertHelper;
use Livewire\Component;
use App\Models\Account\Account;
use App\Models\Company\Company;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinancePayment;
use App\Models\Finance\FinanceRecipe;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Carbon\Carbon;
use DB;
use Log;

class AdminFinanceSaleDetailIndex extends Component
{
    public $financeId;
    public $code, $description, $date, $type, $grand_total, $recipes = [], $details = [], $payments = [], $account_id, $accounts = [], $status;
    public $finance;

    public function mount()
    {
        $this->financeId = session('finance_sale_id', null);

        if ($this->financeId === null) {
            return redirect()->route('user.finance.sale');
        } else {
            $finance = Finance::find($this->financeId);
            if ($finance === null) {
                return redirect()->route('user.finance.sale');
            }

            $this->code = $finance->code;
            $this->description = $finance->description;
            $this->date = $finance->date ? Carbon::parse($finance->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d');
            $this->type = $finance->type;
            $this->account_id = $finance->payments()->first()->account_payment_id;
            $this->grand_total = number_format($finance->grand_total, 0, ',', '.');
            $this->status = $finance->status;
            $this->finance = Finance::find($this->financeId);

            $this->getDetails();
            $this->getRecipes();
            $this->getPayments();
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
        $this->details = [];
        $financeItems = FinanceItem::where('finance_id', $this->financeId)
            ->whereNull('finance_recipe_id')
            ->get();

        foreach ($financeItems as $item) {
            $this->details[] = [
                'id' => $item->id,
                'product_name' => $item->product ? $item->product->name : $item->transactionDetail->name,
                'quantity' => $item->quantity,
                'price' => number_format($item->price, 0, ',', '.'),
                'tax' => number_format($item->tax, 0, ',', '.'),
                'sub_total' => number_format($item->sub_total, 0, ',', '.'),
            ];
        }
    }

    public function getRecipes()
    {
        $this->recipes = [];
        $financeRecipes = FinanceRecipe::where('finance_id', $this->financeId)
            ->get();
        foreach ($financeRecipes as $key => $recipe) {
            $this->recipes[] = [
                'id' => $recipe->id,
                'product_name' => $recipe->product ? $recipe->product->name : $recipe->transactionRecipe->name,
                'medicine_type_id' => $recipe->medicine_type_id,
                'medicine_type' => $recipe->medicineType ? $recipe->medicineType->name : 'Tidak Diketahui',
                'numero_recipe' => $recipe->numero_recipe,
                'is_single' => $recipe->medicineType->is_single ? true : false,
                'price_service_one' => number_format($recipe->price_service_one, 0, ',', '.'),
                'description' => $recipe->transactionRecipe ? $recipe->transactionRecipe->description : 'Tidak Diketahui',
                'product_id' => $recipe->product ? $recipe->product->id : null,
                'product_code' => $recipe->product ? $recipe->product->code : 'Tidak Diketahui',
                'product_name' => $recipe->product ? $recipe->product->name : 'Tidak Diketahui',
                'how_to_use' => $recipe->transactionRecipe ? $recipe->transactionRecipe->howToUse->name . ' - ' . $recipe->transactionRecipe->howToUse->description : 'Tidak Diketahui',
                'route_coding_code' => $recipe->transactionRecipe ? $recipe->transactionRecipe->routeCodingCode->code . ' - ' . $recipe->transactionRecipe->routeCodingCode->display : 'Tidak Diketahui',
                'quantity' => $recipe->quantity,
                'price' => number_format($recipe->price, 0, ',', '.'),
                'sub_total' => number_format($recipe->sub_total_price, 0, ',', '.'),
                'sub_total_hpp' => number_format($recipe->sub_total_price_hpp, 0, ',', '.'),
                'sub_total_ppn' => number_format($recipe->sub_total_price_ppn, 0, ',', '.'),
                'sub_total_dpp' => number_format($recipe->sub_total_price_dpp, 0, ',', '.'),
            ];

            $details = FinanceItem::where('finance_id', $this->financeId)
                ->where('finance_recipe_id', $recipe->id)
                ->get();
            foreach ($details as $detail) {
                $this->recipes[$key]['details'][] = [
                    'id' => $detail->id,
                    'product_name' => $detail->product ? $detail->product->name : $detail->transactionDetail->name,
                    'product_id' => $detail->product ? $detail->product->id : null,
                    'type' => $detail->transactionDetail->type,
                    'dosage_doctor' => $detail->transactionDetail->dosage_doctor ?? 0,
                    'doctor_dosage_gram' => number_format($detail->transactionDetail->doctor_dosage_gram, 0, ',', '.'),
                    'dosage_drug' => $detail->transactionDetail->dosage_drug ?? 0,
                    'quantity' => $detail->quantity,
                    'price' => number_format($detail->price, 0, ',', '.'),
                    'tax' => number_format($detail->tax, 0, ',', '.'),
                    'sub_total' => number_format($detail->sub_total, 0, ',', '.'),
                ];
            }
        }
    }

    public function getPayments()
    {
        $this->payments = [];

        $financePayments = FinancePayment::where('finance_id', $this->financeId)
            ->get();

        foreach ($financePayments as $payment) {
            $this->payments[] = [
                'id' => $payment->id,
                'payment_method' => $payment->transactionPayment->paymentMethod ? $payment->transactionPayment->paymentMethod->name : 'Tidak Diketahui',
                'account_debt' => $payment->accountDebt ? $payment->accountDebt->name : 'Tidak Diketahui',
                'account_payment' => $payment->accountPayment ? $payment->accountPayment->name : 'Tidak Diketahui',
                'amount' => number_format($payment->amount, 0, ',', '.'),
                'date' => $payment->date ? Carbon::parse($payment->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
                'description' => $payment->transactionPayment ? $payment->transactionPayment->description : 'Tidak Diketahui',
            ];
        }
    }

    public function confirmSubmit()
    {
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

                $financePayments = FinancePayment::where('finance_id', $this->financeId)->get();
                foreach ($financePayments as $financePayment) {
                    $journalItem = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePayment->id,
                            'type' => 'credit', // Assuming this is a debit transaction
                            'account_id' => $financePayment->account_debt_id,
                        ]
                    );
                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $financePayment->account_debt_id,
                        'Piutang Usaha - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $finance->number,
                        $finance->grand_total,
                        Carbon::now(),
                        $journal,
                        $journalItem
                    );

                    $journalItemPayment = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePayment->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                            'account_id' => $financePayment->account_payment_id,
                        ]
                    );
                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $financePayment->account_payment_id,
                        $financePayment->accountPayment->name . ' - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $finance->number,
                        $finance->grand_total,
                        Carbon::now(),
                        $journal,
                        $journalItemPayment
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
            Log::error('Error updating sale: ', [
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
        return view('livewire.admin.finance.sale.detail.admin-finance-sale-detail-index')
            ->extends('layout.app')
            ->section('content');
    }
}
