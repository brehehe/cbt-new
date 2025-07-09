<?php

namespace App\Console\Commands\Finance;

use App\Models\Account\Account;
use App\Models\Company\Company;
use App\Models\DeadStock\DeadStock;
use App\Models\Finance\Finance;
use App\Models\Finance\FinanceItem;
use App\Models\Finance\FinancePayment;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class DeadStockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dead-stock-command';

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
        // $this->_resetDeadStock();
        $this->_processDeadStock();
    }

    public function _resetDeadStock()
    {
        $this->info('Resetting dead stock...');

        // Reset dead stock logic here
        // Example: Update all dead stocks to draft status
        \DB::table('dead_stocks')
            ->update(['status' => 'draft', 'is_process_finance' => false]);

        $this->info('Dead stock reset completed.');
    }

    public function _processDeadStock()
    {

        $companys = Company::select('id', 'name')->get();

        foreach ($companys as $company) {
            $deadStocks = DeadStock::where('status', 'finish')
                ->where('company_id', $company->id)
                ->where('is_process_finance', false)
                ->get();

            if ($deadStocks->isNotEmpty()) {
                $this->info('Processing dead stock...');
                $total = 0;

                foreach ($deadStocks as $key => $value) {
                    $total += $value->total ? $value->total : $value->quantity * $value->price;
                }

                $finance = Finance::create([
                    'company_id' => $company->id,
                    'type' => 'dead-stock',
                    'grand_total' => $total,
                    'company_id' => $company->id,
                    'date' => Carbon::now(),
                    'description' => 'Dead stock processing for company name: ' . $company->name
                ]);

                $account_payment = Account::where('company_id', $company->id)
                    ->where('code', '8-81002')
                    ->first();

                $financePayment = FinancePayment::create([
                    'finance_id' => $finance->id,
                    'company_id' => $company->id,
                    'account_payment_id' => $account_payment->id,
                    'amount' => $total,
                    'date' => Carbon::now(),
                    'description' => 'Payment for dead stock processing'
                ]);

                $journal = Journal::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'date' => Carbon::now(),
                        'description' => 'Jurnal for Dead Stock Processing for company name: ' . $company->name . ' - Finance Code: ' . $finance->code,
                    ]
                );

                $journalItem = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_payment_id' => $financePayment->id,
                        'account_id' => $account_payment->id,
                        'type' => 'debit', // Assuming this is a debit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                    $finance,
                    null,
                    $financePayment->id,
                    $company->id,
                    $account_payment->id,
                    'Beban Persediaan Barang - Dead Stock Processing for company name: ' . $company->name . ' - Total: ' . $total,
                    $total,
                    Carbon::now(),
                    $journal,
                    $journalItem
                );

                // Process each dead stock for the company
                foreach ($deadStocks as $deadStock) {
                    $deadStock->total = $deadStock->total ? $deadStock->total : $deadStock->quantity * $deadStock->price;

                    $account = Account::where('company_id', $company->id)
                        ->where('code', '1-10200')
                        ->where('name', 'Persediaan Barang')
                        ->first();

                    $financeItem = FinanceItem::create([
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'account_id' => $account->id,
                        'dead_stock_id' => $deadStock->id,
                        'product_id' => $deadStock->product_id,
                        'quantity' => $deadStock->quantity,
                        'price' => $deadStock->price,
                        'sub_total' => $deadStock->total
                    ]);

                    $journalItem = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_item_id' => $financeItem->id,
                            'account_id' => $account_payment->id,
                            'type' => 'credit', // Assuming this is a credit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                        $finance,
                        $financeItem->id,
                        null,
                        $company->id,
                        $account->id,
                        'Dead stock processing for product name: ' . $deadStock->product->name . ' - Total Produk Rugi: ' . $deadStock->total,
                        $deadStock->total,
                        Carbon::now()
                    );
                }

                $deadStock->is_process_finance = true;
                $deadStock->save();
                $this->info('Dead stock processing completed.');
            }
        }
    }
}
