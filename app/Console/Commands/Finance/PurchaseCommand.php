<?php

namespace App\Console\Commands\Finance;

use App\Models\Company\Company;
use App\Models\Finance\Finance;
use App\Models\Finance\FinancePayment;
use App\Models\PurchaseOrder\PurchaseOrder;
use App\Models\PurchaseOrder\PurchaseOrderItem;
use App\Services\Finance\FinanceAccountTransactionService;
use Illuminate\Console\Command;
use App\Models\Account\Account;
use App\Models\Finance\FinanceItem;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalItem;
use Carbon\Carbon;

class PurchaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purchase-command';

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
        // $this->_resetPurchase();
        $this->_processPurchase();
    }

    private function _resetPurchase()
    {
        // Logic to reset purchase
        $this->info('Purchase reset successfully.');
    }

    private function _processPurchase()
    {
        $companies = Company::select('id', 'name')->get();

        foreach ($companies as $company) {
            $purchaseOrders = PurchaseOrder::where('company_id', $company->id)
                ->where('is_process_finance', false)
                ->get();

            if ($purchaseOrders->isEmpty()) {
                $this->info("No purchase orders found for company name: {$company->name}");
                continue;
            }

            foreach ($purchaseOrders as $purchaseOrder) {
                $purchaseOrderItems = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                    ->where('quantity_accepted', '>', 0)
                    ->where('company_id', $company->id)
                    ->get();

                $purchaseOrder->grand_total_real = 0;
                $purchaseOrder->price_total = 0;
                $purchaseOrder->price_tax_total = 0;
                $purchaseOrder->tax_total = 0;

                foreach ($purchaseOrderItems as $key => $purchaseOrderItem) {
                    $purchaseOrderItem->hna_total = $purchaseOrderItem->quantity * $purchaseOrderItem->hna;
                    $purchaseOrderItem->hna_ppn_total = $purchaseOrderItem->quantity * $purchaseOrderItem->hna_ppn;
                    $purchaseOrderItem->ppn_total = $purchaseOrderItem->quantity * $purchaseOrderItem->ppn;

                    // Calculate total
                    $purchaseOrderItem->total = $purchaseOrderItem->hna_ppn * $purchaseOrderItem->quantity;
                    $purchaseOrderItem->save();

                    $purchaseOrder->grand_total_real += $purchaseOrderItem->total;
                    $purchaseOrder->price_total += $purchaseOrderItem->hna_total;
                    $purchaseOrder->price_tax_total += $purchaseOrderItem->hna_ppn_total;
                    $purchaseOrder->tax_total += $purchaseOrderItem->ppn_total;
                }

                $finance = Finance::create([
                    'type' => 'purchase',
                    'date' => Carbon::now(),
                    'description' => 'Purchase order processing for company name: ' . $company->name . ' and purchase order ID: ' . $purchaseOrder->id,
                    'sub_total' => $purchaseOrder->price_total,
                    'discount' => $purchaseOrder->discount,
                    'tax' => $purchaseOrder->tax_total,
                    'grand_total' => $purchaseOrder->grand_total_real,
                    'company_id' => $company->id,
                    'status' => 'draft',
                ]);

                $accountDebt = Account::where('company_id', $company->id)
                    ->where('code', '2-20100') // Assuming this is the account code for debt
                    ->first();

                $financePayment = FinancePayment::create([
                    'finance_id' => $finance->id,
                    'company_id' => $company->id,
                    'account_debt_id' => $accountDebt->id, // Use the accountDebt retrieved earlier
                    'amount' => $purchaseOrder->grand_total_real,
                    'date' => Carbon::now(),
                ]);

                $journal = Journal::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'date' => Carbon::now(),
                        'description' => 'Jurnal for Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $purchaseOrder->number . ' - Finance Code: ' . $finance->code,
                    ]
                );

                $journalItem = JournalItem::create(
                    [
                        'finance_id' => $finance->id,
                        'company_id' => $company->id,
                        'journal_id' => $journal->id,
                        'finance_payment_id' => $financePayment->id,
                        'account_id' => $accountDebt->id,
                        'type' => 'credit', // Assuming this is a credit transaction
                    ]
                );

                app(FinanceAccountTransactionService::class)->AccountTransactionCredit(
                    $finance,
                    null,
                    $financePayment->id,
                    $company->id,
                    $accountDebt->id,
                    'Hutang Usaha - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $purchaseOrder->number,
                    $purchaseOrder->grand_total_real,
                    Carbon::now(),
                    $journal,
                    $journalItem
                );

                $accountPersediaan = Account::where('company_id', $company->id)
                    ->where('code', '1-10200') // Assuming this is the account code for inventory
                    ->first();

                $accountPPN = Account::where('company_id', $company->id)
                    ->where('code', '1-10500') // Assuming this is the account code for PPN
                    ->first();

                foreach ($purchaseOrderItems as $key => $value) {
                    $financeItem = FinanceItem::create([
                        'finance_id' => $finance->id,
                        'purchase_order_item_id' => $value->id,
                        'company_id' => $company->id,
                        'product_id' => $value->product_id,
                        'description' => $value->product_name,
                        'quantity' => $value->quantity_accepted,
                        'price' => $value->hna_total,
                        'tax' => $value->ppn_total,
                        'discount' => $value->discount,
                        'sub_total' => $value->total,
                    ]);

                    $journalItemPersediaan = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_item_id' => $financeItem->id,
                            'account_id' => $accountPersediaan->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        $financeItem->id,
                        null,
                        $company->id,
                        $accountPersediaan->id,
                        'Persediaan Barang - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $purchaseOrder->number . ' - Item: ' . $value->product_name,
                        $financeItem->hna_total,
                        Carbon::now(),
                        $journal,
                        $journalItemPersediaan
                    );

                    $journalItemPPN = JournalItem::create(
                        [
                            'finance_id' => $finance->id,
                            'company_id' => $company->id,
                            'journal_id' => $journal->id,
                            'finance_item_id' => $financeItem->id,
                            'account_id' => $accountPPN->id,
                            'type' => 'debit', // Assuming this is a debit transaction
                        ]
                    );

                    app(FinanceAccountTransactionService::class)->AccountTransactionDebit(
                        $finance,
                        $financeItem->id,
                        null,
                        $company->id,
                        $accountPPN->id,
                        'PPN Masukan - Purchase Order Processing for company name: ' . $company->name . ' - Purchase Order Code: ' . $purchaseOrder->number . ' - Item: ' . $value->product_name,
                        $financeItem->ppn_total,
                        Carbon::now(),
                        $journal,
                        $journalItemPPN
                    );
                }

                $purchaseOrder->is_process_finance = true;
                $purchaseOrder->save();
            }
        }
    }
}
