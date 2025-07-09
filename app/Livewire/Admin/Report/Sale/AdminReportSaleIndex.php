<?php

namespace App\Livewire\Admin\Report\Sale;

use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class AdminReportSaleIndex extends Component
{
    use WithPagination;
    protected $queryString = [
        // 'page' => ['except' => 1], // Ini akan menghapus ?page=1 dari URL
        'search' => ['except' => ''],
    ];
    public $search = '';
    public $perPage = 5;
    public $start_date, $end_date, $type;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
        Session::forget('transaction_id');
    }

    public function edit($id)
    {
        Session::put('transaction_id', $id);
        return redirect()->route('user.sale.report-sale.detail');
    }

    public function render()
    {
        $transaction = Transaction::search($this->search)
            ->select('id', 'code', 'branch_id', 'company_id', 'patient_name', 'sub_total_price', 'discount_value', 'grand_total_price', 'type', 'status')
            ->where('company_id', auth()->user()->company_id)
            ->with(['branch:id,name', 'company:id,name'])
            ->where('status', 'completed')
            ->orderBy('order', 'desc');

        if ($this->start_date && $this->end_date) {
            $transaction->whereBetween('created_at', [
                $this->start_date . ' 00:00:00',
                $this->end_date . ' 23:59:59'
            ]);
        }

        if ($this->type) {
            $transaction->where('type', $this->type);
        }

        return view('livewire.admin.report.sale.admin-report-sale-index', [
            'transactions' => $transaction->paginate($this->perPage),
        ])
            ->extends('layout.app')
            ->section('content');
    }
}
