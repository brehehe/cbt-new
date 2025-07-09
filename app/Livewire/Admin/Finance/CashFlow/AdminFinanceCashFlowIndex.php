<?php

namespace App\Livewire\Admin\Finance\CashFlow;

use App\Models\Account\AccountTransaction;
use App\Models\Account\CategoryAccount;
use Livewire\Component;

class AdminFinanceCashFlowIndex extends Component
{
    public $cashFlows = ['operasi', 'investasi', 'pendanaan'];
    public $categoryAccounts = [], $accounts = [], $total_accounts = [], $start_date, $end_date;
    public $cashFlowTotals = [], $categoryAccountTotals = [], $accountTotals = [], $grandTotal = [];

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');

        $this->BalanceSheet();
    }

    public function updatedStartDate(): void
    {
        $this->BalanceSheet();
    }

    public function updatedEndDate(): void
    {
        $this->BalanceSheet();
    }

    public function balanceSheet()
    {
        $companyId = auth()->user()->company_id;

        // Reset arrays
        $this->categoryAccounts = [];
        $this->accounts = [];
        $this->total_accounts = [];

        // New arrays for totals
        $this->categoryAccountTotals = [];
        $this->accountTotals = [];

        // Get all data in single queries with relationships - dengan urutan yang benar
        $categoryAccount = CategoryAccount::where('company_id', $companyId)
            ->whereIn('cash_flow', $this->cashFlows)
            ->orderBy('order', 'asc')
            ->get();

        // Get all account IDs for bulk transaction query
        $accountIds = [];
        foreach ($this->cashFlows as $cashFlow) {
            foreach ($categoryAccount as $catAcc) {
                if ($catAcc->cash_flow === $cashFlow) {
                    foreach ($catAcc->accounts as $account) {
                        $accountIds[] = $account->id;
                    }
                }
            }
        }
        // Single query for all account transactions
        $transactions = $this->getBulkAccountTransactions($accountIds, $this->start_date, $this->end_date);

        foreach ($this->cashFlows as $key => $cashFlow) {
            // Initialize detail category totals
            $this->cashFlowTotals[$cashFlow] = [
                'total_debit' => 0,
                'total_credit' => 0,
                'total' => 0,
            ];

            foreach ($categoryAccount as $key => $value) {
                if ($value->cash_flow === $cashFlow) {
                    $this->categoryAccounts[$cashFlow][$value->id] = $value->name;
                    $this->categoryAccountTotals[$cashFlow][$value->id] = [
                        'total_debit' => 0,
                        'total_credit' => 0,
                        'total' => 0,
                    ];
                    foreach ($value->accounts as $account) {
                        $this->accounts[$cashFlow][$value->id][$account->id] = $account->name;
                        $accountTransaction = $transactions[$account->id] ?? [
                            'total_debit' => 0,
                            'total_credit' => 0,
                            'total' => 0
                        ];
                        $this->accountTotals[$cashFlow][$value->id][$account->id] = $accountTransaction;

                        $this->categoryAccountTotals[$cashFlow][$value->id]['total_debit'] += $accountTransaction['total_debit'];
                        $this->categoryAccountTotals[$cashFlow][$value->id]['total_credit'] += $accountTransaction['total_credit'];
                        $this->categoryAccountTotals[$cashFlow][$value->id]['total'] += $accountTransaction['total'];

                        $this->cashFlowTotals[$cashFlow]['total_debit'] += $accountTransaction['total_debit'];
                        $this->cashFlowTotals[$cashFlow]['total_credit'] += $accountTransaction['total_credit'];
                        $this->cashFlowTotals[$cashFlow]['total'] += $accountTransaction['total'];
                    }
                }
            }
        }

        $this->grandTotal = [
            'total_debit' => array_sum(array_column($this->cashFlowTotals, 'total_debit')),
            'total_credit' => array_sum(array_column($this->cashFlowTotals, 'total_credit')),
            'total' => array_sum(array_column($this->cashFlowTotals, 'total')),
        ];

        return $this->formatBalanceSheetData();
    }

    /**
     * Hitung balance yang benar berdasarkan jenis akun untuk balance sheet
     */
    private function calculateCorrectBalance($accountTransaction, $detailCategoryName, $accountName)
    {
        $debit = (float) $accountTransaction['total_debit'];
        $credit = (float) $accountTransaction['total_credit'];
        $categoryName = strtolower($detailCategoryName);
        $accName = strtolower($accountName);

        // Handle akun depresiasi dan amortisasi (contra assets)
        if (
            strpos($accName, 'depresiasi') !== false ||
            strpos($accName, 'amortisasi') !== false ||
            strpos($accName, 'penyusutan') !== false
        ) {
            // Akun kontra aktiva: saldo kredit mengurangi aktiva
            return $credit - $debit;
        }

        // Handle berdasarkan kategori detail
        if (strpos($categoryName, 'aktiva') !== false) {
            // Aktiva: Debit menambah, Credit mengurangi
            return $debit - $credit;
        } elseif (
            strpos($categoryName, 'kewajiban') !== false ||
            strpos($categoryName, 'modal') !== false
        ) {
            // Kewajiban & Modal: Credit menambah, Debit mengurangi
            return $credit - $debit;
        }

        // Default fallback
        return $debit - $credit;
    }

    /**
     * Method yang sudah diperbaiki untuk menentukan tipe balance sheet
     */
    private function getBalanceSheetType($categoryName)
    {
        $categoryName = strtolower(trim($categoryName));

        // Sesuaikan dengan data kategori Anda
        if (strpos($categoryName, 'aktiva') !== false) {
            return 'assets';
        } elseif (strpos($categoryName, 'kewajiban') !== false) {
            return 'liabilities';
        } elseif (strpos($categoryName, 'modal') !== false) {
            return 'equity';
        }

        // Fallback untuk kategori lain
        return 'other';
    }

    /**
     * Get balance sheet yang sudah dikelompokkan dengan format standar
     */
    public function getStandardBalanceSheet()
    {
        $this->balanceSheet();

        $assets = [];
        $liabilities = [];
        $equity = [];

        foreach ($this->detailCategoryAccounts as $detailCatId => $detailCatName) {
            $type = $this->getBalanceSheetType($detailCatName);

            $categoryData = [
                'name' => $detailCatName,
                'totals' => $this->detailCategoryTotals[$detailCatId],
                'category_accounts' => []
            ];

            if (isset($this->categoryAccounts[$detailCatId])) {
                foreach ($this->categoryAccounts[$detailCatId] as $catAccId => $catAccName) {
                    $categoryData['category_accounts'][$catAccId] = [
                        'name' => $catAccName,
                        'totals' => $this->categoryAccountTotals[$detailCatId][$catAccId],
                        'accounts' => []
                    ];

                    if (isset($this->accounts[$detailCatId][$catAccId])) {
                        foreach ($this->accounts[$detailCatId][$catAccId] as $accId => $accName) {
                            $categoryData['category_accounts'][$catAccId]['accounts'][$accId] = [
                                'name' => $accName,
                                'totals' => $this->accountTotals[$detailCatId][$catAccId][$accId]
                            ];
                        }
                    }
                }
            }

            // Kelompokkan berdasarkan tipe
            switch ($type) {
                case 'assets':
                    $assets[$detailCatId] = $categoryData;
                    break;
                case 'liabilities':
                    $liabilities[$detailCatId] = $categoryData;
                    break;
                case 'equity':
                    $equity[$detailCatId] = $categoryData;
                    break;
            }
        }

        // Hitung total per kelompok
        $totalAssets = array_sum(array_column(array_column($assets, 'totals'), 'balance'));
        $totalLiabilities = array_sum(array_column(array_column($liabilities, 'totals'), 'balance'));
        $totalEquity = array_sum(array_column(array_column($equity, 'totals'), 'balance'));

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'summary' => [
                'total_assets' => $totalAssets,
                'total_liabilities' => $totalLiabilities,
                'total_equity' => $totalEquity,
                'total_liabilities_equity' => $totalLiabilities + $totalEquity,
                'is_balanced' => abs($totalAssets - ($totalLiabilities + $totalEquity)) < 0.01,
                'difference' => $totalAssets - ($totalLiabilities + $totalEquity)
            ],
            'period' => [
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'generated_by' => auth()->user()->name ?? 'System'
            ]
        ];
    }

    /**
     * Optimized getBulkAccountTransactions dengan penggunaan index yang lebih baik
     */
    public function getBulkAccountTransactions($accountIds, $startDate, $endDate)
    {
        if (empty($accountIds)) {
            return [];
        }

        $companyId = auth()->user()->company_id;

        // Gunakan index yang lebih efisien dan tambahkan hint jika perlu
        $results = AccountTransaction::whereIn('account_id', $accountIds)
            ->where('company_id', $companyId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw("
            account_id,
            COALESCE(SUM(CASE WHEN type = 'debit' THEN COALESCE(debit, 0) ELSE 0 END), 0) as total_debit,
            COALESCE(SUM(CASE WHEN type = 'credit' THEN COALESCE(credit, 0) ELSE 0 END), 0) as total_credit,
            COALESCE(SUM(CASE WHEN type = 'debit' THEN COALESCE(debit, 0) ELSE 0 END), 0) -
            COALESCE(SUM(CASE WHEN type = 'credit' THEN COALESCE(credit, 0) ELSE 0 END), 0) as total
        ")
            ->groupBy('account_id')
            ->orderBy('account_id') // Tambahkan ordering untuk konsistensi
            ->get();

        // Convert to associative array for quick lookup
        $transactions = [];
        foreach ($results as $result) {
            $transactions[$result->account_id] = [
                'total_debit' => (float) $result->total_debit,
                'total_credit' => (float) $result->total_credit,
                'total' => (float) $result->total
            ];
        }

        return $transactions;
    }

    /**
     * Format data dengan informasi tambahan untuk debugging
     */
    public function formatBalanceSheetData()
    {
        return [
            'cash_flow' => $this->cashFlows,
            'category_accounts' => $this->categoryAccounts,
            'accounts' => $this->accounts,
            'transactions' => $this->total_accounts,
            'totals' => [
                'cash_flow_totals' => $this->cashFlowTotals,
                'category_account_totals' => $this->categoryAccountTotals,
                'account_totals' => $this->accountTotals,
                'grand_total' => $this->grandTotal
            ],
            'metadata' => [
                'period_start' => $this->start_date,
                'period_end' => $this->end_date,
                'company_id' => auth()->user()->company_id,
                'generated_at' => now()->toDateTimeString(),
                'total_accounts_processed' => count($this->accounts)
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.finance.cash-flow.admin-finance-cash-flow-index')
            ->extends('layout.app')
            ->section('content');
    }
}
