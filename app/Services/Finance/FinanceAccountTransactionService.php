<?php

namespace App\Services\Finance;

use App\Models\Account\AccountTransaction;
use Str;
use Auth;

/**
 * Class FinanceAccountTransactionService.
 */
class FinanceAccountTransactionService
{
    public function AccountTransactionDebit($finance, $financeItemId, $financePaymentId, $companyId, $accountId, $description, $amount, $date, $journal, $journalItem, $financeOtherId = null, $financeRecipeId = null)
    {
        $amount = intval(Str::replace('.', '', $amount));

        return AccountTransaction::updateOrCreate([
            'finance_id' => $finance->id,
            'finance_item_id' => $financeItemId,
            'finance_payment_id' => $financePaymentId,
            'type' => 'debit',
            'journal_id' => $journal->id ?? null,
            'journal_item_id' => $journalItem->id ?? null,
            'finance_other_id' => $financeOtherId,
            'finance_recipe_id' => $financeRecipeId,
        ], [
            'account_id' => $accountId,
            'description' => $description,
            'debit' => $amount,
            'company_id' => $companyId,
            'date' => $date,
        ]);
    }

    public function AccountTransactionCredit($finance, $financeItemId, $financePaymentId, $companyId, $accountId, $description, $amount, $date, $journal, $journalItem, $financeOtherId = null, $financeRecipeId = null)
    {
        $amount = intval(Str::replace('.', '', $amount));

        return AccountTransaction::updateOrCreate([
            'finance_id' => $finance->id,
            'finance_item_id' => $financeItemId,
            'finance_payment_id' => $financePaymentId,
            'finance_other_id' => $financeOtherId,
            'type' => 'credit',
            'journal_id' => $journal->id ?? null,
            'journal_item_id' => $journalItem->id ?? null,
            'finance_recipe_id' => $financeRecipeId,
        ], [
            'account_id' => $accountId,
            'description' => $description,
            'credit' => $amount,
            'company_id' => $companyId,
            'date' => $date,
        ]);
    }
}
