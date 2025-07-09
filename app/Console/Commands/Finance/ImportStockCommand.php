<?php

namespace App\Console\Commands\Finance;

use App\Models\Account\Account;
use App\Models\Finance\Finance;
use App\Models\Product\ProductImportStock;
use Illuminate\Console\Command;
use App\Models\Company\Company;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinancePayment;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Carbon\Carbon;

class ImportStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-stock-command';

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
        // $this->_resetImportStock();
        $this->_processImportStock();
    }

    public function _processImportStock(): void
    {
        $companys = Company::select('id', 'name')->get();

        foreach ($companys as $company) {
            $importStocks = ProductImportStock::where('is_process_finance', false)
                ->where('company_id', $company->id)
                ->get();

            if ($importStocks->isNotEmpty()) {
                $this->info('Processing import stock...');

                $total = 0;

                foreach ($importStocks as $key => $value) {
                    $total += $value->quantity * $value->hpp_average;
                }

                $finance = Finance::create([
                    'company_id' => $company->id,
                    'type' => 'import-stock',
                    'grand_total' => $total,
                    'company_id' => $company->id,
                    'date' => Carbon::now(),
                    'description' => 'Import stock processing for company name: ' . $company->name
                ]);

                $account_debt = Account::where('company_id', $company->id)
                    ->where('code', '2-20100')
                    ->first();

                $financePayment = FinancePayment::create([
                    'finance_id' => $finance->id,
                    'company_id' => $company->id,
                    'account_debt_id' => $account_debt->id,
                    'amount' => $total,
                    'date' => Carbon::now(),
                    'description' => 'Payment for import stock processing'
                ]);

                $journal = Journal::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'date' => Carbon::now(),
                        'description' => 'Jurnal for Import Stok Produk Processing for company name: ' . $company->name . ' - Finance Code: ' . $finance->code,
                    ]
                );

                $journalPayment = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_payment_id' => $financePayment->id,
                        'account_id' => $account_debt->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    null,
                    $financePayment->id,
                    $company->id,
                    $account_debt->id,
                    'Hutang Usaha - Import Stock Processing for company name: ' . $company->name . ' - Total: ' . $total,
                    $total,
                    Carbon::now(),
                    $journal,
                    $journalPayment
                );

                foreach ($importStocks as $key => $importStock) {
                    $getTotal = $importStock->quantity * $importStock->hpp_average;

                    $account = Account::where('company_id', $company->id)
                        ->where('code', '1-10200')
                        ->where('name', 'Persediaan Barang')
                        ->first();

                    $financeItem = FinanceItem::create([
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'account_id' => $account->id,
                        'import_stock_id' => $importStock->id,
                        'product_id' => $importStock->product_id,
                        'quantity' => $importStock->quantity,
                        'price' => $importStock->hpp_average,
                        'sub_total' => $getTotal
                    ]);

                    $journalItem = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_item_id' => $financeItem->id,
                            'account_id' => $account_debt->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        $financeItem->id,
                        null,
                        $company->id,
                        $account->id,
                        'Persediaan Barang - Import Stock Processing for product name: ' . $importStock->product->name . ' - Total: ' . $getTotal,
                        $getTotal,
                        Carbon::now(),
                        $journal,
                        $journalItem
                    );

                    $importStock->is_process_finance = true;
                    $importStock->save();
                }
                $this->info('Import stock processing completed.');
            }
        }

        // Add your import stock processing logic here
    }
}
