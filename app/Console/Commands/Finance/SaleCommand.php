<?php

namespace App\Console\Commands\Finance;

use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use App\Models\Company\Company;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinanceOther;
use App\Models\Finance\FinancePayment;
use App\Models\Finance\FinanceRecipe;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Transaction\Transaction;
use App\Models\Transaction\TransactionDetail;
use App\Models\Transaction\TransactionPayment;
use App\Models\Transaction\TransactionRecipe;
use App\Services\Finance\FinanceAccountTransactionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SaleCommand extends Command
{
    // Account Codes Constants
    private const ACCOUNT_CODES = [
        'CASH' => '1-10001',
        'RECEIVABLE' => '1-10100',
        'REVENUE' => '4-40000',
        'VAT_OUTPUT' => '2-20500',
        'INVENTORY' => '1-10200',
        'COST_OF_GOODS_SOLD' => '5-50000',
        'OTHER_INCOME' => '7-70099',
        'OTHER_EXPENSE' => '8-80999',
        'INTEREST_EXPENSE' => '8-80000',
    ];

    // Finance Type Constants
    private const FINANCE_TYPES = [
        'FIRST_SERVICE' => 'first-service-price',
        'SECOND_SERVICE' => 'second-service-price',
        'PAYMENT_CHANGE' => 'payment-change',
        'ADMIN_FEE' => 'admin-fee',
        'ROUNDING' => 'rounding',
    ];

    public $cash;
    public $receivable;
    public $pendapatan;
    public $ppn_keluaran;
    public $persediaan;
    public $bebanpokokpendapatan;
    public $pendapatanlainlain;
    public $bebanlainlain;
    public $bebanBunga;

    protected $signature = 'app:sale-command {--reset : Reset sales before processing}';
    protected $description = 'Process completed sales transactions into finance records with journal entries';

    public function handle()
    {
        if ($this->option('reset')) {
            $this->resetSale();
            $this->info('Sales reset completed.');
            return;
        }
        $this->_processSale();
    }

    private function getAccountCompany($company)
    {
        $this->cash = Account::select('id', 'name')
            ->where('code', '1-10001')
            ->where('company_id', $company->id)
            ->first();
        $this->receivable = Account::select('id', 'name')
            ->where('code', '1-10100')
            ->where('company_id', $company->id)
            ->first();
        $this->pendapatan = Account::select('id', 'name')
            ->where('code', '4-40000')
            ->where('company_id', $company->id)
            ->first();
        $this->ppn_keluaran = Account::select('id', 'name')
            ->where('code', '2-20500')
            ->where('company_id', $company->id)
            ->first();
        $this->persediaan = Account::select('id', 'name')
            ->where('code', '1-10200')
            ->where('company_id', $company->id)
            ->first();
        $this->bebanpokokpendapatan = Account::select('id', 'name')
            ->where('code', '5-50000')
            ->where('company_id', $company->id)
            ->first();
        $this->pendapatanlainlain = Account::select('id', 'name')
            ->where('code', '7-70099')
            ->where('company_id', $company->id)
            ->first();
        $this->bebanlainlain = Account::select('id', 'name')
            ->where('code', '8-80999')
            ->where('company_id', $company->id)
            ->first();
        $this->bebanBunga = Account::select('id', 'name')
            ->where('code', '8-80000')
            ->where('company_id', $company->id)
            ->first();
    }

    private function resetSale(): void
    {
        $companies = Company::select('id', 'name')->get();

        foreach ($companies as $company) {
            $transactions = Transaction::where('company_id', $company->id)
                ->where('status', 'completed')
                // ->where('is_process_finance', true)
                ->get();

            foreach ($transactions as $transaction) {
                $this->info("Resetting sale for company: {$company->name} - Transaction Code: {$transaction->code}");
                $finances = Finance::where('transaction_id', $transaction->id)
                    ->where('company_id', $company->id)
                    ->get();

                foreach ($finances as $finance) {
                    Journal::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();
                    JournalItem::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();

                    FinancePayment::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();

                    FinanceItem::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();

                    FinanceOther::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();

                    FinanceRecipe::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();

                    AccountTransaction::where('finance_id', $finance->id)
                        ->where('company_id', $company->id)
                        ->delete();

                    $finance->delete();
                    $this->info("Deleting finance record: {$finance->code} for company: {$company->name}");
                    $finance->delete();
                }

                $transaction->is_process_finance = false;
                $transaction->save();
            }
        }
    }

    private function _processSale()
    {
        $this->info('🔄 Processing sales...');
        $companies = Company::select('id', 'name')->get();

        foreach ($companies as $company) {
            $this->info("Processing sale for company: {$company->name}");

            $this->getAccountCompany($company);

            $sales = Transaction::where('company_id', $company->id)
                ->where('status', 'completed')
                ->where('is_process_finance', false)
                ->get();

            if ($sales->isEmpty()) {
                $this->info("No sales found for company: {$company->name}");
                continue;
            }

            foreach ($sales as $sale) {
                $this->info("Processing sale: {$sale->code} for company: {$company->name}");

                $paymentChange = intval(Str::replace('.', '', number_format($sale->payment_change, 0, ',', '.')));

                $this->info("Processing sale: {$sale->code} with payment change: {$paymentChange} real value: {$sale->payment_change}");

                $finance = Finance::create([
                    'transaction_id' => $sale->id,
                    'type' => 'sale',
                    'description' => 'Sale transaction for ' . $sale->code,
                    'date' => $sale->created_at->format('Y-m-d'),
                    'sub_total' => $sale->sub_total_price,
                    'single_payment_admin_fee' => $sale->single_payment_admin_fee,
                    'first_service_price' => $sale->first_service_price,
                    'second_service_price' => $sale->second_service_price,
                    'embalage' => $sale->embalage,
                    'rounding' => $sale->rounding,
                    'grand_total' => $sale->grand_total_price_admin_fee,
                    'payment_change' => $paymentChange,
                    'status' => 'draft',
                    'company_id' => $company->id,
                ]);

                $this->info("Processing sale: {$finance->code} for company: {$company->name}");

                $journal = Journal::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'date' => Carbon::now(),
                    ]
                );

                $transactionPayments = TransactionPayment::where('transaction_id', $sale->id)
                    ->where('company_id', $company->id)
                    ->get();

                foreach ($transactionPayments as $payment) {
                    $paymentMethod = PaymentMethod::find($payment->payment_method_id);
                    $financePayment = FinancePayment::create([
                        'finance_id' => $finance->id,
                        'transaction_payment_id' => $payment->id,
                        'amount' => $payment->payment_amount,
                        'account_payment_id' => $paymentMethod->account_id,
                        'account_debt_id' => $this->receivable->id,
                        'company_id' => $company->id,
                    ]);

                    $journalItemPayment = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePayment->id,
                            'account_id' => $this->receivable->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $this->receivable->id,
                        'Piutang Usaha - Sale Processing for company name: ' . $company->name . ' - Sale Code: ' . $sale->code,
                        $payment->payment_amount,
                        Carbon::now(),
                        $journal,
                        $journalItemPayment
                    );
                }

                if ($finance->first_service_price > 0) {
                    $financeOtherFirstServicePrice = FinanceOther::create([
                        'finance_id' => $finance->id,
                        'name' => 'First Service Price',
                        'account_id' => $this->pendapatan->id,
                        'description' => 'First Service Price for Sale Code: ' . $sale->code,
                        'amount' => $sale->first_service_price,
                        'type' => 'credit',
                        'type_finance' => 'first-service-price',
                        'company_id' => $company->id,
                    ]);

                    $journalItemFirstServicePrice = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_other_id' => $financeOtherFirstServicePrice->id,
                            'account_id' => $this->pendapatan->id,
                            'type' => 'credit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $this->pendapatan->id,
                        'Pendapatan - Sale Processing for first service one company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Biaya Jasa 1',
                        $sale->first_service_price,
                        Carbon::now(),
                        $journal,
                        $journalItemFirstServicePrice,
                        $financeOtherFirstServicePrice->id
                    );
                }

                if ($finance->second_service_price > 0) {
                    $financeOtherSecondServicePrice = FinanceOther::create([
                        'finance_id' => $finance->id,
                        'name' => 'Second Service Price',
                        'account_id' => $this->pendapatan->id,
                        'description' => 'Second Service Price for Sale Code: ' . $sale->code,
                        'amount' => $sale->second_service_price,
                        'type' => 'credit',
                        'type_finance' => 'second-service-price',
                        'company_id' => $company->id,
                    ]);

                    $journalItemSecondServicePrice = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_other_id' => $financeOtherSecondServicePrice->id,
                            'account_id' => $this->pendapatan->id,
                            'type' => 'credit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $this->pendapatan->id,
                        'Pendapatan - Sale Processing for second service one company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Biaya Jasa 2',
                        $sale->second_service_price,
                        Carbon::now(),
                        $journal,
                        $journalItemSecondServicePrice,
                        $financeOtherSecondServicePrice->id
                    );
                }

                if ($finance->payment_change > 0) {
                    $financeOtherPaymentChange = FinanceOther::create([
                        'finance_id' => $finance->id,
                        'name' => 'Payment Change',
                        'account_id' => $this->cash->id,
                        'description' => 'Payment Change for Sale Code: ' . $sale->code,
                        'amount' => $paymentChange,
                        'type' => 'credit',
                        'type_finance' => 'payment-change',
                        'company_id' => $company->id,
                    ]);

                    $journalItemPaymentChange = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_other_id' => $financeOtherPaymentChange->id,
                            'account_id' => $this->cash->id,
                            'type' => 'credit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $this->cash->id,
                        'Pendapatan - Sale Processing for payment change company name: ' . $company->name . ' - Sale Code: ' . $sale->code  . ' - Kembalian',
                        $paymentChange,
                        Carbon::now(),
                        $journal,
                        $journalItemPaymentChange,
                        $financeOtherPaymentChange->id
                    );
                }
                if ($finance->single_payment_admin_fee > 0) {
                    $financeOtherAdminFee = FinanceOther::create([
                        'finance_id' => $finance->id,
                        'name' => 'Admin Fee',
                        'account_id' => $this->cash->id,
                        'description' => 'Admin Fee for Sale Code: ' . $sale->code,
                        'amount' => $sale->single_payment_admin_fee,
                        'type' => 'credit',
                        'type_finance' => 'admin-fee',
                        'company_id' => $company->id,
                    ]);

                    $journalItemAdminFee = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_other_id' => $financeOtherAdminFee->id,
                            'account_id' => $this->cash->id,
                            'type' => 'credit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePayment->id,
                        $company->id,
                        $this->cash->id,
                        'Pendapatan - Sale Processing for Admin Fee company name: ' . $company->name . ' - Sale Code: ' . $sale->code  . ' - Biaya Admin',
                        $sale->single_payment_admin_fee,
                        Carbon::now(),
                        $journal,
                        $journalItemAdminFee,
                        $financeOtherAdminFee->id
                    );
                }

                $rounding = abs($finance->rounding);

                if ($rounding > 0) {
                    if ($sale->rounding > 0) {
                        $financeOtherRounding = FinanceOther::create([
                            'finance_id' => $finance->id,
                            'name' => 'Rounding',
                            'account_id' => $this->pendapatan->id,
                            lainlain->id,
                            'description' => 'Rounding for Sale Code: ' . $sale->code,
                            'amount' => $rounding,
                            'type' => 'credit',
                            'type_finance' => 'rounding',
                            'company_id' => $company->id,
                        ]);

                        $journalItemRounding = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_other_id' => $financeOtherRounding->id,
                                'account_id' => $this->cash->id,
                                'type' => 'credit', // Assuming this is a debit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                            $finance,
                            null,
                            $financePayment->id,
                            $company->id,
                            $this->pendapatan->id,
                            lainlain->id,
                            'Pendapatan - Sale Processing for rounding company name: ' . $company->name . ' - Sale Code: ' . $sale->code  . ' - Pembulatan',
                            $rounding,
                            Carbon::now(),
                            $journal,
                            $journalItemRounding,
                            $financeOtherRounding->id
                        );
                    } elseif ($sale->rounding < 0) {
                        $financeOtherRounding = FinanceOther::create([
                            'finance_id' => $finance->id,
                            'name' => 'Rounding',
                            'account_id' => $this->bebanlainlain->id,
                            'description' => 'Rounding for Sale Code: ' . $sale->code,
                            'amount' => $rounding,
                            'type' => 'debit',
                            'type_finance' => 'rounding',
                            'company_id' => $company->id,
                        ]);

                        $journalItemRounding = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_other_id' => $financeOtherRounding->id,
                                'account_id' => $this->bebanlainlain->id,
                                'type' => 'credit', // Assuming this is a debit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                            $finance,
                            null,
                            $financePayment->id,
                            $company->id,
                            $this->bebanlainlain->id,
                            'Pendapatan - Sale Processing for rounding company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Pembulatan',
                            $rounding,
                            Carbon::now(),
                            $journal,
                            $journalItemRounding,
                            $financeOtherRounding->id
                        );
                    }
                }

                $transactionRecips = TransactionRecipe::where('transaction_id', $sale->id)
                    ->where('company_id', $company->id)
                    ->get();

                foreach ($transactionRecips as $recipe) {
                    $dppPPNRecipe = $this->getDppPPN($recipe->sub_total_price, true, 11);

                    $financeRecipe = FinanceRecipe::create([
                        'finance_id' => $finance->id,
                        'transaction_recipe_id' => $recipe->id,
                        'medicine_type_id' => $recipe->medicine_type_id,
                        'price_service_one' => $recipe->price_service_one,
                        'numero_recipe' => $recipe->numero_recipe,
                        'product_id' => $recipe->product_id,
                        'product_name' => $recipe?->product?->name ?? '-',
                        'quantity' => $recipe->quantity,
                        'price' => $recipe->price,
                        'price_hpp' => $recipe->price_hpp,
                        'sub_total_price' => $recipe->sub_total_price,
                        'sub_total_price_hpp' => $recipe->sub_total_price_hpp,
                        'sub_total_price_ppn' => $dppPPNRecipe['ppn'],
                        'sub_total_price_dpp' => $dppPPNRecipe['dpp'],
                        'company_id' => $company->id,
                    ]);

                    if ($financeRecipe->sub_total_price_dpp > 0) {
                        $journalItemRecipeDPP = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_recipe_id' => $financeRecipe->id,
                                'account_id' => $this->pendapatan->id,
                                'type' => 'credit', // Assuming this is a credit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                            $finance,
                            null,
                            null,
                            $company->id,
                            $this->pendapatan->id,
                            'Pendapatan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Produk Pendukung :' . $financeRecipe->product_name,
                            $financeRecipe->sub_total_price_dpp,
                            Carbon::now(),
                            $journal,
                            $journalItemRecipeDPP,
                            null,
                            $financeRecipe->id
                        );
                    }

                    if ($financeRecipe->sub_total_price_ppn > 0) {
                        $journalItemRecipePPN = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_recipe_id' => $financeRecipe->id,
                                'account_id' => $this->ppn_keluaran->id,
                                'type' => 'credit', // Assuming this is a credit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                            $finance,
                            null,
                            null,
                            $company->id,
                            $this->ppn_keluaran->id,
                            'PPN Keluaran - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Produk Pendukung :' . $financeRecipe->product_name,
                            $financeRecipe->sub_total_price_ppn,
                            Carbon::now(),
                            $journal,
                            $journalItemRecipePPN,
                            null,
                            $financeRecipe->id
                        );
                    }

                    if ($financeRecipe->sub_total_price_hpp > 0) {
                        $journalItemRecipeHPP = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_recipe_id' => $financeRecipe->id,
                                'account_id' => $this->persediaan->id,
                                'type' => 'credit', // Assuming this is a credit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                            $finance,
                            null,
                            null,
                            $company->id,
                            $this->persediaan->id,
                            'Persediaan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Produk Pendukung :' . $financeRecipe->product_name,
                            $financeRecipe->sub_total_price_hpp,
                            Carbon::now(),
                            $journal,
                            $journalItemRecipeHPP,
                            null,
                            $financeRecipe->id
                        );

                        $journalItemRecipeBebanPokokPendapatanHPP = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_recipe_id' => $financeRecipe->id,
                                'account_id' => $this->bebanpokokpendapatan->id,
                                'type' => 'debit', // Assuming this is a debit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                            $finance,
                            null,
                            null,
                            $company->id,
                            $this->bebanpokokpendapatan->id,
                            'Beban Pokok Pendapatan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Produk Pendukung :' . $financeRecipe->product_name,
                            $financeRecipe->sub_total_price_hpp,
                            Carbon::now(),
                            $journal,
                            $journalItemRecipeBebanPokokPendapatanHPP,
                            null,
                            $financeRecipe->id
                        );
                    }

                    $this->getTransactionDetails($company, $journal, $finance, $sale, $recipe, $financeRecipe);
                }

                $this->getTransactionDetailMedicineAction($company, $journal, $finance, $sale);
                $this->getTransactionDetailOther($company, $journal, $finance, $sale);

                $sale->is_process_finance = true;
                $sale->save();
            }
        }
        $this->info('✅ All sales have been processed successfully.');
    }

    private function getTransactionDetails($company, $journal, $finance, $sale, $recipe, $financeRecipe)
    {

        $transactionDetails = TransactionDetail::where('transaction_id', $sale->id)
            ->where('transaction_recipe_id', $recipe->id)
            ->get();

        foreach ($transactionDetails as $detail) {
            $this->info('Mendapatkan Detail : ' . $detail->id);
            $detail->sub_total_price = intval(Str::replace('.', '', number_format($detail->sub_total_price, 0, ',', '.')));
            $dppPPNRecipe = $this->getDppPPN($detail->sub_total_price, true, 11);
            $this->info('Total : ' . $detail->sub_total_price);
            $this->info('dpp : ' . $dppPPNRecipe['dpp']);
            $this->info('ppn : ' . $dppPPNRecipe['ppn']);

            $financeItem = FinanceItem::create([
                'finance_id' => $finance->id,
                'finance_recipe_id' => $financeRecipe->id,
                'transaction_detail_id' => $detail->id,
                'product_id' => $detail->product_id,
                'product_name' => $detail?->product?->name ?? '-',
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'price_hpp' => $detail->price_hpp,
                'sub_total' => $detail->sub_total_price,
                'sub_total_hpp' => $detail->sub_total_price_hpp,
                'sub_total_ppn' => $dppPPNRecipe['ppn'],
                'sub_total_dpp' => $dppPPNRecipe['dpp'],
                'company_id' => $company->id,
            ]);

            if ($financeItem->sub_total_dpp > 0) {
                $journalItemRecipeDPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'finance_recipe_id' => $financeRecipe->id,
                        'account_id' => $this->pendapatan->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->pendapatan->id,
                    'Pendapatan - Sale Processing for recipe - detail company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_dpp,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeDPP,
                    null,
                    $financeRecipe->id
                );
            }

            if ($financeItem->sub_total_ppn > 0) {
                $journalItemRecipePPN = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'finance_recipe_id' => $financeRecipe->id,
                        'account_id' => $this->ppn_keluaran->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->ppn_keluaran->id,
                    'PPN Keluaran - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_ppn,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipePPN,
                    null,
                    $financeRecipe->id
                );
            }

            if ($financeItem->sub_total_price_hpp > 0) {
                $journalItemRecipeHPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'finance_recipe_id' => $financeRecipe->id,
                        'account_id' => $this->persediaan->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->persediaan->id,
                    'Persediaan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_price_hpp,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeHPP,
                    null,
                    $financeRecipe->id
                );

                $journalItemRecipeBebanPokokPendapatanHPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'finance_recipe_id' => $financeRecipe->id,
                        'account_id' => $this->persediaan->id,
                        'type' => 'debit', // Assuming this is a debit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->persediaan->id,
                    'Beban Pokok Pendapatan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_price_hpp,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeBebanPokokPendapatanHPP,
                    null,
                    $financeRecipe->id
                );
            }
        }
    }

    private function getTransactionDetailMedicineAction($company, $journal, $finance, $sale)
    {
        $transactionDetails = TransactionDetail::where('transaction_id', $sale->id)
            ->whereIn('type_transaction', ['medicine', 'action'])
            ->get();

        foreach ($transactionDetails as $detail) {
            $this->info('Mendapatkan Detail Medicine & Action ' . $detail->id);
            $dppPPNRecipe = $this->getDppPPN($detail->sub_total_price, true, 11);

            $financeItem = FinanceItem::create([
                'finance_id' => $finance->id,
                'transaction_detail_id' => $detail->id,
                'product_id' => $detail->product_id,
                'product_name' => $detail?->product?->name ?? '-',
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'price_hpp' => $detail->price_hpp,
                'sub_total' => $detail->sub_total_price,
                'sub_total_hpp' => $detail->sub_total_price_hpp,
                'sub_total_ppn' => $dppPPNRecipe['ppn'],
                'sub_total_dpp' => $dppPPNRecipe['dpp'],
                'company_id' => $company->id,
            ]);

            if ($financeItem->sub_total_dpp > 0) {
                $journalItemRecipeDPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'account_id' => $this->pendapatan->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->pendapatan->id,
                    'Pendapatan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_dpp,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeDPP,
                    null,
                    null
                );
            }

            if ($financeItem->sub_total_ppn > 0) {
                $journalItemRecipePPN = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'account_id' => $this->ppn_keluaran->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->ppn_keluaran->id,
                    'PPN Keluaran - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_ppn,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipePPN,
                    null,
                    null
                );
            }

            if ($financeItem->sub_total_hpp > 0) {
                $journalItemRecipeHPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'account_id' => $this->persediaan->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->persediaan->id,
                    'Persediaan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' Produk : ' . $financeItem->product_name,
                    $financeItem->sub_total_hpp,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeHPP,
                    null,
                    null
                );

                $journalItemRecipeBebanPokokPendapatanHPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'account_id' => $this->persediaan->id,
                        'type' => 'debit', // Assuming this is a debit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->persediaan->id,
                    'Beban Pokok Pendapatan - Sale Processing for recipe company name: ' . $company->name . ' - Sale Code: ' . $sale->code,
                    $financeItem->sub_total_hpp,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeBebanPokokPendapatanHPP,
                    null,
                    null
                );
            }
        }
    }

    private function getTransactionDetailOther($company, $journal, $finance, $sale)
    {
        $transactionDetails = TransactionDetail::where('transaction_id', $sale->id)
            ->whereIn('type_transaction', ['other'])
            ->get();

        foreach ($transactionDetails as $detail) {
            $this->info('Mendapatkan Detail Other ' . $detail->id);
            $dppPPNRecipe = $this->getDppPPN($detail->sub_total_price, true, 11);

            $financeItem = FinanceItem::create([
                'finance_id' => $finance->id,
                'transaction_detail_id' => $detail->id,
                'product_id' => $detail->product_id,
                'product_name' => $detail?->product?->name ?? '-',
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'price_hpp' => $detail->price_hpp,
                'sub_total' => $detail->sub_total_price,
                'sub_total_hpp' => $detail->sub_total_price_hpp,
                'sub_total_ppn' => $dppPPNRecipe['ppn'],
                'sub_total_dpp' => $dppPPNRecipe['dpp'],
                'company_id' => $company->id,
            ]);

            if ($financeItem->sub_total_price > 0) {
                $journalItemRecipeDPP = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_item_id' => $financeItem->id,
                        'account_id' => $this->pendapatan->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    $financeItem->id,
                    null,
                    $company->id,
                    $this->pendapatan->id,
                    'Pendapatan - Sale Processing for lainnya company name: ' . $company->name . ' - Sale Code: ' . $sale->code . ' - Biaya Konsultasi',
                    $financeItem->sub_total_price,
                    Carbon::now(),
                    $journal,
                    $journalItemRecipeDPP,
                    null,
                    null
                );
            }
        }
    }

    private function getDppPPN($amount, $isTaxIncluded = true, $rate = 11)
    {
        if ($isTaxIncluded) {
            $dpp = $amount / (1 + $rate / 100);
            $ppn = $amount - $dpp;
        } else {
            $dpp = $amount;
            $ppn = $amount * ($rate / 100);
        }

        return [
            'dpp' => intval(Str::replace('.', '', number_format(round($dpp, 2), 0, ',', '.'))),
            'ppn' => intval(Str::replace('.', '', number_format(round($ppn, 2), 0, ',', '.'))),
        ];
    }
}
