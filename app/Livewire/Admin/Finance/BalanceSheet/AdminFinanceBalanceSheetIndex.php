<?php

namespace App\Livewire\Admin\Finance\BalanceSheet;

use App\Models\Account\Account;
use App\Models\Account\AccountTransaction;
use App\Models\Account\CategoryAccount;
use App\Models\Account\DetailCategoryAccount;
use Livewire\Component;

class AdminFinanceBalanceSheetIndex extends Component
{
    public $detailCategoryAccounts = [], $categoryAccounts = [], $accounts = [], $total_accounts = [], $start_date, $end_date;
    public $detailCategoryTotals = [], $categoryAccountTotals = [], $accountTotals = [], $grandTotal = [];

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
        $this->detailCategoryAccounts = [];
        $this->categoryAccounts = [];
        $this->accounts = [];
        $this->total_accounts = [];

        // New arrays for totals
        $this->detailCategoryTotals = [];
        $this->categoryAccountTotals = [];
        $this->accountTotals = [];

        // Get all data in single queries with relationships - dengan urutan yang benar
        $detailCategories = DetailCategoryAccount::with([
            'categoryAccounts' => function ($query) use ($companyId) {
                $query->where('company_id', $companyId)
                    ->orderBy('name') // Urutkan category accounts
                    ->with(['accounts' => function ($subQuery) use ($companyId) {
                        $subQuery->where('company_id', $companyId)
                            ->orderBy('code') // Urutkan berdasarkan kode akun jika ada
                            ->orderBy('name'); // Atau berdasarkan nama
                    }]);
            }
        ])
            ->where('company_id', $companyId)
            ->where('type', 'balance-sheet')
            ->orderByRaw("CASE
        WHEN LOWER(name) ILIKE '%aktiva%' THEN 1
        WHEN LOWER(name) ILIKE '%kewajiban%' THEN 2
        WHEN LOWER(name) ILIKE '%modal%' THEN 3
        ELSE 4
    END") // Urutkan sesuai standar balance sheet
            ->orderBy('name')
            ->get();

        // Get all account IDs for bulk transaction query
        $accountIds = [];
        foreach ($detailCategories as $detailCategory) {
            foreach ($detailCategory->categoryAccounts as $categoryAccount) {
                foreach ($categoryAccount->accounts as $account) {
                    $accountIds[] = $account->id;
                }
            }
        }

        // Single query for all account transactions
        $transactions = $this->getBulkAccountTransactions($accountIds, $this->start_date, $this->end_date);

        // Build the structure efficiently with totals calculation
        foreach ($detailCategories as $detailCategory) {
            $this->detailCategoryAccounts[$detailCategory->id] = $detailCategory->name;

            // Initialize detail category totals
            $this->detailCategoryTotals[$detailCategory->id] = [
                'total_debit' => 0,
                'total_credit' => 0,
                'total' => 0,
                'balance' => 0 // Tambahkan balance yang sudah disesuaikan dengan jenis akun
            ];

            foreach ($detailCategory->categoryAccounts as $categoryAccount) {
                $this->categoryAccounts[$detailCategory->id][$categoryAccount->id] = $categoryAccount->name;

                // Initialize category account totals
                $this->categoryAccountTotals[$detailCategory->id][$categoryAccount->id] = [
                    'total_debit' => 0,
                    'total_credit' => 0,
                    'total' => 0,
                    'balance' => 0
                ];

                foreach ($categoryAccount->accounts as $account) {
                    $this->accounts[$detailCategory->id][$categoryAccount->id][$account->id] = $account->name;

                    // Get transaction data from our bulk query result
                    $accountTransaction = $transactions[$account->id] ?? [
                        'total_debit' => 0,
                        'total_credit' => 0,
                        'total' => 0
                    ];

                    // Hitung balance yang benar berdasarkan jenis akun
                    $balance = $this->calculateCorrectBalance(
                        $accountTransaction,
                        $detailCategory->name,
                        $account->name
                    );

                    $accountTransaction['balance'] = $balance;

                    $this->total_accounts[$detailCategory->id][$categoryAccount->id][$account->id] = $accountTransaction;

                    // Store account totals
                    $this->accountTotals[$detailCategory->id][$categoryAccount->id][$account->id] = $accountTransaction;

                    // Add to category account totals
                    $this->categoryAccountTotals[$detailCategory->id][$categoryAccount->id]['total_debit'] += $accountTransaction['total_debit'];
                    $this->categoryAccountTotals[$detailCategory->id][$categoryAccount->id]['total_credit'] += $accountTransaction['total_credit'];
                    $this->categoryAccountTotals[$detailCategory->id][$categoryAccount->id]['total'] += $accountTransaction['total'];
                    $this->categoryAccountTotals[$detailCategory->id][$categoryAccount->id]['balance'] += $balance;

                    // Add to detail category totals
                    $this->detailCategoryTotals[$detailCategory->id]['total_debit'] += $accountTransaction['total_debit'];
                    $this->detailCategoryTotals[$detailCategory->id]['total_credit'] += $accountTransaction['total_credit'];
                    $this->detailCategoryTotals[$detailCategory->id]['total'] += $accountTransaction['total'];
                    $this->detailCategoryTotals[$detailCategory->id]['balance'] += $balance;
                }
            }
        }

        // Calculate grand total
        $this->grandTotal = [
            'total_debit' => array_sum(array_column($this->detailCategoryTotals, 'total_debit')),
            'total_credit' => array_sum(array_column($this->detailCategoryTotals, 'total_credit')),
            'total' => array_sum(array_column($this->detailCategoryTotals, 'total')),
            'total_balance' => array_sum(array_column($this->detailCategoryTotals, 'balance'))
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
            'detail_categories' => $this->detailCategoryAccounts,
            'category_accounts' => $this->categoryAccounts,
            'accounts' => $this->accounts,
            'transactions' => $this->total_accounts,
            'totals' => [
                'detail_category_totals' => $this->detailCategoryTotals,
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
        return view('livewire.admin.finance.balance-sheet.admin-finance-balance-sheet-index')
            ->extends('layout.app')
            ->section('content');
    }
}
