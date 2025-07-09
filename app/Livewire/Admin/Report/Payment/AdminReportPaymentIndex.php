<?php

namespace App\Livewire\Admin\Report\Payment;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\TransactionPayment;
use App\Models\PaymentMethod\PaymentMethod;
use App\Models\Transaction\Transaction;

class AdminReportPaymentIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date, $end_date, $type;

    public $payments = [];

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
        $this->getPayments();
    }

    public function updatedSearch()
    {
        $this->getPayments();
        $this->resetPage(); // Reset halaman ke 1 saat pencarian berubah
    }

    public function hydrate()
    {
        $this->resetPage();
    }

    public function updatedType()
    {
        $this->getPayments();
        $this->resetPage(); // Reset halaman ke 1 saat tipe berubah
    }

    public function updatedStartDate()
    {
        $this->getPayments();
    }

    public function updatedEndDate()
    {
        $this->getPayments();
    }

    public function getPayments()
    {
        $this->reset(['payments']);

        $case = "CASE WHEN transactions.code IS NOT NULL";

        if (!empty($this->search)) {
            $case .= " AND transactions.code ILIKE ?";
        }

        if (!empty($this->type)) {
            $case .= " AND transactions.type = ?";
        }

        $case .= " THEN transaction_payments.payment_real ELSE 0 END";

        $this->payments = DB::table('payment_methods')
            ->leftJoin('transaction_payments', function ($join) {
                $join->on('transaction_payments.payment_method_id', '=', 'payment_methods.id')
                    ->where('transaction_payments.company_id', auth()->user()->company_id);

                if (!empty($this->start_date) && !empty($this->end_date)) {
                    $join->whereBetween('transaction_payments.created_at', [
                        $this->start_date . ' 00:00:00',
                        $this->end_date . ' 23:59:59'
                    ]);
                }
            })
            ->leftJoin('transactions', function ($join) {
                $join->on('transactions.id', '=', 'transaction_payments.transaction_id')
                    ->where('transactions.company_id', auth()->user()->company_id)
                    ->where('transactions.status', 'completed');

                if (!empty($this->search)) {
                    $join->where('transactions.code', 'ilike', "%{$this->search}%");
                }

                if (!empty($this->type)) {
                    $join->where('transactions.type', $this->type);
                }
            })
            ->select(
                'payment_methods.name as payment_method_name',
                DB::raw("COALESCE(SUM($case), 0) as total_amount")
            )
            ->addBinding(array_values(array_filter([
                !empty($this->search) ? "%{$this->search}%" : null,
                !empty($this->type) ? $this->type : null
            ])), 'select')
            ->where('payment_methods.company_id', auth()->user()->company_id)
            ->groupBy('payment_methods.name')
            ->orderBy('payment_methods.name')
            ->get()
            ->toArray();
    }


    public function render()
    {
        $transactionPayments = Transaction::with(['transactionPayments', 'transactionPayments.paymentMethod'])
            ->search($this->search)
            ->where('company_id', auth()->user()->company_id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc');

        if ($this->start_date && $this->end_date) {
            $transactionPayments->whereBetween('created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }

        if ($this->type) {
            $transactionPayments->where('type', $this->type);
        }

        $paymentMethods = PaymentMethod::select('id', 'name')->get();

        return view('livewire.admin.report.payment.admin-report-payment-index', [
            'transactionPayments' => $transactionPayments->paginate($this->perPage),
            'paymentMethods' => $paymentMethods,
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
