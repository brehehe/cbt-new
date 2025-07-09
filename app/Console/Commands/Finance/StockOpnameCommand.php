<?php

namespace App\Console\Commands\Finance;

use App\Models\Account\Account;
use App\Models\Company\Company;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinancePayment;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Models\StockOpname\StockOpname;
use App\Models\StockOpname\StockOpnameItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class StockOpnameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stock-opname-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        // $this->_resetStockOpname();
        $this->_processStockOpname();
    }

    private function _resetStockOpname()
    {
        // Logic to reset stock opname
        $this->info('Stock opname has been reset.');
    }

    private function _processStockOpname()
    {

        $companys = Company::select('id', 'name')->get();

        foreach ($companys as $company) {
            $stockOpnames = StockOpname::where('company_id', $company->id)
                ->where('status', 'approve')
                ->where('is_process_finance', false)
                ->get();

            if ($stockOpnames->isEmpty()) {
                $this->info("No stock opname found for company ID: {$company->id}");
                continue;
            }

            $accountDebtFirst = Account::where('company_id', $company->id)
                ->where('code', '8-81005') // Assuming this is the account code for debt
                ->first();

            $accountDebtSecond = Account::where('company_id', $company->id)
                ->where('code', '7-70099') // Assuming this is the account code for debt
                ->first();

            $accountPersediaan = Account::where('company_id', $company->id)
                ->where('code', '1-10200') // Assuming this is the account code for inventory
                ->first();

            foreach ($stockOpnames as $stockOpname) {
                $finance = Finance::create([
                    'stock_opname_id' => $stockOpname->id,
                    'company_id' => $company->id,
                    'type' => 'stock-opname',
                    'date' => Carbon::now(),
                    'description' => 'Stock opname processing for company name: ' . $company->name . ' and Stock Opname Code: ' . $stockOpname->code . ' with total loss value: ' . $stockOpname->total_loss_value . ' and total excess value: ' . $stockOpname->total_excess_value,
                    'total_loss_value' => $stockOpname->total_loss_value,
                    'total_excess_value' => $stockOpname->total_excess_value,
                    'sub_total' => $stockOpname->total_loss_value + $stockOpname->total_excess_value,
                    'grand_total' => $stockOpname->total_loss_value + $stockOpname->total_excess_value,
                ]);

                $journal = Journal::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'date' => Carbon::now(),
                        'description' => 'Jurnal for Stock Opname Processing for company name: ' . $company->name . ' - Stock Opname Code: ' . $stockOpname->code . ' - Finance Code: ' . $finance->code,
                    ]
                );

                $total_loss_value = $stockOpname->total_loss_value;

                $display_value = abs($total_loss_value);


                if ($display_value > 0) {
                    $financePaymentFirst = FinancePayment::create([
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'account_payment_id' => $accountDebtFirst->id, // Assuming account_id is not set here
                        'amount' => $finance->sub_total,
                        'total_loss_value' => $stockOpname->total_loss_value,
                        'date' => Carbon::now(),
                        'description' => 'Payment for stock opname ID: ' . $stockOpname->id,
                    ]);

                    $journalItemFirst = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePaymentFirst->id,
                            'account_id' => $accountDebtFirst->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        null,
                        $financePaymentFirst->id,
                        $company->id,
                        $accountDebtFirst->id,
                        'Beban Selisih Persediaan - Stock Opname Processing for company name: ' . $company->name . ' - Total Loss Value: ' . $stockOpname->total_loss_value,
                        $display_value,
                        Carbon::now(),
                        $journal,
                        $journalItemFirst
                    );
                }

                $total_excess_value = $stockOpname->total_excess_value;

                $display_excess_value = abs($total_excess_value);

                if ($display_excess_value > 0) {
                    $financePaymentSecond = FinancePayment::create([
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'account_payment_id' => $accountDebtSecond->id, // Assuming account_id is not set here
                        'amount' => $finance->sub_total,
                        'total_excess_value' => $stockOpname->total_excess_value,
                        'date' => Carbon::now(),
                        'description' => 'Payment for stock opname ID: ' . $stockOpname->id,
                    ]);

                    $journalItemSecond = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_payment_id' => $financePaymentSecond->id,
                            'account_id' => $accountDebtSecond->id,
                            'type' => 'credit', // Assuming this is a credit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        null,
                        $financePaymentSecond->id,
                        $company->id,
                        $accountDebtSecond->id,
                        'Pendapatan Lain - Lain - Stock Opname Processing for company name: ' . $company->name . ' - Total Excess Value: ' . $stockOpname->total_excess_value,
                        $display_excess_value,
                        Carbon::now(),
                        $journal,
                        $journalItemSecond
                    );
                }

                $stockOpnameItems = StockOpnameItem::where('stock_opname_id', $stockOpname->id)->get();

                foreach ($stockOpnameItems as $key => $stockOpnameItem) {
                    $financeItem = FinanceItem::create([
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'account_id' => $stockOpnameItem->account_id, // Assuming account_id is set in StockOpnameItem
                        'stock_opname_item_id' => $stockOpnameItem->id,
                        'product_id' => $stockOpnameItem->product_id,
                        'quantity' => $stockOpnameItem->quantity_difference,
                        'price' => $stockOpnameItem->hpp_average,
                        'loss_value' => $stockOpnameItem->loss_value,
                        'excess_value' => $stockOpnameItem->excess_value,
                        'sub_total' => $stockOpnameItem->loss_value + $stockOpnameItem->excess_value,
                    ]);

                    $loss_value = $stockOpnameItem->loss_value;

                    $loss_value = abs($loss_value);

                    if ($loss_value > 0) {
                        $journalItemLoss = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_item_id' => $financeItem->id,
                                'account_id' => $accountPersediaan->id,
                                'type' => 'credit', // Assuming this is a credit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                            $finance,
                            $financeItem->id,
                            null, // Assuming no finance item ID for credit
                            $company->id,
                            $accountPersediaan->id,
                            'Persediaan Barang - Stock Opname Processing for product ID: ' . $stockOpnameItem->product_id . ' - Total Loss Value: ' . $loss_value,
                            $loss_value,
                            Carbon::now(),
                            $journal,
                            $journalItemLoss
                        );
                    }

                    $excess_value = $stockOpnameItem->excess_value;

                    $excess_value = abs($excess_value);

                    if ($excess_value > 0) {
                        $journalItemExcess = JournalItem::create(
                            [
                                'finance_id' => $finance->id,
                                'company_id' => $company->id,
                                'journal_id' => $journal->id,
                                'finance_item_id' => $financeItem->id,
                                'account_id' => $accountDebtSecond->id,
                                'type' => 'debit', // Assuming this is a debit transaction
                            ]
                        );

                        app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                            $finance,
                            $financeItem->id,
                            null, // Assuming no finance item ID for credit
                            $company->id,
                            $accountPersediaan->id,
                            'Persediaan Barang - Stock Opname Processing for product ID: ' . $stockOpnameItem->product_id . ' - Total Excess Value: ' . $excess_value,
                            $excess_value,
                            Carbon::now(),
                            $journal,
                            $journalItemExcess
                        );
                    }
                }

                $stockOpname->is_process_finance = true;
                $stockOpname->save();
            }
        }

        // Logic to process stock opname
        $this->info('Stock opname has been processed.');
    }
}
